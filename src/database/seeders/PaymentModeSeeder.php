<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMode;

class PaymentModeSeeder extends Seeder
{
    public function run(): void
    {
        $modes = [
            ['mode' => 'Cash'],
            ['mode' => 'GCash'],
        ];

        foreach ($modes as $mode) {
            PaymentMode::create([
                'mode' => $mode['mode'],
                'created_date' => now(),
                'last_updated' => now(),
                'is_active' => true,
            ]);
        }
    }
}
