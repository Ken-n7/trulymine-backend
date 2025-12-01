<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed lookup tables in correct order
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            PerfumeSizeSeeder::class,
            PerfumeTierSeeder::class,
            OrderStatusSeeder::class,
            PaymentStatusSeeder::class,
            PaymentModeSeeder::class,
            DiscountTypeSeeder::class,
            PerfumeSeeder::class,
            PerfumeVariantSeeder::class,
        ]);

        // Create default admin user
        $adminRole = \App\Models\Role::where('role', 'Admin')->first();
        
        User::create([
            'name' => 'Admin',
            'email' => 'admin@perfume.com',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
            'created_date' => now(),
            'last_updated' => now(),
            'is_active' => true,
        ]);

        $this->command->info('✅ Lookup tables seeded successfully!');
        $this->command->info('✅ 16 perfumes created!');
        $this->command->info('✅ 16 variants created (All Regular tier @ ₱170.00)!');
        $this->command->info('📧 Admin Email: admin@perfume.com');
        $this->command->info('🔑 Admin Password: password123');
    }
}

