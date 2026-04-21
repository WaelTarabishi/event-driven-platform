<?php

namespace App\Exceptions;

use RuntimeException;

final class AlreadyBookedException extends RuntimeException
{
    public function __construct(string $message = 'You have already booked this event.')
    {
        parent::__construct($message);
    }
}
