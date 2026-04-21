<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'booking_number' => 'BKG-'.now()->format('Ymd').'-'.Str::upper(Str::random(10)),
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'seat_count' => 1,
            'unit_price' => fake()->randomFloat(2, 10, 200),
            'status' => BookingStatus::Confirmed->value,
        ];
    }
}
