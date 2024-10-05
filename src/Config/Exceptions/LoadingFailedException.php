<?php 

declare(strict_types=1);

namespace App\Config\Exceptions;

class LoadingFailedException extends \Exception {
    public function __construct($message = 'Loading failed', $code = 500, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
