<?php

namespace FileStorage\Core;

use Dez\Auth\Auth;
use Dez\Authorizer\Adapter\Token;
use Dez\Config\Config;
use Dez\Db\Connection;
use Dez\DependencyInjection\Injectable;
use Dez\EventDispatcher\Dispatcher;
use Dez\Flash\Flash\Session;
use Dez\Http\Cookies;
use Dez\Http\Request;
use Dez\Http\Response;
use Dez\Loader\Loader;
use Dez\Router\Router;
use Dez\Session\Adapter;
use Dez\Url\Url;
use Dez\View\View;

/**
 * Class Application
 * @package Dez\Mvc
 *
 * @property Loader loader
 * @property Config config
 * @property Dispatcher eventDispatcher
 * @property Dispatcher event
 * @property Request request
 * @property Cookies cookies
 * @property Response response
 * @property Adapter session
 * @property Router router
 * @property Url url
 * @property View view
 * @property Connection db
 * @property Token authorizerToken
 * @property Session authorizerSession
 * @property Session flash
 */

abstract class InjectableAware extends Injectable { }