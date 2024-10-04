<?php

namespace App\Config;

use App\Commons\Context;
use App\Http\Domain\RouterInterface;
use App\Http\Infrastructure\Router;

class Application
{
    protected $context;
    protected $router;
    protected $config;

    public function __construct(Context $context, RouterInterface $router, array $config)
    {
        $this->context = $context;
        $this->router = $router;
        $this->config = $config;
    }

    public function run()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->router->dispatch($this->context); 
    }
}
