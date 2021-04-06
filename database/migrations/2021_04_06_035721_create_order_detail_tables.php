<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('order_detail', function (Blueprint $table) {
            $table->increments('id_order_detail');
            $table->unsignedInteger('id_order');
            $table->unsignedInteger('id_product');
            $table->double('qty');
            $table->double('price');
            $table->double('total_price');

            $table->timestamps();
            
            $table->foreign('id_order')->references('id_order')->on('order')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('order_detail');
    }
}
