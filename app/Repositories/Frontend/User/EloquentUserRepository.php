<?php namespace App\Repositories\Frontend\User;

use App\Models\Access\User\User;
use App\Models\Access\User\UserProvider;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use Imagick;
use Storage;

/**
 * Class EloquentUserRepository
 * @package App\Repositories\User
 */
class EloquentUserRepository implements UserContract {

	/**
	 * @var RoleRepositoryContract
	 */
	protected $role;

	/**
	 * @param RoleRepositoryContract $role
	 */
	public function __construct(RoleRepositoryContract $role) {
		$this->role = $role;
	}

	/**
	 * @param $id
	 * @return \Illuminate\Support\Collection|null|static
	 * @throws GeneralException
	 */
	public function findOrThrowException($id) {
		$user = User::find($id);
		if (! is_null($user)) return $user;
		throw new GeneralException('That user does not exist.');
	}

	/**
	 * @param $data
	 * @param bool $provider
	 * @return static
	 */
	public function create($data, $provider = false) {
		$user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => $provider ? null : $data['password'],
			'confirmation_code' => md5(uniqid(mt_rand(), true)),
			'confirmed' => config('access.users.confirm_email') ? 0 : 1,
		]);
		$user->attachRole($this->role->getDefaultUserRole());

		if (config('access.users.confirm_email') and $provider === false)
        		$this->sendConfirmationEmail($user);
    		else
        		$user->confirmed = 1;

		return $user;
	}

	/**
	 * @param $data
	 * @param $provider
	 * @return static
	 */
	public function findByUserNameOrCreate($data, $provider) {
		$user = User::where('email', $data->email)->first();
		$providerData = [
			'avatar' => $data->avatar,
			'provider' => $provider,
			'provider_id' => $data->id,
		];

		if(! $user) {
			$user = $this->create([
				'name' => $data->name,
				'email' => $data->email,
			], true);
		}

		if ($this->hasProvider($user, $provider))
			$this->checkIfUserNeedsUpdating($provider, $data, $user);
		else
		{
			$user->providers()->save(new UserProvider($providerData));
		}

		return $user;
	}

	/**
	 * @param $user
	 * @param $provider
	 * @return bool
	 */
	public function hasProvider($user, $provider) {
		foreach ($user->providers as $p) {
			if ($p->provider == $provider)
				return true;
		}

		return false;
	}

	/**
	 * @param $provider
	 * @param $providerData
	 * @param $user
	 */
	public function checkIfUserNeedsUpdating($provider, $providerData, $user) {
		//Have to first check to see if name and email have to be updated
		$userData = [
			'email' => $providerData->email,
			'name' => $providerData->name,
		];
		$dbData = [
			'email' => $user->email,
			'name' => $user->name,
		];
		$differences = array_diff($userData, $dbData);
		if (! empty($differences)) {
			$user->email = $providerData->email;
			$user->name = $providerData->name;
			$user->save();
		}

		//Then have to check to see if avatar for specific provider has changed
		$p = $user->providers()->where('provider', $provider)->first();
		if ($p->avatar != $providerData->avatar) {
			$p->avatar = $providerData->avatar;
			$p->save();
		}
	}

	/**
	 * @param $input
	 * @return mixed
	 * @throws GeneralException
	 */
	public function updateProfile($input) {
		$user = access()->user();
		$user->name = $input['name'];

		if ($user->canChangeEmail()) {
			//Address is not current address
			if ($user->email != $input['email'])
			{
				//Emails have to be unique
				if (User::where('email', $input['email'])->first())
					throw new GeneralException("That e-mail address is already taken.");

				$user->email = $input['email'];
			}
		}

		return $user->save();
	}

	/**
	 * @param $input
	 * @return mixed
	 * @throws GeneralException
	 */
	public function changePassword($input) {
		$user = $this->findOrThrowException(auth()->id());

		if (Hash::check($input['old_password'], $user->password)) {
			//Passwords are hashed on the model
			$user->password = $input['password'];
			return $user->save();
		}

		throw new GeneralException("That is not your old password.");
	}

	/**
	 * @param $token
	 * @throws GeneralException
	 */
	public function confirmAccount($token) {
		$user = User::where('confirmation_code', $token)->first();

		if ($user) {
			if ($user->confirmed == 1)
				throw new GeneralException("Your account is already confirmed.");

			if ($user->confirmation_code == $token) {
				$user->confirmed = 1;
				return $user->save();
			}

			throw new GeneralException("Your confirmation code does not match.");
		}

		throw new GeneralException("That confirmation code does not exist.");
	}

	/**
	 * @param $user
	 * @return mixed
	 */
	public function sendConfirmationEmail($user) {
		//$user can be user instance or id
		if (! $user instanceof User)
			$user = User::findOrFail($user);

		return Mail::send('emails.confirm', ['token' => $user->confirmation_code], function($message) use ($user)
		{
			$message->to($user->email, $user->name)->subject(app_name().': Confirm your account!');
		});
	}
        
        public function upload_photo($request) {
            $destinationPath = access()->photo_profile_path();
            Storage::makeDirectory(access()->photo_profile_path(FALSE));
            
            // upload image
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileName = md5(uniqid(mt_rand()).microtime()).'.'.$extension;
            $request->file('photo')->move($destinationPath, $fileName);

            // crop image
            $record = User::find(access()->id());
            $old_fileName = base_path($destinationPath . $record->photo);
            $old_record = $record->photo;
            $record->photo = $fileName;
            if ($record->save()) {
                if ($old_record != NULL && file_exists($old_fileName)) {
                    unlink($old_fileName);
                }
                // crop center & resize
                $my_path = base_path($destinationPath . $fileName);
                $imagick = new Imagick($my_path);
                $my_width = $imagick->getimagewidth();
                $my_height = $imagick->getimageheight();
                $my_small_size = $my_width > $my_height ? $my_height : $my_width;
                $my_large_size = $my_width < $my_height ? $my_height : $my_width;
                $size_type = $my_width < $my_height ? 'image_height' : 'image_width';
                $axis = ($my_small_size == $my_large_size) ? 0 : ($my_large_size-$my_small_size)/2;
                $x_axis = $size_type == 'image_width' ? $axis : 0;
                $y_axis = $size_type == 'image_height' ? $axis : 0;
                $imagick->cropimage($my_small_size, $my_small_size, $x_axis, $y_axis);
                if ($my_small_size > 200) {
                    $imagick->adaptiveresizeimage(200, 200);
                }
                $imagick->writeimage($my_path);
            }
        }
}
