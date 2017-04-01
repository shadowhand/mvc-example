<?php

use Auryn\Injector;

// Templating will be handled by Plates.
// http://platesphp.com/
use League\Plates\Engine;

return function (Injector $injector) {
    // Define the template directory.
    $injector->define(Engine::class, [
        ':directory' => realpath(__DIR__ . '/../../templates'),
        ':fileExtension' => 'phtml',
    ]);
};
