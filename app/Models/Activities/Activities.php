<?php namespace App\Models\Activities;

use App\Models\MY_Model as Model;
use Request;

/**
 * Class Activities
 * @package App\Models\Activities
 */
class Activities extends Model {

    protected $table = 'activities';
    protected $primaryKey = 'id_activity';
    public $timestamps = ['created_at'];


    public function log_activity($request, $activity, $module)
    {
           /* $activities             = new Activities;
            $activities->log_activity('login', 'login');*/
        
        $userId 				= access()->id();
        $activities 			= new Activities;
        $activities->user_id  	= $userId;
		$activities->activity	= $activity.' ON '.$request->ip();
		$activities->module 	= $module;
		$activities->save();

    }
    
}


