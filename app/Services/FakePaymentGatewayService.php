<?php

namespace App\Services;

use App\Exceptions\PaymentFailedException;
use Illuminate\Support\Str;

class FakePaymentGatewayService
{
    public function charge(string $paymentMethod, string $paymentToken): array
    {
        if ($paymentMethod !== 'fake_card') {
            throw new PaymentFailedException('Unsupported fake payment method.');
        }

        if (Str::startsWith($paymentToken, 'tok_demo_fail')) {
            throw new PaymentFailedException('Fake payment was declined.');
        }

        if ($paymentToken !== 'tok_demo_success') {
            throw new PaymentFailedException('Invalid fake payment token.');
        }

        return [
            'gateway' => $paymentMethod,
            'paid_at' => now(),
        ];
    }
}
