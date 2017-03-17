<?php
declare(strict_types=1);

namespace Demo\Login;

use Equip\SessionInterface;
use League\OAuth2\Client\Provider\Github;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginBeginController
{
    /**
     * @var LoginView
     */
    private $view;

    /**
     * @var Github
     */
    private $github;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        LoginView $view,
        Github $github,
        SessionInterface $session
    ) {
        $this->view = $view;
        $this->github = $github;
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        // https://github.com/thephpleague/oauth2-github#authorization-code-flow
        $target = $this->github->getAuthorizationUrl([
            'redirect_uri' => $this->loginCompleteUrl($request),
            'scope' => $this->scopes(),
        ]);

        $this->session->set('oauth-state', $this->github->getState());

        return $this->view->redirectTo($target);
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

    private function scopes(): array
    {
        return ['user', 'repo'];
    }
}
