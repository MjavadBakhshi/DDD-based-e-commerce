<?php

namespace Domain\Payment\IPG;

use Domain\Payment\Enums\IPGType;

class Paypal extends IPG
{
    function getRedirectUri():string
    {   
        /** Attach the invoice data to main paypal url and encode it */
        /** alseo we should set the callbackuri */
        return "https://paypal.com/payment/apikey=something&invoice_id={$this->invoice->id}";
    }

    function checkingStatus():bool
    {
        /** getting $context and do some IPG spcefic operations to validate payment */
        $paymentStatus = true;
        return $paymentStatus;
    }

    protected function getIPGType():IPGType {
        return IPGType::Paypal;
    }
}