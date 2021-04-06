<?php namespace Modules\Localizations\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Localizations\Entities\Localizations;
use Modules\Localizations\Entities\Modules;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use Cache;
use View;
use DB;
use Module;
use App\Models\Access\Permission\Permission;
use App\Models\Activities\Activities;

class LocalizationsController extends Controller {
	
    protected $createPermissionExcept;
    protected $editPermissionExcept;
    protected $deletePermissionExcept;
    protected $keyExcept;
    protected $keyCountPageExcept;
    
    protected $createPermissionPopular;
    protected $editPermissionPopular;
    protected $deletePermissionPopular;
    protected $keyPopular;
    protected $keyCountPagePopular;
    
    protected $createPermissionFeature;
    protected $editPermissionFeature;
    protected $deletePermissionFeature;
    protected $keyFeature;
    protected $keyCountPageFeature;

    public function __construct()
    {
        parent::__construct();
        
        $this->createPermissionExcept = 'create-except-localizations';
        $this->editPermissionExcept = 'edit-except-localizations';
        $this->deletePermissionExcept = 'delete-except-localizations';
        $this->keyExcept = access()->myTimeZone() . 'except-localizations';
        $this->keyCountPageExcept = $this->keyExcept . '-count-page';
        
        $this->createPermissionPopular = 'create-popular-localizations';
        $this->editPermissionPopular = 'edit-popular-localizations';
        $this->deletePermissionPopular = 'delete-popular-localizations';
        $this->keyPopular = access()->myTimeZone() . 'popular-localizations';
        $this->keyCountPagePopular = $this->keyPopular . '-count-page';
        
        $this->createPermissionFeature = 'create-feature-localizations';
        $this->editPermissionFeature = 'edit-feature-localizations';
        $this->deletePermissionFeature = 'delete-feature-localizations';
        $this->keyFeature = access()->myTimeZone() . 'feature-localizations';
        $this->keyCountPageFeature = $this->keyFeature . '-count-page';
        
        View::share('createPermissionExcept', $this->createPermissionExcept);
        View::share('editPermissionExcept', $this->editPermissionExcept);
        View::share('deletePermissionExcept', $this->deletePermissionExcept);
        
        View::share('createPermissionPopular', $this->createPermissionPopular);
        View::share('editPermissionPopular', $this->editPermissionPopular);
        View::share('deletePermissionPopular', $this->deletePermissionPopular);
        
        View::share('createPermissionFeature', $this->createPermissionFeature);
        View::share('editPermissionFeature', $this->editPermissionFeature);
        View::share('deletePermissionFeature', $this->deletePermissionFeature);
    }
	
    public function except(Request $request)
    {
        $key_cache = isset($request['page']) ? $this->keyExcept . $request->input('page') : $this->keyExcept;
        $limit = config('access.users.default_per_page');
        $search = $request->input('search');
        
        if ($search == NULL) {
            $records = Cache::rememberForever($key_cache, function() use($limit) {
                $records = Localizations::join('modules', 'localizations.modules_id_module', '=', 'modules.id_module')
                                    ->where('type', '=', 'except')
                                    ->orderBy('timezone', 'asc')
                                    ->orderBy('module_name', 'asc')
                                    ->paginate($limit);
                if ($records->count()) {
                    foreach ($records as $record) {
                        $classObj = new Modules();
                        $except_records = $classObj->get_records($record->table_name, $record->field_id, $record->field_name, $record->records);
                        $record->records = $except_records;
                    }
                }
                return $records;
            });
        } else {
            $records = Localizations::join('modules', 'localizations.modules_id_module', '=', 'modules.id_module')
                                    ->where('type', '=', 'except')
                                    ->orderBy('timezone', 'asc')
                                    ->orderBy('module_name', 'asc')
                                    ->search(
                                        $search,
                                        array('module_name'),
                                        array('timezone' => access()->listTimeZone())
                                    )->paginate($limit);
            if ($records->count()) {
                foreach ($records as $record) {
                    $classObj = new Modules();
                    $except_records = $classObj->get_records($record->table_name, $record->field_id, $record->field_name, $record->records);
                    $record->records = $except_records;
                }
            }
        }
        Cache::forever($this->keyCountPageExcept, $records->lastPage());
        
        $numb = (($records->currentPage()-1) * $limit) + 1;
        return view('localizations::except')
                ->withRecords($records)
                ->withNumb($numb)
                ->withSearch($search);
    }
    
