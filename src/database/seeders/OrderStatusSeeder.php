<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['status' => 'Pending'],
            ['status' => 'Approved'],
            ['status' => 'Processing'],
            ['status' => 'Shipped'],
            ['status' => 'Completed'],
            ['status' => 'Cancelled'],
        ];

        foreach ($statuses as $status) {
            OrderStatus::create([
                'status' => $status['status'],
                'created_date' => now(),
                'last_updated' => now(),
                'is_active' => true,
            ]);
        }
    }
}
