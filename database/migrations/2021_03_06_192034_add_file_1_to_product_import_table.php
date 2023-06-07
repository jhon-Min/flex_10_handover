<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFile1ToProductImportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stock_import_status', function (Blueprint $table) {
            $table->string('file_path1');
            $table->string('error_file_path1');
            $table->string('import_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_stock_import_status', function (Blueprint $table) {
            //
        });
    }
}
