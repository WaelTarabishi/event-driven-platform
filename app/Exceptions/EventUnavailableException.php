<?php

namespace App\Exceptions;

use RuntimeException;

final class EventUnavailableException extends RuntimeException
{
    public function __construct(string $message = 'This event is not available for booking.')
    {
        parent::__construct($message);
    }
}
