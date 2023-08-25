<?php

namespace Domain\Payment\Enums;

use Domain\Payment\IPG\{IPG, Paypal};

enum IPGType:string {
    case Paypal = 'paypal';
    
    /** This is a map method which gives the ipg instance according to the case. */
    function getIPG():IPG{
        return match($this){
            self::Paypal => new Paypal(),
            default => throw new \Exception("The payment method is invalid.")
        };
    }

}

