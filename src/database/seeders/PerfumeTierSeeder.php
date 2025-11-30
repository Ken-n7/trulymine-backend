<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PerfumeTier;

class PerfumeTierSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = [
            ['tier' => 'Regular'],
            ['tier' => 'Limited Edition'],
        ];

        foreach ($tiers as $tier) {
            PerfumeTier::create([
                'tier' => $tier['tier'],
                'created_date' => now(),
                'last_updated' => now(),
                'is_active' => true,
            ]);
        }
    }
}