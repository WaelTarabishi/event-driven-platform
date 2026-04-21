<?php

use App\Enums\EventStatus;
use App\Models\Event;

use function Pest\Laravel\getJson;

it('lists only published events from the public api', function () {
    $published = Event::factory()->create([
        'title' => 'Published Event',
        'status' => EventStatus::Published->value,
    ]);

    Event::factory()->draft()->create([
        'title' => 'Draft Event',
    ]);

    Event::factory()->create([
        'title' => 'Cancelled Event',
        'status' => EventStatus::Cancelled->value,
    ]);

    getJson(route('api.events.index'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'id' => $published->id,
            'title' => 'Published Event',
        ])
        ->assertJsonMissing([
            'title' => 'Draft Event',
        ])
        ->assertJsonMissing([
            'title' => 'Cancelled Event',
        ]);
});
