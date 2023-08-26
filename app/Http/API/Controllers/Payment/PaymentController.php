<?php

namespace App\Http\API\Controllers\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\API\Controllers\Shared\Controller;
use Domain\Payment\DataTransferObjects\{BasketData, BasketItemData};
use Domain\Payment\Actions\{
    GenerateInvoiceAction,
    ReserveBasketItemsAction
};
use Domain\Payment\IPG\IPG;
use Spatie\LaravelData\Attributes\Validation\IP;

class PaymentController extends Controller
{

    function __construct(protected readonly IPG $IPG){}

    /** Inject the IPG and laravel will automaticaly resolve that. */
    function startPayment(BasketData $basket) {

        DB::beginTransaction();
        
        try {
        
            $result = ReserveBasketItemsAction::execute($basket);
                        
            if($result === true)
            {
                /** generate innvoice for user */
                $invoice = GenerateInvoiceAction::execute(
                    $basket,
                    request()->user()
                );

                if($invoice === false) throw new \Exception('Generating invoice was not successful.');

                /** Proceed payment by generating redirect url*/
                $paymetUri = $this->IPG->setInvoice($invoice)->startPayment();

                return response()->json([
                    'ok' => true,
                    'data' => [
                        'payment_uri' => $paymetUri
                    ]
                ]);

            }else {
                if($result == false) throw new \Exception('Reserving items was not been successful.');

                return response()->json([
                    'ok' => false,
                    'data' => $result # unavailable product ids.
                ], 205);
            }
        }
        catch (\Exception $e) {
            DB::rollBack();
            return $this->failedResponse($e->getMessage());    
        }
    }

    function checkStatus(Request $request, ){

        # we get payment information via API request
        # Check payment status
        # do some actions like changing invoice status
        # sending notification 
        # or rollback the reserved inventory
        $reuslt = $this->IPG->setContext($request->all())->checkStatus();
        # we can also redirect user to determined url in the startPayment flow and the result.
        # for example when users request to start payment, they must send a redirect_uri paramter
        # and we can store that in the payment record in order to automatically redirect user to certain store.
        # we also do this by settings for each api token.
    }
}