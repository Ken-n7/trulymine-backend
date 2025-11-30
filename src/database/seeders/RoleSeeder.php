<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role' => 'Admin'],
            ['role' => 'Reseller'],
        ];

        foreach ($roles as $role) {
            Role::create([
                'role' => $role['role'],
                'created_date' => now(),
                'last_updated' => now(),
                'is_active' => true,
            ]);
        }
    }
}
