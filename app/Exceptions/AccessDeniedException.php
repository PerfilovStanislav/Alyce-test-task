<?php

namespace App\Exceptions;

use Throwable;

class AccessDeniedException extends \UnexpectedValueException
{
    public function __construct($message = 'Not enough rights for the operation', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