    public function create_except(Request $request)
    {
        if (! access()->can($this->createPermissionExcept)) {
            return access()->block();
        }
        
        if (isset($request['save'])) {
            $input_records = $request->input('records');
            $except_records = '';
            
            $this->validate($request, [
                'timezone' => 'required',
                'module' => 'required'
            ]);
            
            if (count($input_records)) {
                $input_records = array_unique($input_records);
                foreach ($input_records as $key => $input_record) {
                    $request['records'] = $input_record;
                    $this->validate($request, [
                        'records' => 'required'
                    ]);
                }
            } else {
                $this->validate($request, [
                    'records' => 'required'
                ]);
            }
            
            $lzt_records = Localizations::where('type', '=', 'except')
                                        ->where('timezone', '=', $request->input('timezone'))
                                        ->where('modules_id_module', '=', $request->input('module'))
                                        ->first();
            if ($lzt_records == null) {
                $lzt_records = new Localizations;
                $except_records = $input_records;
            } else {
                $old_except_records = $lzt_records->records;
                $arr_old_except_records = explode(',', $old_except_records);
                $except_records = array_merge($input_records, $arr_old_except_records);
            }
            $result = $this->save_except($request, $lzt_records, $except_records);
            // die('ini'.$result['save']);
            if ($result['save']) {
                $activities = new Activities;
                $activities->log_activity($request,'Create Except Localization id '.$result['id'].'','Localizations');
                Cache::flush();
                return redirect()->route('admin.localizations.except')->withFlashSuccess(trans("localizations::except.alerts.created"));
            } else {
                throw new GeneralException(trans("localizations::except.alerts.create_failed"));
            }
        }
        
        $listTimezone = access()->listTimeZone();
        $listModules = Modules::lists('module_name', 'id_module')->toArray();
        
        return view('localizations::form_except')
                ->withTimezone($listTimezone)
                ->withModules($listModules)
                ->withTitle(trans('localizations::except.menus.create'))
                ->withMy_link(link_to_route('admin.localizations.create_except', trans('localizations::except.menus.create')))
                ->withForm_type('create')
                ->withRecord(NULL);
    }
    
    public function edit_except(Request $request, $id)
    {
        if (! access()->can($this->editPermissionExcept)) {
            return access()->block();
        }
        
        $record = Localizations::where('id_localization', '=', $id)
                                ->where('type', '=', 'except')
                                ->first();
        if (is_null($record)) {
            throw new GeneralException(trans("localizations::except.alerts.no_exist"));
        }
        
        if (isset($request['save'])) {
            $input_records = $request->input('records');
            $except_records = '';
            
            $this->validate($request, [
                'timezone' => 'required',
                'module' => 'required'
            ]);
            
            if (count($input_records)) {
                $input_records = array_unique($input_records);
                foreach ($input_records as $key => $input_record) {
                    $request['records'] = $input_record;
                    $this->validate($request, [
                        'records' => 'required'
                    ]);
                }
                $except_records = $input_records;
            } else {
                $this->validate($request, [
                    'records' => 'required'
                ]);
            }
            
            if ($this->save_except($request, $record, $except_records)) {
                $activities = new Activities;
                $activities->log_activity($request,'Update Except Localization with id '.$id,'Localizations');
                Cache::flush();
                return redirect()->route('admin.localizations.except')->withFlashSuccess(trans("localizations::except.alerts.updated"));
            } else {
                throw new GeneralException(trans("localizations::except.alerts.update_failed"));
            }
        }
        
        $except_records = $record->records;
        $listTimezone = access()->listTimeZone();
        $listModules = Modules::lists('module_name', 'id_module')->toArray();
        
        return view('localizations::form_except')
                ->withTimezone($listTimezone)
                ->withModules($listModules)
                ->withTitle(trans('localizations::except.menus.edit'))
                ->withMy_link(link_to_route('admin.localizations.edit_except', trans('localizations::except.menus.edit'), $record->id_localization))
                ->withForm_type('edit')
                ->withRecord($record)
                ->withExcept_records($except_records);
    }
    
