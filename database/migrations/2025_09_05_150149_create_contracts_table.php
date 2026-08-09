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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            // bookings.id e uuid, deci si cheia straina trebuie sa fie uuid.
            // Aici era foreignUlid (char(26)), corectat abia de migrarea
            // 2025_09_06_093647. MySQL tolera nepotrivirea (permite lungimi
            // diferite la coloane text in chei straine), dar MariaDB mapeaza
            // uuid() pe tipul nativ UUID si respinge legatura cu errno 150,
            // oprind tot lantul de migrari inainte sa apuce sa se corecteze.
            $table->foreignUuid('booking_id')->constrained()->cascadeOnDelete();

            // Starea contractului (începem cu pending)
            // (vom sincroniza cu ReservationStatus: contract_pending / partially_signed / signed)
            $table->string('status')->default('pending');

            // Dacă generezi un PDF/HTML, poți salva path-ul aici
            $table->string('document_path', 2048)->nullable();

            // Cine a inițiat (opțional)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['booking_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
