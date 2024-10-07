<?php

use App\Shared\Context;
use App\Shared\DotEnv\DotEnv;
use App\Config\Application;
use App\Http\Infraestructure\Cors;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/routes.php';



// Load environment variables
$envs = (new DotEnv(__DIR__ . '/../.env'))->load();

new Cors();

//start the application
$app = new Application(
    new Context($envs),
    $router,
    $_ENV
);

$app->run();
