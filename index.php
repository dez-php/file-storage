<?php

use Dez\Config\Config;
use Dez\Loader\Loader;
use FileStorage\StorageApplication;

include_once __DIR__ . "/vendor/autoload.php";

$loader = new Loader();
$loader->registerNamespaces([
    'FileStorage' => __DIR__ . '/app/'
]);

$application = new StorageApplication(Config::factory(
    __DIR__ . '/app/config/storage-config.php'
));

$application->configure()->initialize()->injection()->run();