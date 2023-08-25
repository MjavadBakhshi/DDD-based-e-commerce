<?php

namespace Domain\Payment\IPG;

use Domain\Payment\Actions\InsertPaymentAction;
use Domain\Payment\DataTransferObjects\PaymentData;
use Domain\Payment\Enums\IPGType;

class Paypal extends IPG
{

    function startPayment():string
    {
        $user = $this->invoice->user;
        # Save the payment record
        InsertPaymentAction::execute(
            PaymentData::from([
                'ipg_type' => IPGType::Paypal,
                'total_price' => $this->invoice->total_price,
                'invoice' => $this->invoice,
            ]), 
            $user
        );
        /** Attach the invoice data to main paypal url and encode it */
        /** alseo we should set the callbackuri */
        return "https://paypal.com/payment/apikey=something&invoice_id={$this->invoice->id}";
    }

    function checkStatus():bool
    {
        return true;
    }
}