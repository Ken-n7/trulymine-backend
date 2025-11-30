<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category' => 'Male'],
            ['category' => 'Female'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'category' => $category['category'],
                'created_date' => now(),
                'last_updated' => now(),
                'is_active' => true,
            ]);
        }
    }
}