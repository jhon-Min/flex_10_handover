<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('branches')
          ->where('code', 'AC001')
          ->update([
            "address" => "--",
            "city" => "--",
            "state" => "--",
            "phone" => "--",
            "email" => "--",
            "contact" => "--"
          ]);
        DB::table('branches')
          ->where('code', 'NSW01')
          ->update([
            "address" => "Unit 17, 70 Holbeche Road",
            "city" => "Arndell Park",
            "state" => "NSW",
            "phone" => "0417 100 145",
            "email" => "nswsales@flexibledrive.com.au",
            "contact" => "Darren Bagatella"
          ]);
        DB::table('branches')
          ->where('code', 'QLD01')
          ->update([
            "address" => "11 Boyland Ave",
            "city" => "Coopers Plains",
            "state" => "QLD",
            "phone" => "0419 425 749",
            "email" => "qldsales@flexibledrive.com.au",
            "contact" => "Brendan Kerr"
          ]);
        DB::table('branches')
          ->where('code', 'SA001')
          ->update([
            "address" => "138 Days Rd",
            "city" => "Ferryden Park",
            "state" => "SA",
            "phone" => "0408 100 284",
            "email" => "sasales@flexibledrive.com.au",
            "contact" => "Ralph Sette"
          ]);
        DB::table('branches')
          ->where('code', 'TAS01')
          ->update([
            "address" => "15 Chesterman St",
            "city" => "Moonah",
            "state" => "TAS",
            "phone" => "0407 503 546",
            "email" => "tassales@flexibledrive.com.au",
            "contact" => "David Howlett"
          ]);
        DB::table('branches')
          ->where('code', 'VIC01')
          ->update([
            "address" => "86 Stubbs Street",
            "city" => "Kensington",
            "state" => "VIC",
            "phone" => "0419 009 086",
            "email" => "vicsales@flexibledrive.com.au",
            "contact" => "James Ferry"
          ]);
        DB::table('branches')
          ->where('code', 'WA001')
          ->update([
            "address" => "37 Adrian St",
            "city" => "Welshpool",
            "state" => "WA",
            "phone" => "",
            "email" => "wasales@flexibledrive.com.au",
            "contact" => "Alan Lewis"
          ]);
        DB::table('branches')
          ->where('code', 'WHS01')
          ->update([
            "address" => "86 Stubbs Street",
            "city" => "Kensington",
            "state" => "VIC",
            "phone" => "0419 009 086",
            "email" => "vicsales@flexibledrive.com.au",
            "contact" => "James Ferry"
          ]);
        DB::table('branches')
          ->where('code', 'DCV01')
          ->update([
            "address" => "86 Stubbs Street",
            "city" => "Kensington",
            "state" => "VIC",
            "phone" => "0419 009 086",
            "email" => "vicsales@flexibledrive.com.au",
            "contact" => "James Ferry"
          ]);
        DB::table('branches')
          ->where('code', 'DCV02')
          ->update([
            "address" => "86 Stubbs Street",
            "city" => "Kensington",
            "state" => "VIC",
            "phone" => "0419 009 086",
            "email" => "vicsales@flexibledrive.com.au",
            "contact" => "James Ferry"
          ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
