<?php

// Use awesome dependency injection.
// https://github.com/rdlowrey/auryn
//
// Dependency injection config helper.
// https://github.com/shadowhand/cairon
use Cairon\InjectorConfig;

return InjectorConfig::make()
    ->configure([
        require 'injection/http.php',
        require 'injection/session.php',
        require 'injection/commandbus.php',
        require 'injection/oauth.php',
        require 'injection/templates.php',
    ])
    ->injector();
