<?php

namespace App\Exceptions;

use RuntimeException;

final class BookingCancellationException extends RuntimeException
{
    public function __construct(string $message = 'This booking cannot be cancelled.')
    {
        parent::__construct($message);
    }
}
