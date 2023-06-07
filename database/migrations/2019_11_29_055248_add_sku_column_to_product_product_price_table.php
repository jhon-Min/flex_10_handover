<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSkuColumnToProductProductPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('company_sku', 20)->nullable()->after('fitting_position');
        });

        Schema::table('product_prices', function (Blueprint $table) {
            $table->string('company_sku', 20)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('company_sku');
        });
        Schema::table('product_prices', function (Blueprint $table) {
            $table->dropColumn('company_sku');
        });
    }
}
