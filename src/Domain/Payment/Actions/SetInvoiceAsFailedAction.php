<?php

namespace Domain\Payment\Actions;

use Illuminate\Support\Facades\DB;

use Domain\Payment\Enums\InvoiceStatus;
use Domain\Payment\Models\{Invoice, InvoiceItem};
use Domain\Product\Actions\UpdateInventoryQuantityAction;
use Domain\Product\Models\Product;

class SetInvoiceAsFailedAction  
{
    static function execute(Invoice $invoice):bool
    {
        DB::beginTransaction();
        try {
            
            # Puting lock on the record.
            $invoice = $invoice->lockForUpdate()->find($invoice->id);
          
            # Check status transition.
            if(!$invoice->status->canTransitTo(InvoiceStatus::Failed))
                 throw new \Exception("The invoice status can not change to {$invoice->status->name}");
           
            # update status to paid.
            $invoice->update([
                'status' => InvoiceStatus::Failed
            ]);

            # The reserved items should be returned to inventory.
            if($invoice->items->map(
                fn(InvoiceItem $item) => UpdateInventoryQuantityAction::execute(
                    new Product($item->product_id),
                    $item->quantity
                ) === false ? 1 : 0
            )->sum() > 0 ) throw new \Exception("System can not return back the items to inventory.");

            DB::commit();
            
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            return false;
        }
    }

}