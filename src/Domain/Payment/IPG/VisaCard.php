<?php

namespace Domain\Payment\IPG;

use Domain\Payment\Enums\IPGType;

class VisaCard extends IPG
{

    function getRedirectUri():string
    {
        return "https://visa.io/action/pay?apikey=sth&invoice={$this->invoice->id}";
    }
  
    function checkingStatus():bool
    {
        /** getting $context and do some IPG spcefic operations to validate payment */
        $paymentStatus = false;
        return $paymentStatus;
    }

    protected function getIPGType():IPGType
    {
        return IPGType::VisaCard;
    }
}