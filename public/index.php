<?php

error_reporting(1); ini_set('display_errors', 1);

include_once '../vendor/autoload.php';

include_once 'config/app.services.php';
include_once 'config/app.loader.php';
include_once 'config/app.routes.php';

$application    = new Dez\Micro\Application();

new AppLoader($application);

new AppRoutes($application);

try {
    $application->response->setBodyFormat(\Dez\Http\Response::RESPONSE_JSON);
    $application->execute();
} catch (\Exception $e) {
    die($e->getMessage());
}