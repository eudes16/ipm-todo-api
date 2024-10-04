<?php

use App\Http\Infraestructure\Router;

$router = Router::getInstance();

$router->get('/', [App\Http\Controllers\RootController::class, 'index']);
$router->get('/todos', [App\Http\Controllers\TodosController::class, 'index']);
$router->post('/todos', [App\Http\Controllers\TodosController::class, 'create']);
// $router->get('/{id:[1-9]+}', [App\Http\Controllers\RootController::class, 'index']);
// $router->get('/signal', [App\Http\Controllers\RootController::class, 'signal']);