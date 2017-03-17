<?php

namespace Demo;

use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

// https://github.com/nikic/FastRoute#defining-routes
return simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', Profile\ProfileController::class);

    $r->addGroup('/login', function (RouteCollector $r) {
        $r->get('', Login\LoginController::class);
        $r->post('', Login\LoginBeginController::class);
        $r->get('/complete', Login\LoginCompleteController::class);
    });

    $r->post('/logout', Logout\LogoutController::class);
});
