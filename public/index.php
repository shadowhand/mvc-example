<?php

if (php_sapi_name() == 'cli-server') {
    if (preg_match('/\.(?:png|jpg|gif|css|js)$/', $_SERVER['REQUEST_URI'])) {
        // This request cannot be handled by PHP, stream the output.
        return false;
    }
}

// Start a session
session_start();

// In your own application, this would probably be:
require __DIR__ . '/../vendor/autoload.php';

// Activate exception handler.
require __DIR__ . '/../config/errors.php';

// Load additional ENV secrets.
require __DIR__ . '/../config/env.php';

// Activate dependency injection.
$injector = require __DIR__ . '/../config/injector.php';

// Activate routing.
$dispatcher = require __DIR__ . '/../config/routing.php';

// Define how routing errors will be transformed to Response.
$errorHandler = function (int $status) use ($injector): Psr\Http\Message\ResponseInterface {
    $response = $injector->make(Psr\Http\Message\ResponseInterface::class);
    $response = $response->withStatus($status);
    return $response;
};

// Define how valid requests will be transformed to Response.
$successHandler = function (EitherWay\Route $route) use ($injector): Psr\Http\Message\ResponseInterface {
    $handler = $injector->make($route->handler());
    $response = $handler($route->request());
    return $response;
};

// Parse the incoming request.
$request = $injector->make(Psr\Http\Message\ServerRequestInterface::class);

// Route the request and get the error or success.
$response = EitherWay\dispatch($request, $dispatcher)->either($errorHandler, $successHandler);

// fin.
Http\Response\send($response);
