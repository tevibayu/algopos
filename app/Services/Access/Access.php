<?php namespace App\Services\Access;

use Illuminate\Support\Facades\Redirect;
use App\Models\Menu\Menu;
use HieuLe\Active\Facades\Active;
use Illuminate\Support\Facades\Request;
use App\Models\Settings\Settings;
use Cache;
use App\Models\Languages\Languages;
use Modules\Localizations\Entities\Localizations;
use App\Models\Access\Permission\Permission;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_AWSTransport;

/**
 * Class Access
 * @package App\Services\Access
 */
class Access
{
    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get the currently authenticated user or null.
     */
    public function user()
    {
        return auth()->user();
    }

    /**
     * @return mixed
     * Get the currently authenticated user's id
     */
    public function id()
    {
        return auth()->id();
    }

    /**
     * Checks if the current user has a Role by its name or id
     *
     * @param string $role Role name.
     *
     * @return bool
     */
    public function hasRole($role)
    {
        if ($user = $this->user())
            return $user->hasRole($role);

        return false;
    }

    /**
     * Checks if the user has either one or more, or all of an array of roles
     * @param $roles
     * @param bool $needsAll
     * @return bool
     */
    public function hasRoles($roles, $needsAll = false)
    {
        if ($user = $this->user()) {
            //If not an array, make a one item array
            if (! is_array($roles))
                $roles = array($roles);

            return $user->hasRoles($roles, $needsAll);
        }

        return false;
    }

    /**
     * Check if the current user has a permission by its name or id
     *
     * @param string $permission Permission name or id.
     *
     * @return bool
     */
    public function can($permission)
    {
        if ($user = $this->user())
            return $user->can($permission);

        return false;
    }

    /**
     * Check an array of permissions and whether or not all are required to continue
     * @param $permissions
     * @param $needsAll
     * @return bool
     */
    public function canMultiple($permissions, $needsAll = false) {
        if ($user = $this->user()) {
            //If not an array, make a one item array
            if (!is_array($permissions))
                $permissions = array($permissions);

            return $user->canMultiple($permissions, $needsAll);
        }

        return false;
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->can($permission);
    }

    /**
     * @param $permissions
     * @param $needsAll
     * @return bool
     */
    public function hasPermissions($permissions, $needsAll = false)
    {
        return $this->canMultiple($permissions, $needsAll);
    }

    /**
     * back to previous page when acces blocked
     */
    public function block(){
        return Redirect::back()->withFlashDanger(trans('alerts.block'));
    }
    
