<?php

namespace Domain\Payment\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Domain\Shared\Models\Concerns\HasUser;
use Domain\Shared\Models\BaseModel;

class Invoice extends BaseModel
{
    use HasUser;

    protected $guarded = [];

    function items():HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    function payments():HasMany
    {
        return $this->hasMany(Payment::class);
    }

}
