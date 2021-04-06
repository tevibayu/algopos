<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Custom Model
 */
class MY_Model extends Model {
    
    /**
     * Search
     * @param object $query
     * @param string $search
     * @param array $fields
     * @param array $specialFields => ex : array('is_active' => array(1 => 'active', 0 => 'inactive'))
     * @return object
     */
    public function scopeSearch($query, $search, $fields = null, $specialFields = null)
    {
        $query->where(function($query) use($search, $fields, $specialFields) {
            if (count($fields)) {
                foreach ($fields as $key => $field) {
                    $query->orWhere($field, 'like', '%'.$search.'%');
                }
            }
            if (count($specialFields)) {
                foreach ($specialFields as $field => $values) {
                    if (count($values)) {
                        foreach ($values as $value => $name) {
                            if (str_contains(strtolower($name), strtolower($search))) {
                                $query->orWhere($field, '=', $value);
                            }
                        }
                    }
                }
            }
        });
        return $query;
    }
    
    /**
     * Localizations
     * @param object $query
     * @param type $table
     * @param type $request
     * @return object
     */
    public function scopeLocalization($query, $table, $request = NULL)
    {
        $limit = config('access.users.default_per_page');
        $timezone = access()->myTimeZone();
        $localizations = \Illuminate\Support\Facades\DB::table('localizations')
                        ->join('modules', 'localizations.modules_id_module', '=', 'modules.id_module')
                        ->where('timezone', '=', $timezone)
                        ->where('table_name', '=', $table)
                        ->get();
        
        if (count($localizations)) {
            foreach ($localizations as $localization) {
                // except localization
                if ($localization->type == 'except') {
                    $records = $localization->records;
                    $arr_records = explode(',', $records);
                    foreach ($arr_records as $record) {
                        $query->where($this->primaryKey, '<>', $record);
                    }
                    $result = $query->paginate($limit);
                }
                // end except localization
                
                // pupolar localization
                if ($localization->type == 'popular') {
                    $temp_records = $query;
                    $id_popular = explode(',', $localization->records);
                    $new_rec = array();
                    $old_req_page = $request['page'];
                    $numb_data = -1;
                    $field_id = $localization->field_id;
                    
                    for ($i=1; $i<=$temp_records->paginate($limit)->lastPage(); $i++) {
                        $request['page'] = $i;
                        $pop_rec = $temp_records->paginate($limit);
                        if ($pop_rec->count()) {
                            foreach ($pop_rec as $key => $rec) {
                                $numb_data++;
                                if (in_array($rec->$field_id, $id_popular)) {
                                    $new_rec[] = array(
                                                    'data' => $pop_rec[$key],
                                                    'numb_data' => $numb_data
                                                );
                                }
                            }
                        }
                    }

                    $request['page'] = $old_req_page-1;
                    $temp_records2 = $temp_records->paginate($limit);
                    $count_prev = $limit-1;
                    for ($i=$limit-1; $i>=0; $i--) {
                        if (is_object($temp_records2[$i]) && in_array($temp_records2[$i]->$field_id, $id_popular)) {
                            $count_prev--;
                        } else {
                            break;
                        }
                    }

                    $request['page'] = $old_req_page;
                    $query = $temp_records->paginate($limit);

                    if ($query->count() && count($new_rec)) {
                        $count_switch = count($new_rec);
                        $numb_current = (($query->currentPage() - 1) * $query->perPage()) - 1;

                        foreach ($new_rec as $key => $rec) {
                            for ($i=$query->count()-1; $i>=0; $i--) {
                                if (($numb_current + $i) < $rec['numb_data']) {
                                    $temp_rec = $query[$i-1];
                                    $query[$i] = $temp_rec;
                                }
                            }

                            if ($query->currentPage() == 1) {
                                $query[0] = $rec['data'];
                            } else if ($query->currentPage() > 1) {
                                if ($numb_current < $rec['numb_data']) {
                                    $query[0] = $temp_records2[$count_prev];
                                    for ($i=$count_prev-1; $i>=0; $i--) {
                                        if (is_object($temp_records2[$i]) && in_array($temp_records2[$i]->$field_id, $id_popular)) {
                                            $count_prev--;
                                        } else {
                                            break;
                                        }
                                    }
                                    $count_prev--;
                                }
                            }
                        }
                    }
                    $result = $query;
                }
                // end popular localization
            }
        } else {
            $result = $query->paginate($limit);
        }
        
        return $result;
    }

}