<?php namespace App\Http\Controllers\Backend\Access\User;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\User\UserContract;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Repositories\Frontend\Auth\AuthenticationContract;
use App\Http\Requests\Backend\Access\User\CreateUserRequest;
use App\Http\Requests\Backend\Access\User\StoreUserRequest;
use App\Http\Requests\Backend\Access\User\EditUserRequest;
use App\Http\Requests\Backend\Access\User\MarkUserRequest;
use App\Http\Requests\Backend\Access\User\UpdateUserRequest;
use App\Http\Requests\Backend\Access\User\DeleteUserRequest;
use App\Http\Requests\Backend\Access\User\RestoreUserRequest;
use App\Http\Requests\Backend\Access\User\ChangeUserPasswordRequest;
use App\Http\Requests\Backend\Access\User\UpdateUserPasswordRequest;
use App\Repositories\Backend\Permission\PermissionRepositoryContract;
use App\Http\Requests\Backend\Access\User\PermanentlyDeleteUserRequest;
use App\Http\Requests\Backend\Access\User\ResendConfirmationEmailRequest;
use Illuminate\Http\Request;
use App\Models\Activities\Activities;

/**
 * Class UserController
 */
class UserController extends Controller {

	/**
	 * @var UserContract
	 */
	protected $users;

	/**
	 * @var RoleRepositoryContract
	 */
	protected $roles;

	/**
	 * @var PermissionRepositoryContract
	 */
	protected $permissions;

	/**
	 * @param UserContract $users
	 * @param RoleRepositoryContract $roles
	 * @param PermissionRepositoryContract $permissions
	 */
	public function __construct(UserContract $users, RoleRepositoryContract $roles, PermissionRepositoryContract $permissions) {
                
                parent::__construct();
        
		$this->users = $users;
		$this->roles = $roles;
		$this->permissions = $permissions;
	}

	/**
	 * @return mixed
	 */
	public function index(Request $request) {
            $limit = config('access.users.default_per_page');
            $search = $request->input('search');
            if ($search == NULL) {
                $users = $this->users->getUsersPaginated($limit, 1);
            } else {
                $users = $this->users->getUsersPaginated($limit, 1, true, $search);
            }
            return view('backend.access.index')
                    ->withUsers($users)
                    ->withSearch($search);
	}

	/**
	 * @param CreateUserRequest $request
	 * @return mixed
     */
	public function create(CreateUserRequest $request) {
		return view('backend.access.create')
			->withRoles($this->roles->getAllRoles('sort', 'asc', true))
			->withPermissions($this->permissions->getAllPermissions());
	}

	/**
	 * @param StoreUserRequest $request
	 * @return mixed
     */
	public function store(StoreUserRequest $request) {
		$this->users->create(
			$request->except('assignees_roles', 'permission_user'),
			$request->only('assignees_roles'),
			$request->only('permission_user'),
			$request
		);
		return redirect()->route('admin.access.users.index')->withFlashSuccess(trans("alerts.users.created"));
	}

	/**
	 * @param $id
	 * @param EditUserRequest $request
	 * @return mixed
     */
	public function edit($id, EditUserRequest $request) {
		$user = $this->users->findOrThrowException($id, true);
		return view('backend.access.edit')
			->withUser($user)
			->withUserRoles($user->roles->lists('id')->all())
			->withRoles($this->roles->getAllRoles('sort', 'asc', true))
			->withUserPermissions($user->permissions->lists('id')->all())
			->withPermissions($this->permissions->getAllPermissions());
	}

	/**
	 * @param $id
	 * @param UpdateUserRequest $request
	 * @return mixed
	 */
	public function update($id, UpdateUserRequest $request) {
		$this->users->update($id,
			$request->except('assignees_roles', 'permission_user'),
			$request->only('assignees_roles'),
			$request->only('permission_user')
		);
		$activities = new Activities;
        $activities->log_activity($request,'Update User id '.$id,'User Management');
		return redirect()->route('admin.access.users.index')->withFlashSuccess(trans("alerts.users.updated"));
	}

