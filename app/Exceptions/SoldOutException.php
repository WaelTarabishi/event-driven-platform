<?php

namespace App\Exceptions;

use RuntimeException;

final class SoldOutException extends RuntimeException
{
    public function __construct(string $message = 'This event is sold out.')
    {
        parent::__construct($message);
    }
}
