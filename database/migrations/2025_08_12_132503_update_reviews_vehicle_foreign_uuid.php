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
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'car_id')) {
                $table->dropForeign(['car_id']);
                $table->dropColumn('car_id');
            }

            // 2. Adaugă vehicle_id ca foreignUuid
            $table->foreignUuid('vehicle_id')
                ->after('owner_id')
                ->constrained('vehicles')
                ->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            //
        });
    }
};
