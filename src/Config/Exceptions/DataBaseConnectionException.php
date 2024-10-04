<?php

declare(strict_types=1);

namespace App\Config\Exceptions;

class DataBaseConnectionException extends \Exception
{
    public function __construct($message = 'Database connection error', $code = 500, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
