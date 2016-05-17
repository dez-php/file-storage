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
                'public' => __DIR__ . '/../../public-files',
                'private' => __DIR__ . '/../../private-files',
            ],
            'validation' => [
                'sizes' => [
                    'min' => 1024,
                    'max' => 1024 * 1024 * 4
                ],
                'mimes' => [
                    'black' => [

                    ],
                    'white' => [
                        'text/plain', 'text/html', 'application/json'
                    ]
                ],
                'extensions' => [
                    'black' => [],
                    'white' => []
                ]
            ],
            'public_uri' => '/public-files'
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