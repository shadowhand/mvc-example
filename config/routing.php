<?php

namespace Demo;

use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

// https://github.com/nikic/FastRoute#defining-routes
return simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', Profile\ProfileController::class);

    $r->get('/login', Login\LoginController::class);
    $r->post('/login', Login\LoginCompleteController::class);

    $r->post('/logout', Logout\LogoutController::class);
});
