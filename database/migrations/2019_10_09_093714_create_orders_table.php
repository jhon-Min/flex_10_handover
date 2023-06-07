<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('order_number')->nullable();
            $table->double('subtotal', 8, 2)->default(0);
            $table->double('gst', 8, 2)->default(0);
            $table->double('delivery', 8, 2)->default(0);
            $table->double('total', 8, 2)->default(0);
            $table->string('reference_number')->nullable();
            $table->tinyInteger('repeat_order')->default(0);
            $table->enum('delivery_method',[1,2,3])->default(1);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
