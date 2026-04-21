<?php

namespace App\Repositories\Contracts;

use App\Models\Payment;

interface PaymentRepositoryInterface
{
    public function create(array $attributes): Payment;

    public function totalRevenue(): string;
}
