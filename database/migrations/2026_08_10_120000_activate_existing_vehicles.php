<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * De acum, listarile publice arata doar vehiculele cu status 'active'
 * (Vehicle::scopePubliclyVisible). Coloana are default 'pending', deci fara
 * migrarea asta toate masinile deja introduse ar disparea de pe site in
 * momentul deploy-ului, fara ca nimeni sa fi facut vreo greseala.
 *
 * Marcam retroactiv ca aprobate doar ce exista in momentul migrarii.
 * Masinile adaugate dupa aceea trec prin aprobarea adminului.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('vehicles')
            ->where('status', 'pending')
            ->update([
                'status' => 'active',
                'is_verified' => true,
            ]);
    }

    public function down(): void
    {
        // Nu se poate distinge intre vehiculele activate de migrarea asta si
        // cele aprobate ulterior de admin, asa ca nu revenim automat.
    }
};