    private function save_except($request, $record, $except_records)
    {
        $my_except_records = '';
        if (count($except_records)) {
            $except_records = array_unique($except_records);
            foreach ($except_records as $key => $except_record) {
                if ($key == 0) {
                    $my_except_records .= $except_record;
                } else {
                    $my_except_records .= ',' . $except_record;
                }
            }
        }
        $record->type = 'except';
        $record->timezone = $request->input('timezone');
        $record->modules_id_module = $request->input('module');
        $record->records = $my_except_records;
        $save = $record->save();        

        $result = array(
            'save' => $save,
            'id' => $record->id_localization
        );
        return $result;
    }
    
    public function get_records(Request $request)
    {
        $id_module = $request->input('id_module');
        $module = Modules::where('id_module', '=', $id_module)->first();
        $field_id = $module->field_id;
        $field_name = $module->field_name;
        
        $records = DB::table($module->table_name)->get();
        $result = array();
        if (count($records)) {
            foreach ($records as $record) {
                $result[] = array(
                    'id_record' => $record->$field_id,
                    'name' => $record->$field_name
                );
            }
        }
        
        die(json_encode(array('records' => $result)));
    }
    
    public function delete_except($id, Request $request) {
        if (! access()->can($this->deletePermissionExcept)) {
            return access()->block();
        }
        
        $record = Localizations::where('id_localization', '=', $id)
                                ->where('type', '=', 'except')
                                ->first();
        if (is_null($record)) {
            throw new GeneralException(trans("localizations::except.alerts.no_exist"));
        }
        
        if ($record->delete()) {
            $activities = new Activities;
            $activities->log_activity($request,'Delete Except Localization with id '.$id,'Localizations');
            Cache::flush();
            return redirect()->route('admin.localizations.except')->withFlashSuccess(trans("localizations::except.alerts.deleted"));
        } else {
            throw new GeneralException(trans("localizations::except.alerts.delete_failed"));
        }
    }
    
    public function batch_delete_except(Request $request)
    {
        if (! access()->can($this->deletePermissionExcept)) {
            return access()->block();
        }
        
        $checked = $request->input('checked');
        if (is_array($checked) && count($checked)) {
            foreach ($checked as $id) {
                $record = Localizations::where('id_localization', '=', $id)
                                        ->where('type', '=', 'except')
                                        ->first();
                if (! is_null($record)) {
                    $record->delete();
                    $activities = new Activities;
                    $activities->log_activity($request,'Delete Except Localization with id '.$id,'Localizations');
                }
            }
            Cache::flush();
            return redirect()->route('admin.localizations.except')->withFlashSuccess(trans("localizations::except.alerts.deleted"));
        } else {
            throw new GeneralException(trans("localizations::except.alerts.not_selected"));
        }
    }
    
    public function popular(Request $request)
    {
        $key_cache = isset($request['page']) ? $this->keyPopular . $request->input('page') : $this->keyPopular;
        $limit = config('access.users.default_per_page');
        $search = $request->input('search');
        
        if ($search == NULL) {
            $records = Cache::rememberForever($key_cache, function() use($limit) {
                $records = Localizations::join('modules', 'localizations.modules_id_module', '=', 'modules.id_module')
                                        ->where('type', '=', 'popular')
                                        ->orderBy('timezone', 'asc')
                                        ->orderBy('module_name', 'asc')
                                        ->paginate($limit);
                if ($records->count()) {
                    foreach ($records as $record) {
                        $classObj = new Modules();
                        $popular_records = $classObj->get_records($record->table_name, $record->field_id, $record->field_name, $record->records);
                        $record->records = $popular_records;
                    }
                }
                return $records;
            });
        } else {
            $records = Localizations::join('modules', 'localizations.modules_id_module', '=', 'modules.id_module')
                                    ->where('type', '=', 'popular')
                                    ->orderBy('timezone', 'asc')
                                    ->orderBy('module_name', 'asc')
                                    ->search(
                                        $search,
                                        array('module_name'),
                                        array('timezone' => access()->listTimeZone())
                                    )->paginate($limit);
            if ($records->count()) {
                foreach ($records as $record) {
                    $classObj = new Modules();
                    $popular_records = $classObj->get_records($record->table_name, $record->field_id, $record->field_name, $record->records);
                    $record->records = $popular_records;
                }
            }
        }
        Cache::forever($this->keyCountPagePopular, $records->lastPage());
        
        $numb = (($records->currentPage()-1) * $limit) + 1;
        return view('localizations::popular')
                ->withRecords($records)
                ->withNumb($numb)
                ->withSearch($search);
    }
    
