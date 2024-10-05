<?php

declare(strict_types=1);

namespace App\Shared\Exceptions;

class RecordNotFoundException extends \Exception
{
    public function __construct(string $message = 'Record not found', int $code = 404, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}