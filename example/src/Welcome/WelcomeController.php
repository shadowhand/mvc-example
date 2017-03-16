<?php
declare(strict_types=1);

namespace Cove\Example\Welcome;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class WelcomeController
{
    /**
     * @var WelcomeView
     */
    private $responder;

    public function __construct(
        WelcomeView $responder
    ) {
        $this->responder = $responder;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getAttribute('name', 'world');

        return $this->responder->sayHello($name);
    }
}
