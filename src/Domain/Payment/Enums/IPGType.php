<?php

namespace Domain\Payment\Enums;

use Domain\Payment\IPG\{IPG, Paypal, VisaCard};

enum IPGType:string {
    case Paypal = 'Paypal';
    case VisaCard = 'VisaCard';
    
    /** This is a map method which gives the ipg instance according to the case. */
    function getIPG():IPG{
        return match($this){
            self::Paypal => new Paypal(),
            self::VisaCard => new VisaCard(),
            default => throw new \Exception("The payment method is invalid.")
        };
    }

}

