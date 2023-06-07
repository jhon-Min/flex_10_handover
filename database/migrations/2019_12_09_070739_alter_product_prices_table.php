<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_prices', function (Blueprint $table) {
           $table->double('price_retail', 8, 6)->default(0)->after('company_sku');
           $table->double('price_nett', 8, 6)->default(0)->after('price_retail');

           $table->dropColumn('price');
           $table->unique(['product_id', 'user_id', 'company_sku']);
        });

        Schema::dropIfExists('product_price_tmp');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_prices', function (Blueprint $table) {
            $table->dropColumn('price_retail');
            $table->dropColumn('price_nett');

            $table->double('price', 8, 2)->default(0)->after('company_sku');            
        });
    }
}
