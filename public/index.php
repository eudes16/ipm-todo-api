<?php

use App\Commons\Context;
use App\Commons\DotEnv\DotEnv;
use App\Config\Application;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/routes.php';
// Load environment variables


$envs = (new DotEnv(__DIR__ . '/../.env'))->load();

//start the application
$app = new Application(
    new Context($envs),
    $router,
    $_ENV
);

$app->run();