	/**
	 * @param $id
	 * @param DeleteUserRequest $request
	 * @return mixed
     */
	public function destroy($id, DeleteUserRequest $request) {
		$this->users->destroy($id);
		$activities = new Activities;
        $activities->log_activity($request,'Request Delete User id '.$id,'User Management');
		return redirect()->back()->withFlashSuccess(trans("alerts.users.deleted"));
	}

	/**
	 * @param $id
	 * @param PermanentlyDeleteUserRequest $request
	 * @return mixed
     */
	public function delete($id, PermanentlyDeleteUserRequest $request) {
		$this->users->delete($id);
		$activities = new Activities;
        $activities->log_activity($request,'Delete Permanently User id '.$id,'User Management');
		return redirect()->back()->withFlashSuccess(trans("alerts.users.deleted_permanently"));
	}

	/**
	 * @param $id
	 * @param RestoreUserRequest $request
	 * @return mixed
     */
	public function restore($id, RestoreUserRequest $request) {
		$this->users->restore($id);
		$activities = new Activities;
        $activities->log_activity($request,'Restore User id '.$id,'User Management');
		return redirect()->back()->withFlashSuccess(trans("alerts.users.restored"));
	}

	/**
	 * @param $id
	 * @param $status
	 * @param MarkUserRequest $request
	 * @return mixed
     */
	public function mark($id, $status, MarkUserRequest $request) {
		$this->users->mark($id, $status);
		$activities = new Activities;
		if($status==0)
		{
	        $activities->log_activity($request,'Deactivated User with id '.$id,'User Management');
		}else
		if($status==1)
		{
	        $activities->log_activity($request,'Activated User with id '.$id,'User Management');
		}else
		if($status==2)
		{
	        $activities->log_activity($request,'Banned User with id '.$id,'User Management');
		}
		return redirect()->back()->withFlashSuccess(trans("alerts.users.updated"));
	}

	/**
	 * @return mixed
	 */
	public function deactivated(Request $request) {
            $limit = config('access.users.default_per_page');
            $search = $request->input('search');
            if ($search == NULL) {
                $users = $this->users->getUsersPaginated($limit, 0);
            } else {
                $users = $this->users->getUsersPaginated($limit, 0, true, $search);
            }
            return view('backend.access.deactivated')
                    ->withUsers($users)
                    ->withSearch($search);
	}

	/**
	 * @return mixed
	 */
	public function deleted(Request $request) {
            $limit = config('access.users.default_per_page');
            $search = $request->input('search');
            if ($search == NULL) {
                $users = $this->users->getDeletedUsersPaginated($limit);
            } else {
                $users = $this->users->getDeletedUsersPaginated($limit, true, $search);
            }
            return view('backend.access.deleted')
                    ->withUsers($users)
                    ->withSearch($search);
	}

	/**
	 * @return mixed
	 */
	public function banned(Request $request) {
            $limit = config('access.users.default_per_page');
            $search = $request->input('search');
            if ($search == NULL) {
                $users = $this->users->getUsersPaginated($limit, 2);
            } else {
                $users = $this->users->getUsersPaginated($limit, 2, true, $search);
            }
            return view('backend.access.banned')
                    ->withUsers($users)
                    ->withSearch($search);
	}

	/**
	 * @param $id
	 * @param ChangeUserPasswordRequest $request
	 * @return mixed
     */
	public function changePassword($id, ChangeUserPasswordRequest $request) {
		return view('backend.access.change-password')
			->withUser($this->users->findOrThrowException($id));
	}

	/**
	 * @param $id
	 * @param UpdateUserPasswordRequest $request
	 * @return mixed
	 */
	public function updatePassword($id, UpdateUserPasswordRequest $request) {
		$this->users->updatePassword($id, $request->all());
		$activities = new Activities;
        $activities->log_activity($request,'Update Password with id '.$id,'User Management');
		return redirect()->route('admin.access.users.index')->withFlashSuccess(trans("alerts.users.updated_password"));
	}

	/**
	 * @param $user_id
	 * @param AuthenticationContract $auth
	 * @param ResendConfirmationEmailRequest $request
	 * @return mixed
     */
	public function resendConfirmationEmail($user_id, AuthenticationContract $auth, ResendConfirmationEmailRequest $request) {
		$auth->resendConfirmationEmail($user_id);
		return redirect()->back()->withFlashSuccess(trans("alerts.users.confirmation_email"));
	}
}