<?php namespace App\Http\Controllers\Backend\Language;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use Validator;
use Cache;
use App\Models\Languages\Languages;
use Lang;
use View;
use Storage;

/**
 * Class LanguageController
 */
class LanguageController extends Controller {
    
    protected $createPermission;
    protected $editPermission;
    protected $deletePermission;
    protected $key;
    protected $keyCountPage;

    public function __construct()
    {
        parent::__construct();
        
        $this->createPermission = 'create-language';
        $this->editPermission = 'edit-language';
        $this->deletePermission = 'delete-language';
        $this->key = access()->myTimeZone() . 'language';
        $this->keyCountPage = $this->key . '-count-page';
        
        View::share('createPermission', $this->createPermission);
        View::share('editPermission', $this->editPermission);
        View::share('deletePermission', $this->deletePermission);
    }
    
    public function index()
    {
        $my_langs = array();
        
        $modules = glob('modules/*');
        if (count($modules)) {
            foreach ($modules as $module) {
                $module_name = last(explode('/', $module));
                $lang_modules = glob($module . '/Resources/lang/en/*');
                if (count($lang_modules)) {
                    foreach ($lang_modules as $path) {
                        $lang_name = last(explode('/', $path));
                        $my_langs[] = array(
                            'type' => 'module',
                            'module_name' => $module_name,
                            'lang_name' => head(explode('.', $lang_name))
                        );
                    }
                }
            }
        }
        
        $lang_cores = glob('resources/lang/en/*');
        if (count($lang_cores)) {
            foreach ($lang_cores as $path) {
                $lang_name = last(explode('/', $path));
                $my_langs[] = array(
                    'type' => 'core',
                    'module_name' => null,
                    'lang_name' => head(explode('.', $lang_name))
                );
            }
        }
        
        $groups = Languages::all();
        return view('backend.language.index')
                ->withGroups($groups)
                ->withLanguages($my_langs);
    }
    
    public function create_language(Request $request)
    {
        if (! access()->can($this->createPermission)) {
            return access()->block();
        }
        
        if (isset($request['save'])) {
            $request['name'] = strtolower(str_slug($request->input('name')));
            $type = $request->input('type');
            $name = $request->input('name');
            $keys = $request->input('key');
            $value_en = $request->input('value_en');
            
            $this->validate($request, [
                'type' => 'required',
                'name' => 'required'
            ]);

            if ($keys == NULL) {
                $this->validate($request, [
                    'key' => 'required'
                ]);
            }
            
            if ($type == 'core') {
                $my_filename = base_path('resources/lang/en/' . $name.'.php');
                if (!file_exists($my_filename)) {
                    $lang_en = array();
                    foreach ($keys as $x => $key) {
                        $request['key'] = $key;
                        $this->validate($request, [
                            'key' => 'required',
                        ]);
                        if ($value_en[$x] != '') {
                            array_set($lang_en, $key, $value_en[$x]);
                        }
                    }
                    
                    $new_file = fopen($my_filename, 'w');
                    fwrite($new_file, '<?php return ' . var_export(array_sort_recursive($lang_en), true) . ';');
                    fclose($new_file);
                } else {
                    throw new GeneralException(trans("crud.languages.lang_create_failed"));
                }
            } else {
                if (str_contains($type, 'module')) {
                    $module_name = last(explode('module', $type));
                    $glob_modules = glob('modules/*');
                    if (count($glob_modules)) {
                        foreach ($glob_modules as $my_module) {
                            if (str_contains(strtolower($my_module), $module_name)) {
                                $my_filename = base_path($my_module . '/Resources/lang/en/'.$name.'.php');
                                if (!file_exists($my_filename)) {
                                    $lang_en = array();
                                    foreach ($keys as $x => $key) {
                                        $request['key'] = $key;
                                        $this->validate($request, [
                                            'key' => 'required',
                                        ]);
                                        if ($value_en[$x] != '') {
                                            array_set($lang_en, $key, $value_en[$x]);
                                        }
                                    }
                                    
                                    $new_file = fopen($my_filename, 'w');
                                    fwrite($new_file, '<?php return ' . var_export(array_sort_recursive($lang_en), true) . ';');
                                    fclose($new_file);
                                } else {
                                    throw new GeneralException(trans("crud.languages.lang_create_failed"));
                                }
                            }
                        }
                    }
                    
                } else {
                    throw new GeneralException(trans("crud.languages.lang_create_failed"));
                }
            }
            
            return redirect()->route('admin.language.index')->withFlashSuccess(trans("crud.languages.lang_created"));
        }
        
        $type = array();
        $type['core'] = 'Core';
        $modules = glob('modules/*');
        if (count($modules)) {
            foreach ($modules as $module) {
                $module_name = last(explode('/', $module));
                $type['module' . strtolower($module_name)] = $module_name;
            }
        }
        return view('backend.language.language_form')
            ->withTitle(trans('crud.languages.create_lang'))
            ->withMy_link(link_to_route('admin.language.create_language', trans('crud.languages.create_lang')))
            ->withForm_type('create')
            ->withType(array_sort_recursive($type))
            ->withRecords(array('' => ''));
    }
    
