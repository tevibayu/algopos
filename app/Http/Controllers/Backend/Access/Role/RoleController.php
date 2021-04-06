<?php namespace App\Http\Controllers\Backend\Access\Role;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Http\Requests\Backend\Access\Role\CreateRoleRequest;
use App\Http\Requests\Backend\Access\Role\StoreRoleRequest;
use App\Http\Requests\Backend\Access\Role\EditRoleRequest;
use App\Http\Requests\Backend\Access\Role\UpdateRoleRequest;
use App\Http\Requests\Backend\Access\Role\DeleteRoleRequest;
use App\Repositories\Backend\Permission\PermissionRepositoryContract;
use App\Repositories\Backend\Permission\Group\PermissionGroupRepositoryContract;
use Illuminate\Http\Request;
use App\Models\Activities\Activities;
/**
 * Class RoleController
 * @package App\Http\Controllers\Access
 */
class RoleController extends Controller {

	/**
	 * @var RoleRepositoryContract
	 */
	protected $roles;

	/**
	 * @var PermissionRepositoryContract
	 */
	protected $permissions;

	/**
	 * @param RoleRepositoryContract $roles
	 * @param PermissionRepositoryContract $permissions
	 */
	public function __construct(RoleRepositoryContract $roles, PermissionRepositoryContract $permissions) {
                
                parent::__construct();
        
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
                $roles = $this->roles->getRolesPaginated($limit);
            } else {
                $roles = $this->roles->getRolesPaginated($limit, true, $search);
            }
            return view('backend.access.roles.index')
                    ->withRoles($roles)
                    ->withSearch($search);
	}

	/**
	 * @param PermissionGroupRepositoryContract $group
	 * @param CreateRoleRequest $request
	 * @return mixed
     */
	public function create(PermissionGroupRepositoryContract $group, CreateRoleRequest $request) {
		return view('backend.access.roles.create')
			->withGroups($group->getAllGroups())
			->withPermissions($this->permissions->getUngroupedPermissions());
	}

	/**
	 * @param StoreRoleRequest $request
	 * @return mixed
	 */
	public function store(StoreRoleRequest $request) {
		$this->roles->create($request->all(),$request);
		return redirect()->route('admin.access.roles.index')->withFlashSuccess(trans("alerts.roles.created"));
	}

	/**
	 * @param $id
	 * @param PermissionGroupRepositoryContract $group
	 * @param EditRoleRequest $request
	 * @return mixed
     */
	public function edit($id, PermissionGroupRepositoryContract $group, EditRoleRequest $request) {
		$role = $this->roles->findOrThrowException($id, true);
		return view('backend.access.roles.edit')
			->withRole($role)
			->withRolePermissions($role->permissions->lists('id')->all())
			->withGroups($group->getAllGroups())
			->withPermissions($this->permissions->getUngroupedPermissions());
	}

	/**
	 * @param $id
	 * @param UpdateRoleRequest $request
	 * @return mixed
	 */
	public function update($id, UpdateRoleRequest $request) {
		$this->roles->update($id, $request->all());
		$activities = new Activities;
        $activities->log_activity($request,'Update Role with id '.$id,'User Management');
		return redirect()->route('admin.access.roles.index')->withFlashSuccess(trans("alerts.roles.updated"));
	}

	/**
	 * @param $id
	 * @param DeleteRoleRequest $request
	 * @return mixed
     */
	public function destroy($id, DeleteRoleRequest $request) {
		$this->roles->destroy($id);
		$activities = new Activities;
        $activities->log_activity($request,'Delete Role with id '.$id,'User Management');
		return redirect()->route('admin.access.roles.index')->withFlashSuccess(trans("alerts.roles.deleted"));
	}
}