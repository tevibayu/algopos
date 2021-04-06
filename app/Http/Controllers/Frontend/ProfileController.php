<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\User\UserContract;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Models\Access\User\User;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Frontend
 */
class ProfileController extends Controller {
    
        public function __construct() {
            parent::__construct();
        }

    /**
	 * @return mixed
     */
	public function edit() {
		return view('frontend.user.profile.edit')
			->withUser(auth()->user());
	}

	/**
	 * @param UserContract $user
	 * @param UpdateProfileRequest $request
	 * @return mixed
	 */
	public function update(UserContract $user, UpdateProfileRequest $request) {
		$user->updateProfile($request->all());
		return redirect()->route('frontend.profile.edit')->withFlashSuccess(trans("strings.profile_successfully_updated"));
	}
        
    public function photo(UserContract $user, Request $request) {
        if (isset($request['photo'])) {
            $this->validate($request, [
                'photo' => 'required|mimes:jpeg,jpg,bmp,png',
            ]);
            if ($request->file('photo')->isValid()) {
                $user->upload_photo($request);
                return redirect()->route('photo.change')
                        ->withFlashSuccess(trans("strings.photo_successfully_updated"));
            } else {
                throw new GeneralException(trans("strings.photo_invalid"));
            }
        }

        return view('frontend.user.profile.photo');
	}
}