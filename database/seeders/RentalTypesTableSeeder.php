<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rental_types')->insert([
            ['name' => 'peer_to_peer', 'label' => 'Peer-to-peer'],
            ['name' => 'ridesharing', 'label' => 'Ridesharing'],
            ['name' => 'partner_company', 'label' => 'Firmă parteneră'],
        ]);
    }
}
