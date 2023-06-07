<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportScriptHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_script_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('login')->default(0);
            $table->tinyInteger('brand')->default(0);
            $table->tinyInteger('categories')->default(0);
            $table->tinyInteger('make_model')->default(0);
            $table->tinyInteger('products')->default(0);
            $table->tinyInteger('product_categories')->default(0);
            $table->tinyInteger('vehicle')->default(0);
            $table->tinyInteger('product_vehicles')->default(0);
            $table->tinyInteger('product_images')->default(0);
            $table->tinyInteger('vehicle_engine_code')->default(0);
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->string('duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_script_histories');
    }
}
