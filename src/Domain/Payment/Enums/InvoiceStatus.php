<?php

namespace Domain\Payment\Enums;

enum InvoiceStatus:int {
    case Pending = 2;
    case Failed = 0;
    case Paid = 1;

}