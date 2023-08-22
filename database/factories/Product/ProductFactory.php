<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

use Domain\Product\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'price' => fake()->numberBetween(100, 10000)
        ];
    }
}
