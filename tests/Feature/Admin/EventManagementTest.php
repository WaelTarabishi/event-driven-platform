<?php

use App\Enums\BookingStatus;
use App\Enums\EventStatus;
use App\Models\Booking;
use App\Models\Event;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\delete;
use function Pest\Laravel\from;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('blocks non admins from the admin events area', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('admin.events.index'))
        ->assertForbidden();
});

it('allows admins to create events', function () {
    $admin = User::factory()->admin()->create();

    $payload = [
        'title' => 'Annual Tech Summit',
        'description' => 'A full day conference for engineering teams.',
        'venue' => 'Innovation Center',
        'starts_at' => now()->addWeek()->format('Y-m-d\TH:i'),
        'ends_at' => now()->addWeek()->addHours(4)->format('Y-m-d\TH:i'),
        'total_seats' => 25,
        'price' => '49.99',
        'status' => EventStatus::Published->value,
    ];

    actingAs($admin)
        ->post(route('admin.events.store'), $payload)
        ->assertRedirect(route('admin.events.index'));

    assertDatabaseHas('events', [
        'title' => 'Annual Tech Summit',
        'venue' => 'Innovation Center',
        'total_seats' => 25,
        'available_seats' => 25,
        'status' => EventStatus::Published->value,
        'created_by' => $admin->id,
    ]);
});

it('recalculates available seats when an admin updates event capacity', function () {
    $admin = User::factory()->admin()->create();
    $event = Event::factory()->create([
        'created_by' => $admin->id,
        'total_seats' => 10,
        'available_seats' => 8,
        'status' => EventStatus::Published->value,
    ]);

    Booking::factory()->create([
        'event_id' => $event->id,
        'user_id' => User::factory()->create()->id,
        'status' => BookingStatus::Confirmed->value,
    ]);

    Booking::factory()->create([
        'event_id' => $event->id,
        'user_id' => User::factory()->create()->id,
        'status' => BookingStatus::Confirmed->value,
    ]);

    $payload = [
        'title' => 'Annual Tech Summit Updated',
        'description' => 'Updated agenda.',
        'venue' => 'Innovation Center',
        'starts_at' => now()->addWeek()->format('Y-m-d\TH:i'),
        'ends_at' => now()->addWeek()->addHours(4)->format('Y-m-d\TH:i'),
        'total_seats' => 12,
        'price' => '59.99',
        'status' => EventStatus::Published->value,
    ];

    actingAs($admin)
        ->put(route('admin.events.update', $event), $payload)
        ->assertRedirect(route('admin.events.index'));

    assertDatabaseHas('events', [
        'id' => $event->id,
        'title' => 'Annual Tech Summit Updated',
        'total_seats' => 12,
        'available_seats' => 10,
    ]);
});

it('prevents deleting events that already have confirmed bookings', function () {
    $admin = User::factory()->admin()->create();
    $event = Event::factory()->create([
        'created_by' => $admin->id,
        'status' => EventStatus::Published->value,
    ]);

    Booking::factory()->create([
        'event_id' => $event->id,
        'user_id' => User::factory()->create()->id,
        'status' => BookingStatus::Confirmed->value,
    ]);

    actingAs($admin);

    from(route('admin.events.index'))
        ->delete(route('admin.events.destroy', $event))
        ->assertSessionHasErrors('event');

    expect($event->fresh()?->deleted_at)->toBeNull();
});
