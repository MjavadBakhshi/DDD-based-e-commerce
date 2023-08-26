<?php

namespace Domain\Payment\Enums;

enum InvoiceStatus:string {
    case Pending = 'Pending';
    case Failed = 'Failed';
    case Paid = 'Paid';


    function canTransitTo(self $status):bool 
    {
        $allowedTransition = [
            self::Pending->value => [self::Paid, self::Failed],
            self::Failed->value => [self::Pending],
        ];

        return isset($allowedTransition[$this->value]) && 
         in_array($status, $allowedTransition[$this->value]);
    }
}