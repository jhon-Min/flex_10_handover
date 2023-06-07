<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMakesTableAddIsCommonColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('makes', function (Blueprint $table) {
            $table->enum('is_common',['0','1'])->default('0')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('makes', function (Blueprint $table) {
            $table->dropColumn('is_common');
        });
    }
}
