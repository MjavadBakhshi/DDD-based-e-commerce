<?php

namespace Database\Factories\Payment;

use Illuminate\Database\Eloquent\Factories\Factory;

use Domain\Payment\Models\Invoice;
use Domain\Shared\Models\User;

class InvoiceFactory extends Factory
{

    protected $model = Invoice::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total_price' => fake()->numberBetween(1000, 2000),
            'total_items' => fake()->numberBetween(1, 20),
            'address' => fake()->address(),
        ];
    }
}
