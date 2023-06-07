<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePricesColumnsInProductPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_prices', function (Blueprint $table) {
            $table->float('price_retail', 9, 6)->default(0)->change();
            $table->float('price_nett', 9, 6)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_prices', function (Blueprint $table) {
            $table->float('price_retail', 8, 6)->change();
            $table->float('price_nett', 8, 6)->change();
        });
    }
}
