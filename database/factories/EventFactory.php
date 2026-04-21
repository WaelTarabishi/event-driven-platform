<?php

namespace Database\Factories;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('+1 day', '+2 months');
        $totalSeats = fake()->numberBetween(5, 50);

        return [
            'title' => fake()->sentence(3),
            'slug' => Str::slug(fake()->unique()->sentence(4)),
            'description' => fake()->paragraph(),
            'venue' => fake()->company().' Hall',
            'starts_at' => $startsAt,
            'ends_at' => (clone $startsAt)->modify('+2 hours'),
            'total_seats' => $totalSeats,
            'available_seats' => $totalSeats,
            'price' => fake()->randomFloat(2, 10, 200),
            'status' => EventStatus::Published->value,
            'created_by' => User::factory(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => EventStatus::Draft->value]);
    }

    public function soldOut(): static
    {
        return $this->state(fn () => ['available_seats' => 0]);
    }
}
