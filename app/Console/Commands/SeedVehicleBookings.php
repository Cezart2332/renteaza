<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\ReservationStatus;

class SeedVehicleBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-vehicle-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserează 3 booking-uri hardcodate pentru un vehicul specific';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('bookings')->insert([
            [
                'id'                => Str::uuid(),
                'client_id'         => '3', // schimbă cu UUID real
                'vehicle_id'        => '29896815-f91c-3a81-ae7f-d66a0f5b4558',
                'pickup_location_id'=> 1,
                'rental_type_id'    => 1,
                'owner_id'          => 1,
                'start_date'        => '2025-07-05',
                'end_date'          => '2025-07-07',
                'price_per_day'     => 100,
                'total_price'       => 300,
                'security_deposit'  => 200,
                'status'            => ReservationStatus::OwnerAccepted->value,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'id'                => Str::uuid(),
                'client_id'         => '5',
                'vehicle_id'        => '29896815-f91c-3a81-ae7f-d66a0f5b4558',
                'pickup_location_id'=> 1,
                'rental_type_id'    => 1,
                'owner_id'          => 1,
                'start_date'        => '2025-07-15',
                'end_date'          => '2025-07-18',
                'price_per_day'     => 100,
                'total_price'       => 400,
                'security_deposit'  => 200,
                'status'            => ReservationStatus::OwnerAccepted->value,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'id'                => Str::uuid(),
                'client_id'         => '6',
                'vehicle_id'        => '29896815-f91c-3a81-ae7f-d66a0f5b4558',
                'pickup_location_id'=> 1,
                'rental_type_id'    => 1,
                'owner_id'          => 1,
                'start_date'        => '2025-08-10',
                'end_date'          => '2025-08-13',
                'price_per_day'     => 100,
                'total_price'       => 400,
                'security_deposit'  => 200,
                'status'            => ReservationStatus::OwnerAccepted->value,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);

        $this->info('Booking-urile hardcodate au fost inserate cu succes!');
    }
}
