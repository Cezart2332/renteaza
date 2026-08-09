<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Numele de aici trebuie sa fie identice cu cele cerute de middleware-ul
        // 'role:...' din bootstrap/app.php, altfel utilizatorii primesc 403.
        $roles = [
            'admin',
            'user',
            'company-owner', // atentie: cu cratima, nu 'company_owner'
        ];

        foreach ($roles as $role) {
            // Cautarea se face DOAR dupa nume. Varianta veche includea si
            // 'id' => Str::uuid() in criterii, deci nu gasea niciodata randul
            // existent si crea duplicate la fiecare rulare.
            // Id-ul UUID e generat automat de UuidModel la creare.
            Role::query()->firstOrCreate(['name' => $role]);
        }
    }
}
