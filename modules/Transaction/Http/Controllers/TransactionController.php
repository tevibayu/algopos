<?php namespace Modules\Transaction\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\POS\Product;
use App\Models\POS\Order;
use App\Models\POS\OrderDetail;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Models\Activities\Activities;
use Cache;
use Module;
use View;
use DB;

class TransactionController extends Controller {
	
	protected $key;
    protected $keyCountPage;


    public function __construct()
    {
        parent::__construct();

    }

	public function index(Request $request)
	{

        $limit = config('access.users.default_per_page');
        $search = $request->input('search');

        if ($search == NULL) {
            
                $records = Order::select('order.id_order', 'order.buyer_name', 'order.address', 'order.created_at', DB::raw("SUM(order_detail.total_price) as total_amount"))
                				->leftJoin('order_detail', 'order.id_order', '=', 'order_detail.id_order')
                                ->orderBy('order.id_order', 'desc')
                                ->groupBy('order.id_order')
                                ->paginate($limit);
           
        } else {
            $records = Order::select('order.id_order', 'order.buyer_name', 'order.address', 'order.created_at', DB::raw("SUM(order_detail.total_price) as total_amount"))
            					->leftJoin('order_detail', 'order.id_order', '=', 'order_detail.id_order')
                                ->orderBy('order.id_order', 'desc')
                                ->groupBy('order.id_order')
                                ->search(
                                    $search,
                                    array('order.id_order', 'order.buyer_name', 'order.address'),
                                    array()
                                )->paginate($limit);
        }


        $numb = (($records->currentPage()-1) * $limit) + 1;
        return view('transaction::index')
                ->withRecords($records)
                ->withNumb($numb)
                ->withSearch($search);
	}

	public function detail(Request $request){

		$id = $request->input('id');
		$records = OrderDetail::select('order_detail.id_order', 'product.product_name', 'order_detail.qty', 'order_detail.price', 'order_detail.total_price', 'order_detail.created_at')
								->leftJoin('product', 'order_detail.id_product', '=', 'product.id_product')
                				->where('order_detail.id_order', $id)
                                ->orderBy('order_detail.created_at', 'desc')
                                ->get();

        return view('transaction::detail')
                ->withRecords($records);
	}


	
}