<?php

namespace Cove;

use Auryn\Injector;
use FastRoute\Dispatcher;
use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Factory\ServerRequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Application
{
    /**
     * @return static
     */
    public static function make(Injector $injector = null)
    {
        if (null === $injector) {
            $injector = new Injector();
        }

        $injector->share($injector);

        return new self($injector);
    }

    /**
     * @return static
     */
    public function configure(array $classes)
    {
        foreach ($classes as $class) {
            $this->injector->make($class)->apply($this->injector);
        }

        return $this;
    }

    /**
     * @return static
     */
    public function run(callable $fn)
    {
        $this->injector->execute($fn);

        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function dispatch(Dispatcher $dispatcher)
    {
        $request = $this->serverRequest();
        $route = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        if ($route[0] === Dispatcher::NOT_FOUND) {
            return $this->response(404);
        }

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            return $this->response(405);
        }

        foreach ($route[2] as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        return $this->handle($request, $this->injector->make($route[1]));
    }

    /**
     * @return void
     */
    private function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * @return ServerRequestInterface
     */
    private function serverRequest()
    {
        return $this->injector
            ->make(ServerRequestFactoryInterface::class)
            ->createServerRequest($_SERVER);
    }

    /**
     * @return ResponseInterface
     */
    private function response($status = 200)
    {
        return $this->injector
            ->make(ResponseFactoryInterface::class)
            ->createResponse($status);
    }

    /**
     * @return ResponseInterface
     */
    private function handle(ServerRequestInterface $request, callable $fn)
    {
        return $fn($request);
    }
}
