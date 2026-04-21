<?php

namespace App\Exceptions;

use RuntimeException;

final class PaymentFailedException extends RuntimeException
{
    public function __construct(string $message = 'Fake payment failed.')
    {
        parent::__construct($message);
    }
}
