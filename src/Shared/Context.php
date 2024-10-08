<?php 

declare(strict_types=1);

namespace App\Shared;

class Context {
    /**
     * Application configuration
     * @var array
     */
    public $app;
    /**
     * Database configuration
     * @var array
     */
    public $database;

    /**
     * Session data
     * @var array
     */
    public $session;

    protected $connection;

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
            'database' => $envs['DATABASE_NAME'],
            'log_level' => $envs['DATABASE_LOG_LEVEL']
        ];
    }

    public function setConnection($connection) {
        $this->connection = $connection;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function getDatabaseDebugLevel() {
        return $this->database['log_level'];
    }
}