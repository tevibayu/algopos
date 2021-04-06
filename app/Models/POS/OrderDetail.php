<?php namespace App\Models\POS;

use App\Models\MY_Model as Model;

/**
 * Class POS
 * @package App\Models\POS
 */
class OrderDetail extends Model {

    protected $table = 'order_detail';
    protected $primaryKey = 'id_order_detail';
    public $timestamps = true;

}