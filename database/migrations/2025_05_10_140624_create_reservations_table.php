<?php

use App\Enums\ReservationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
             $table->uuid('id')->primary();

            $table->foreignId('client_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Mașina rezervată
            $table->foreignId('car_id')
                  ->constrained('cars')
                  ->cascadeOnDelete();

            // Perioada rezervării
            $table->date('start_date');
            $table->date('end_date');

            // Costuri
            $table->decimal('price_per_day', 8, 2);
            $table->decimal('total_price', 10, 2);
            $table->decimal('deposit_amount', 10, 2)->default(0);

            $table->enum('status', ReservationStatus::values())
                  ->default(ReservationStatus::Pending->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
