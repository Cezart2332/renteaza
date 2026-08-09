<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransmissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('transmissions')->insert([
            ['name' => 'automatic'],
            ['name' => 'manual'],
            ['name' => 'none'],
        ]);
    }
}
