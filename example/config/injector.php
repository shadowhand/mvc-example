<?php

// Use awesome dependency injection.
// https://github.com/rdlowrey/auryn
use Auryn\Injector;

// PSR-17 HTTP Factories
// https://github.com/http-interop/http-factory
use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Factory\ServerRequestFactoryInterface;
use Interop\Http\Factory\StreamFactoryInterface;

// Use Diactoros for PSR-7 implementation.
// https://github.com/http-interop/http-factory-diactoros
use Http\Factory\Diactoros\ResponseFactory;
use Http\Factory\Diactoros\ServerRequestFactory;
use Http\Factory\Diactoros\StreamFactory;

// Request and response will be created with PSR-17.
// http://www.php-fig.org/psr/psr-7/
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

// Create injector
$injector = new Injector();

// Make PSR-17 factories shared globally.
$injector->share(ResponseFactoryInterface::class);
$injector->share(ServerRequestFactoryInterface::class);
$injector->share(StreamFactoryInterface::class);

// Provide implementation for PSR-17.
$injector->alias(ResponseFactoryInterface::class, ResponseFactory::class);
$injector->alias(ServerRequestFactoryInterface::class, ServerRequestFactory::class);
$injector->alias(StreamFactoryInterface::class, StreamFactory::class);

// ServerRequest can be shared globally.
$injector->share(ServerRequestInterface::class);

// Use HTTP factory to create the ServerRequest.
$injector->delegate(ServerRequestInterface::class, function (ServerRequestFactoryInterface $factory) {
    return $factory->createServerRequest($_SERVER);
});

// Use HTTP factory to create Responses.
$injector->delegate(ResponseInterface::class, function (ResponseFactoryInterface $factory) {
    return $factory->createResponse();
});

return $injector;
