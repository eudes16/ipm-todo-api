<?php

declare(strict_types=1);

namespace App\Shared\Exceptions;

use Exception;

class FieldNotFoundException extends Exception
{
    public function __construct($message = "Field not found", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
} 