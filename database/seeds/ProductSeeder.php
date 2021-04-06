<?php

use Illuminate\Database\Seeder;
use App\Models\POS\Product;
use Illuminate\Support\Str;
// use Faker\Generator as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $j=1;

        for($i = 0; $i < 1000; $i++) {
            Product::create([
                'product_code' => 'PD_'.($i+1),
                'product_name' => 'Product '.($i+1),
                'sell_price' => $faker->numberBetween($min = 1500, $max = 6000),
                'buy_price' => $faker->numberBetween($min = 6000, $max = 8000),
                'stock' => $faker->numberBetween($min = 1, $max = 30),
                'product_category' => 'Category '.($j)
            ]);

            if($i % 10 == 0){
                $j++;
            }
        }
    }
}
