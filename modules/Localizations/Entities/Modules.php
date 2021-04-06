<?php namespace Modules\Localizations\Entities;
   
use App\Models\MY_Model as Model;
use DB;

class Modules extends Model {

    protected $table = 'modules';
    protected $primaryKey = 'id_module';
    public $timestamps = false;
    protected $fillable = [];
    
    /**
     * 
     * @param type $module
     * @param type $field_id
     * @param type $field_name
     * @param type $records, ex : 1,2,3
     */
    public function get_records($table, $field_id, $field_name, $ids, $is_feature = FALSE)
    {
        $ids = explode(',', $ids);
        $records = DB::table($table);
        if (count($ids)) {
            $records->where(function($records) use($ids, $field_id) {
                foreach ($ids as $id) {
                    $records->orWhere($field_id, '=', $id);
                }
            });
        }
        $my_records = $records->get();
        $return = '';
        if (count($my_records)) {
            foreach ($my_records as $key => $my_record) {
                if ($key == 0) {
                    $return .= $is_feature == TRUE ? last(explode('View', $my_record->$field_name)) : $my_record->$field_name;
                } else {
                    $return .= $is_feature == TRUE ? ', ' . last(explode('View', $my_record->$field_name)) : ', ' . $my_record->$field_name;
                }
            }
        }
        return $return;
    }

}