<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleTmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_tmp', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned();
            $table->bigInteger('make_id')->nullable();
            $table->bigInteger('model_id')->nullable();
            $table->integer('year_from')->nullable();
            $table->integer('year_to')->nullable();
            $table->string('year_range')->nullable();
            $table->string('sub_model')->nullable();
            $table->string('chassis_code')->nullable();
            $table->string('engine_code')->nullable();
            $table->string('cc')->nullable();
            $table->string('power')->nullable();
            $table->string('body_type')->nullable();
            $table->string('brake_system')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_tmp');
    }
}
