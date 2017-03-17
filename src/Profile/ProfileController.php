<?php
declare(strict_types=1);

namespace Demo\Profile;

use Equip\SessionInterface;
use Demo\Authorization;
use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Tool\QueryBuilderTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ProfileController
{
    use QueryBuilderTrait;

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
     * @var Github
     */
    private $github;

    public function __construct(
        Authorization $auth,
        ProfileView $view,
        SessionInterface $session,
        Github $github
    ) {
        $this->auth = $auth;
        $this->view = $view;
        $this->session = $session;
        $this->github = $github;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if (false == $this->auth->isAuthorized($request)) {
            return $this->auth->redirect();
        }

        $token = $this->token();
        $repositories = $this->repositories($token);
        $followers = $this->followers($token);

        return $this->view->render($repositories, $followers);
    }

    private function token(): string
    {
        $token = $this->session->get('github-token');
        $token = json_decode($token, true);

        return $token['access_token'];
    }

    private function followers(string $token): array
    {
        $url = $this->github->apiDomain . '/user/followers';

        $request = $this->github->getAuthenticatedRequest('GET', $url, $token);
        $response = $this->github->getParsedResponse($request);

        return $response;
    }

    private function repositories(string $token): array
    {
        $params = [
            'visibility' => 'public',
            'affiliation' => 'owner',
        ];

        $url = $this->github->apiDomain . '/user/repos?' . $this->buildQueryString($params);

        $request = $this->github->getAuthenticatedRequest('GET', $url, $token);
        $response = $this->github->getParsedResponse($request);

        return $this->filterRepositories($response);
    }

    private function filterRepositories(array $repos): array
    {
        return array_map(static function ($repo) {
            return array_intersect_key($repo, array_flip([
                'id',
                'full_name',
                'html_url',
            ]));
        }, $repos);
    }
}
