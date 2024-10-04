<?php

declare(strict_types=1);

namespace App\Config\Exceptions;

class DataBaseDisconnectionException extends \Exception
{
    public function __construct($message = 'Database disconnection error', $code = 500, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
