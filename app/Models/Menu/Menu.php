<?php namespace App\Models\Menu;

use App\Models\MY_Model as Model;

/**
 * Class Menu
 * @package App\Models\Menu
 */
class Menu extends Model {

    protected $table = 'menus';
    protected $primaryKey = 'id';
    public $timestamps = false;

}