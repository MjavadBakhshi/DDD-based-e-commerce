<?php

namespace Domain\Payment\Actions;


use Illuminate\Support\Facades\DB;

use Domain\Payment\DataTransferObjects\{BasketData, BasketItemData};
use Domain\Payment\Enums\InvoiceStatus;
use Domain\Payment\Models\{Invoice, InvoiceItem};
use Domain\Product\Models\Product;
use Domain\Shared\Models\User;

class GenerateInvoiceReportAction  
{
    static function execute(?InvoiceStatus $invoiceStatus = null):array
    {
        $invoiceQuery = Invoice::select('id', 'total_price', 'total_items');
        if(!is_null($invoiceStatus))
            $invoiceQuery->whereStatus($invoiceStatus);

        return $invoiceQuery->latest()->get()->toArray();
    }
}