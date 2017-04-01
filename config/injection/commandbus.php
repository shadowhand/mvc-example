<?php

use Auryn\Injector;

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

return function (Injector $injector) {
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
};
