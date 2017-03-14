<?php
declare(strict_types=1);

namespace Cove\Example;

use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Factory\StreamFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class WelcomeResponder
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }

    public function sayHello(string $name): ResponseInterface
    {
        $body = $this->streamFactory->createStream("Hello, $name!");
        return $this->responseFactory->createResponse()->withBody($body);
    }
}
