<?php
declare(strict_types=1);

namespace Demo\Profile;

use Interop\Http\Factory\ResponseFactoryInterface;
use Interop\Http\Factory\StreamFactoryInterface;
use League\Plates\Engine as Plates;
use Psr\Http\Message\ResponseInterface;

class ProfileView
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
        StreamFactoryInterface $streamFactory,
        Plates $templates
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->templates = $templates;
    }

    public function render(array $repositories, array $followers, array $stars): ResponseInterface
    {
        $content = $this->templates->render('profile', [
            'repositories' => $repositories,
            'followers' => $followers,
            'stars' => $stars,
        ]);

        $body = $this->streamFactory->createStream($content);
        return $this->responseFactory->createResponse()->withBody($body);
    }
}
