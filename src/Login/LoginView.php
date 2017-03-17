<?php
declare(strict_types=1);

namespace Demo\Login;

use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Factory\StreamFactoryInterface;
use League\Plates\Engine as Plates;
use Psr\Http\Message\ResponseInterface;

class LoginView
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @var Plates
     */
    private $templates;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory,
        Plates $templates
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->templates = $templates;
    }

    public function showLogin(): ResponseInterface
    {
        $content = $this->templates->render('login');
        $body = $this->streamFactory->createStream($content);
        return $this->responseFactory->createResponse()->withBody($body);
    }

    public function redirectTo(string $target): ResponseInterface
    {
        return $this->responseFactory->createResponse(302)
            ->withHeader('Location', $target);
    }
}
