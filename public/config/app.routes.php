<?php

    class AppRoutes {

        static protected $routes    = [
            '/'     => [
                'handler'   => [
                    \DezCDN\Action\GetFile::class => 'fetchInfo'
                ],
                'regexp'    => ['hash', '[a-f0-9]{32}'],
                'methods'   => ['get']
            ],
            '/file/:hash'   => [
                'handler'   => [
                    \DezCDN\Action\GetFile::class => 'fetchInfo'
                ],
                'regexp'    => ['hash', '[a-f0-9]{32}'],
                'methods'   => ['get']
            ]
        ];

        public function __construct(\Dez\Micro\Application $application)
        {
            foreach(static::$routes as $route => $routeParams) {
                $hansler    = function() use ($routeParams){
                    $class  = key($routeParams);
                    $method = $routeParams[$class];
                    return call_user_func_array([new $class, $method], []);
                };
                $application->any($hansler, $hansler)->via($routeParams['methods']);
            }
        }

    }