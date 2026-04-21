<?php

namespace App\Repositories\Eloquent;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentBookingRepository implements BookingRepositoryInterface
{
    public function existsForUserEvent(int $userId, int $eventId): bool
    {
        return Booking::query()
            ->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();
    }

    public function createConfirmed(array $attributes): Booking
    {
        return Booking::query()->create($attributes);
    }

    public function countConfirmedByEvent(int $eventId): int
    {
        return (int) Booking::query()
            ->where('event_id', $eventId)
            ->where('status', BookingStatus::Confirmed->value)
            ->sum('seat_count');
    }

    public function hasConfirmedForEvent(int $eventId): bool
    {
        return Booking::query()
            ->where('event_id', $eventId)
            ->where('status', BookingStatus::Confirmed->value)
            ->exists();
    }

    public function totalConfirmedCount(): int
    {
        return (int) Booking::query()
            ->where('status', BookingStatus::Confirmed->value)
            ->count();
    }

    public function recentConfirmed(int $limit = 10): Collection
    {
        return Booking::query()
            ->with([
                'user:id,name,email',
                'event:id,title,venue,starts_at',
            ])
            ->where('status', BookingStatus::Confirmed->value)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function recentForUser(int $userId, int $limit = 5): Collection
    {
        return Booking::query()
            ->with('event:id,title,venue,starts_at')
            ->where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
