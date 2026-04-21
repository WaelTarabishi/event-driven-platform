<?php

namespace App\Repositories\Eloquent;

use App\Enums\BookingStatus;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentEventRepository implements EventRepositoryInterface
{
    public function paginatePublished(int $perPage = 10): LengthAwarePaginator
    {
        return Event::query()
            ->where('status', EventStatus::Published->value)
            ->orderBy('starts_at')
            ->paginate($perPage);
    }

    public function paginateForAdmin(int $perPage = 10): LengthAwarePaginator
    {
        return Event::query()
            ->with('creator:id,name')
            ->withCount([
                'bookings as confirmed_bookings_count' => fn ($query) => $query->where('status', BookingStatus::Confirmed->value),
            ])
            ->latest('starts_at')
            ->paginate($perPage);
    }

    public function create(array $attributes): Event
    {
        return Event::query()->create($attributes);
    }

    public function update(Event $event, array $attributes): Event
    {
        $event->fill($attributes);
        $event->save();

        return $event->refresh();
    }

    public function delete(Event $event): void
    {
        $event->delete();
    }

    public function findById(int $eventId): ?Event
    {
        return Event::query()->find($eventId);
    }

    public function findForUpdate(int $eventId): ?Event
    {
        return Event::query()
            ->lockForUpdate()
            ->find($eventId);
    }

    public function slugExists(string $slug, ?int $ignoreEventId = null): bool
    {
        return Event::query()
            ->withTrashed()
            ->where('slug', $slug)
            ->when($ignoreEventId !== null, fn ($query) => $query->whereKeyNot($ignoreEventId))
            ->exists();
    }

    public function totalCount(): int
    {
        return Event::query()->count();
    }

    public function publishedCount(): int
    {
        return Event::query()
            ->where('status', EventStatus::Published->value)
            ->count();
    }

    public function soldOutCount(): int
    {
        return Event::query()
            ->where('status', EventStatus::Published->value)
            ->where('available_seats', 0)
            ->count();
    }
}