    public function create_popular(Request $request)
    {
        if (! access()->can($this->createPermissionPopular)) {
            return access()->block();
        }
        
        if (isset($request['save'])) {
            $input_records = $request->input('records');
            $popular_records = '';
            
            $this->validate($request, [
                'timezone' => 'required',
                'module' => 'required'
            ]);
            
            if (count($input_records)) {
                $limit = config('access.users.default_per_page');
                $input_records = array_unique($input_records);
                $temp_records = array();
                foreach ($input_records as $key => $input_record) {
                    $request['records'] = $input_record;
                    $this->validate($request, [
                        'records' => 'required'
                    ]);
                    if ($key < $limit) {
                        $temp_records[] = $input_record;
                    }
                }
                $input_records = $temp_records;
            } else {
                $this->validate($request, [
                    'records' => 'required'
                ]);
            }
            
            $lzt_records = Localizations::where('type', '=', 'popular')
                                        ->where('timezone', '=', $request->input('timezone'))
                                        ->where('modules_id_module', '=', $request->input('module'))
                                        ->first();
            if ($lzt_records == null) {
                $lzt_records = new Localizations;
                $popular_records = $input_records;
            } else {
                $old_popular_records = $lzt_records->records;
                $arr_old_popular_records = explode(',', $old_popular_records);
                $popular_records = array_merge($input_records, $arr_old_popular_records);
            }
            $result = $this->save_popular($request, $lzt_records, $popular_records);
            if ($result['save']) {
                $activities = new Activities;
                $activities->log_activity($request,'Create Popular Localization id '.$result['id'].'','Localizations');
                Cache::flush();
                return redirect()->route('admin.localizations.popular')->withFlashSuccess(trans("localizations::popular.alerts.created"));
            } else {
                throw new GeneralException(trans("localizations::popular.alerts.create_failed"));
            }
        }
        
        $listTimezone = access()->listTimeZone();
        $listModules = Modules::lists('module_name', 'id_module')->toArray();
        
        return view('localizations::form_popular')
                ->withTimezone($listTimezone)
                ->withModules($listModules)
                ->withTitle(trans('localizations::popular.menus.create'))
                ->withMy_link(link_to_route('admin.localizations.create_popular', trans('localizations::popular.menus.create')))
                ->withForm_type('create')
                ->withRecord(NULL);
    }
    
