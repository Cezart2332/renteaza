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
    public function run(): void
    {
        DB::table('fuel_types')->insert([
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
