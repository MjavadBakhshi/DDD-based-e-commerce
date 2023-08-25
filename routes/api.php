<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\API\Controllers\Payment\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



/** Authenticated rotues */
// Route::middleware('auth:sanctum')
// ->group(function () {
    
  
// });


Route::post('payment', [
        PaymentController::class, 
        'startPayment'
])->name('payment.start');

Route::post('payment/check-status', [
        PaymentController::class, 
        'checkStatus'
])->name('payment.check-status');
