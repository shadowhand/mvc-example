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

// Sessions will be handled by Equip.
// https://github.com/equip/session
use Equip\SessionInterface;
use Equip\NativeSession;

// Templating will be handled by Plates.
// http://platesphp.com/
use League\Plates\Engine as Plates;

// Github API will be handled with OAuth2.
// https://github.com/thephpleague/oauth2-github
use League\OAuth2\Client\Provider\Github;

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

// Session is shared globally.
$injector->share(SessionInterface::class);

// Use native sessions.
$injector->alias(SessionInterface::class, NativeSession::class);

// Define the template directory.
$injector->define(Plates::class, [
    ':directory' => realpath(__DIR__ . '/../templates'),
    ':fileExtension' => 'phtml',
]);

// Github is shared globally.
$injector->share(Github::class);

// Define credentials for Github OAuth.
$injector->define(Github::class, [
    ':options' => [
        'clientId' => getenv('GITHUB_CLIENT_ID'),
        'clientSecret' => getenv('GITHUB_CLIENT_SECRET'),
    ],
]);

return $injector;