    /**
     * generate menu
     * @return string
     */
    public function menu()
    {
        $html = '<li class="'.Active::pattern(array('admin/dashboard', 'admin')).'"><a href="'.route('backend.dashboard').'">'
        . '<i class="fa fa-pie-chart"></i> <span>'.trans('menus.dashboard').'</span></a></li>';
        
        $roles = $this->user()->roles;
        $arr_role_ids = array();
        $role_ids = '';
        if (count($roles)) {
            foreach ($roles as $key => $role) {
                $arr_role_ids[] = $role['id'];
                if ($key == 0) {
                    $role_ids .= $role['id'];
                } else {
                    $role_ids .= ',' . $role['id'];
                }
            }
        }
        
        $menu = Cache::rememberForever($role_ids . 'my_sidebar_menu', function() {
            return Menu::where('parent_id', 0)
                        ->where('group_menu', 1)
                        ->where('status', 1)
                        ->groupBy('id')
                        ->orderBy('order', 'asc')
                        ->get();
        });
        
        if (is_array($menu->toArray()) && count($menu->toArray())) {
            foreach ($menu as $key => $rw) {
                $id = $rw->id;
                $title = ($rw->lang == NULL || $rw->lang == '') ? $rw->title : trans($rw->lang);
                $link = $rw->link;
                $icon = $rw->icon;
                $target = $rw->target;
                $id_name = str_replace(" ", "-", strtolower($title));

                
                if ($role_ids == 1) {
                    $submenu = Cache::rememberForever($role_ids . 'my_sidebar_menu' . $key, function() use($id) {
                        return Menu::where('menus.parent_id', $id)
                                    ->where('menus.group_menu', 1)
                                    ->where('menus.status', 1)
                                    ->groupBy('menus.id')
                                    ->orderBy('menus.order', 'asc')->get();
                    });
                } else {
                    $submenu = Cache::rememberForever($role_ids . 'my_sidebar_menu' . $key, function() use($arr_role_ids, $id) {
                        return Menu::join('permission_role', 'menus.permission_id', '=', 'permission_role.permission_id')
                                    ->whereIn('permission_role.role_id', $arr_role_ids)
                                    ->where('menus.parent_id', $id)
                                    ->where('menus.group_menu', 1)
                                    ->where('menus.status', 1)
                                    ->groupBy('menus.id')
                                    ->orderBy('menus.order', 'asc')->get();
                    });
                }
                
                $count_submenu = 0;
                foreach ($submenu as $sub) {
                    if ($role_ids != 1) {
                        $count_submenu++;
                    }
                }
                
                if ($role_ids != 1 && $count_submenu == 0 && $link == "#") {
                    goto end_for;
                }
                
                $active = '';
                
                foreach ($submenu as $sub) {
                    if (Active::pattern($sub->link . '*') == 'active') {							
                        $active = 'active';
                        break;
                    }
                }
                
                if ($active == '') {
                    if (Active::pattern($rw->link . '*') == 'active') {							
                        $active = 'active';
                    }
                }
                
                if ($count_submenu == 0 && $link != "#") {
                    if ($this->hasPermission($rw->permission_id)) {
                        $html .= '<li id="'.$id_name.'" class="'.$active.'"><a href="'.url($link).'"><i class="'.$icon.'"></i> <span>'.ucwords($title).'</span>'
                        . '</a>';
                    }
                } else {
                    $html .= '<li id="'.$id_name.'" class="treeview '.$active.'"><a href="#"><i class="'.$icon.'"></i> <span>'.ucwords($title).'</span>'
                    . '<i class="fa fa-angle-left pull-right"></i></a><ul class="treeview-menu">';
                }
                
                foreach ($submenu as $sub) {
                    $subid = $sub->id;
                    $subtitle = ($sub->lang == NULL || $sub->lang == '') ? $sub->title : trans($sub->lang);
                    $sublink = $sub->link;
                    $subicon = $sub->icon;
                    $subtarget = $sub->target;
                    $id_sub = str_replace(" ", "-", strtolower($subtitle));

                    $subtarget = ($subtarget == '_blank') ? 'target="_blank"' : '';

                    $html .= '<li id="'.$id_sub.'" class="'.Active::pattern($sublink . '*').'"><a href="'.url($sublink).'" '.$subtarget.'><i class="'.$subicon.'"></i>'.ucwords($subtitle).'</a></li>';
                }
                
                if ($count_submenu == 0 && $link != "#") {
                    if ($this->hasPermission($rw->permission_id)) {
                        $html .= '</li>';
                    }
                } else {
                    $html .= '</ul></li>';
                }
                
                end_for:
            }
        }
        
        $html .= '</ul>';
        
        return $html;
    }
    
    /**
     * initialize config settings from database
     */
    public function settings()
    {
        $settings = Cache::rememberForever('my_settings', function() {
            return Settings::lists('value', 'name');
        });
        
        if (count($settings)) {
            foreach ($settings as $name => $value) {
                app('config')->set($name, $value);
            }
            
            if (app('config')->get('app.profiler') == FALSE) {
                \Debugbar::disable();
            }
            
            /* Settings Email (Swiftmailer) */
            // extract config
            extract(app('config')->get('mail'));
			
            if ($driver == 'smtp') {
                // create new mailer with new settings
                $transport = Swift_SmtpTransport::newInstance($host, $port);
                // set encryption
                if (isset($encryption)) { 
                    $transport->setEncryption($encryption);
                }
                // set username and password
                if (isset($username)) {
                    $transport->setUsername($username);
                    $transport->setPassword($password);
                }
                // set new swift mailer
                Mail::setSwiftMailer(new Swift_Mailer($transport));
            } else if ($driver == 'ses') {
                // create new mailer with new settings
                $aws_key = config('services.ses.key');
                $aws_secret = config('services.ses.secret');
                $transport = Swift_AWSTransport::newInstance($aws_key, $aws_secret);
                // set new swift mailer
                Mail::setSwiftMailer(new Swift_Mailer($transport));
            }
			
            // set from name and address
            if (is_array($from) && isset($from['address'])) {
                Mail::alwaysFrom($from['address'], $from['name']);
            }
        }
    }
    
