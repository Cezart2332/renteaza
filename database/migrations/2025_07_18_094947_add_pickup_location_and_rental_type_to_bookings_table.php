<?php

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
        Schema::table('bookings', function (Blueprint $table) {
              $table->renameColumn('car_id', 'vehicle_id');
            $table->unsignedBigInteger('pickup_location_id')->nullable()->after('vehicle_id');
            $table->unsignedBigInteger('rental_type_id')->nullable()->after('pickup_location_id');

            $table->foreign('pickup_location_id')->references('id')->on('locations')->nullOnDelete();
            $table->foreign('rental_type_id')->references('id')->on('rental_types')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['pickup_location_id']);
            $table->dropForeign(['rental_type_id']);
            $table->dropColumn(['pickup_location_id', 'rental_type_id']);
        });
    }
};
