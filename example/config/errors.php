<?php

// Use excellent exception handling.
// https://github.com/filp/whoops
$whoops = new Whoops\Run();

// Set the handlers for Whoops, multiple options available:
// https://github.com/filp/whoops#available-handlers
// NOTE: Do *NOT* use PrettyPageHandler in production!
$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());

// Activate error/exception handling.
$whoops->register();
