<?php

// Use awesome dependency injection.
// https://github.com/rdlowrey/auryn
use Auryn\Injector;

// PSR-17 HTTP Factories
// https://github.com/http-interop/http-factory
use Interop\Http\Factory\{
    ResponseFactoryInterface,
    ServerRequestFactoryInterface,
    StreamFactoryInterface
};

// Use Diactoros for PSR-7 implementation.
// https://github.com/http-interop/http-factory-diactoros
use Http\Factory\Diactoros\{
    ResponseFactory,
    ServerRequestFactory,
    StreamFactory
};

// Request and response will be created with PSR-17.
// http://www.php-fig.org/psr/psr-7/
use Psr\Http\Message\{
    ResponseInterface,
    ServerRequestInterface
};

// Sessions will be handled by Equip.
// https://github.com/equip/session
use Equip\{
    SessionInterface,
    NativeSession
};

// Github API will be handled with OAuth2.
// https://github.com/thephpleague/oauth2-github
use League\OAuth2\Client\Provider\Github;

// Templating will be handled by Plates.
// http://platesphp.com/
use League\Plates\Engine as Plates;

// Commands will be handled by Tactician.
// http://tactician.thephpleague.com/
use League\Tactician\{
    CommandBus,
    Exception\MissingHandlerException,
    Handler\CommandHandlerMiddleware,
    Handler\CommandNameExtractor\CommandNameExtractor,
    Handler\CommandNameExtractor\ClassNameExtractor,
    Handler\Locator\HandlerLocator,
    Handler\MethodNameInflector\MethodNameInflector,
    Handler\MethodNameInflector\HandleInflector
};

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

// Github is shared globally.
$injector->share(Github::class);

// Define credentials for Github OAuth.
$injector->define(Github::class, [
    ':options' => [
        'clientId' => getenv('GITHUB_CLIENT_ID'),
        'clientSecret' => getenv('GITHUB_CLIENT_SECRET'),
    ],
]);

// Define the template directory.
$injector->define(Plates::class, [
    ':directory' => realpath(__DIR__ . '/../templates'),
    ':fileExtension' => 'phtml',
]);

// Command bus is shared globally.
$injector->share(CommandBus::class);

// Commands are referenced by class name.
$injector->alias(CommandNameExtractor::class, ClassNameExtractor::class);

// Handlers have a handle() method.
$injector->alias(MethodNameInflector::class, HandleInflector::class);

// Use an anonymous class to defer creation of command handlers.
$injector->delegate(HandlerLocator::class, function () use ($injector) {
    return new class($injector) implements HandlerLocator
    {
        private $injector;

        public function __construct(Injector $injector)
        {
            $this->injector = $injector;
        }

        public function getHandlerForCommand($command)
        {
            // FooCommand -> FooHandler
            $handler = preg_replace('/Command$/', 'Handler', $command);
            if (!class_exists($handler)) {
                throw MissingHandlerException::forCommand($command);
            }
            return $this->injector->make($handler);
        }
    };
});

// Create command bus middleware by delegation.
$injector->delegate(CommandBus::class, function (
    CommandHandlerMiddleware $handlerMiddleware
) {
    return new CommandBus([
        $handlerMiddleware,
    ]);
});

return $injector;
