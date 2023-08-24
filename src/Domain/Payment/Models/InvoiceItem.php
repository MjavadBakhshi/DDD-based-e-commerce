<?php

namespace Domain\Payment\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Domain\Shared\Models\BaseModel;

class InvoiceItem extends BaseModel
{
    
    protected $guarded = [];


    function invoice():BelongsTo 
    {
        return $this->belongsTo(Invoice::class);
    }
}
