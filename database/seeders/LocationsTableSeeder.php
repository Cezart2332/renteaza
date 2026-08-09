<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('locations')->insert([
            ['name' => 'București - Piața Victoriei', 'city' => 'București'],
            ['name' => 'Cluj - Aeroport', 'city' => 'Cluj-Napoca'],
            ['name' => 'Iași - Gara Centrală', 'city' => 'Iași'],
        ]);
    }
}
