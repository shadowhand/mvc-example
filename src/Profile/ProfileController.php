<?php
declare(strict_types=1);

namespace Demo\Profile;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ProfileController
{
    /**
     * @var ProfileView
     */
    private $view;

    public function __construct(
        ProfileView $view
    ) {
        $this->view = $view;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getAttribute('name', 'world');

        return $this->view->sayHello($name);
    }
}
