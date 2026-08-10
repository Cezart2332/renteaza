<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Cont de proprietar de flota (cel care listeaza masini), pentru testare.
 *
 * Aplicatia nu avea, pana acum, nicio cale de a atribui roluri: nici la
 * inregistrare, nici din panoul de admin. Acum tipul de cont se alege la
 * inregistrare, dar seederul asta ramane util ca sa existe un cont gata facut.
 */
class CompanyOwnerUserSeeder extends Seeder
{
    private const NAME = 'Proprietar Test';
    private const EMAIL = 'owner@example.ro';
    private const PASSWORD = 'test1234';

    public function run(): void
    {
        // 'user' = zona principala (masini, rezervari, plati)
        // 'company-owner' = doar mini-site-ul public al firmei
        $roleIds = collect(['user', 'company-owner'])
            ->map(fn (string $name) => Role::query()->firstOrCreate(['name' => $name])->id)
            ->all();

        $owner = User::query()->firstOrNew(['email' => self::EMAIL]);
        $owner->name = self::NAME;
        // cast-ul 'hashed' din User face hash-ul
        $owner->password = self::PASSWORD;
        // 'accepted' ca sa nu apara ca in asteptare in panoul de admin
        $owner->status = UserStatus::Accepted;
        $owner->email_verified_at = now();
        $owner->save();

        $owner->roles()->syncWithoutDetaching($roleIds);

        // Fara rand in `companies`, /company-owner/profile intoarce 404
        // (controllerul face firstOrFail pe compania utilizatorului).
        $company = Company::query()->firstOrNew(['user_id' => $owner->id]);
        $company->name = 'Firma Test SRL';
        $company->email = self::EMAIL;
        $company->user_id = $owner->id;
        $company->save();

        $this->command?->info('Company owner: ' . self::EMAIL . ' / ' . self::PASSWORD);
    }
}
