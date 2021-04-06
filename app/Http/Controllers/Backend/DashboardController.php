<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\POS\Product;
use App\Models\POS\Order;
use App\Models\POS\OrderDetail;
use DB;
use Charts;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
class DashboardController extends Controller {
    
        public function __construct() {
            parent::__construct();
        }

	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{


        $date_int = range(1,date('t', mktime(0, 0, 0, date('m', strtotime("-1 month")), 1, date('Y', strtotime("-1 month")))));
        array_walk($date_int, function(&$item) {
            $item = date('Y', strtotime("-1 month")).'-'.date('m', strtotime("-1 month")).'-'.str_pad($item, 2, '0', STR_PAD_LEFT); 
        });
        $date_Res = [];
        foreach ($date_int as $key => $value) {
            $date_Res[$value] = []; 
        }
        
        $day = " = '".date('Y-m-d', strtotime("-1 month"))."'";
        $select = "DATE_FORMAT(created_at, '%Y-%m-%d')";
        $where = "DATE_FORMAT(created_at, '%Y-%m') = '".date('Y-m', strtotime("now"))."'";

        $result = DB::select("SELECT ".$select." as date, sum(qty) as total_qty
                                FROM `order_detail` 
                                WHERE ".$where."
                                group by ".$select."
            ");

        $date = array_map(function ($value) {
            return $value->date;
        }, $result);

        $orderqty = array_map(function ($value) {
            return $value->total_qty;
        }, $result);

        // die(print_r($date));

        $chart = Charts::multi('bar', 'chartjs')
            // Setup the chart settings
            ->title("Order Graph (Monthly)")
            ->dimensions(0, 400) // Width x Height
            ->template("chartjs")
            ->colors(['#2196F3', '#F44336', '#FFC107']);

            $chart = $chart->dataset('Qty by Order', $orderqty);

   

            $chart = $chart->labels($date);



        $category = OrderDetail::select('product.product_category', DB::raw("SUM(order_detail.qty) as total_qty"))
                                ->leftJoin('product', 'order_detail.id_product', '=', 'product.id_product')
                                ->groupBy('product.product_category')
                                ->orderBy('product.product_category', 'asc');



        $pie = Charts::create('pie', 'highcharts')
                ->title('Order Category Percentage (Monthly)')
                ->labels($category->lists('product_category')->toArray())
                ->values($category->lists('total_qty')->toArray())
                ->dimensions(400,400)
                ->responsive(true);


        $limit = 10;
		$last = Order::select('order.id_order', 'order.buyer_name', 'order.address', 'order.created_at', DB::raw("SUM(order_detail.total_price) as total_amount"))
                                ->leftJoin('order_detail', 'order.id_order', '=', 'order_detail.id_order')
                                ->orderBy('order.id_order', 'desc')
                                ->groupBy('order.id_order')
                                ->paginate($limit);

        return view('backend.dashboard')
        ->withPie($pie)
        ->withChart($chart)
        ->withLast($last);
	}
}