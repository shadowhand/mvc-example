<?php
declare(strict_types=1);

namespace Cove;

use Auryn\Injector;
use Psr\Http\Message\ResponseInterface;

/**
 * @return void
 */
function inject(Injector $injector, array $configs): void
{
    foreach ($configs as $config) {
        $injector->make($config)->apply($injector);
    }
}

/**
 * @return void
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
