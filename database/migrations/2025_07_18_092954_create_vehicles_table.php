<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('owner_id'); 
            $table->unsignedBigInteger('vehicle_type_id');
            $table->string('brand');
            $table->string('model');
            $table->year('year')->nullable();
            $table->text('description')->nullable();

            $table->unsignedBigInteger('fuel_type_id')->nullable();
            $table->unsignedBigInteger('transmission_id')->nullable();
            $table->integer('autonomy_km')->nullable();
            $table->decimal('battery_capacity_kwh', 5, 2)->nullable();
            $table->integer('max_speed_kph')->nullable();
            $table->tinyInteger('seats')->nullable();
            $table->integer('cargo_volume_liters')->nullable();

            $table->string('license_plate', 50)->nullable();
            $table->string('location');
            $table->string('cover_image')->nullable();
            $table->longText('gallery_images')->nullable();
            $table->longText('availability_calendar')->nullable();
            $table->decimal('price_per_day', 8, 2);

            $table->boolean('is_verified')->default(0);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');

            $table->timestamps();

            // Foreign keys
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_types');
            $table->foreign('fuel_type_id')->references('id')->on('fuel_types');
            $table->foreign('transmission_id')->references('id')->on('transmissions');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
