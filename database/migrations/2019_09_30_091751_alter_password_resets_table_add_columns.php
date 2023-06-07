<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPasswordResetsTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('password_resets', function (Blueprint $table) {

            $table->bigIncrements('id')->first();
            $table->timestamp('updated_at')->last();
          
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('password_resets', function (Blueprint $table) {

            $table->dropColumn('id');
            $table->dropColumn('updated_at');
           
        });
    }
}
