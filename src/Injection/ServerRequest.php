<?php
declare(strict_types=1);

namespace Cove\Injection;

use Auryn\Injector;
use Closure;
use Interop\Http\Factory\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequest
{
    public function apply(Injector $injector): void
    {
        $injector->share(ServerRequestInterface::class);
        $injector->delegate(ServerRequestInterface::class, $this->requestFromFactory());
    }

    private function requestFromFactory(): Closure
    {
        return function (ServerRequestFactoryInterface $factory): ServerRequestInterface {
            return $factory->createServerRequest($_SERVER);
        };
    }
}
