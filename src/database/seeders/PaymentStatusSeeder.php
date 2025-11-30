<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['status_name' => 'Unpaid'],
            ['status_name' => 'Partial'],
            ['status_name' => 'Paid'],
        ];

        foreach ($statuses as $status) {
            PaymentStatus::create([
                'status_name' => $status['status_name'],
                'created_date' => now(),
                'last_updated' => now(),
                'is_active' => true,
            ]);
        }
    }
}