    public function edit_popular(Request $request, $id)
    {
        if (! access()->can($this->editPermissionPopular)) {
            return access()->block();
        }
        
        $record = Localizations::where('id_localization', '=', $id)
                                ->where('type', '=', 'popular')
                                ->first();
        if (is_null($record)) {
            throw new GeneralException(trans("localizations::popular.alerts.no_exist"));
        }
        
        if (isset($request['save'])) {
            $input_records = $request->input('records');
            $popular_records = '';
            
            $this->validate($request, [
                'timezone' => 'required',
                'module' => 'required'
            ]);
            
            if (count($input_records)) {
                $limit = config('access.users.default_per_page');
                $input_records = array_unique($input_records);
                $temp_records = array();
                foreach ($input_records as $key => $input_record) {
                    $request['records'] = $input_record;
                    $this->validate($request, [
                        'records' => 'required'
                    ]);
                    if ($key < $limit) {
                        $temp_records[] = $input_record;
                    }
                }
                $popular_records = $temp_records;
            } else {
                $this->validate($request, [
                    'records' => 'required'
                ]);
            }
            
            if ($this->save_popular($request, $record, $popular_records)) {
                $activities = new Activities;
                $activities->log_activity($request,'Update Popular Localization with id '.$id,'Localizations');
                Cache::flush();
                return redirect()->route('admin.localizations.popular')->withFlashSuccess(trans("localizations::popular.alerts.updated"));
            } else {
                throw new GeneralException(trans("localizations::popular.alerts.update_failed"));
            }
        }
        
        $popular_records = $record->records;
        $listTimezone = access()->listTimeZone();
        $listModules = Modules::lists('module_name', 'id_module')->toArray();
        
        return view('localizations::form_popular')
                ->withTimezone($listTimezone)
                ->withModules($listModules)
                ->withTitle(trans('localizations::popular.menus.edit'))
                ->withMy_link(link_to_route('admin.localizations.edit_popular', trans('localizations::popular.menus.edit'), $record->id_localization))
                ->withForm_type('edit')
                ->withRecord($record)
                ->withPopular_records($popular_records);
    }
    
    private function save_popular($request, $record, $popular_records)
    {
        $my_popular_records = '';
        if (count($popular_records)) {
            $popular_records = array_unique($popular_records);
            foreach ($popular_records as $key => $popular_record) {
                if ($key == 0) {
                    $my_popular_records .= $popular_record;
                } else {
                    $my_popular_records .= ',' . $popular_record;
                }
            }
        }
        $record->type = 'popular';
        $record->timezone = $request->input('timezone');
        $record->modules_id_module = $request->input('module');
        $record->records = $my_popular_records;
        $save = $record->save();        

        $result = array(
            'save' => $save,
            'id' => $record->id_localization
        );

        return $result;
    }
    
    public function delete_popular($id, Request $request) {
        if (! access()->can($this->deletePermissionPopular)) {
            return access()->block();
        }
        
        $record = Localizations::where('id_localization', '=', $id)
                                ->where('type', '=', 'popular')
                                ->first();
        if (is_null($record)) {
            throw new GeneralException(trans("localizations::popular.alerts.no_exist"));
        }
        
        if ($record->delete()) {
            $activities = new Activities;
            $activities->log_activity($request,'Delete Popular Localization with id '.$id,'Localizations');
            Cache::flush();
            return redirect()->route('admin.localizations.popular')->withFlashSuccess(trans("localizations::popular.alerts.deleted"));
        } else {
            throw new GeneralException(trans("localizations::popular.alerts.delete_failed"));
        }
    }
    
    public function batch_delete_popular(Request $request)
    {
        if (! access()->can($this->deletePermissionPopular)) {
            return access()->block();
        }
        
        $checked = $request->input('checked');
        if (is_array($checked) && count($checked)) {
            foreach ($checked as $id) {
                $record = Localizations::where('id_localization', '=', $id)
                                        ->where('type', '=', 'popular')
                                        ->first();
                if (! is_null($record)) {
                    $record->delete();
                    $activities = new Activities;
                    $activities->log_activity($request,'Delete Popular Localization with id '.$id,'Localizations');
                }
            }
            Cache::flush();
            return redirect()->route('admin.localizations.popular')->withFlashSuccess(trans("localizations::popular.alerts.deleted"));
        } else {
            throw new GeneralException(trans("localizations::popular.alerts.not_selected"));
        }
    }
    
