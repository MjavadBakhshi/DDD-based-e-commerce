<?php

namespace Domain\Payment\Actions;


use Illuminate\Support\Facades\DB;

use Domain\Payment\DataTransferObjects\{BasketData, BasketItemData};
use Domain\Payment\Models\{Invoice, InvoiceItem};
use Domain\Product\Models\Product;
use Domain\Shared\Models\User;

class GenerateInvoiceAction  
{
    static function execute(
        BasketData $basket,
        User $user
    ):Invoice|false{
        /** Run one query to retrieve price of all basket items. */
        $productsPrice = Product::select('id', 'price')
        ->whereIn(
            'id',
            $basket->basket_items->toCollection()
            ->map(fn(BasketItemData $item) => $item->product->id)
            ->toArray()
        )->get();

        /** sum up the bill. */
        $totalPrice = $basket->basket_items->toCollection()
                    ->sum(function(BasketItemData $item) use (&$productsPrice){
                        return  $item->quantity * 
                                $productsPrice->firstWhere('id', $item->product->id)->price;
                    });
    
        /** sum up total items */
        $total_items = $basket->basket_items->toCollection()->sum('quantity');

        DB::beginTransaction();
        try {
            /** create new invoice */
            $invoice = $user->invoices()->create([
                'total_price' => $totalPrice,
                'total_items' => $total_items,
                'address' => fake()->address()
            ]);
        
            # insert invoice items.
            $invoiceItems = $basket->basket_items->toCollection()->map(
                fn(BasketItemData $item) =>
                new InvoiceItem([
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity
                ]) 
            );
          
            $invoice->items()->saveMany($invoiceItems);
            
            DB::commit();
            return $invoice;
        }catch(\Exception $e){
            DB::rollBack();
            return false;
        }
    }
}