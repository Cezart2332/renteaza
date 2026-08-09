<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Contul de administrator. Schimba parola imediat dupa primul login
     * intr-un mediu accesibil din exterior.
     */
    private const NAME = 'Administrator';
    private const EMAIL = 'admin@example.ro';
    private const PASSWORD = 'test1234';

    public function run(): void
    {
        // 'admin' e rolul cel mai puternic care chiar e verificat de aplicatie:
        // rutele din routes/admin.php trec prin middleware-ul 'role:admin'.
        // ('super-admin' apare doar comentat in RoleSeeder, nu il cere nimeni.)
        $adminRole = Role::query()->firstOrCreate(['name' => 'admin']);

        // firstOrNew + save => rularea repetata actualizeaza contul existent
        // in loc sa crape pe unique-ul de email.
        $admin = User::query()->firstOrNew(['email' => self::EMAIL]);
        $admin->name = self::NAME;
        // cast-ul 'hashed' din User face hash-ul; nu pune Hash::make aici.
        $admin->password = self::PASSWORD;
        $admin->status = UserStatus::Accepted;
        $admin->email_verified_at = now();
        $admin->save();

        // nu ataseaza a doua oara daca rolul e deja legat
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        $this->command?->info('Admin: ' . self::EMAIL . ' / ' . self::PASSWORD);

        if (app()->environment('production')) {
            $this->command?->warn(
                'Rulezi in productie cu parola implicita. Schimb-o acum!'
            );
        }
    }
}
