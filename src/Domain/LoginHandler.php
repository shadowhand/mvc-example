<?php
declare(strict_types=1);

namespace Demo\Domain;

use League\OAuth2\Client\Provider\Github;

class LoginHandler
{
    /**
     * @var Github
     */
    private $github;

    public function __construct(Github $github)
    {
        $this->github = $github;
    }

    public function handle(LoginCommand $command): LoginState
    {
        // https://github.com/thephpleague/oauth2-github#authorization-code-flow
        $url = $this->github->getAuthorizationUrl([
            'redirect_uri' => $command->completeUrl(),
            'scope' => $command->scopes(),
        ]);

        $state = $this->github->getState();

        return new LoginState($url, $state);
    }
}
