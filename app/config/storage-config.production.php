<?php

return [
    'application' => [
        'debug' => [
            'exceptions' => 0,
            'php_errors' => 0,
        ],
    ],
    'db' => [
        'connectionName' => 'production',
        'connection' => [
            'production' => [
                'dsn' => 'mysql:host=localhost;dbname=my-site',
                'user' => 'root',
                'password' => '0000',
            ],
        ],
    ],
];