<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPickupLocationFieldsToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('phone');
            $table->string('email');
            $table->string('contact');
            $table->string('mobile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('phone');
            $table->dropColumn('email');
            $table->dropColumn('contact');
            $table->dropColumn('mobile');
            //
        });
    }
}
