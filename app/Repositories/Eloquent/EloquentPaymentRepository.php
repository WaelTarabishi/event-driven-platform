<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class EloquentPaymentRepository implements PaymentRepositoryInterface
{
    public function create(array $attributes): Payment
    {
        return Payment::query()->create($attributes);
    }

    public function totalRevenue(): string
    {
        return number_format((float) Payment::query()->sum('amount'), 2, '.', '');
    }
}
