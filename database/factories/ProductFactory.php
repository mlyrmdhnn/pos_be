<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'uuid' => fake()->uuid(),
            'name' => fake()->sentence(rand(1,2)),
            'description' => fake()->text(),
            'price' => fake()->numberBetween(10000, 50000),
            'category_id' => ProductCategory::inRandomOrder()->first()->id,
            'quantity' => fake()->numberBetween(1,50),
            'sold' => fake()->numberBetween(1,20),
            'image_path' => ''
        ];
    }
}
