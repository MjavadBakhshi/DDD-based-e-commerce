<?php

namespace Domain\Payment\Models;

use Domain\Shared\Models\Concerns\HasUser;

use Domain\Payment\Enums\IPGType;
use Domain\Shared\Models\BaseModel;

class Payment extends BaseModel
{
    use HasUser;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'date',
        'ipg_info' => 'array',
        'ipg_type' => IPGType::class,
    ];
    
}