<?php

use Dez\Config\Config;
use FileStorage\StorageApplication;

include_once __DIR__ . "/vendor/autoload.php";
include_once __DIR__ . "/app/StorageApplication.php";

$config = Config::factory(__DIR__ . '/app/config/storage-config.php');

$application = new StorageApplication($config);

$application->configure()->initialize()->injection()->run();