<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'user_id' => User::factory()->state(['role' => 'auteur', 'status' => 'active']),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'main_image_url' => 'https://example.com/image.jpg',
            'video_url' => null,
            'prep_time' => 20,
            'cook_time' => 40,
            'servings' => 4,
            'ingredients' => [['name' => 'Farine', 'quantity' => '200 g']],
            'steps' => [['order' => 1, 'text' => 'Mélanger.']],
            'status' => 'published',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }
}
