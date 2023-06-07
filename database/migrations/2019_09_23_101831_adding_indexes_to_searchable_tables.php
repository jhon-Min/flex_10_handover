<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingIndexesToSearchableTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->index('make_id');
            $table->index('model_id');
            $table->index('year_from');
            $table->index('year_to');
            $table->index('sub_model');
            $table->index('chassis_code');
            $table->index('engine_code');
            $table->index('cc');
            $table->index('power');
            $table->index('body_type');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->index('product_nr');
            $table->index('brand_id');
        });
        Schema::table('product_vehicles', function (Blueprint $table) {
            $table->index('vehicle_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndex(['make_id']);
            $table->dropIndex(['model_id']);
            $table->dropIndex(['year_from']);
            $table->dropIndex(['year_to']);
            $table->dropIndex(['sub_model']);
            $table->dropIndex(['chassis_code']);
            $table->dropIndex(['engine_code']);
            $table->dropIndex(['cc']);
            $table->dropIndex(['power']);
            $table->dropIndex(['body_type']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['product_nr']);
            $table->dropIndex(['brand_id']);
        });
        Schema::table('product_vehicles', function (Blueprint $table) {
            $table->dropIndex(['vehicle_id']);
            $table->dropIndex(['product_id']);
        });
    }
}
