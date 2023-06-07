<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductImportStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_import_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_name');
            $table->string('error_file');
            $table->bigInteger('total_records');
            $table->bigInteger('error_records');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('product_import_status');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
