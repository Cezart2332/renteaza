<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            // 'super-admin',
            // 'admin',
            //'user'
            'company_owner'
        ];

        foreach ($roles as $role) {
            Role::query()->firstOrCreate([
                'id' => Str::uuid(),
                'name' => $role,
            ]);
        }
    }
}
