<?php namespace App\Http\Controllers\Backend\Access\Permission;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Permission\Group\PermissionGroupRepositoryContract;
use App\Http\Requests\Backend\Access\Permission\Group\EditPermissionGroupRequest;
use App\Http\Requests\Backend\Access\Permission\Group\SortPermissionGroupRequest;
use App\Http\Requests\Backend\Access\Permission\Group\StorePermissionGroupRequest;
use App\Http\Requests\Backend\Access\Permission\Group\CreatePermissionGroupRequest;
use App\Http\Requests\Backend\Access\Permission\Group\UpdatePermissionGroupRequest;
use App\Http\Requests\Backend\Access\Permission\Group\DeletePermissionGroupRequest;
use App\Models\Activities\Activities;
/**
 * Class PermissionGroupController
 * @package App\Http\Controllers\Access
 */
class PermissionGroupController extends Controller {

    /**
     * @var PermissionGroupRepositoryContract
     */
    protected $groups;

    /**
     * @param PermissionGroupRepositoryContract $groups
     */
    public function __construct(PermissionGroupRepositoryContract $groups) {
        
        parent::__construct();
        
        $this->groups = $groups;
    }

    /**
     * @param CreatePermissionGroupRequest $request
     * @return \Illuminate\View\View
     */
    public function create(CreatePermissionGroupRequest $request) {
        return view('backend.access.roles.permissions.groups.create');
    }

    /**
     * @param StorePermissionGroupRequest $request
     * @return mixed
     */
    public function store(StorePermissionGroupRequest $request) {
        $this->groups->store($request->all(),$request);
        return redirect()->route('admin.access.roles.permissions.index')->withFlashSuccess(trans("alerts.permissions.groups.created"));
    }

    /**
     * @param $id
     * @param EditPermissionGroupRequest $request
     * @return mixed
     */
    public function edit($id, EditPermissionGroupRequest $request) {
        return view('backend.access.roles.permissions.groups.edit')
            ->withGroup($this->groups->find($id));
    }

    /**
     * @param $id
     * @param UpdatePermissionGroupRequest $request
     * @return mixed
     */
    public function update($id, UpdatePermissionGroupRequest $request) {
        $this->groups->update($id, $request->all());
        $activities = new Activities;
        $activities->log_activity($request,'Update Permission Group '.$id,'User Management');
        return redirect()->route('admin.access.roles.permissions.index')->withFlashSuccess(trans("alerts.permissions.groups.updated"));
    }

    /**
     * @param $id
     * @param DeletePermissionGroupRequest $request
     * @return mixed
     */
    public function destroy($id, DeletePermissionGroupRequest $request) {
        $this->groups->destroy($id);
        $activities = new Activities;
        $activities->log_activity($request,'Delete Permission Group '.$id,'User Management');
        return redirect()->route('admin.access.roles.permissions.index')->withFlashSuccess(trans("alerts.permissions.groups.deleted"));
    }

    /**
     * @param SortPermissionGroupRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSort(SortPermissionGroupRequest $request) {
        $this->groups->updateSort($request->get('data'));
        return response()->json(['status' => 'OK']);
    }
}