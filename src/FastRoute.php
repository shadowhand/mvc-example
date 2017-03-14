<?php
declare(strict_types=1);

namespace Cove;

use Cove\Resolver;
use FastRoute\Dispatcher;
use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Factory\ServerRequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FastRoute
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var Resolver
     */
    private $resolver;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        Resolver $resolver
    ) {
        $this->responseFactory = $responseFactory;
        $this->resolver = $resolver;
    }

    public function __invoke(ServerRequestInterface $request, Dispatcher $dispatcher): ResponseInterface
    {
        // https://github.com/nikic/FastRoute#dispatching-a-uri
        $route = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        if ($route[0] === Dispatcher::NOT_FOUND) {
            return $this->response(404);
        }

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            return $this->response(405);
        }

        foreach ($route[2] as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        // 'Acme\Class' -> new Acme\Class()
        $handler = $this->resolver->get($route[1]);

        // fn(ServerRequest req): Response
        return $handler($request);
    }

    private function response($status = 200): ResponseInterface
    {
        return $this->responseFactory->make($status);
    }
}