    /**
     * delete cache records
     * @param String $key_get = cache key dari count page
     * @param String $key_forget = cache key yang akan di hapus
     */
    public function delete_cache_records($key_get, $key_forget)
    {
        $count_page = Cache::get($key_get, 1);
        if ($count_page == 0) {
            Cache::forget($key_forget);
        } else if ($count_page > 0) {
            for ($i = 1; $i <= $count_page; $i++) {
                $numb = ($i==1) ? '' : $i;
                Cache::forget($key_forget . $numb);
            }
        }
        Cache::forget($key_get);
    }
    
    /**
     * initialize photo profile path
     * @param bool $full_path
     * @return string
     */
    public function photo_profile_path($full_path = TRUE)
    {
        if ($full_path) {
            $path = 'storage/app/photo_profile/';
        } else {
            $path = 'photo_profile';
        }
        return $path;
    }
    
    /**
     * get languages
     * @param bool $is_array
     * @return object / array
     */
    public function languages($is_array = false)
    {
        if ($is_array) {
            $lang = Languages::lists('code')->toArray();
        } else {
            $lang = Languages::all();
        }
        return $lang;
    }
    
    /**
     * initialize language path
     * @param bool $full_path
     * @return string
     */
    public function language_path($full_path = TRUE)
    {
        if ($full_path) {
            $path = 'storage/app/language/';
        } else {
            $path = 'language';
        }
        return $path;
    }
    
    /**
     * get ip geolocation
     * @param string $ip
     * @return string
     */
    private function myGeolocation($ip = NULL)
    {
        $repIP = str_replace('.', '-', $ip);
        if (!session()->has('geolocation' . $repIP)) {
            $object = (object) ['query' => '127.0.0.1', 'status' => 'fail'];
            $getGeo = $object;
//            if ($ip == NULL) {
//                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
//                    $myIp = $_SERVER['HTTP_CLIENT_IP'];
//                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//                    $myIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
//                } else {
//                    $myIp = $_SERVER['REMOTE_ADDR'];
//                }
//            } else {
//                $myIp = $ip;
//            }
//            $getGeo = json_decode(@file_get_contents('http://ip-api.com/json/' . $myIp));
            session()->put('geolocation' . $repIP, $getGeo);
        }
        return session()->get('geolocation' . $repIP);
    }

    /**
     * get ip address
     * @return string
     */
    public function myIP()
    {
        $myGeo = $this->myGeolocation();
        return $myGeo->query;
    }
    
    /**
     * get id timezone
     * @param string $ip
     * @return int
     */
    public function myTimeZone($ip = NULL)
    {
        $myGeo = $this->myGeolocation($ip);
        $myTimeZone = is_object($myGeo) && $myGeo->status == 'success' ? $myGeo->timezone : 'UTC';
        return array_search($myTimeZone, $this->listTimeZone());
    }
    
    /**
     * get list timezone
     * @param int $id
     * @return array
     */
    public function listTimeZone($id = NULL)
    {
        if ($id == NULL) {
            return \DateTimeZone::listIdentifiers();
        } else {
            return \DateTimeZone::listIdentifiers()[$id];
        }
        
    }
    
    /**
     * get id permission of feature localization
     * @return array
     */
    public function featureLocalization()
    {
        $timezone = $this->myTimeZone();
        $key_cache = $timezone . '-feature-localization';
        $return = Cache::rememberForever($key_cache, function() use($timezone) {
            $records = Localizations::where('timezone', '=', $timezone)
                                    ->where('type', '=', 'feature')
                                    ->first();
            $id_permissions = is_object($records) ? $records->records : NULL;
            $results = $id_permissions != NULL ? explode(',', $id_permissions) : NULL;
            if (is_array($results) && count($results)) {
                foreach ($results as $result) {
                    $permission = Permission::find($result);
                    $results[] = is_object($permission) ? $permission->name : NULL;
                }
            }
            return $results;
        });
        
        return $return;
    }

    public function generateInvoiceNo($id, $date)
    {
        
        $precode = 'ORD';

        $year = substr(date('Y', strtotime($date)), 2);

        $number = str_pad($id, 8, 0, STR_PAD_LEFT);

        $result = $precode.''.$year.''.$number;

        return $result;
    }

}