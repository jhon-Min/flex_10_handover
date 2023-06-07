<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('search_type')->nullable();
            $table->string('part_number')->nullable();
            $table->string('state')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('vin')->nullable();
            $table->bigInteger('make_id')->unsigned();
            $table->bigInteger('model_id')->unsigned();
            $table->string('sub_model')->nullable();
            $table->string('year')->nullable();
            $table->string('chassis_code')->nullable();
            $table->string('engine_code')->nullable();
            $table->string('cc')->nullable();
            $table->string('power')->nullable();
            $table->string('body_type')->nullable();
            $table->tinyInteger('in_stock')->default(1);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('search_history');
    }
}
