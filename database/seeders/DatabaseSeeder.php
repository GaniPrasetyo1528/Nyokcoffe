<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DrinkDetail;
use App\Models\FoodDetail;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'phone' => '089874743744',
            'address' => 'Bondowoso',
            'role' => 'admin',
            'status' => 'active',
            'password' => '123'
        ]);

        User::create([
            'username' => 'customer',
            'email' => 'customer@example.com',
            'phone' => '089985854454',
            'address' => 'Bondowoso',
            'role' => 'customer',
            'status' => 'active',
            'password' => '123'
        ]);

        Category::insert([
            [
                'name' => 'Makanan',
                'slug' => 'makanan'
            ],
            [
                'name' => 'Minuman',
                'slug' => 'minuman'
            ]
        ]);

        Product::factory(20)->create();
        DrinkDetail::factory(10)->create();
        FoodDetail::factory(10)->create();
    }
}
