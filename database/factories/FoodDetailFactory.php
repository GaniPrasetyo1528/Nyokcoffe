<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FoodDetail>
 */
class FoodDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::where('category_id', 1)->inRandomOrder()->first()->id,
            'is_available' => fake()->boolean(50),
            'level' => fake()->numberBetween(0, 5), // kadang NULL, kadang angka 0–5
            'price' => fake()->randomFloat(2, 15000, 60000), // harga 15k–60k
            'image' => fake()->imageUrl(640, 480, 'food', true),
        ];
    }
}
