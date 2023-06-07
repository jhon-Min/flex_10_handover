<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignKeyFromPickupAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       DB::statement('alter table pickup_addresses DROP FOREIGN KEY  pickup_addresses_pickup_location_id_foreign') ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pickup_addresses', function (Blueprint $table) {
            //
        });
    }
}
