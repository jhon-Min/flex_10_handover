<?php

namespace Database\Seeders;

use App\Models\Make;
use Illuminate\Database\Seeder;

class MakesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $makes = ['Audi', 'BMW', 'Ford Australia', 'Holden', 'Hyundai', 'Kia', 'Mazda', 'Mercedes-Benz', 'Mitsubishi', 'Nissan', 'Subaru', 'Toyota', 'Volkswagen'];

        Make::whereIn('name', $makes)->update(['is_common' => '1']);
    }
}
