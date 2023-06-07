<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUnusedTablesAndFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('products_tmp');
        Schema::dropIfExists('product_criteria_tmp');
        Schema::dropIfExists('product_price_tmp');
        Schema::dropIfExists('product_sku_tmp');
        Schema::dropIfExists('products_counts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
