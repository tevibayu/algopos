<?php namespace App\Http\Requests\Backend\Access\Permission;

use App\Http\Requests\Request;

/**
 * Class StorePermissionRequest
 * @package App\Http\Requests\Backend\Access\Permission
 */
class StorePermissionRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->can('create-permissions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'			=>  'required|unique:permissions',
            'display_name'	=>	'required',
        ];
    }
}