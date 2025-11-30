<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PerfumeSize;

class PerfumeSizeSeeder extends Seeder
{
    public function run(): void
    {
        PerfumeSize::create([
            'size_ml' => 50,
            'created_date' => now(),
            'last_updated' => now(),
            'is_active' => true,
        ]);
    }
}