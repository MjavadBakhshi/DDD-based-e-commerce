<?php

namespace Domain\Payment\IPG;

use Domain\Payment\Models\Invoice;

abstract class  IPG 
{

    protected Invoice $invoice;
    /** @param $context keep the information about the payment after user is redirected to callback route. */
    protected array $context;

    final public function setInvoice(Invoice $invoice):self
    {
        $this->invoice = $invoice;
        return $this; # chaining.
    }

    /** TODO: We mawy use DTO for $context to typed it for better maintanance. */
    final public function setIContext(array $context):self
    {
        $this->context = $context;
        return $this; # chaining.
    }


    /** Generate a payment uri which is used to redirect user to payment page. */
    abstract  function startPayment():string;
    abstract function checkStatus():bool;
    protected function onSuccess(){}
    protected function onFailed(){}
}