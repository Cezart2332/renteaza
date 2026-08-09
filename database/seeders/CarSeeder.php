<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\RentalAvailability;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // 2. Truncate pentru toate tabelele pe care vrei să le golești
        DB::table('rental_availabilities')->truncate();
        DB::table('cars')->truncate();

        $brandsModels = [
            'Tesla' => ['Model 3', 'Model Y', 'Model S', 'Model X'],
            'BMW' => ['i3', 'i4', 'iX'],
            'Hyundai' => ['Ioniq 5', 'Kona Electric'],
            'Renault' => ['Zoe', 'Megane E-Tech'],
            'Volkswagen' => ['ID.3', 'ID.4'],
            'Kia' => ['EV6', 'Niro EV'],
            'Audi' => ['Q4 e-tron', 'e-tron GT'],
            'Nissan' => ['Leaf', 'Ariya'],
        ];

        $fuelTypes = ['electric', 'hibrid'];
        $transmissions = ['automat', 'manual'];
        $statuses = ['active', 'inactive', 'pending'];
        $carTypes = ['Cars', 'Sedan', 'Sports', 'Jeep', 'Limousine'];
        $rentTypes = ['Peer-to-peer', 'Ridesharing', 'Firmă parteneră'];
        $pickupLocations = ['Houston', 'Texas', 'New York', 'Other Location'];

        for ($i = 0; $i < 1000; $i++) {
            $brand = array_rand($brandsModels);
            $model = $brandsModels[$brand][array_rand($brandsModels[$brand])];

            $car = Car::create([
                'owner_id' => rand(1, 20),
                'brand' => $brand,
                'model' => $model,
                'year' => rand(2018, 2024),
                'fuel_type' => $fuelTypes[array_rand($fuelTypes)],
                'autonomy_km' => rand(150, 600),
                'transmission' => $transmissions[array_rand($transmissions)],
                'price_per_day' => rand(30, 150),
                'cover_image' => 'https://via.placeholder.com/640x480.png?text=Car',
                'gallery_images' => json_encode([]),
                'location' => fake()->city(),
                'license_plate' => strtoupper(Str::random(3)) . '-' . rand(100, 999),
                'insurance_valid_until' => now()->addMonths(rand(3, 36)),
                'description' => fake()->text(150),
                'availability_calendar' => json_encode([]),
                'is_verified' => (bool)rand(0, 1),
                'status' => $statuses[array_rand($statuses)],
                'type' => $carTypes[array_rand($carTypes)],
            ]);

            // Adaugă o înregistrare în tabela rental_availabilities
            RentalAvailability::create([
                'car_id' => $car->id,
                'pickup_location' => $pickupLocations[array_rand($pickupLocations)],
                'rent_type' => $rentTypes[array_rand($rentTypes)],
                'available_from' => now()->addDays(rand(1, 10)),
                'available_until' => now()->addDays(rand(15, 60)),
            ]);
        }
    }
}
