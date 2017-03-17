<?php

// In your own application, this would probably be:
// require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../../vendor/autoload.php';

// Activate exception handler.
require __DIR__ . '/../config/errors.php';

// Activate dependency injection.
$injector = require __DIR__ . '/../config/injector.php';

// https://github.com/nikic/FastRoute#defining-routes
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->get('/[{name}]', Cove\Example\Welcome\WelcomeController::class);
});

// Parse the incoming request.
$request = $injector->make(Psr\Http\Message\ServerRequestInterface::class);

// Define how routing errors will be transformed to Response.
$errorResponse = function (int $status) use ($injector): Psr\Http\Message\ResponseInterface {
    $response = $injector->make(Psr\Http\Message\ResponseInterface::class);
    $response = $response->withStatus($status);
    return $response;
};

// Define how valid requests will be transformed to Response.
$successResponse = function (Cove\Dispatch $dispatch) use ($injector): Psr\Http\Message\ResponseInterface {
    $handler = $injector->make($dispatch->handler());
    $response = $handler($dispatch->request());
    return $response;
};

// Route the request and get the error or success.
$response = Cove\route($request, $dispatcher)->either($errorResponse, $successResponse);

// fin.
Cove\send($response);
