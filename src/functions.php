<?php
declare(strict_types=1);

namespace Cove;

use FastRoute\Dispatcher;
use PhpFp\Either\Constructor\Left;
use PhpFp\Either\Constructor\Right;
use PhpFp\Either\Either;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Execute routing with FastRoute
 */
function route(ServerRequestInterface $request, Dispatcher $dispatcher): Either
{
    // https://github.com/nikic/FastRoute#dispatching-a-uri
    $route = $dispatcher->dispatch(
        $request->getMethod(),
        $request->getUri()->getPath()
    );

    if ($route[0] === Dispatcher::NOT_FOUND) {
        return new Left(404);
    }

    if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
        return new Left(405);
    }

    // Map the URI parameters into the request.
    foreach ($route[2] as $name => $value) {
        $request = $request->withAttribute($name, $value);
    }

    return new Right(new Dispatch($request, $route[1]));
}

/**
 * Send an HTTP response
 */
function send(ResponseInterface $response): void
{
    header(sprintf(
        'HTTP/%s %s %s',
        $response->getProtocolVersion(),
        $response->getStatusCode(),
        $response->getReasonPhrase()
    ));

    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header("$name: $value");
        }
    }

    $stream = $response->getBody();
    $stream->rewind();
    while (!$stream->eof()) {
        echo $stream->read(1024 * 8);
    }
}
