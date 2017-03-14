<?php
declare(strict_types=1);

namespace Cove\Injection;

use Auryn\Injector;
use Cove\Resolver;

class ResolveAuryn
{
    public function apply(Injector $injector): void
    {
        $injector->share(Resolver::class);
        $injector->delegate(Resolver::class, $this->resolver($injector));
    }

    private function resolver(Injector $injector)
    {
        return function () use ($injector) {
            return new class($injector) implements Resolver
            {
                private $injector;

                public function __construct(Injector $injector)
                {
                    $this->injector = $injector;
                }

                public function get($class)
                {
                    return $this->injector->make($class);
                }
            };
        };
    }
}
