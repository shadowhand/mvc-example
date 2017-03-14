<?php

namespace Cove\Injection;

use Auryn\Injector;
use Http\Factory\Diactoros\ResponseFactory;
use Http\Factory\Diactoros\ServerRequestFactory;
use Http\Factory\Diactoros\StreamFactory;
use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Factory\ServerRequestFactoryInterface;
use Interop\Http\Factory\StreamFactoryInterface;

class HttpFactory
{
    public function apply(Injector $injector)
    {
        $injector->alias(ResponseFactoryInterface::class, ResponseFactory::class);
        $injector->alias(ServerRequestFactoryInterface::class, ServerRequestFactory::class);
        $injector->alias(StreamFactoryInterface::class, StreamFactory::class);

        $injector->share(ResponseFactoryInterface::class);
        $injector->share(ServerRequestFactoryInterface::class);
        $injector->share(StreamFactoryInterface::class);
    }
}
