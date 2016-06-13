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
            'directories' => [
                'public' => __DIR__ . '/../../public',
                'private' => __DIR__ . '/../../2c17c6393771ee3048ae34d6b380c5ec',
            ],
            'validation' => [
                'sizes' => [
                    'min' => 1,
                    'max' => 1024 * 1024 * 1024
                ],
                'mimes' => [
                    'black' => [],
                    'white' => []
                ],
                'extensions' => [
                    'black' => ['php',],
                    'white' => []
                ]
            ],
            'public_uri' => '/public'
        ],
        'debug' => [
            'exceptions' => 1,
            'php_errors' => 1,
        ],
        'production-config' => __DIR__ . '/storage-config.production.php',
    ],
    'db' => [
        'connectionName' => 'development',
        'connection' => [
            'development' => [
                'dsn' => 'mysql:host=localhost;dbname=file-storage',
                'user' => 'root',
                'password' => 'root',
            ],
        ],
    ],
    'server' => [
        'timezone' => 'Europe/Kiev',
        'displayErrors' => 'Off',
        'errorLevel' => 0,
    ],
];