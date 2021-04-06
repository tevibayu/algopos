<?php namespace App\Models\Access\User;

use App\Models\MY_Model as Model;

/**
 * Class UserProvider
 * @package App\Models\Access\User
 */
class UserProvider extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_providers';

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];
}
