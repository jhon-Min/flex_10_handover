<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMarketIntelsTableAddSortDescriptionColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_intels', function (Blueprint $table) {
            $table->text('short_description')->nullable()->after('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_intels', function (Blueprint $table) {
            $table->dropColumn('short_description');
        });
    }
}
