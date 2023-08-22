<?php

namespace Domain\Product\Models;

use Spatie\LaravelData\WithData;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Domain\Shared\Models\BaseModel;
use Domain\Product\DataTransferObjects\InventoryData;

class Inventory extends BaseModel
{
    use WithData;

    protected $dataClass = InventoryData::class;

    protected $primaryKey = 'product_id';
    public  $incrementing = false;
    
    protected $fillable = ['product_id',  'quantity'];


    function product():BelongsTo 
    {
        return $this->belongsTo(Product::class);
    }
}
