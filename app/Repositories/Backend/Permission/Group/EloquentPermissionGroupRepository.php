<?php namespace App\Repositories\Backend\Permission\Group;

use App\Exceptions\GeneralException;
use App\Models\Access\Permission\PermissionGroup;
use App\Models\Activities\Activities;
/**
 * Class EloquentPermissionGroupRepository
 * @package App\Repositories\Backend\Permission\Group
 */
class EloquentPermissionGroupRepository implements PermissionGroupRepositoryContract {

    /**
     * @param $id
     * @return mixed
     */
    public function find($id) {
        return PermissionGroup::findOrFail($id);
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function getGroupsPaginated($limit = 50)
    {
        return PermissionGroup::with('children', 'permissions')
            ->whereNull('parent_id')
            ->orderBy('sort', 'asc')->paginate($limit);
    }

    /**
     * @param bool $withChildren
     * @return mixed
     */
    public function getAllGroups($withChildren = false) {
        if ($withChildren)
            return PermissionGroup::orderBy('name', 'asc')->get();

        return PermissionGroup::with('children', 'permissions')
            ->whereNull('parent_id')
            ->orderBy('sort', 'asc')
            ->get();
    }

    /**
     * @param $input
     * @return static
     */
    public function store($input,$request) {
        $group = new PermissionGroup;
        $group->name = $input['name'];
        $save = $group->save();
        $activities = new Activities;
        $activities->log_activity($request,'Create Permission Group with id '.$group->id,'User Management');
        return $save;
    }

    /**
     * @param $id
     * @param $input
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $input) {
        $group = $this->find($id);

        //Name is changing for whatever reason
        if ($group->name != $input['name'])
            if (PermissionGroup::where('name', $input['name'])->count())
                throw new GeneralException("There is already a group with that name");

        return $group->update($input);
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function destroy($id) {
        $group = $this->find($id);

        if ($group->children->count())
            throw new GeneralException("You can not delete this group because it has child groups.");

        if ($group->permissions->count())
            throw new GeneralException("You can not delete this group because it has associated permissions.");

        return $group->delete();
    }

    /**
     * @param $hierarchy
     * @return bool
     */
    public function updateSort($hierarchy) {
        $parent_sort = 1;
        $child_sort = 1;

        foreach ($hierarchy as $group) {
           $this->find((int)$group['id'])->update([
              'parent_id' => null,
               'sort' => $parent_sort
           ]);

           if (isset($group['children']) && count($group['children'])) {
               foreach ($group['children'] as $child) {
                   $this->find((int)$child['id'])->update([
                       'parent_id' => (int)$group['id'],
                       'sort' => $child_sort
                   ]);

                   $child_sort++;
               }
           }

           $parent_sort++;
        }

        return true;
    }
}