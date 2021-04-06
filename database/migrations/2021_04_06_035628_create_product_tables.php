<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('product', function (Blueprint $table) {
            $table->increments('id_product');
            $table->string('product_code');
            $table->text('product_name');
            $table->double('sell_price');
            $table->double('buy_price');
            $table->float('stock');
            $table->text('product_category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}


