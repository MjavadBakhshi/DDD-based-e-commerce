<?php

namespace Domain\Payment\Actions;

use Domain\Payment\DataTransferObjects\BasketData;
use Domain\Payment\DataTransferObjects\BasketItemData;
use Domain\Product\Actions\UpdateInventoryQuantityAction;
use Illuminate\Support\Facades\DB;

class ReserveBasketItemsAction 
{
    /**
     * @return array if there are some products which are not available.
     * @return bool (true) if everything is ok (false) some thing was wrong.
     */
    static function execute(
        BasketData $basket
    ):array|bool{

        DB::beginTransaction();
        try {
            $unavaibleProducts = [];
            $basket->basket_items->toCollection()->each(
                function(BasketItemData $basketItem) use(&$unavaibleProducts) {
                if(!UpdateInventoryQuantityAction::execute(
                    $basketItem->product, 
                    -$basketItem->quantity
                )) $unavaibleProducts[] = $basketItem->product->id;  
            });
            
            if($unavaibleProducts)
            {
                DB::rollBack();
                return $unavaibleProducts;
            }
            
            DB::commit();
            return true;
        }
        catch(\Exception $e){
            DB::rollBack();
            return false;
        }

    }
}