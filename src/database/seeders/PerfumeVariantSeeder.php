<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perfume;
use App\Models\PerfumeSize;
use App\Models\PerfumeTier;
use App\Models\PerfumeVariant;

class PerfumeVariantSeeder extends Seeder
{
    public function run(): void
    {
        $size50ml = PerfumeSize::where('size_ml', 50)->first();
        $regularTier = PerfumeTier::where('tier', 'Regular')->first();
        
        // Get all perfumes
        $perfumes = Perfume::all();

        foreach ($perfumes as $perfume) {
            // Create Regular tier variant only
            PerfumeVariant::create([
                'perfume_id' => $perfume->id,
                'size_id' => $size50ml->id,
                'tier_id' => $regularTier->id,
                'stock_quantity' => rand(50, 200),
                'price' => 170.00,
                'created_date' => now(),
                'last_updated' => now(),
                'is_active' => true,
            ]);
        }
    }
}


