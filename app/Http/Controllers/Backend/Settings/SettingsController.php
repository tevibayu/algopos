<?php namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use Validator;
use App\Models\Settings\Settings;
use App\Models\Activities\Activities;
use Cache;

/**
 * Class SettingsController
 */
class SettingsController extends Controller {

    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $records = Settings::lists('value', 'name');
        
        return view('backend.settings.index')
                ->withRecords($records);
    }
    
    public function email()
    {
        $records = Settings::lists('value', 'name');
        
        return view('backend.settings.email')
                ->withRecords($records);
    }
    
    public function save_general_settings(Request $request)
    {
        $this->validate($request, [
            'site_name' => 'required',
            'default_per_page' => 'required|numeric',
        ]);
        // save site name
        $this->save_setting('app.name', $request->input('site_name'));
        // save default 
        $this->save_setting('access.users.default_per_page', $request->input('default_per_page'));
        // save debug
        $this->save_setting('app.debug', $request->input('debug'));
        // save debug
        $this->save_setting('app.profiler', $request->input('profiler'));
        // save log activity
        $activities = new Activities;
        $activities->log_activity($request,'Change General Setting','Settings');
        // delete cache
        Cache::forget('my_settings');
        
        return redirect()->route('admin.settings.general')->withFlashSuccess(trans("alerts.general_settings_saved"));
    }
    
    public function save_email_settings(Request $request)
    {
        $this->validate($request, [
            'driver' => 'required',
            'host' => 'required',
            'port' => 'required|numeric',
            'username' => 'required',
            'email_name' => 'required',
            'email_address' => 'required|email',
        ]);
        // save driver
        $this->save_setting('mail.driver', $request->input('driver'));
        // save host 
        $this->save_setting('mail.host', $request->input('host'));
        // save debug
        $this->save_setting('mail.port', $request->input('port'));
        // save username
        $this->save_setting('mail.username', $request->input('username'));
        // save password
        if (isset($request['password']) && $request->input('password') != '') {
            $this->save_setting('mail.password', $request->input('password'));
        }
        // save email name
        $this->save_setting('mail.from.name', $request->input('email_name'));
        // save email address
        $this->save_setting('mail.from.address', $request->input('email_address'));
        // save log activity
        $activities = new Activities;
        $activities->log_activity($request,'Change Email Setting','Settings');
        // delete cache
        Cache::forget('my_settings');
        
        return redirect()->route('admin.settings.email')->withFlashSuccess(trans("alerts.email_settings_saved"));
    }
    
    private function save_setting($name, $value)
    {
        $record = Settings::find($name);
        if (!is_null($record)) {
            if ($name == 'access.users.default_per_page' && $record->value != $value) {
                // delete all cache
                Cache::flush();
            }
            $record->value = $value;
            $record->save();
        }
    }
    
}