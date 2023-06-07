<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterImportScriptHistoriesTableAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_script_histories', function (Blueprint $table) {
           
            $table->tinyInteger('product_fitting_position')->default(0)->after('product_vehicles');
           
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

            $table->dropColumn('product_fitting_position');
        });
    }
}
