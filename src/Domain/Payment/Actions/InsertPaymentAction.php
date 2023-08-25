<?php

namespace Domain\Payment\Actions;

use Domain\Payment\DataTransferObjects\PaymentData;
use Domain\Payment\Models\Payment;
use Domain\Shared\Models\User;

class InsertPaymentAction  
{
    static function execute(
        PaymentData $paymentData,
        User $user
    ):Payment|false {
       
       return  $paymentData->invoice->payments()->create([
                    ...$paymentData->except('invoice')->toArray(),
                    'user_id' => $user->id
                ]) ?? false;
    }
}