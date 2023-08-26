<?php

namespace App\Http\API\Controllers\Payment;

use App\Http\API\Controllers\Shared\Controller;

use Domain\Payment\Actions\GenerateInvoiceReportAction;
use Domain\Payment\Enums\InvoiceStatus;

class InvoiceReportController extends Controller
{
    /** We can implent a filter class to generate advanced reports */
    function __invoke(?InvoiceStatus $status = null)
    { 
        return $this->successResponse(
            GenerateInvoiceReportAction::execute($status)
        );
    }
}