<?php

declare(strict_types=1);

namespace App\Config;

use App\Config\Exceptions\DataBaseConnectionException;
use Exception;
use PDO;

class Database
{

    private static $db;

    static $instance = null;

    /**
     * Private constructor to prevent direct instatiation.
     */
    private function __construct() {}

    /**
     * Private clone method to prevent cloning of the instance of the Singleton instance.
     */
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public static function getInstance(): Database
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function connect(array $config): \PDO
    {
        try {
            if (!isset(self::$db)) {
    
                $host = $config['host'];
                $port = $config['port'];
                $user = $config['user'];
                $password = $config['password'];
                $database = $config['database'];
    
                $connectionString = "mysql:host=$host;port=$port;dbname=$database";
    
                self::$db = new PDO($connectionString, $user, $password);
                self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }
    
            return self::$db;
        } catch (Exception $e) {
            throw new DataBaseConnectionException();
        }
    }

    public function disconnect(): void
    {
        self::$db = null;
    }

    public function query(string $sql): \PDOStatement
    {
        return $this->db->query($sql);
    }
}
