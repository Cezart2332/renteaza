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
        Schema::table('companies', function (Blueprint $table) {
            $table->foreignId('user_id')
                  ->unique()                // fiecare user are max 1 companie
                  ->constrained()           // references users(id)
                  ->cascadeOnDelete()       // dacă se șterge userul, se șterge și compania
                  ->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
              $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
