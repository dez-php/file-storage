<?php

namespace App\Config;

use Dez\Auth\Adapter\Session;
use Dez\Auth\Auth;
use Dez\Config\Adapter\Json as ConfigJson;
use Dez\DependencyInjection\Container as DiContainer;
use Dez\EventDispatcher\Dispatcher;
use Dez\Http\Cookies;
use Dez\Http\Request;
use Dez\Http\Response;
use Dez\Loader\Loader;
use Dez\Router\Router;
use Dez\Session\Adapter\Files as SessionHandler;
use Dez\Url\Url;
use Dez\View\Engine\Php as ViewPhpEngine;
use Dez\View\View;

// requires services

$di = DiContainer::instance();

$di->set( 'loader', new Loader() )->resolve( [], $di )->register();
$di->set( 'config', new ConfigJson( __DIR__ . '/config.json' ) );

$di->set( 'eventDispatcher', new Dispatcher() );
$di->set( 'event', $di['eventDispatcher'] );

$di->set( 'request', new Request() );
$di->set( 'cookies', new Cookies() );
$di->set( 'response', new Response() );

$di->set( 'session', (new SessionHandler())->start() );

$di->set( 'router', new Router() );

$di->set( 'url', new Url() );

$di->set( 'view', function() use ( $di ) {
    $viewDirectory  = __DIR__ . '/..' . $di['config']['app']['viewDirectory'];

    $view     = new View();
    $view->setViewDirectory( $viewDirectory );
    $view->registerEngine( '.php', new ViewPhpEngine( $view ) );

    return $view;
} )->resolve( [], $di );

//$di->set( 'auth', new Auth( new Session( $di ) ) );