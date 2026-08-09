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
        Schema::create('calendar_day_overrides', function (Blueprint $table) {
            $table->id();
            // referință la vehicul
            $table->foreignUuid('vehicle_id')->constrained('vehicles')->onDelete('cascade');

            // ziua la care se aplică override-ul
            $table->date('date');

            // preț custom (dacă e setat)
            $table->decimal('custom_price', 10, 2)->nullable();

            // blocare disponibilitate
            $table->boolean('is_blocked')->default(false);

            $table->timestamps();

            // fiecare vehicul poate avea un singur override pe zi
            $table->unique(['vehicle_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_day_overrides');
    }
};
