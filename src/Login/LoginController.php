<?php
declare(strict_types=1);

namespace Demo\Login;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController
{
    /**
     * @var LoginView
     */
    private $view;

    public function __construct(
        LoginView $view
    ) {
        $this->view = $view;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->view->showLogin();
    }
}
