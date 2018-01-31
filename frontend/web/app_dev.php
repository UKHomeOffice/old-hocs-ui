<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$loader = require_once __DIR__.'/../var/bootstrap.php.cache';

require_once __DIR__.'/../app/AppKernel.php';

$appEnv = "prod";

if (getenv("APP_ENV") !== false) {
    $appEnv = getenv("APP_ENV");
}

$debugMode = false;

if ($appEnv == "dc" || $appEnv == "dev" || $appEnv == "qa") {
    ini_set('session.cookie_secure', false);
}

if ($appEnv == "dc") {
    $debugMode = false;
    //Debug::enable();
}

$kernel = new AppKernel($appEnv, $debugMode);
$kernel->loadClassCache();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
