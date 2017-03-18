<?php
declare(strict_types=1);

namespace Demo\Profile;

use Equip\SessionInterface;
use Demo\Authorization;
use Demo\Domain\FetchFollowersCommand;
use Demo\Domain\FetchRepositoriesCommand;
use League\Tactician\CommandBus;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ProfileController
{
    /**
     * @var Authorization
     */
    private $auth;

    /**
     * @var ProfileView
     */
    private $view;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var CommandBus
     */
    private $bus;

    public function __construct(
        Authorization $auth,
        ProfileView $view,
        SessionInterface $session,
        CommandBus $bus
    ) {
        $this->auth = $auth;
        $this->view = $view;
        $this->session = $session;
        $this->bus = $bus;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if (false == $this->auth->isAuthorized($request)) {
            return $this->auth->redirect();
        }

        $token = $this->token();

        $repositories = FetchRepositoriesCommand::forToken($token);
        $followers = FetchFollowersCommand::forToken($token);

        return $this->view->render(
            $this->bus->handle($repositories),
            $this->bus->handle($followers)
        );
    }

    private function token(): string
    {
        $token = $this->session->get('github-token');
        $token = json_decode($token, true);

        return $token['access_token'];
    }
}
