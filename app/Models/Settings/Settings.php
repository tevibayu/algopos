<?php namespace App\Models\Settings;

use App\Models\MY_Model as Model;

/**
 * Class Settings
 * @package App\Models\Settings
 */
class Settings extends Model {

    protected $table = 'settings';
    protected $primaryKey = 'name';
    public $timestamps = false;
    
}