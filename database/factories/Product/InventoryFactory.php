<?php

namespace Database\Factories\Product;

use Domain\Product\Models\{Inventory, Product};
use Illuminate\Database\Eloquent\Factories\Factory;


class InventoryFactory extends Factory
{

    protected $model = Inventory::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'quantity' => fake()->numberBetween(1, 10),
        ];
    }
}
