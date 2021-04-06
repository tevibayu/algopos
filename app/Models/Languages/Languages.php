<?php namespace App\Models\Languages;

use App\Models\MY_Model as Model;

/**
 * Class Languages
 * @package App\Models\Languages
 */
class Languages extends Model {

    protected $table = 'languages';
    protected $primaryKey = 'id_language';
    public $timestamps = false;
    
}