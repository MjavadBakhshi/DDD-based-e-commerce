<?php

namespace App\Http\API\Controllers\Payment;

use Illuminate\Support\Facades\DB;

use App\Http\API\Controllers\Shared\Controller;
use Domain\Payment\Actions\ReserveBasketItemsAction;
use Domain\Payment\DataTransferObjects\{BasketData, BasketItemData};

class PaymentController extends Controller
{
    function __invoke(BasketData $basket) {

        DB::beginTransaction();
        
        try {
        
            $result = ReserveBasketItemsAction::execute($basket);
                        
            if($result === true)
            {
                /** generate invoice for user */
                /** Proceed payment */
                return response()->json([
                    'ok' => true,
                ]);
            }else {
                if($result == false) throw new \Exception('error');

                


                return response()->json([
                    'ok' => false,
                    'data' => $result # unavailable product ids.
                ], 205);
            }
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->failedResponse('Something was wrong.');    
        }

    }
}