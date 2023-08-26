<?php

namespace Domain\Payment\IPG;

use Domain\Payment\Actions\InsertPaymentAction;
use Domain\Payment\Actions\SetInvoiceAsPaidAction;
use Domain\Payment\DataTransferObjects\PaymentData;
use Domain\Payment\Enums\IPGType;
use Domain\Payment\Models\Invoice;
use Domain\Payment\Models\Payment;

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
    final public function setContext(array $context):self
    {
        $this->context = $context;
        return $this; # chaining.
    }

    /** Generate a payment uri which is used to redirect user to payment page. */
    function savePayment():Payment|false
    {
        $user = $this->invoice->user;
        # Save the payment record
        return InsertPaymentAction::execute(
            PaymentData::from([
                'ipg_type' => $this->getIPGType(),
                'total_price' => $this->invoice->total_price,
                'invoice' => $this->invoice,
            ]), 
            $user
        );
    }

    abstract function getRedirectUri():string;

    abstract protected function getIPGType():IPGType;
    
    abstract function checkingStatus():bool;

    final function process()
    {
        $this->checkingStatus() ? $this->onSuccess() : $this->onFailed();
    }

    protected function onSuccess(){
        /** Get invoice id from $context */
        $invoiceId = $this->context['invoice_id'];
        SetInvoiceAsPaidAction::execute(new Invoice(['id' => $invoiceId]));
    }

    protected function onFailed(){}
}