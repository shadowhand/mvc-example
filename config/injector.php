<?php

// Use awesome dependency injection.
// https://github.com/rdlowrey/auryn
// https://github.com/shadowhand/cairon
use Cairon\InjectorConfig;

return InjectorConfig::make()
    ->configure([
        require 'injection/commandbus.php',
        require 'injection/http.php',
        require 'injection/oauth.php',
        require 'injection/routing.php',
        require 'injection/session.php',
        require 'injection/templates.php',
    ])
    ->injector();
