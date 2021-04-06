<?php namespace App\Models\POS;

use App\Models\MY_Model as Model;

/**
 * Class POS
 * @package App\Models\POS
 */
class Product extends Model {

    protected $table = 'product';
    protected $primaryKey = 'id_product';
    public $timestamps = true;

}