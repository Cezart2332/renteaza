<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddStudioLocationSeeder extends Seeder
{
    public function run(): void
    {
       Location::create([
            'name' => 'RENTeeaza Studio',
            'address' => 'Strada Constantin Brâncuși 9',
            'city' => 'București',
            'postal_code' => '030423',
            'country' => 'România',
            'latitude' => 44.429278,
            'longitude' => 26.159077,
        ]);
    }
}
