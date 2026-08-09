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
        Schema::create('owner_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // date de payout
            $table->string('account_holder_name');        // Nume titular
            $table->string('iban');                       // IBAN (RO..)
            $table->string('bank_name')->nullable();      // opțional
            $table->string('currency', 3)->default('RON');

            // verificare/admin workflow minimal
            $table->string('status')->default('pending'); // pending|verified|rejected
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['user_id']);                 // un IBAN activ per owner (simplu)
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_bank_accounts');
    }
};
