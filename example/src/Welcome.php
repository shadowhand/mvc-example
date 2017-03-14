<?php

namespace Cove\Example;

use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Factory\StreamFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class Welcome
{
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $name = $request->getAttribute('name', 'world');
        $body = $this->streamFactory->createStream("Hello, $name!");

        return $this->responseFactory->createResponse()->withBody($body);
    }
}
