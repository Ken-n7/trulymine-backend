<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perfume;
use App\Models\Category;

class PerfumeSeeder extends Seeder
{
    public function run(): void
    {
        $maleCategory = Category::where('category', 'Male')->first();
        $femaleCategory = Category::where('category', 'Female')->first();

        $perfumes = [
            // Male Perfumes
            [
                'name' => 'The Temptation',
                'description' => 'A bold and seductive fragrance for the modern man',
                'category_id' => $maleCategory->id,
            ],
            [
                'name' => 'Machismo',
                'description' => 'Strong and masculine scent with woody undertones',
                'category_id' => $maleCategory->id,
            ],
            [
                'name' => 'Ventus',
                'description' => 'Fresh and sporty fragrance inspired by the Mediterranean coast',
                'category_id' => $maleCategory->id,
            ],
            [
                'name' => 'Wild Spice',
                'description' => 'Exotic spicy blend with oriental notes',
                'category_id' => $maleCategory->id,
            ],
            [
                'name' => 'Extreme',
                'description' => 'Intense and powerful fragrance for the adventurous',
                'category_id' => $maleCategory->id,
            ],
            [
                'name' => 'Citrus Waves',
                'description' => 'Refreshing citrus scent with aquatic notes',
                'category_id' => $maleCategory->id,
            ],
            [
                'name' => 'Colossus',
                'description' => 'Grand and imposing fragrance with leather accents',
                'category_id' => $maleCategory->id,
            ],
            
            // Female Perfumes
            [
                'name' => 'Sugar Crush',
                'description' => 'Sweet and playful fragrance with fruity notes',
                'category_id' => $femaleCategory->id,
            ],
            [
                'name' => 'Vanilla Charm',
                'description' => 'Warm vanilla scent with elegant undertones',
                'category_id' => $femaleCategory->id,
            ],
            [
                'name' => 'Valaya',
                'description' => 'Sophisticated floral fragrance with a touch of mystery',
                'category_id' => $femaleCategory->id,
            ],
            [
                'name' => 'Dream Away',
                'description' => 'Light and dreamy scent perfect for day or night',
                'category_id' => $femaleCategory->id,
            ],
            [
                'name' => 'Rosy Velvet',
                'description' => 'Rich rose fragrance with velvety smooth finish',
                'category_id' => $femaleCategory->id,
            ],
            [
                'name' => 'Golden Bloom',
                'description' => 'Luxurious floral bouquet with golden amber notes',
                'category_id' => $femaleCategory->id,
            ],
            [
                'name' => 'Garden Aura',
                'description' => 'Fresh garden florals with natural green notes',
                'category_id' => $femaleCategory->id,
            ],
            [
                'name' => 'Tender Mist',
                'description' => 'Soft and delicate fragrance with powdery notes',
                'category_id' => $femaleCategory->id,
            ],
            [
                'name' => 'Goddess Limitless',
                'description' => 'Empowering and bold scent for the modern goddess',
                'category_id' => $femaleCategory->id,
            ],
        ];

        foreach ($perfumes as $perfume) {
            Perfume::create([
                'name' => $perfume['name'],
                'description' => $perfume['description'],
                'category_id' => $perfume['category_id'],
                'created_date' => now(),
                'last_updated' => now(),
                'is_active' => true,
            ]);
        }
    }
}
