<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\DiscountType;

class DiscountTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Percentage',
                'description' => 'Discount calculated as percentage of item price'
            ],
            [
                'name' => 'Fixed Amount',
                'description' => 'Discount as fixed amount deducted from item price'
            ],
        ];

        foreach ($types as $type) {
            DiscountType::create($type);
        }
    }
}
