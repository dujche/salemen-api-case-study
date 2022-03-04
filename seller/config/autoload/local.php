<?php

declare(strict_types=1);

return [
    'db' => [
        'adapters' => [
            'seller-db' => [
                'driver' => 'pdo-mysql',
                'database' => 'seller',
                'username' => 'seller-rw',
                'password' => 'a1a1a1AA!!',
                'hostname' => 'mysql-anwalt'
            ]
        ]
    ],
];
