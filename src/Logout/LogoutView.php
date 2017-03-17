<?php
declare(strict_types=1);

namespace Demo\Logout;

use Interop\Http\Factory\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class LogoutView
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        ResponseFactoryInterface $responseFactory
    ) {
        $this->responseFactory = $responseFactory;
    }

    public function redirectTo(string $target): ResponseInterface
    {
        return $this->responseFactory->createResponse(302)
            ->withHeader('Location', $target);
    }
}
