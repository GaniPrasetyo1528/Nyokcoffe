<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $slug = Str::slug($name);
        return [
            // 'category_id' => Category::factory(), ini akan berfungsi jika ada file CategoryFactory
            'category_id' => Category::inRandomOrder()->first()->id,
            'name' => $name,
            'slug' => $slug,
        ];
    }
}
