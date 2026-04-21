<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'gateway' => 'fake_card',
            'transaction_reference' => 'PAY-'.now()->format('Ymd').'-'.Str::upper(Str::random(10)),
            'amount' => fake()->randomFloat(2, 10, 200),
            'paid_at' => now(),
        ];
    }
}