    public function feature(Request $request)
    {
        $key_cache = isset($request['page']) ? $this->keyFeature . $request->input('page') : $this->keyFeature;
        $limit = config('access.users.default_per_page');
        $search = $request->input('search');
        
        if ($search == NULL) {
            $records = Cache::rememberForever($key_cache, function() use($limit) {
                $records = Localizations::where('type', '=', 'feature')
                                        ->orderBy('timezone', 'asc')
                                        ->paginate($limit);
                if ($records->count()) {
                    foreach ($records as $record) {
                        $classObj = new Modules();
                        $feature_records = $classObj->get_records('permissions', 'id', 'display_name', $record->records, TRUE);
                        $record->records = $feature_records;
                    }
                }
                return $records;
            });
        } else {
            $records = Localizations::where('type', '=', 'feature')
                                    ->orderBy('timezone', 'asc')
                                    ->search(
                                        $search,
                                        null,
                                        array('timezone' => access()->listTimeZone())
                                    )->paginate($limit);
            if ($records->count()) {
                foreach ($records as $record) {
                    $classObj = new Modules();
                    $feature_records = $classObj->get_records('permissions', 'id', 'display_name', $record->records, TRUE);
                    $record->records = $feature_records;
                }
            }
        }
        Cache::forever($this->keyCountPageFeature, $records->lastPage());
        
        $numb = (($records->currentPage()-1) * $limit) + 1;
        return view('localizations::feature')
                ->withRecords($records)
                ->withNumb($numb)
                ->withSearch($search);
    }
    
    public function create_feature(Request $request)
    {
        if (! access()->can($this->createPermissionFeature)) {
            return access()->block();
        }
        
        if (isset($request['save'])) {
            $input_records = $request->input('records');
            $feature_records = '';
            
            $this->validate($request, [
                'timezone' => 'required'
            ]);
            
            if (count($input_records)) {
                $limit = config('access.users.default_per_page');
                $input_records = array_unique($input_records);
                $temp_records = array();
                foreach ($input_records as $key => $input_record) {
                    $request['module'] = $input_record;
                    $this->validate($request, [
                        'module' => 'required'
                    ]);
                    if ($key < $limit) {
                        $temp_records[] = $input_record;
                    }
                }
                $input_records = $temp_records;
            } else {
                $this->validate($request, [
                    'module' => 'required'
                ]);
            }
            
            $lzt_records = Localizations::where('type', '=', 'feature')
                                        ->where('timezone', '=', $request->input('timezone'))
                                        ->where('modules_id_module', '=', $request->input('module'))
                                        ->first();
            if ($lzt_records == null) {
                $lzt_records = new Localizations;
                $feature_records = $input_records;
            } else {
                $old_feature_records = $lzt_records->records;
                $arr_old_feature_records = explode(',', $old_feature_records);
                $feature_records = array_merge($input_records, $arr_old_feature_records);
            }
            $result = $this->save_feature($request, $lzt_records, $feature_records);
            if ($result['save']) {
                $activities = new Activities;
                $activities->log_activity($request,'Create Feature Localization id '.$result['id'].'','Localizations');
                Cache::flush();
                return redirect()->route('admin.localizations.feature')->withFlashSuccess(trans("localizations::feature.alerts.created"));
            } else {
                throw new GeneralException(trans("localizations::feature.alerts.create_failed"));
            }
        }
        
        $listTimezone = access()->listTimeZone();
        $listModules = Modules::lists('module_name', 'id_module')->toArray();
        
        $permissions = Permission::where('display_name', 'like', '%View%')
                                ->orderBy('display_name')
                                ->lists('display_name', 'id');
        if (count($permissions)) {
            foreach ($permissions as $key => $perm) {
                $permissions[$key] = last(explode('View', $perm));
            }
        }
        
        return view('localizations::form_feature')
                ->withTimezone($listTimezone)
                ->withModules($listModules)
                ->withTitle(trans('localizations::feature.menus.create'))
                ->withMy_link(link_to_route('admin.localizations.create_feature', trans('localizations::feature.menus.create')))
                ->withForm_type('create')
                ->withRecord(NULL)
                ->withPermissions($permissions);
    }
    
