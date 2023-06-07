<?php

namespace Database\Seeders;

use App\Models\PickupLocation;
use Illuminate\Database\Seeder;

class PickupLocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            [
                "name" => 'Flexible Drive Victoria',
                "address" => '86 Stubbs Street',
                "city" => 'Kensington',
                "state" => 'VIC',
                "post_code" => '3031',
                "phone" => '+61 3 9381 9222',
                "email" => 'vicsales@flexibledrive.com.au',
                "contact" => 'James Ferry',
                "mobile" => '0419 009 086',
                "contact_email" => 'jferry@flexibledrive.com.au',
            ],
            [
                "name" => 'Flexible Drive New South Wales',
                "address" => 'Unit 17, 70 Holbeche Road',
                "city" => 'Arndell Park',
                "state" => 'NSW',
                "post_code" => '2148',
                "phone" => '+61 2 9933 7400',
                "email" => 'nswsales@flexibledrive.com.au',
                "contact" => 'Darren Bagatella',
                "mobile" => '0417 100 145',
                "contact_email" => 'dbagatella@flexibledrive.com.au',
            ],
            [
                "name" => 'Flexible Drive Western Australia',
                "address" => '37 Adrian St',
                "city" => 'Welshpool',
                "state" => 'WA',
                "post_code" => '6106',
                "phone" => '+61 8 9229 4900',
                "email" => 'wasales@flexibledrive.com.au',
                "contact" => 'Alan Lewis',
                "mobile" => '',
                "contact_email" => 'wasales@flexibledrive.com.au',
            ],
            [
                "name" => 'Flexible Drive South Australia',
                "address" => '138 Days Rd',
                "city" => 'Ferryden Park',
                "state" => 'SA',
                "post_code" => '5010',
                "phone" => '+61 8 8139 3600',
                "email" => 'sasales@flexibledrive.com.au',
                "contact" => 'Ralph Sette',
                "mobile" => '0408 100 284',
                "contact_email" => 'rsette@flexibledrive.com.au',
            ],
            [
                "name" => 'Flexible Drive Queensland',
                "address" => '11 Boyland Ave',
                "city" => 'Coopers Plains',
                "state" => 'QLD',
                "post_code" => '4108',
                "phone" => '+61 7 3728 3800',
                "email" => 'qldsales@flexibledrive.com.au',
                "contact" => 'Brendan Kerr',
                "mobile" => '0419 425 749',
                "contact_email" => 'bkerr@flexibledrive.com.au',
            ],
            [
                "name" => 'Flexible Drive Tasmania',
                "address" => '15 Chesterman St',
                "city" => 'Moonah',
                "state" => 'TAS',
                "post_code" => '7009',
                "phone" => '+61 3 6214 2600',
                "email" => 'tassales@flexibledrive.com.au',
                "contact" => 'David Howlett',
                "mobile" => '0407 503 546',
                "contact_email" => 'dhowlett@flexibledrive.com.au',
            ]
        ];
        if (!empty($locations)) {
            foreach ($locations as $location) {
                if (!(PickupLocation::where('email', $location['email'])->exists())) {
                    PickupLocation::firstOrCreate($location);
                }
            }
        }
    }
}
