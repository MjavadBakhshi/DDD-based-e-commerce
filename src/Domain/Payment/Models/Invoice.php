<?php

namespace Domain\Payment\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Domain\Payment\Enums\InvoiceStatus;
use Domain\Shared\Models\Concerns\HasUser;
use Domain\Shared\Models\BaseModel;

class Invoice extends BaseModel
{
    use HasUser;

    protected $guarded = [];

    protected $casts = [
        'status' => InvoiceStatus::class
    ];

    function items():HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    function payments():HasMany
    {
        return $this->hasMany(Payment::class);
    }

}
