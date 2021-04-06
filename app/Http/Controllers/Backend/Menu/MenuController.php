<?php namespace App\Http\Controllers\Backend\Menu;

use App\Http\Controllers\Controller;
use App\Models\Menu\Menu;
use App\Models\Access\Permission\Permission;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use Validator;
use DB;
use Cache;

class MenuController extends Controller {
    
    protected $createPermission;
    protected $editPermission;
    protected $deletePermission;

    public function __construct()
    {
        parent::__construct();
        
        $this->createPermission = 'create-menu';
        $this->editPermission = 'edit-menu';
        $this->deletePermission = 'delete-menu';
    }
    
    public function index()
    {
        $menus = Menu::where('parent_id', 0)->orderBy('order', 'asc')->get();
        
        foreach ($menus as $key => $mn) {
            $submenus = Menu::where('parent_id', $mn->id)->orderBy('order', 'asc')->get();
            if ($submenus) {
                $menus[$key]->child = $submenus;
            } else {
                $submenus = array();
                $menus[$key]->child = $submenus;
            }
        }
        
        return view('backend.menu.index')
                ->withMenus($menus);
    }
    
    public function create()
    {
        $dataparents = Menu::where('parent_id', 0)->orderBy('title', 'asc')->get();
        $parents[0] = 'This is parent menu';
        foreach ($dataparents as $dp) {
            $parents[$dp->id] = $dp->title; 
        }
        $permissions = Permission::where('name', 'like', '%view%')->orderBy('display_name', 'asc')->lists('display_name', 'id');
        $group = DB::table('group_menus')->lists('group_name', 'id');
        
        return view('backend.menu.form')
                ->withParents($parents)
                ->withPermissions($permissions)
                ->withGroup_menus($group)
                ->withData(NULL);
    }
    
    public function edit($id)
    {
        $data = NULL;
        $type = 'add';
        $record = Menu::find($id);
        if (!is_null($record)) {
            $data = $record;
            $type = 'edit';
        }
        $dataparents = Menu::where('parent_id', 0)->orderBy('title', 'asc')->get();
        $parents[0] = 'This is parent menu';
        foreach ($dataparents as $dp) {
            $parents[$dp->id] = $dp->title; 
        }
        $permissions = Permission::where('name', 'like', '%view%')->orderBy('display_name', 'asc')->lists('display_name', 'id');
        $group = DB::table('group_menus')->lists('group_name', 'id');
        
        return view('backend.menu.form')
                ->withParents($parents)
                ->withPermissions($permissions)
                ->withGroup_menus($group)
                ->withType($type)
                ->withData($data);
    }

    public function save_order(Request $request)
    {
        $input = $request->get('data');
        $parent_sort = 1;
        $child_sort = 1;

        foreach ($input as $group) {
            $record = Menu::find((int)$group['id']);
            $record->parent_id = 0;
            $record->order = $parent_sort;
            $record->save();

            if (isset($group['children']) && count($group['children'])) {
                foreach ($group['children'] as $child) {
                    $record = Menu::find((int)$child['id']);
                    $record->parent_id = (int)$group['id'];
                    $record->order = $child_sort;
                    $record->save();

                    $child_sort++;
                }
            }

            $parent_sort++;
        }
        
        Cache::flush();
        die(json_encode(array('type' => 'success', 'title' => 'Success!', 'text' => trans('alerts.menu.menu_order_saved'))));
    }
    
    public function save_menu(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('menuid');
        
        if ($type == 'add') {
            if (!access()->can($this->createPermission)) {
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans('alerts.block'))));
            }
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:menus,title'
            ]);
        } else if ($type == 'edit') {
            if (!access()->can($this->editPermission)) {
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans('alerts.block'))));
            }
            $validator = Validator::make($request->all(), [
                'title' => 'required|unique:menus,title,'.$id.',id'
            ]);
        }
        
        if ($validator->fails()) {
            $my_errors = '';
            foreach ($validator->errors()->all() as $error) {
                $my_errors = $my_errors . $error . '<br />';
            }
            die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => $my_errors)));
        }
        
        if ($type == 'add') {
            $record = new Menu;
            if ($this->save($record, $request)) {
                Cache::flush();
                die(json_encode(array('type' => 'success', 'title' => 'Success!', 'text' => trans("alerts.menu.created"))));
            } else {
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans("alerts.menu.create_failed"))));
            }
        } else if ($type == 'edit') {
            $record = Menu::find($id);
            if (!is_null($record)) {
                if ($this->save($record, $request)) {
                    Cache::flush();
                    die(json_encode(array('type' => 'success', 'title' => 'Success!', 'text' => trans("alerts.menu.updated"))));
                } else {
                    die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans("alerts.menu.update_failed"))));
                }
            } else {
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans('alerts.menu.no_exist'))));
            }
        }
    }
    
    private function save($record, $request)
    {
        $record->title = $request->input('title');
        $record->link = $request->input('link');
        $record->lang = $request->input('lang');
        $record->icon = $request->input('icon');
        $record->target = $request->input('target');
        $record->group_menu = $request->input('group_menu');
        $record->parent_id = $request->input('parent_id');
        $record->permission_id = $request->input('permission_id');
        $record->status = $request->input('status');
        return $record->save();
    }

    public function delete(Request $request) {
        if (! access()->can($this->deletePermission)) {
            die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans('alerts.block'))));
        }
        
        if (isset($request['id'])) {
            $record = Menu::find($request->input('id'));
            if (is_null($record)) {
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans('alerts.menu.no_exist'))));
            }
            
            $my_parent_id = $record->parent_id;
            $parent_id = $record->id;
            if ($record->delete()) {
                // check parent
                if ($my_parent_id == 0) {
                    $record2 = Menu::where('parent_id', $parent_id)->get();
                    if (count($record2->toArray())) {
                        if (Menu::where('parent_id', $parent_id)->delete()) {
                            die(json_encode(array('type' => 'success', 'title' => 'Success!', 'text' => trans('alerts.menu.deleted'))));
                        } else {
                            die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans('alerts.menu.delete_failed'))));
                        }
                    } 
                }
                Cache::flush();
                die(json_encode(array('type' => 'success', 'title' => 'Success!', 'text' => trans('alerts.menu.deleted'))));
            } else {
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans('alerts.menu.delete_failed'))));
            }
        }
    }
}