<?php

namespace Domain\Payment\DataTransferObjects;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

use Domain\Payment\Enums\IPGType;
use Domain\Payment\Models\Invoice;

class PaymentData extends Data
{
    function __construct(
        #[WithCast(EnumCast::class)]
        public readonly IPGType $ipg_type,
        public readonly Invoice $invoice,
        public readonly float $total_price,
        public readonly ?array $ipg_info
    ){}
}