<?php

declare(strict_types=1);

use Parser\DataHandler\ContactDataHandler;
use Parser\DataHandler\SaleDataHandler;
use Parser\DataHandler\SellerDataHandler;

return [
    'data-handlers' => [
        SellerDataHandler::class,
        ContactDataHandler::class,
        SaleDataHandler::class
    ]
];
