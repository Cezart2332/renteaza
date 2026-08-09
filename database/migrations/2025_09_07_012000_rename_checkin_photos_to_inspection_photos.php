<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Migrarea 2025_09_07_010523 a redenumit doar `checkin_submissions` in
 * `inspection_submissions`, nu si tabela de poze. Modelul CheckinPhoto si
 * migrarea 2025_09_07_014021 asteapta insa `inspection_photos`, asa ca pe o
 * baza de date curata migrarile se opreau aici.
 *
 * Verificarile hasTable fac migrarea sigura si pe bazele unde redenumirea a
 * fost facuta deja manual.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('checkin_photos') && ! Schema::hasTable('inspection_photos')) {
            Schema::rename('checkin_photos', 'inspection_photos');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('inspection_photos') && ! Schema::hasTable('checkin_photos')) {
            Schema::rename('inspection_photos', 'checkin_photos');
        }
    }
};
