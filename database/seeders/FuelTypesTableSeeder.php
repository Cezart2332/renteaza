<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FuelTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // insertOrIgnore, nu insert: seederele ruleaza la fiecare deploy.
    // (upsert cu array de update gol e tratat de Laravel exact ca insert,
    //  deci ar fi crapat pe cheia unica 'name'.)
    public function run(): void
    {
        DB::table('fuel_types')->insertOrIgnore([
            ['name' => 'electric'],
            ['name' => 'hybrid'],
            ['name' => 'gasoline'],
            ['name' => 'diesel'],
            ['name' => 'pedal'],
            ['name' => 'human'],
            ['name' => 'none'],
        ]);
    }
}
