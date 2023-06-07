<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeProductNrCompanySkuCombinationUnique extends Migration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function(Blueprint $table) {
            $table->string('company_sku', 20)->nullable()->change();
            $table->dropUnique('products_product_nr_unique');
            $table->unique(['product_nr', 'company_sku'], 'product_nr_company_sku_unqiue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function(Blueprint $table) {
            $table->dropUnique('product_nr_company_sku_unqiue');
            $table->unique('product_nr');
            $table->string('company_sku', 20)->nullable()->change();
        });
    }
}
