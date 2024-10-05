<?php

declare(strict_types=1);

namespace App\Shared\Exceptions;

use Exception;

class RecordAlreadExistsException extends Exception
{
    public function __construct($message = "Record alread exist", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
} 