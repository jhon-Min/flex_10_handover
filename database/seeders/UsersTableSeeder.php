<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = [
            [
                'first_name' => 'Irshad',
                'last_name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'account_code' => '',
                'company_name' => 'Test Comopany',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Admin'
            ],
            [
                'first_name' => 'Irshad',
                'last_name' => 'Admin',
                'email' => 'myueway98@gmail.com',
                'password' => Hash::make('password'),
                'account_code' => '',
                'company_name' => 'Test Comopany',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Irshad',
                'last_name' => 'Admin',
                'email' => 'irshad@flexibledrive.com',
                'password' => Hash::make('123456@admin'),
                'account_code' => '',
                'company_name' => 'Test Comopany',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Admin'
            ],
            [
                'first_name' => 'Kholmes',
                'last_name' => 'Kholmes',
                'email' => 'kholmes@flexibledrive.com.au',
                'password' => Hash::make('123456@admin'),
                'account_code' => '',
                'company_name' => 'Flexible Drive',
                'address_line1' => '86 Stubbs Street Kensington',
                'address_line2' => '',
                'state' => 'VIC',
                'zip' => '3031',
                'mobile' => '',
                'role' => 'Admin'
            ],
            [
                'first_name' => 'Dparolin',
                'last_name' => 'Dparolin',
                'email' => 'dparolin@flexibledrive.com.au',
                'password' => Hash::make('123456@admin'),
                'account_code' => '',
                'company_name' => 'Flexible Drive',
                'address_line1' => '86 Stubbs Street Kensington',
                'address_line2' => '',
                'state' => 'VIC',
                'zip' => '3031',
                'mobile' => '',
                'role' => 'Admin'
            ],

            [
                'first_name' => 'Damien',
                'last_name' => 'Parolin',
                'email' => 'dparolin@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'VIT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '86 Stubbs Street Kensington',
                'address_line2' => '',
                'state' => 'VIC',
                'zip' => '3031',
                'mobile' => '',
                'role' => 'Customer',
                'is_confirmed' => 1
            ], [
                'first_name' => 'Chris',
                'last_name' => 'Gill',
                'email' => 'cgill@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'VIT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '86 Stubbs Street Kensington',
                'address_line2' => '',
                'state' => 'VIC',
                'zip' => '3031',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Darren',
                'last_name' => 'Bagatella',
                'email' => 'dbagatella@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'NST99',
                'company_name' => 'Flexible Drive',
                'address_line1' => 'Unit 17/70 Holbeche Road Arndell Park',
                'address_line2' => '',
                'state' => 'NSW',
                'zip' => '2148',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Jackson',
                'last_name' => 'Main',
                'email' => 'jmain@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'NST99',
                'company_name' => 'Flexible Drive',
                'address_line1' => 'Unit 17/70 Holbeche Road Arndell Park',
                'address_line2' => '',
                'state' => 'NSW',
                'zip' => '2148',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Milad',
                'last_name' => 'Main',
                'email' => 'myousif@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'NST99',
                'company_name' => 'Flexible Drive',
                'address_line1' => 'Unit 17/70 Holbeche Road Arndell Park',
                'address_line2' => '',
                'state' => 'NSW',
                'zip' => '2148',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Gary',
                'last_name' => 'Lawrence',
                'email' => 'glawrence@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'QLT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '11 Boyland Avenue Coopers Plains',
                'address_line2' => '',
                'state' => 'QLD',
                'zip' => '4108',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Chesney',
                'last_name' => 'Wilcox',
                'email' => 'wilcox@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'QLT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '11 Boyland Avenue Coopers Plains',
                'address_line2' => '',
                'state' => 'QLD',
                'zip' => '4108',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Brendan',
                'last_name' => 'Kerr',
                'email' => 'bkerr@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'QLT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '11 Boyland Avenue Coopers Plains',
                'address_line2' => '',
                'state' => 'QLD',
                'zip' => '4108',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Ralph',
                'last_name' => 'Sette',
                'email' => 'rsette@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'SAT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '138 Days Road Ferryden Park',
                'address_line2' => '',
                'state' => 'SA',
                'zip' => '5010',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Colin',
                'last_name' => 'Park',
                'email' => 'cpark@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'SAT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '138 Days Road Ferryden Park',
                'address_line2' => '',
                'state' => 'SA',
                'zip' => '5010',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Howlett',
                'email' => 'dhowlett@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'TAT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '15 Chesterman Strett Moonah',
                'address_line2' => '',
                'state' => 'TAS',
                'zip' => '7009',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Chris',
                'last_name' => 'Coleman',
                'email' => 'ccoleman@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'TAT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '15 Chesterman Strett Moonah',
                'address_line2' => '',
                'state' => 'TAS',
                'zip' => '7009',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Damian',
                'last_name' => 'Cameron',
                'email' => 'dcameron@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'WAT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '37 Adrian Street Welshpool',
                'address_line2' => '',
                'state' => 'WA',
                'zip' => '6016',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Alan',
                'last_name' => 'Lewis',
                'email' => 'alewis@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'WAT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '37 Adrian Street Welshpool',
                'address_line2' => '',
                'state' => 'WA',
                'zip' => '6016',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Josh',
                'last_name' => 'Carroll',
                'email' => 'jcarroll@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'WAT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '37 Adrian Street Welshpool',
                'address_line2' => '',
                'state' => 'WA',
                'zip' => '6016',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Kevin',
                'last_name' => 'Holmes',
                'email' => 'kholmes@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'VIT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '86 Stubbs Street Kensington',
                'address_line2' => '',
                'state' => 'VIC',
                'zip' => '3031',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Andrea',
                'last_name' => 'Rushton',
                'email' => 'arushton@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'VIT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '86 Stubbs Street Kensington',
                'address_line2' => '',
                'state' => 'VIC',
                'zip' => '3031',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Mark',
                'last_name' => 'Morrison',
                'email' => 'mmorrison@flexibledrive.com.au',
                'password' => Hash::make('FD@123456'),
                'account_code' => 'VIT99',
                'company_name' => 'Flexible Drive',
                'address_line1' => '86 Stubbs Street Kensington',
                'address_line2' => '',
                'state' => 'VIC',
                'zip' => '3031',
                'mobile' => '',
                'role' => 'Customer'
            ],




            [
                'first_name' => 'Mat',
                'last_name' => 'Jones',
                'email' => 'sales@betterbrakesvic.com',
                'password' => Hash::make('123456'),
                'account_code' => 'BET01',
                'company_name' => 'Better Brakes',
                'address_line1' => '1/381 Bayswater Rd, Bayswater VIC 3153',
                'address_line2' => 'Bayswater',
                'state' => 'VIC',
                'zip' => '3153',
                'mobile' => '0417 518 855',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Zac',
                'last_name' => 'Weiss',
                'email' => 'sales@moorabbin.absauto.com.au',
                'password' => Hash::make('123456'),
                'account_code' => 'ABS04',
                'company_name' => 'ABS Moorabbin',
                'address_line1' => 'Cnr Chesterville and Wickham Rds,',
                'address_line2' => 'Moorabbin',
                'state' => 'VIC',
                'zip' => '3189',
                'mobile' => '0418 583 314',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Darden',
                'last_name' => 'Brown',
                'email' => 'bsbc@live.com.au',
                'password' => Hash::make('123456'),
                'account_code' => 'BEN15',
                'company_name' => 'Bendigo Specialist Brake & Clutch',
                'address_line1' => '5 Stanley St,',
                'address_line2' => 'Quarry Hill',
                'state' => 'VIC',
                'zip' => '3550',
                'mobile' => '0409 189 606',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Singh',
                'email' => 'james@highwayclutch.com.au',
                'password' => Hash::make('123456'),
                'account_code' => 'HIG04',
                'company_name' => 'Highway Clutch & Brake ',
                'address_line1' => '187-189 Barry Rd,',
                'address_line2' => 'Campbellfield',
                'state' => 'VIC',
                'zip' => '3061',
                'mobile' => '0421 152 954',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Trudi',
                'last_name' => 'Gillie',
                'email' => 'trudi@actbrakes.com.au',
                'password' => Hash::make('123456'),
                'account_code' => 'ACT15',
                'company_name' => 'ACT Brake Service',
                'address_line1' => '21 Huddart Ct,',
                'address_line2' => 'Mitchell',
                'state' => 'VIC',
                'zip' => '2911',
                'mobile' => '0405 617 726',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Ben',
                'last_name' => 'Ford',
                'email' => 'sales@reliancebrakes.com.au',
                'password' => Hash::make('123456'),
                'account_code' => 'REL00',
                'company_name' => 'Reliance Brakes',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Amruta',
                'last_name' => 'Mistry',
                'email' => 'amruta.mistry@gmail.com',
                'password' => Hash::make('123456'),
                'account_code' => 'FD001',
                'company_name' => 'Ilham Solutions',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Irshad',
                'last_name' => 'Nainar',
                'email' => 'irshad@ilhamsolutions.com',
                'password' => Hash::make('123456'),
                'account_code' => 'FD002',
                'company_name' => 'Ilham Solutions',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'subha',
                'last_name' => 'flexibledrive',
                'email' => 'subha@flexibledrive.com',
                'password' => Hash::make('123456'),
                'account_code' => 'FD002',
                'company_name' => 'Ilham Solutions',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Krutik',
                'last_name' => 'flexibledrive',
                'email' => 'kkrutikk@gmail.com',
                'password' => Hash::make('123456'),
                'account_code' => 'FD002',
                'company_name' => 'Test Comopany',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Customer'
            ],

            [
                'first_name' => 'Irshad',
                'last_name' => 'Admin',
                'email' => 'irshad@flexibledrive.com',
                'password' => Hash::make('123456@admin'),
                'account_code' => '',
                'company_name' => 'Test Comopany',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Admin'
            ],
            [
                'first_name' => 'Mat',
                'last_name' => 'Jones',
                'email' => 'sales@betterbrakesvic.com',
                'password' => Hash::make('123456'),
                'account_code' => 'BET01',
                'company_name' => 'Better Brakes',
                'address_line1' => '1/381 Bayswater Rd, Bayswater VIC 3153',
                'address_line2' => 'Bayswater',
                'state' => 'VIC',
                'zip' => '3153',
                'mobile' => '0417 518 855',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Zac',
                'last_name' => 'Weiss',
                'email' => 'sales@moorabbin.absauto.com.au',
                'password' => Hash::make('123456'),
                'account_code' => 'ABS04',
                'company_name' => 'ABS Moorabbin',
                'address_line1' => 'Cnr Chesterville and Wickham Rds,',
                'address_line2' => 'Moorabbin',
                'state' => 'VIC',
                'zip' => '3189',
                'mobile' => '0418 583 314',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Darden',
                'last_name' => 'Brown',
                'email' => 'bsbc@live.com.au',
                'password' => Hash::make('123456'),
                'account_code' => 'BEN15',
                'company_name' => 'Bendigo Specialist Brake & Clutch',
                'address_line1' => '5 Stanley St,',
                'address_line2' => 'Quarry Hill',
                'state' => 'VIC',
                'zip' => '3550',
                'mobile' => '0409 189 606',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Singh',
                'email' => 'james@highwayclutch.com.au',
                'password' => Hash::make('123456'),
                'account_code' => 'HIG04',
                'company_name' => 'Highway Clutch & Brake ',
                'address_line1' => '187-189 Barry Rd,',
                'address_line2' => 'Campbellfield',
                'state' => 'VIC',
                'zip' => '3061',
                'mobile' => '0421 152 954',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Trudi',
                'last_name' => 'Gillie',
                'email' => 'trudi@actbrakes.com.au',
                'password' => Hash::make('123456'),
                'account_code' => 'ACT15',
                'company_name' => 'ACT Brake Service',
                'address_line1' => '21 Huddart Ct,',
                'address_line2' => 'Mitchell',
                'state' => 'VIC',
                'zip' => '2911',
                'mobile' => '0405 617 726',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Ben',
                'last_name' => 'Ford',
                'email' => 'sales@reliancebrakes.com.au',
                'password' => Hash::make('123456'),
                'account_code' => 'REL00',
                'company_name' => 'Reliance Brakes',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Amruta',
                'last_name' => 'Mistry',
                'email' => 'amruta.mistry@gmail.com',
                'password' => Hash::make('123456'),
                'account_code' => 'FD001',
                'company_name' => 'Ilham Solutions',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Irshad',
                'last_name' => 'Nainar',
                'email' => 'irshad@ilhamsolutions.com',
                'password' => Hash::make('123456'),
                'account_code' => 'FD002',
                'company_name' => 'Ilham Solutions',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'subha',
                'last_name' => 'flexibledrive',
                'email' => 'subha@flexibledrive.com',
                'password' => Hash::make('123456'),
                'account_code' => 'FD002',
                'company_name' => 'Ilham Solutions',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Krutik',
                'last_name' => 'flexibledrive',
                'email' => 'kkrutikk@gmail.com',
                'password' => Hash::make('123456'),
                'account_code' => 'FD002',
                'company_name' => 'Test Comopany',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Customer'
            ],
            [
                'first_name' => 'Flexible drive',
                'last_name' => 'Demo user',
                'email' => 'test@flexibledrive.com.au',
                'password' => Hash::make('123456'),
                'account_code' => 'BEN05',
                'company_name' => 'Test Comopany',
                'address_line1' => '1/112-116 Jardine St,',
                'address_line2' => 'Fairy Meadow',
                'state' => 'NSW',
                'zip' => '2519',
                'mobile' => '',
                'role' => 'Customer'
            ]
        ];
        if (!empty($users)) {
            foreach ($users as $user) {

                // if((User::where('email', $user['email'])->exists())) {
                $role = Role::where('name', $user['role'])->first();
                unset($user['role']);
                //$user_id = User::firstOrCreate($user);
                $user_id = User::firstOrCreate(['email' => $user['email']], $user);
                $user_role = UserRole::firstOrCreate(['user_id' =>  $user_id->id, 'role_id' =>  $role->id]);

                // }
            }
        }
    }
}
