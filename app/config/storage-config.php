<?php

return [
    'application' => [
        'staticPath' => '/web/static/',
        'basePath' => '/',
        'autoload' => [
            'FileStorage\\Controllers' => __DIR__ . '/../controllers',
            'FileStorage\\Models' => __DIR__ . '/../models',
            'FileStorage\\Services' => __DIR__ . '/../services',
            'FileStorage\\Core' => __DIR__ . '/../core',
        ],
        'controllerNamespace' => 'FileStorage\\Controllers\\',
        'viewDirectory' => __DIR__ . '/../templates',
        'uploader' => [
            'filesDirectory' => __DIR__ . '/../../shared-files'
        ]
    ],
    'db' => [
        'connection' => [
            'development' => [
                'dsn' => 'mysql:host=localhost;dbname=my-site',
                'user' => 'root',
                'password' => '0000',
            ],
            'production' => [
                'dsn' => 'mysql:host=localhost;dbname=my-site',
                'user' => 'root',
                'password' => '0000',
            ],
        ],
    ],
    'server' => [
        'timezone' => 'Europe/Kiev',
        'displayErrors' => 'Off',
        'errorLevel' => 0,
    ],
];