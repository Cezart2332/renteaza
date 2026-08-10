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
    // insertOrIgnore, nu insert: seederele ruleaza la fiecare deploy.
    // (upsert cu array de update gol e tratat de Laravel exact ca insert,
    //  deci ar fi crapat pe cheia unica 'name'.)
    public function run(): void
    {
         DB::table('transmissions')->insertOrIgnore([
            ['name' => 'automatic'],
            ['name' => 'manual'],
            ['name' => 'none'],
        ]);
    }
}
