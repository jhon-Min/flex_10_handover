<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductStockImportStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stock_import_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_path');
            $table->string('error_file_path');
            $table->bigInteger('total_records');
            $table->bigInteger('error_records');
            $table->string('status');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_stock_import_status');
    }
}
