<?php
declare(strict_types=1);

namespace Demo;

use Equip\SessionInterface;
use Interop\Http\Factory\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Authorization
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    public function __construct(
        SessionInterface $session,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->session = $session;
        $this->responseFactory = $responseFactory;
    }

    public function isAuthorized(ServerRequestInterface $request): bool
    {
        if ($this->session->get('github-token')) {
            return true;
        }

        return false;
    }

    public function redirect(): ResponseInterface
    {
        return $this->responseFactory
            ->createResponse(302)
            ->withHeader('Location', '/login');
    }
}
