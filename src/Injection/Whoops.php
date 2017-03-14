<?php

namespace Cove\Injection;

use Auryn\Injector;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

class Whoops
{
    public function apply(Injector $injector)
    {
        $injector->prepare(Run::class, function (Run $whoops) use ($injector) {
            $whoops->pushHandler($injector->make(PrettyPageHandler::class));
            $whoops->register();
        });
    }
}
