<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use DB;
use App\Models\Settings\Settings;
use App\Models\Activities\Activities;
use Excel;
use Storage;
use Illuminate\Http\Request;
use Cache;
use Illuminate\Support\Facades\Redirect;
use Modules\Locations\Entities\Locations;
use PHPExcel_Shared_Date;
use Modules\Departments\Entities\Departments;
use Illuminate\Support\Facades\Mail;

/**
 * Class FrontendController
 * @package App\Http\Controllers
 */
class FrontendController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return redirect('auth/login');
    }
    
    public function cache()
    {
        Cache::flush();
        return Redirect::back();
    }
    
    
}