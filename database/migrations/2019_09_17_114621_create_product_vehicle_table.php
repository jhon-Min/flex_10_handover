<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('vehicle_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->timestamps();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('product_vehicles');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
