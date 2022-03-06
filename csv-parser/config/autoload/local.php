<?php

return [
    'db' => [
        'adapters' => [
            'import-db' => [
                'driver' => 'pdo-mysql',
                'database' => 'import',
                'username' => 'import-rw',
                'password' => 'a4a4a4AA!!',
                'hostname' => 'mysql-anwalt'
            ]
        ]
    ],
    'api' => [
        'seller' => 'http://php-seller-api/sellers',
        'contact' => 'http://php-contact-api/contacts',
        'sale' => 'http://php-sale-api/sales'
    ],
];
