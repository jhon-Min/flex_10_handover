<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('make_id')->unsigned();
            $table->bigInteger('model_id')->unsigned();
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
            $table->timestamps();
            $table->foreign('make_id')->references('id')->on('makes')->onDelete('cascade');
            $table->foreign('model_id')->references('id')->on('models')->onDelete('cascade');
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
        Schema::dropIfExists('vehicles');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
