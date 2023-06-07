<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('qty')->unsigned();
            $table->double('price', 8, 2)->default(0);
            $table->double('total', 8, 2)->default(0);
            $table->bigInteger('delivery_address_id')->unsigned()->default(0);
            $table->bigInteger('pickup_address_id')->unsigned()->default(0);
            $table->string('order_status')->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            //$table->foreign('delivery_address_id')->references('id')->on('delivery_addresses')->onDelete('cascade');
            //$table->foreign('pickup_address_id')->references('id')->on('pickup_addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('order_products');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
