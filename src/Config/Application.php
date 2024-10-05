<?php

declare(strict_types=1);

namespace App\Config;

use App\Shared\Context;
use App\Http\Domain\RouterInterface;
use App\Http\Infraestructure\DataResponse;

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
        try {
            // Instantiate the database connection and add it to the context.
            $db = Database::getInstance(); 
            $con = $db->connect($this->context->database);
            $this->context->setConnection($con);

        } catch (\Exception $e) {
            return DataResponse::internalServerError();
        }

        $this->router->dispatch($this->context); 
    }
}
