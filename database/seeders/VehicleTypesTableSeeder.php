<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           DB::table('vehicle_types')->insert([
            ['name' => 'car'],
            ['name' => 'motorcycle'],
            ['name' => 'scooter'],
            ['name' => 'bike'],
            ['name' => 'kick_scooter'],
            ['name' => 'van'],
            ['name' => 'atv'],
            ['name' => 'truck'],
        ]);
    }
}
