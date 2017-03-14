<?php

namespace Cove;

use Psr\Http\Message\ResponseInterface;

/**
 * @return void
 */
function send(ResponseInterface $response)
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
