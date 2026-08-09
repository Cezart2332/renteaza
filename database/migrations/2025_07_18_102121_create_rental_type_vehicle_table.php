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
        Schema::create('rental_type_vehicle', function (Blueprint $table) {
            $table->id();
            $table->uuid('vehicle_id');
            $table->unsignedBigInteger('rental_type_id');

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('rental_type_id')->references('id')->on('rental_types')->onDelete('cascade');

            $table->unique(['vehicle_id', 'rental_type_id']); // previne duplicate

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_type_vehicle');
    }
};
