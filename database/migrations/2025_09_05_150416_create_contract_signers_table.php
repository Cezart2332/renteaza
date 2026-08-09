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
        Schema::create('contract_signers', function (Blueprint $table) {
            $table->id();

            // FK spre contracts (BIGINT id)
            $table->foreignId('contract_id')->constrained('contracts')->cascadeOnDelete();

            // Utilizatorul asociat semnatarului (owner/client)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Rolul în contract: 'owner' sau 'client'
            $table->string('role', 20); // ex: owner | client
            $table->unique(['contract_id', 'role']); // un singur owner și un singur client per contract

            // Snapshot date contact (utile dacă userul își schimbă profilul)
            $table->string('name_snapshot')->nullable();
            $table->string('email_snapshot')->nullable();
            $table->string('phone_snapshot')->nullable();

            // Semnătură & status
            $table->boolean('has_signed')->default(false);
            $table->timestamp('signed_at')->nullable();
            $table->string('signature_path', 2048)->nullable(); // ex: path PNG cu semnătura

            $table->timestamps();

            $table->index(['contract_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_signers');
    }
};
