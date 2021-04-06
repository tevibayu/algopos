<?php namespace App\Http\Controllers\Backend\Access\Permission;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Role\RoleRepositoryContract;
use App\Repositories\Backend\Permission\PermissionRepositoryContract;
use App\Http\Requests\Backend\Access\Permission\EditPermissionRequest;
use App\Http\Requests\Backend\Access\Permission\StorePermissionRequest;
use App\Http\Requests\Backend\Access\Permission\CreatePermissionRequest;
use App\Http\Requests\Backend\Access\Permission\UpdatePermissionRequest;
use App\Http\Requests\Backend\Access\Permission\DeletePermissionRequest;
use App\Repositories\Backend\Permission\Group\PermissionGroupRepositoryContract;
use Illuminate\Http\Request;
use App\Models\Activities\Activities;
/**
 * Class PermissionController
 * @package App\Http\Controllers\Access
 */
class PermissionController extends Controller {

	/**
	 * @var RoleRepositoryContract
	 */
	protected $roles;

	/**
	 * @var PermissionRepositoryContract
	 */
	protected $permissions;

	/**
	 * @var PermissionGroupRepositoryContract
     */
	protected $groups;

	/**
	 * @param RoleRepositoryContract $roles
	 * @param PermissionRepositoryContract $permissions
	 * @param PermissionGroupRepositoryContract $groups
     */
	public function __construct(RoleRepositoryContract $roles, PermissionRepositoryContract $permissions, PermissionGroupRepositoryContract $groups) {
                
                parent::__construct();
        
		$this->roles = $roles;
		$this->permissions = $permissions;
		$this->groups = $groups;
	}

	/**
	 * @return mixed
	 */
	public function index(Request $request) {
            $limit = config('access.users.default_per_page');
            $search = $request->input('search');
            if ($search == NULL) {
                $permissions = $this->permissions->getPermissionsPaginated($limit);
            } else {
                $permissions = $this->permissions->getPermissionsPaginated($limit, true, $search);
            }
            return view('backend.access.roles.permissions.index')
                    ->withPermissions($permissions)
                    ->withGroups($this->groups->getAllGroups())
                    ->withSearch($search);
	}

	/**
	 * @param CreatePermissionRequest $request
	 * @return mixed
     */
	public function create(CreatePermissionRequest $request) {
		return view('backend.access.roles.permissions.create')
			->withGroups($this->groups->getAllGroups(true))
			->withRoles($this->roles->getAllRoles())
			->withPermissions($this->permissions->getAllPermissions());
	}

	/**
	 * @param StorePermissionRequest $request
	 * @return mixed
     */
	public function store(StorePermissionRequest $request) {
		$this->permissions->create($request->except('permission_roles'), $request->only('permission_roles'),$request);
		return redirect()->route('admin.access.roles.permissions.index')->withFlashSuccess(trans("alerts.permissions.created"));
	}

	/**
	 * @param $id
	 * @param EditPermissionRequest $request
	 * @return mixed
     */
	public function edit($id, EditPermissionRequest $request) {
		$permission = $this->permissions->findOrThrowException($id, true);
		return view('backend.access.roles.permissions.edit')
			->withPermission($permission)
			->withPermissionRoles($permission->roles->lists('id')->all())
			->withGroups($this->groups->getAllGroups(true))
			->withRoles($this->roles->getAllRoles())
			->withPermissions($this->permissions->getAllPermissions())
			->withPermissionDependencies($permission->dependencies->lists('dependency_id')->all());
	}

	/**
	 * @param $id
	 * @param UpdatePermissionRequest $request
	 * @return mixed
     */
	public function update($id, UpdatePermissionRequest $request) {
		$this->permissions->update($id, $request->except('permission_roles'), $request->only('permission_roles'));
		$activities = new Activities;
	    $activities->log_activity($request,'Update Permission id '.$id,'User Management');
		return redirect()->route('admin.access.roles.permissions.index')->withFlashSuccess(trans("alerts.permissions.updated"));
	}

	/**
	 * @param $id
	 * @param DeletePermissionRequest $request
	 * @return mixed
     */
	public function destroy($id, DeletePermissionRequest $request) {
		$this->permissions->destroy($id);
		$activities = new Activities;
	    $activities->log_activity($request,'Delete Permission id '.$id,'User Management');
		return redirect()->route('admin.access.roles.permissions.index')->withFlashSuccess(trans("alerts.permissions.deleted"));
	}
}