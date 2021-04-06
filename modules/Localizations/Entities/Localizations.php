<?php namespace Modules\Localizations\Entities;
   
use App\Models\MY_Model as Model;

class Localizations extends Model {

    protected $table = 'localizations';
    protected $primaryKey = 'id_localization';
    public $timestamps = false;
    protected $fillable = [];

}