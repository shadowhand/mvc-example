<?php

use Auryn\Injector;

// Sessions will be handled by Equip.
// https://github.com/equip/session
use Equip\{
    SessionInterface,
    NativeSession
};

return function (Injector $injector) {
    // Session is shared globally.
    $injector->share(SessionInterface::class);

    // Use native sessions.
    $injector->alias(SessionInterface::class, NativeSession::class);
};
