<?php

namespace Domain\Payment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Domain\Payment\Models\Invoice;

class InvoicePaid extends Notification
{
    use Queueable;

    public function __construct(public Invoice $invoice){}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->from(config('notification.email.from'))
        ->subject("Invoice Paid")
        ->greeting('Hello!')
        ->line('New invoice has been paid.')
        ->line('Invoice details:')
        ->line('Invoice ID: ' . $this->invoice->id)
        ->line('Total price: ' . $this->invoice->total_price)
        ->line('Total items: ' . $this->invoice->total_items);
    }
}