    public function edit_language(Request $request, $type, $name, $code)
    {
        if (! access()->can($this->editPermission)) {
            return access()->block();
        }
        
        $type = strtolower($type);
        $name = strtolower($name);
        $code = strtolower($code);
        $module_name = '';
        $group = Languages::where('code', '=', $code)->first();
        
        if ($type == 'core') {
            if (!Lang::hasForLocale($name, 'en')) {
                throw new GeneralException(trans("crud.languages.lang_no_exist"));
            }
            // save language
            if (isset($request['save'])) {
                
                $keys = $request->input('key');
                $value_en = $request->input('value_en');
                
                if ($keys == NULL) {
                    $this->validate($request, [
                        'key' => 'required',
                    ]);
                }
                
                $lang_en = array();
                foreach ($keys as $x => $key) {
                    $request['key'] = $key;
                    $this->validate($request, [
                        'key' => 'required',
                    ]);
                    if ($value_en[$x] != '') {
                        array_set($lang_en, $key, $value_en[$x]);
                    }
                }
                file_put_contents(base_path('resources/lang/en/'.$name.'.php'), '<?php return ' . var_export(array_sort_recursive($lang_en), true) . ';');
                
                $my_lang = array();
                $my_value = $request->input('value_' . $group->code);
                foreach ($keys as $x => $key) {
                    if ($my_value[$x] != '') {
                        array_set($my_lang, $key, $my_value[$x]);
                    }
                }
                file_put_contents(base_path('resources/lang/'.$group->code.'/'.$name.'.php'), '<?php return ' . var_export(array_sort_recursive($my_lang), true) . ';');

                return redirect()->route('admin.language.index')->withFlashSuccess(trans("crud.languages.lang_updated"));
            }
            
            $records = array_dot(array_sort_recursive(trans($name, array(), 'messages', 'en')));
            // create file php
            $my_filename = base_path('resources/lang/' . $group->code . '/' . $name.'.php');
            if (!file_exists($my_filename)) {
                $new_file = fopen($my_filename, 'w');
                fwrite($new_file, '<?php return ["" => ""];');
                fclose($new_file);
            }
            
            $my_type = $type;
            
        } else {
            if (str_contains($type, 'module')) {
                $module_name = last(explode('module', $type));
                if (!Lang::hasForLocale($module_name . '::' . $name, 'en')) {
                    throw new GeneralException(trans("crud.languages.lang_no_exist"));
                }
                
                // save language
                if (isset($request['save'])) {
                    
                    $keys = $request->input('key');
                    $value_en = $request->input('value_en');
                    
                    if ($keys == NULL) {
                        $this->validate($request, [
                            'key' => 'required',
                        ]);
                    }
                                
                    $glob_modules = glob('modules/*');
                    if (count($glob_modules)) {
                        foreach ($glob_modules as $my_module) {
                            if (str_contains(strtolower($my_module), $module_name)) {
                                $lang_en = array();
                                foreach ($keys as $x => $key) {
                                    $request['key'] = $key;
                                    $this->validate($request, [
                                        'key' => 'required',
                                    ]);
                                    if ($value_en[$x] != '') {
                                        array_set($lang_en, $key, $value_en[$x]);
                                    }
                                }
                                file_put_contents(base_path($my_module . '/Resources/lang/'.$group->code.'/'.$name.'.php'), '<?php return ' . var_export(array_sort_recursive($lang_en), true) . ';');
                                
                                $my_lang = array();
                                $my_value = $request->input('value_' . $group->code);
                                foreach ($keys as $x => $key) {
                                    if ($my_value[$x] != '') {
                                        array_set($my_lang, $key, $my_value[$x]);
                                    }
                                }
                                file_put_contents(base_path($my_module . '/Resources/lang/'.$group->code.'/'.$name.'.php'), '<?php return ' . var_export(array_sort_recursive($my_lang), true) . ';');
                            }
                        }
                    }

                    return redirect()->route('admin.language.index')->withFlashSuccess(trans("crud.languages.lang_updated"));
                }
                
                $records = array_dot(array_sort_recursive(trans($module_name . '::' . $name, array(), 'messages', 'en')));
                // create file php
                $glob_modules = glob('modules/*');
                if (count($glob_modules)) {
                    foreach ($glob_modules as $my_module) {
                        if (str_contains(strtolower($my_module), $module_name)) {
                            $my_filename = base_path($my_module . '/Resources/lang/'.$group->code.'/'.$name.'.php');
                            if (!file_exists($my_filename)) {
                                $new_file = fopen($my_filename, 'w');
                                fwrite($new_file, '<?php return ["" => ""];');
                                fclose($new_file);
                            }
                        }
                    }
                }
                
                $my_type = 'module';
            }
        }
        return view('backend.language.language_form')
                ->withTitle(trans('crud.languages.edit_lang'))
                ->withMy_link(link_to_route('admin.language.edit_language', trans('crud.languages.edit_lang'), array($type, $name, $code)))
                ->withGroup($group)
                ->withRecords($records)
                ->withFilename($name)
                ->withCode($code)
                ->withType($my_type)
                ->withForm_type('edit')
                ->withModule_name($module_name);
    }

