<?php

use Illuminate\Database\Seeder;
use App\Models\POS\Product;
use App\Models\POS\Order;
use App\Models\POS\OrderDetail;
use Illuminate\Support\Str;
// use Faker\Generator as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for($i = 0; $i < 10; $i++) {

            $date = $faker->numberBetween($min = 1, $max = 5);

            $order = Order::create([
                'buyer_name' => $faker->name,
                'address' => $faker->address,
                'created_at' => date('Y-m-d H:i:s', strtotime("- $date days"))
            ]);


            for($j = 0; $j < 6; $j++) {

                $order_id = $faker->numberBetween($min = 1, $max = 1000);
                $product = Product::select('sell_price')->find($order_id);

                OrderDetail::create([
                    'id_order' => $order->id_order,
                    'id_product' => $order_id,
                    'qty' => 2,
                    'price' => $product->sell_price,
                    'total_price' => 2*$product->sell_price,
                    'created_at' => date('Y-m-d H:i:s', strtotime("- $date days"))
                ]);
            }
        }
    }
}
