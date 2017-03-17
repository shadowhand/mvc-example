<?php
declare(strict_types=1);

namespace Demo\Login;

use Equip\SessionInterface;
use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginCompleteController
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
        $code = $this->authCode($request);

        if (empty($code)) {
            return $this->view->redirectTo('/login');
        }

        $token = $this->accessToken($code);
        $this->session->set('github-token', json_encode($token));

        return $this->view->redirectTo('/');
    }

    private function authCode(ServerRequestInterface $request): ?string
    {
        $query = $request->getUri()->getQuery();
        parse_str($query, $params);

        return $params['code'] ?? null;
    }

    private function accessToken(string $code): AccessToken
    {
        $params = [
            'code' => $code,
            'state' => $this->session->get('oauth-state'),
        ];

        return $this->github->getAccessToken('authorization_code', $params);
    }

}
