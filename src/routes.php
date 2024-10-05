<?php

use App\Http\Infraestructure\Router;

$router = Router::getInstance();

// Root route
$router->get('/', [App\Http\Controllers\RootController::class, 'index']);

// Todos routes
$router->get('/todos', [App\Http\Controllers\TodosController::class, 'index']);
$router->post('/todos', [App\Http\Controllers\TodosController::class, 'create']);
$router->put('/todos/{id:[1-9]+}', [App\Http\Controllers\TodosController::class, 'update']);
$router->delete('/todos/{id:[1-9]+}', [App\Http\Controllers\TodosController::class, 'delete']);

// $router->get('/{id:[1-9]+}', [App\Http\Controllers\RootController::class, 'index']);
// $router->get('/signal', [App\Http\Controllers\RootController::class, 'signal']);