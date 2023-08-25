<?php

use Domain\Payment\Enums\IPGType;

return [
    'default' => IPGType::Paypal,

    'drivers' => [
        IPGType::Paypal->value => [
            /** paypal configuration such as  endpoint uri and etc. */
        ]
    ]

];