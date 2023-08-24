<?php

namespace Domain\Payment\DataTransferObjects;

use Illuminate\Http\Request;

use Spatie\LaravelData\{Data, DataCollection};
use Spatie\LaravelData\Attributes\WithoutValidation;

use Domain\Product\Models\Product;

class BasketData extends Data
{
    function __construct(
        #[WithoutValidation]
        /** @var DataCollection<BasketItemData> */
        public readonly DataCollection $basket_items
    ){}

    /**
     * I expected to have an array on basket items which is reprensented as an assoc array with keys: 
     * [product_id, quantitiy]
     */
    static function fromRequest(Request $request)
    {       
        $basket_items = collect($request->get('data'))
        ->map(fn($item) => 
            BasketItemData::validateAndCreate([
                'product' => new Product(['id' => $item['product_id']]),
                'quantity' => $item['quantity']
            ])
        );     

        return self::from([
            'basket_items' => $basket_items
        ]);
    }
}