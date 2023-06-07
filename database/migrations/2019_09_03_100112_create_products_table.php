<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('brand_id')->unsigned();
            $table->string('product_nr')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('corresponding_part_number')->nullable();
            $table->double('price_nett',8,2)->default(0);
            $table->double('price_retail',8,2)->default(0);
            $table->text('fitting_position')->nullable();
            $table->string('length')->nullable();
            $table->string('height')->nullable();
            $table->string('thickness')->nullable();
            $table->string('weight')->nullable();
            $table->string('standard_description_id')->nullable();
            $table->timestamps();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');

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
        Schema::dropIfExists('products');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
