<?php

namespace Domain\Product\Actions;

use Illuminate\Support\Facades\DB;

use Domain\Product\Models\Product;

class UpdateInventoryQuantityAction 
{
    static function execute(
        Product $product,
        # This can be a signed or unsgined so the sign specifies we want to increment or decrement.
        int $quantity
    ):bool {

        DB::beginTransaction();
        try {
            # Put a lock on invetory record to prevent undetermined result.
            $inventory = $product->inventory()->lockForUpdate()->first();

            # checking availability before reducing the quantity.
            if($quantity < 0 && $inventory->quantity < abs($quantity)) return false;
            
            $inventory->increment('quantity', $quantity);

            DB::commit();
            return true;

        }catch(\Exception $e){
            DB::rollBack();
            return false;
        }
    }
}