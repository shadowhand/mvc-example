<?php

use Auryn\Injector;

// Github API will be handled with OAuth2.
// https://github.com/thephpleague/oauth2-github
use League\OAuth2\Client\Provider\Github;

return function (Injector $injector) {
    // Github is shared globally.
    $injector->share(Github::class);

    // Define credentials for Github OAuth.
    $injector->define(Github::class, [
        ':options' => [
            'clientId' => getenv('GITHUB_CLIENT_ID'),
            'clientSecret' => getenv('GITHUB_CLIENT_SECRET'),
        ],
    ]);
};
