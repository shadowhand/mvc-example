<?php
declare(strict_types=1);

namespace Cove\Example;

use Auryn\Injector;
use Closure;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

class Routing
{
    public function apply(Injector $injector): void
    {
        $injector->delegate(Dispatcher::class, $this->dispatcher());
    }

    private function routes(): Closure
    {
        // https://github.com/nikic/FastRoute#defining-routes
        return function (RouteCollector $r) {
            $r->get('/[{name}]', Welcome::class);
        };
    }

    private function dispatcher(): Closure
    {
        return function () {
            return simpleDispatcher($this->routes());
        };
    }
}
