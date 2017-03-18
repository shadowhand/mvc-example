<?php
declare(strict_types=1);

namespace Demo\Login;

use Demo\Domain\LoginCommand;
use Demo\Domain\LoginState;
use Equip\SessionInterface;
use League\Tactician\CommandBus;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginBeginController
{
    /**
     * @var CommandBus
     */
    private $bus;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var LoginView
     */
    private $view;


    public function __construct(
        CommandBus $bus,
        SessionInterface $session,
        LoginView $view
    ) {
        $this->bus = $bus;
        $this->session = $session;
        $this->view = $view;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $url = $this->loginCompleteUrl($request);
        $login = LoginCommand::forUser($url);
        $state = $this->bus->handle($login);

        return $this->finish($state);
    }

    private function finish(LoginState $login): ResponseInterface
    {
        $this->session->set('oauth-state', $login->state());

        return $this->view->redirectTo($login->url());
    }

    private function loginCompleteUrl(ServerRequestInterface $request): string
    {
        $uri = $request->getUri();

        return sprintf(
            '%s://%s:%s%s/complete',
            $uri->getScheme(),
            $uri->getHost(),
            $uri->getPort(),
            $uri->getPath()
        );
    }
}
