<?php

use App\Enums\BookingStatus;
use App\Enums\EventStatus;
use App\Models\Booking;
use App\Models\Event;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\postJson;

it('creates a booking and payment for an authenticated user', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'status' => EventStatus::Published->value,
        'total_seats' => 10,
        'available_seats' => 10,
    ]);

    actingAs($user)
        ->postJson(route('api.bookings.store'), [
            'event_id' => $event->id,
            'payment_method' => 'fake_card',
            'payment_token' => 'tok_demo_success',
        ])
        ->assertCreated()
        ->assertJsonPath('message', 'Booking confirmed')
        ->assertJsonPath('data.event_id', $event->id)
        ->assertJsonPath('data.status', BookingStatus::Confirmed->value)
        ->assertJsonPath('data.available_seats', 9);

    assertDatabaseHas('bookings', [
        'user_id' => $user->id,
        'event_id' => $event->id,
        'status' => BookingStatus::Confirmed->value,
    ]);

    assertDatabaseHas('payments', [
        'booking_id' => Booking::query()->where('user_id', $user->id)->where('event_id', $event->id)->value('id'),
        'gateway' => 'fake_card',
    ]);

    assertDatabaseHas('events', [
        'id' => $event->id,
        'available_seats' => 9,
    ]);
});

it('rejects duplicate bookings for the same user and event', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'status' => EventStatus::Published->value,
        'available_seats' => 5,
    ]);

    Booking::factory()->create([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'status' => BookingStatus::Confirmed->value,
    ]);

    actingAs($user)
        ->postJson(route('api.bookings.store'), [
            'event_id' => $event->id,
            'payment_method' => 'fake_card',
            'payment_token' => 'tok_demo_success',
        ])
        ->assertConflict()
        ->assertJsonPath('code', 'already_booked');
});

it('rejects bookings when an event is sold out', function () {
    $user = User::factory()->create();
    $event = Event::factory()->soldOut()->create([
        'status' => EventStatus::Published->value,
        'total_seats' => 10,
        'available_seats' => 0,
    ]);

    actingAs($user)
        ->postJson(route('api.bookings.store'), [
            'event_id' => $event->id,
            'payment_method' => 'fake_card',
            'payment_token' => 'tok_demo_success',
        ])
        ->assertConflict()
        ->assertJsonPath('code', 'sold_out');
});

it('returns validation failure when the fake payment is declined', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'status' => EventStatus::Published->value,
        'available_seats' => 6,
    ]);

    actingAs($user)
        ->postJson(route('api.bookings.store'), [
            'event_id' => $event->id,
            'payment_method' => 'fake_card',
            'payment_token' => 'tok_demo_fail_card',
        ])
        ->assertUnprocessable()
        ->assertJsonPath('code', 'payment_failed');

    assertDatabaseMissing('bookings', [
        'user_id' => $user->id,
        'event_id' => $event->id,
    ]);

    assertDatabaseHas('events', [
        'id' => $event->id,
        'available_seats' => 6,
    ]);
});
