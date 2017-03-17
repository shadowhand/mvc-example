<?php
declare(strict_types=1);

namespace Cove;

use Psr\Http\Message\ServerRequestInterface;

final class Dispatch
{
    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var string
     */
    private $handler;

    public function __construct(ServerRequestInterface $request, string $handler)
    {
        $this->request = $request;
        $this->handler = $handler;
    }

    /**
     * Get the request to dispatch.
     */
    public function request(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * Get the request handler class name.
     */
    public function handler(): string
    {
        return $this->handler;
    }
}
