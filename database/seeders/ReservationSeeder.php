<?php

namespace Database\Seeders;

use App\Enums\ReservationStatus;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Reservation;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ownerId   = 6;
        $clientIds = [34, 32, 44];

        // Preluăm mașinile proprietarului
        $cars = Car::where('owner_id', $ownerId)->get();

        foreach ($cars as $car) {
            foreach ($clientIds as $clientId) {
                // Generăm interval random de rezervare
                $start = Carbon::today()->addDays(rand(1, 30));
                $end   = (clone $start)->addDays(rand(1, 5));

                // Calculăm prețurile
                $pricePerDay = $car->price_per_day ?? 100;
                $days        = $end->diffInDays($start) + 1;
                $totalPrice  = $pricePerDay * $days;

                Booking::create([
                    'id'             => Uuid::uuid(),
                    'client_id'      => $clientId,
                    'car_id'         => $car->id,
                    'owner_id'       => $ownerId,
                    'start_date'     => $start->toDateString(),
                    'end_date'       => $end->toDateString(),
                    'price_per_day'  => $pricePerDay,
                    'total_price'    => $totalPrice,
                    'security_deposit' => 0,
                    'status'         => ReservationStatus::Pending->value,
                ]);
            }
        }
    }
}
