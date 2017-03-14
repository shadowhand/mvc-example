<?php

namespace Cove\Injection;

use Auryn\Injector;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

class Whoops
{
    /**
     * @var array
     */
    private $handlers;

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    public function apply(Injector $injector)
    {
        $injector->share(Run::class);

        $injector->prepare(Run::class, function (Run $whoops) use ($injector) {
            foreach ($this->handlers as $handler) {
                $whoops->pushHandler($injector->make($handler));
            }
            $whoops->register();
        });

        $injector->make(Run::class);
    }
}
