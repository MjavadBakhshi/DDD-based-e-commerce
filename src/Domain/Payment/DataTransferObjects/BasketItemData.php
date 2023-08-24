<?php

namespace Domain\Payment\DataTransferObjects;

use Illuminate\Validation\Validator;
use Spatie\LaravelData\Data;

use Domain\Product\Models\Product;

class BasketItemData extends Data 
{
    
    function __construct(
        public readonly Product $product,
        public readonly int $quantity
    ){}

        
    static function rules():array 
    {
        return [
            'product' => 'required|numeric|exists:products,id',
            'quantity' => 'required|numeric|min:1',
        ];
    }

    public static function withValidator(Validator $validator): void
    {
        $data = $validator->getData();
        $data['product'] = $data['product']->id;
        $validator->setData($data);
    }

}