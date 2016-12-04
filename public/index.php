<?php

use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

$app = require __DIR__ . '/../bootstrap/http.php';

$request = ServerRequestFactory::fromGlobals();
$response = $app->handle($request);

(new SapiEmitter())->emit($response);
