<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DrinkDetail>
 */
class DrinkDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'product_id' => Product::factory(),
            'product_id' => Product::where('category_id', 2)->inRandomOrder()->first()->id,
            'is_available' => fake()->boolean(50), // 50% kemungkinan true
            'size' => fake()->randomElement(['Small', 'Medium', 'Large']),
            'price' => fake()->randomFloat(2, 10000, 50000), // harga antara 10k–50k
            'image' => fake()->imageUrl(640, 480, 'drink', true), // URL gambar dummy
        ];
    }
}
