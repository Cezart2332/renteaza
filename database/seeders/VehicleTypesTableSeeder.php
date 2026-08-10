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
    // insertOrIgnore, nu insert: seederele ruleaza la fiecare deploy.
    // (upsert cu array de update gol e tratat de Laravel exact ca insert,
    //  deci ar fi crapat pe cheia unica 'name'.)
    public function run(): void
    {
           DB::table('vehicle_types')->insertOrIgnore([
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
