<?php

namespace Domain\Payment\Actions;

use Illuminate\Support\Facades\DB;

use Domain\Payment\Enums\InvoiceStatus;
use Domain\Payment\Models\Invoice;

class SetInvoiceAsPaidAction  
{
    static function execute(Invoice $invoice):bool
    {
        DB::beginTransaction();
        try {
            
            # Puting lock on the record.
            $invoice = $invoice->lockForUpdate()->find($invoice->id);
          
            # Check status transition.
            if(!$invoice->status->canTransitTo(InvoiceStatus::Paid))
                 throw new \Exception("The invoice status can not change to {$invoice->status->name}");
           
            # update status to paid.
            $invoice->update([
                'status' => InvoiceStatus::Paid
            ]);
            DB::commit();
            
            /** Sending business notification here. */
            
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            return false;
        }
    }

}