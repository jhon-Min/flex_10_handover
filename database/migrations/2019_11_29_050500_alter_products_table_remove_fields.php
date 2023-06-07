<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductsTableRemoveFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function ($table) {
            $table->dropColumn('price_nett');
            $table->dropColumn('price_retail');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function ($table) {
            $table->double('price_nett', 8, 2)->default(0);
            $table->double('price_retail', 8, 2)->default(0);
        });
    }
}
