<?php

namespace Tests\Feature\Product;

use Domain\Product\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    function test_purchase_payment_when_items_are_available()
    {
        $inventories = Inventory::factory(3)->create();

        $data = $inventories->map(fn($item) => [
            'product_id' => $item->product->id,
            'quantity' => $item->quantity,
        ])->toArray();

        $response = $this->post(route('api.payment'), [
            'data' => $data
        ]);

        $response
            ->assertSuccessful()
            ->assertStatus(200)
            ->assertJson(['ok' => true]);

        $inventories->each(fn($item) => $item->refresh());

        $this->assertEquals([0, 0, 0], $inventories->pluck('quantity')->toArray());
    }

    function test_products_payment_when_some_items_unavailable()
    {
        $inventories = Inventory::factory(3)->create();
       
        $data = $inventories->map(fn($item) => [
            'product_id' => $item->product->id,
            'quantity' => $item->quantity,
        ])->toArray();

        # we want produt 1 and 2 one item more than available in the inventory.
        $data[0]['quantity']++;
        $data[1]['quantity']++;

        $response = $this->post(route('api.payment'), [
            'data' => $data
        ]);

        $response
            ->assertSuccessful()
            ->assertStatus(205)
            # we expect the output result includes the product ids which are unavailable.
            ->assertJson(['ok' => false, 'data' => [
                $data[0]['product_id'], $data[1]['product_id']
            ]]);

        $this->assertEquals(
            $inventories->pluck('quantity')->toArray(), 
            $inventories->map(fn($model) => $model->refresh())->pluck('quantity')->toArray()
        );
    }

}