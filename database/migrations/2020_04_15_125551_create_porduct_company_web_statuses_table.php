<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePorductCompanyWebStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('porduct_company_web_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_nr', 20);
            $table->string('company_sku', 20);
            $table->string('company_web_status', 20);

            $table->unique(['product_nr', 'company_sku']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('porduct_company_web_statuses');
    }
}
