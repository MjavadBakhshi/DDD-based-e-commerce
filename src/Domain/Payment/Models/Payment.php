<?php

namespace Domain\Payment\Models;

use Domain\Shared\Models\Concerns\HasUser;

use Domain\Shared\Models\BaseModel;

class Payment extends BaseModel
{
    use HasUser;

    protected $guarded = [];
}
