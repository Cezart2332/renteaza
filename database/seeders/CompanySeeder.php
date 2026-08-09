<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fixed = [
            [
                'name' => 'Renteaza SRL',
                'description' => 'Flotă variată de mașini și servicii premium.',
                'email' => 'contact@renteaza.ro',
                'phone' => '+40 745 000 001',
                'address' => 'Bd. Unirii 10, București, România',
                'latitude' => '44.43000000',
                'longitude' => '26.10000000',
                'website' => 'https://renteaza.ro',
                'logo' => null,
                'user_id' => 1
            ],
            [
                'name' => 'Urban Move SA',
                'description' => 'Ridesharing & rent a car pentru orașele mari.',
                'email' => 'hello@urban-move.ro',
                'phone' => '+40 744 222 333',
                'address' => 'Str. Memorandumului 2, Cluj-Napoca, România',
                'latitude' => '46.77000000',
                'longitude' => '23.59000000',
                'website' => 'https://urban-move.ro',
                'logo' => null,
                'user_id' => 2,
            ],
        ];
        Company::insert($fixed);
    }
}
