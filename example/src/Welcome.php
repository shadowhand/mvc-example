<?php
declare(strict_types=1);

namespace Cove\Example;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Welcome
{
    /**
     * @var WelcomeResponder
     */
    private $responder;

    public function __construct(
        WelcomeResponder $responder
    ) {
        $this->responder = $responder;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getAttribute('name', 'world');

        return $this->responder->sayHello($name);
    }
}
