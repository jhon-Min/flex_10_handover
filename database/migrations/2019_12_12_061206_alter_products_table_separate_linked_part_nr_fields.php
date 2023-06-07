<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductsTableSeparateLinkedPartNrFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('cross_reference_numbers')->nullable()->after('description');
            $table->string('associated_part_numbers')->nullable()->after('cross_reference_numbers');

            $table->dropColumn('corresponding_part_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('corresponding_part_number')->nullable()->after('description');

            $table->dropColumn('cross_reference_numbers');
            $table->dropColumn('associated_part_numbers');
        });
    }
}
