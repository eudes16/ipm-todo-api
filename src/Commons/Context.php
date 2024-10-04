<?php 

namespace App\Commons;

class Context {
    public $app;
    public $database;

    public function __construct($envs) {
        
        $this->app = [
            'name' => $envs['APP_NAME'],
            'mode' => $envs['APP_MODE'],
            'version' => $envs['APP_VERSION'],
        ];

        $this->database = [
            'host' => $envs['DATABASE_HOST'],
            'port' => $envs['DATABASE_PORT'],
            'user' => $envs['DATABASE_USER'],
            'password' => $envs['DATABASE_PASSWORD'],
            'database' => $envs['DATABASE_NAME']
        ];
    }
}