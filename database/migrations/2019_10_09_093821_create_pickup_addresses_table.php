<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickupAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('pickup_location_id')->unsigned();
            $table->string('first_name', 25)->nullable();
            $table->string('last_name', 25)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->dateTime('pickup_date_time')->nullable()->default(NULL);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pickup_location_id')->references('id')->on('pickup_locations')->onDelete('cascade');
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
        Schema::dropIfExists('pickup_addresses');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
