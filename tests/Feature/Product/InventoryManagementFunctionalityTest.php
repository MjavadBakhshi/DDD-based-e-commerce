<?php

namespace Tests\Feature\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Domain\Product\Models\Inventory;
use Domain\Product\Actions\UpdateInventoryQuantityAction;

class InventoryManagementFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_update_inventory(): void
    {
        # create some products with random inventory of them.
        $inventories = Inventory::factory(9)->create(['quantity' => 0]);

        $quantities = [5, 9, 10, 15, 14, 12, 11, 2, 0];

        # Setting quantities for each product.
        $inventories->each(
            fn($inventory, $key) => 
            UpdateInventoryQuantityAction::execute($inventory->product, $quantities[$key])
        );

        # get actual quantities.
        $actualQuantities = $inventories->map(fn($inventory) => $inventory->fresh()->quantity)->toArray();
        
        $this->assertEquals($quantities, $actualQuantities);

        # decrement one of them.
        UpdateInventoryQuantityAction::execute($inventories[0]->product, -2);
        
        $this->assertEquals(($quantities[0] - 2) , $inventories[0]->refresh()->quantity);
    }


    function test_check_update_quantity_transaction_roll_back()
    {
        $inventory = Inventory::factory()->create(['quantity' => 0]);

        # try to decrement quantity in order to be negative and make error.
        $result = UpdateInventoryQuantityAction::execute($inventory->product, -1);

        $this->assertFalse($result);

        $this->assertEquals(0, $inventory->refresh()->quantity);


    }
    
}
