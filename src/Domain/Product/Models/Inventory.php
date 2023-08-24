<?php

namespace Domain\Product\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Domain\Shared\Models\BaseModel;

class Inventory extends BaseModel
{

    protected $primaryKey = 'product_id';
    public  $incrementing = false;
    
    protected $fillable = ['product_id',  'quantity'];


    function product():BelongsTo 
    {
        return $this->belongsTo(Product::class);
    }
}
