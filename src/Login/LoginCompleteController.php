<?php
declare(strict_types=1);

namespace Demo\Login;

use Demo\Domain\FetchTokenCommand;
use Equip\SessionInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\Tactician\CommandBus;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginCompleteController
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
        $code = $this->authCode($request);
        $state = $this->authState();

        if (empty($code) || empty($state)) {
            return $this->view->redirectTo('/login');
        }

        $command = FetchTokenCommand::forCode($code, $state);
        $token = $this->bus->handle($command);

        return $this->finish($token);
    }

    private function finish(AccessToken $token): ResponseInterface
    {
        $this->session->set('github-token', json_encode($token));

        return $this->view->redirectTo('/');
    }

    private function authCode(ServerRequestInterface $request): ?string
    {
        $query = $request->getUri()->getQuery();
        parse_str($query, $params);

        return $params['code'] ?? null;
    }

    private function authState(): ?string
    {
        return $this->session->get('oauth-state');
    }
}
