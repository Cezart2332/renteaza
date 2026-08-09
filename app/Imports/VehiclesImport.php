<?php

namespace App\Imports;

use App\Models\FuelType;
use App\Models\Location;
use App\Models\Transmission;
use App\Models\Vehicle;
use Faker\Provider\Uuid;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VehiclesImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        $vehicle = new Vehicle([
            'id' => Uuid::uuid(),
            'owner_id' => 1,
            'vehicle_type_id' => 1,
            'car_type' => $row['car_type'] ?? null,
            'brand' => $row['brand'] ?? null,
            'model' => $row['model'] ?? null,
            'year' => $row['year'] ?? null,
            'description' => $row['description'] ?? null,

            'fuel_type_id' => FuelType::where('name', $row['fuel_type'] ?? '')->value('id'),
            'transmission_id' => Transmission::where('name', $row['transmission'] ?? '')->value('id'),

            'autonomy_km' => $row['autonomy_km'] ?? null,
            'battery_capacity_kwh' => $row['battery_capacity_kwh'] ?? null,
            'max_speed_kph' => $row['max_speed_kph'] ?? null,
            'seats' => $row['seats'] ?? null,
            'cargo_volume_liters' => $row['cargo_volume_liters'] ?? null,
            'license_plate' => $row['license_plate'] ?? null,
            'location' => $row['location'] ?? null,
            'cover_image' => $row['cover_image'] ?? null,
            'gallery_images' => $row['gallery_images'] ?? null,
            'availability_calendar' => $row['availability_calendar'] ?? null,
            'price_per_day' => $row['price_per_day'] ?? null,
            'is_verified' => $row['is_verified'] ?? false,
            'status' => $row['status'] ?? 'pending',
        ]);

        $vehicle->save();
        
        $studioLocationId = Location::where('name', 'RENTeeaza Studio')->value('id');

        if ($studioLocationId && $vehicle->exists) {
            $vehicle->locations()->attach($studioLocationId);
        }

        return $vehicle;
    }
}