    public function delete_language($type, $name)
    {
        if (! access()->can($this->deletePermission)) {
            return access()->block();
        }
        
        $type = strtolower($type);
        $name = strtolower($name);
        $groups = Languages::all();
        
        if ($type == 'core') {
            if (count($groups->toArray())) {
                foreach ($groups as $group) {
                    $my_file = base_path('resources/lang/'.$group->code.'/'.$name.'.php');
                    if (file_exists($my_file)) {
                        unlink($my_file);
                    }
                }
            }
        } else {
            if (str_contains($type, 'module')) {
                $module_name = last(explode('module', $type));
                if (count($groups->toArray())) {
                    // check nama module
                    // memakai glob untuk mendapatkan nama folder yang sesuai (kadang nama folder ada huruf besarnya, di linux case sensitive)
                    $glob_modules = glob('modules/*');
                    if (count($glob_modules)) {
                        foreach ($glob_modules as $my_module) {
                            if (str_contains(strtolower($my_module), $module_name)) {
                                foreach ($groups as $group) {
                                    $my_file = base_path($my_module . '/Resources/lang/'.$group->code.'/'.$name.'.php');
                                    if (file_exists($my_file)) {
                                        unlink($my_file);
                                    }
                                }
                            }
                        }
                    }            
                }
            }
        }
        
        return redirect()->route('admin.language.index')->withFlashSuccess(trans("crud.languages.lang_deleted"));
    }

