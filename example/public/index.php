<?php

require __DIR__ . '/../../vendor/autoload.php';
// In your own application, this would probably be:
// require __DIR__ . '/../vendor/autoload.php';

// Use awesome dependency injection.
// https://github.com/rdlowrey/auryn#the-guide
$injector = new Auryn\Injector();

// Set the handlers for Whoops, multiple options available:
// https://github.com/filp/whoops#available-handlers
$injector->define(Cove\Injection\Whoops::class, [
    ':handlers' => [
        // DO NOT USE IN PRODUCTION!
        Whoops\Handler\PrettyPageHandler::class,
    ],
]);

// Apply injector configuration.
// Each class will be created with Auryn and then executed with the injector.
Cove\inject($injector, [
    Cove\Injection\Whoops::class,
    Cove\Injection\ResolveAuryn::class,
    Cove\Injection\HttpDiactoros::class,
    Cove\Injection\ServerRequest::class,

    // Replace with your own routing!
    Cove\Example\Routing::class,
]);

// Use FastRoute to process the request.
// This will find the correct request handler and then execute it with request.
// All classes must use the signature:
//
// public function __invoke(ServerRequestInterface $request): Response
//
$runner = $injector->make(Cove\FastRoute::class);
$response = $injector->execute($runner);

// fin.
Cove\send($response);