    public function edit_feature(Request $request, $id)
    {
        if (! access()->can($this->editPermissionFeature)) {
            return access()->block();
        }
        
        $record = Localizations::where('id_localization', '=', $id)
                                ->where('type', '=', 'feature')
                                ->first();
        if (is_null($record)) {
            throw new GeneralException(trans("localizations::feature.alerts.no_exist"));
        }
        
        if (isset($request['save'])) {
            $input_records = $request->input('records');
            $feature_records = '';
            
            $this->validate($request, [
                'timezone' => 'required'
            ]);
            
            if (count($input_records)) {
                $limit = config('access.users.default_per_page');
                $input_records = array_unique($input_records);
                $temp_records = array();
                foreach ($input_records as $key => $input_record) {
                    $request['module'] = $input_record;
                    $this->validate($request, [
                        'module' => 'required'
                    ]);
                    if ($key < $limit) {
                        $temp_records[] = $input_record;
                    }
                }
                $feature_records = $temp_records;
            } else {
                $this->validate($request, [
                    'module' => 'required'
                ]);
            }
            
            if ($this->save_feature($request, $record, $feature_records)) {
                $activities = new Activities;
                $activities->log_activity($request,'Update Feature Localization with id '.$id,'Localizations');
                Cache::flush();
                return redirect()->route('admin.localizations.feature')->withFlashSuccess(trans("localizations::feature.alerts.updated"));
            } else {
                throw new GeneralException(trans("localizations::feature.alerts.update_failed"));
            }
        }
        
        $feature_records = explode(',', $record->records);
        $listTimezone = access()->listTimeZone();
        $listModules = Modules::lists('module_name', 'id_module')->toArray();
        
        $permissions = Permission::where('display_name', 'like', '%View%')
                                ->orderBy('display_name')
                                ->lists('display_name', 'id');
        if (count($permissions)) {
            foreach ($permissions as $key => $perm) {
                $permissions[$key] = last(explode('View', $perm));
            }
        }
        
        return view('localizations::form_feature')
                ->withTimezone($listTimezone)
                ->withModules($listModules)
                ->withTitle(trans('localizations::feature.menus.edit'))
                ->withMy_link(link_to_route('admin.localizations.edit_feature', trans('localizations::feature.menus.edit'), $record->id_localization))
                ->withForm_type('edit')
                ->withRecord($record)
                ->withFeature_records($feature_records)
                ->withPermissions($permissions);
    }
    
    private function save_feature($request, $record, $feature_records)
    {
        $my_feature_records = '';
        if (count($feature_records)) {
            $feature_records = array_unique($feature_records);
            foreach ($feature_records as $key => $feature_record) {
                if ($key == 0) {
                    $my_feature_records .= $feature_record;
                } else {
                    $my_feature_records .= ',' . $feature_record;
                }
            }
        }
        $record->type = 'feature';
        $record->timezone = $request->input('timezone');
        $record->records = $my_feature_records;
        $save = $record->save();        

        $result = array(
            'save' => $save,
            'id' => $record->id_localization
        );

        return $result;
    }
    
    public function delete_feature($id, Request $request) {
        if (! access()->can($this->deletePermissionFeature)) {
            return access()->block();
        }
        
        $record = Localizations::where('id_localization', '=', $id)
                                ->where('type', '=', 'feature')
                                ->first();
        if (is_null($record)) {
            throw new GeneralException(trans("localizations::feature.alerts.no_exist"));
        }
        
        if ($record->delete()) {
            $activities = new Activities;
            $activities->log_activity($request,'Delete Feature Localization with id '.$id,'Localizations');
            Cache::flush();
            return redirect()->route('admin.localizations.feature')->withFlashSuccess(trans("localizations::feature.alerts.deleted"));
        } else {
            throw new GeneralException(trans("localizations::feature.alerts.delete_failed"));
        }
    }
    
    public function batch_delete_feature(Request $request)
    {
        if (! access()->can($this->deletePermissionFeature)) {
            return access()->block();
        }
        
        $checked = $request->input('checked');
        if (is_array($checked) && count($checked)) {
            foreach ($checked as $id) {
                $record = Localizations::where('id_localization', '=', $id)
                                        ->where('type', '=', 'feature')
                                        ->first();
                if (! is_null($record)) {
                    $record->delete();
                    $activities = new Activities;
                    $activities->log_activity($request,'Delete Feature Localization with id '.$id,'Localizations');
                }
            }
            Cache::flush();
            return redirect()->route('admin.localizations.feature')->withFlashSuccess(trans("localizations::feature.alerts.deleted"));
        } else {
            throw new GeneralException(trans("localizations::feature.alerts.not_selected"));
        }
    }
	
}