    public function create_group(Request $request)
    {
        if (! access()->can($this->createPermission)) {
            return access()->block();
        }
        
        if (isset($request['code'])) {
            $request['code'] = str_slug($request->input('code'));
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:languages,code',
                'lang' => 'required',
                'name' => 'required',
                'flag' => 'required|mimes:jpeg,jpg,bmp,png',
            ]);
            if ($validator->fails()) {
                $my_errors = '';
                foreach ($validator->errors()->all() as $error) {
                    $my_errors = $my_errors . $error . '<br />';
                }
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => $my_errors)));
            }
            
            $record = new Languages;
            
            if (isset($request['flag'])) {
                if ($request->file('flag')->isValid()) {
                    $destinationPath = access()->language_path();
                    Storage::makeDirectory(access()->language_path(FALSE));

                    // upload image
                    $extension = $request->file('flag')->getClientOriginalExtension();
                    $fileName = md5(uniqid(mt_rand()).microtime()).'.'.$extension;
                    if ($request->file('flag')->move($destinationPath, $fileName)) {
                        $request['flag'] = $fileName;
                    }
                } else {
                    die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans('crud.languages.image_invalid'))));
                }
            }
            
            if ($this->save_group($record, $request)) {
                // create core directory
                $core_dir = 'resources/lang/' . $record->code;
                if (!file_exists(base_path($core_dir))) {
                    mkdir(base_path($core_dir));
                }
                // create module directory
                $modules = glob('modules/*');
                if (count($modules)) {
                    foreach ($modules as $module) {
                        $module_dir = $module . '/Resources/lang/' . $record->code;
                        if (!file_exists(base_path($module_dir))) {
                            mkdir(base_path($module_dir));
                        }
                    }
                }
                
                $button_edit = '<a style="cursor: pointer" class="btn btn-xs btn-primary" onclick="edit_group('.$record->id_language . ',' . "'new'" .')" data-placement="top" data-toggle="tooltip" data-original-title="'.trans("crud.edit_button").'"><i title="" class="fa fa-pencil"></i></a> ';
                $button_delete = '<a style="cursor: pointer" class="btn btn-xs btn-danger" onclick="delete_group('.$record->id_language.')" data-toggle="tooltip" data-placement="top" title="'.trans("crud.delete_button").'"><i class="fa fa-trash"></i></a>';

                $new_data = array('', 
                                '<span id="record_code' . $record->id_language . '">' . $record->code . '</span>', 
                                '<span id="record_lang' . $record->id_language . '">' . $record->lang . '</span>', 
                                '<span id="record_name' . $record->id_language . '">' . $record->name . '</span>', 
                                '<span id="record_flag' . $record->id_language . '"><img src="'.asset(access()->language_path().$record->flag).'" style="width: 20px; height: 20px;"></span>',
                                $button_edit . $button_delete);
                
                die(json_encode(array('type' => 'success', 'title' => 'Success!', 'text' => trans("crud.languages.group_created"), 'new_data' => $new_data, 'id' => 'group'.$record->id_language)));
            } else {
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans("crud.languages.group_create_failed"))));
            }
        }
        
        if (isset($request['create_group'])) {
            return view('backend.language.group_form')
                    ->withTitle(trans('crud.languages.create_group'))
                    ->withForm_type('create');
        }
    }
    
    public function edit_group(Request $request)
    {
        if (! access()->can($this->editPermission)) {
            return access()->block();
        }
        
        $record = Languages::find($request->input('id'));
        
        if (isset($request['ajax_check_data'])) {
            if (is_null($record)) {
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans('crud.languages.group_no_exist'))));
            } else {
                die(json_encode(array('type' => 'success', 'title' => 'Success!', 'text' => '')));
            }
        }
        
        if (isset($request['lang'])) {
            $validator = Validator::make($request->all(), [
                'lang' => 'required',
                'name' => 'required',
                'flag' => 'mimes:jpeg,jpg,bmp,png',
            ]);
            if ($validator->fails()) {
                $my_errors = '';
                foreach ($validator->errors()->all() as $error) {
                    $my_errors = $my_errors . $error . '<br />';
                }
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => $my_errors)));
            }
            
            if (isset($request['flag']) && $request['flag'] != '') {
                if ($request->file('flag')->isValid()) {
                    $destinationPath = access()->language_path();
                    Storage::makeDirectory(access()->language_path(FALSE));

                    // upload image
                    $extension = $request->file('flag')->getClientOriginalExtension();
                    $fileName = md5(uniqid(mt_rand()).microtime()).'.'.$extension;
                    if ($request->file('flag')->move($destinationPath, $fileName)) {
                        $request['flag'] = $fileName;
                        $old_file = base_path(access()->language_path() . $record->flag);
                        if ($record->flag != '' && file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }
                } else {
                    die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans('crud.languages.image_invalid'))));
                }
            }
            
            if ($this->save_group($record, $request, 'edit')) {
                die(json_encode(array('type' => 'success', 'title' => 'Success!', 'text' => trans("crud.languages.group_updated"),
                    'code' => $record->code, 'lang' => $record->lang, 'name' => $record->name, 'flag' => asset(access()->language_path() . $record->flag))));
            } else {
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans("crud.languages.group_update_failed"))));
            }
        }
        
        if (isset($request['edit_group'])) {
            return view('backend.language.group_form')
                    ->withTitle(trans('crud.languages.edit_group'))
                    ->withForm_type('edit')
                    ->withRecord($record)
                    ->withType($request->input('type'));
        }
    }
    
    private function save_group($record, $request, $type = 'create')
    {
        if ($type == 'create') {
            $record->code = $request->input('code');
        }
        $record->lang = $request->input('lang');
        $record->name = $request->input('name');
        if ($request->input('flag') != '') {
            $record->flag = $request->input('flag');
        }
        return $record->save();
    }
    
    public function delete_group(Request $request)
    {
        if (! access()->can($this->deletePermission)) {
            return access()->block();
        }
        
        if (isset($request['delete_group'])) {
            $record = Languages::find($request->input('id'));
            if (is_null($record)) {
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans('crud.languages.group_no_exist'))));
            }
            $code = $record->code;
            $flag = $record->flag;
            if ($record->delete()) {
                // delete flag
                $old_file = base_path(access()->language_path() . $flag);
                if ($flag != '' && file_exists($old_file)) {
                    unlink($old_file);
                }
                /*
                // delete core directory
                $core_dir = 'resources/lang/' . $code;
                $core_glob = glob($core_dir . "/*.*");
                if (count($core_glob)) {
                    foreach ($core_glob as $filename) {
                        if (is_file(base_path($filename))) {
                            unlink(base_path($filename));
                        }
                    }
                }
                if (file_exists(base_path($core_dir))) {
                    rmdir(base_path($core_dir));
                }
                
                // delete module directory
                $modules_glob = glob('modules/*');
                if (count($modules_glob)) {
                    foreach ($modules_glob as $module) {
                        $module_dir = $module . '/Resources/lang/' . $code;
                        $module_glob = glob($module_dir . "/*.*");
                        if (count($module_glob)) {
                            foreach ($module_glob as $filename) {
                                if (is_file(base_path($filename))) {
                                    unlink(base_path($filename));
                                }
                            }
                        }
                        if (file_exists(base_path($module_dir))) {
                            rmdir(base_path($module_dir));
                        }
                    }
                }
                */
                die(json_encode(array('type' => 'success', 'title' => 'Success!', 'text' => trans('crud.languages.group_deleted'))));
            } else {
                die(json_encode(array('type' => 'error', 'title' => 'Error!', 'text' => trans('crud.languages.group_delete_failed'))));
            }
        }
    }
    
}