<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Frontend
 */
class DashboardController extends Controller {

    public function __construct() {
        parent::__construct();
    }

	/**
	 * @return mixed
	 */
	public function index()
	{
        if (count(access()->user()->roles)) {
            return redirect('admin/dashboard');
        } else {
            return view('frontend.user.dashboard')
                    ->withUser(auth()->user());
        }
	}

}
