<?php 

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Models\POS\Product::class, function (Faker $faker) {
    return [
    	'product_code' => Str::random(5),
        'product_name' => $faker->productName,
        'sell_price' => $faker->numberBetween($min = 1500, $max = 6000),
        'buy_price' => $faker->numberBetween($min = 6000, $max = 8000),
        'stock' => $faker->numberBetween($min = 1, $max = 30),
        'product_category' => $faker->word
    ];
});