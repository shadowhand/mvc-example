<?php

require __DIR__ . '/../../vendor/autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->get('/[{name}]', Cove\Example\Welcome::class);
});

$response = Cove\Application::make()
    ->configure([
        Cove\Injection\HttpFactory::class,
        Cove\Injection\Whoops::class,
    ])
    ->run(function (Auryn\Injector $injector) {
        $injector->make(Whoops\Run::class);
    })
    ->dispatch($dispatcher);

Cove\send($response);
