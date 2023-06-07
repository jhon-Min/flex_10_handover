<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterImportScriptHistoriesTableAddProductCriteria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_script_histories', function (Blueprint $table) {
            $table->tinyInteger('product_criteria')->default(0)->after('vehicle_engine_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_script_histories', function (Blueprint $table) {
            $table->dropColumn('product_criteria');
        });
    }
}
