<?php

namespace Database\Seeders;

use App\Models\FaqCategory;
use App\Models\RentalType;
use App\Models\RentalTypeVehicle;
use App\Models\Role;
use App\Models\User;
use App\Models\Vehicle;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(100)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // RentalType::create([
        //     'name' => 'rent a car',
        //     'label' => 'Rent a Car',
        // ]);

        // $vehicles = Vehicle::all();
        // foreach ($vehicles as $vehicle) {
        //     RentalTypeVehicle::create([
        //         'rental_type_id' => 2,
        //         'vehicle_id' => $vehicle->id,
        //     ]);
        //     RentalTypeVehicle::create([
        //         'rental_type_id' => 4,
        //         'vehicle_id' => $vehicle->id,
        //     ]);
        // }

        // Vehicle::all()->each(function ($vehicle) {
        //     // $vehicle->slug = Str::slug("{$vehicle->brand} {$vehicle->model} {$vehicle->year}");
        //     // $vehicle->doors = 4; // Default value for doors
        //     // $vehicle->seats = 5;
        //     // $vehicle->save();
        // });

        $this->call([
            // RoleSeeder::class,
            // CarSeeder::class,
            // VehicleTypesTableSeeder::class,
            // FuelTypesTableSeeder::class,
            // TransmissionsTableSeeder::class,
            // RentalTypesTableSeeder::class,
            // LocationsTableSeeder::class,
            // AddStudioLocationSeeder::class,
            //FaqSeeder::class,
            RoleSeeder::class,
            AdminUserSeeder::class,
            CompanyOwnerUserSeeder::class,
        ]);
    }
}
