<?php
declare(strict_types=1);

namespace Cove\Injection;

use Auryn\Injector;
use Http\Factory\Diactoros\ResponseFactory;
use Http\Factory\Diactoros\ServerRequestFactory;
use Http\Factory\Diactoros\StreamFactory;
use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Factory\ServerRequestFactoryInterface;
use Interop\Http\Factory\StreamFactoryInterface;

class HttpDiactoros
{
    public function apply(Injector $injector): void
    {
        // Use Diactoros for HTTP Messages
        $injector->alias(ResponseFactoryInterface::class, ResponseFactory::class);
        $injector->alias(ServerRequestFactoryInterface::class, ServerRequestFactory::class);
        $injector->alias(StreamFactoryInterface::class, StreamFactory::class);

        // Factories are global
        $injector->share(ResponseFactoryInterface::class);
        $injector->share(ServerRequestFactoryInterface::class);
        $injector->share(StreamFactoryInterface::class);
    }
}
