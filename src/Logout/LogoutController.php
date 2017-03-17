<?php
declare(strict_types=1);

namespace Demo\Logout;

use Equip\SessionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class LogoutController
{
    /**
     * @var LogoutView
     */
    private $view;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        LogoutView $view,
        SessionInterface $session
    ) {
        $this->view = $view;
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $this->session->clear();

        return $this->view->redirectTo('/');
    }
}
