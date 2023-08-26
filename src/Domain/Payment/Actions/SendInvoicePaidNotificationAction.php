<?php

namespace Domain\Payment\Actions;

use Illuminate\Support\Facades\Notification;

use Domain\Payment\Models\Invoice;
use Domain\Payment\Notifications\InvoicePaid;

class SendInvoicePaidNotificationAction 
{
    static function execute(Invoice $invoice)
    {
        Notification::route('mail', config('notification.email.recipients'))
            // ->route('vonage', '5555555555')
            ->notify(new InvoicePaid($invoice));
    }
}