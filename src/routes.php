<?php

use App\Http\Infraestructure\Router;

$router = Router::getInstance();

$router->get('/', [App\Http\Controllers\RootController::class, 'index']);
// $router->get('/{id:[1-9]+}', [App\Http\Controllers\RootController::class, 'index']);
// $router->get('/signal', [App\Http\Controllers\RootController::class, 'signal']);