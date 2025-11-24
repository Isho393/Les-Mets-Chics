<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cuisine Française',
                'slug' => 'cuisine-francaise',
                'description' => 'Découvrez les recettes raffinées de la gastronomie française, du coq au vin aux macarons parisiens.',
            ],
            [
                'name' => 'Cuisine Japonaise',
                'slug' => 'cuisine-japonaise',
                'description' => 'L\'art culinaire japonais : sushi, ramen, tempura et bien plus encore.',
            ],
            [
                'name' => 'Cuisine Italienne',
                'slug' => 'cuisine-italienne',
                'description' => 'Les saveurs authentiques d\'Italie : pasta, risotto, et dolce vita.',
            ],
            [
                'name' => 'Cuisine Méditerranéenne',
                'slug' => 'cuisine-mediterraneenne',
                'description' => 'Les délices ensoleillés de la Méditerranée, sains et savoureux.',
            ],
            [
                'name' => 'Pâtisserie',
                'slug' => 'patisserie',
                'description' => 'L\'excellence de la pâtisserie française et internationale.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
