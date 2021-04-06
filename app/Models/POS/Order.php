<?php namespace App\Models\POS;

use App\Models\MY_Model as Model;

/**
 * Class POS
 * @package App\Models\POS
 */
class Order extends Model {

    protected $table = 'order';
    protected $primaryKey = 'id_order';
    public $timestamps = true;

}