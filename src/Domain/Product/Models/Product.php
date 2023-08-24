<?php

namespace Domain\Product\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

use Domain\Shared\Models\BaseModel;

class Product extends BaseModel
{
    protected $guarded = [];

    /** Relations */

    public function inventory():HasOne
    {
        return $this->hasOne(Inventory::class)
                ->withDefault();
    }
}
