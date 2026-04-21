<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use Illuminate\Support\Collection;

interface BookingRepositoryInterface
{
    public function existsForUserEvent(int $userId, int $eventId): bool;

    public function createConfirmed(array $attributes): Booking;

    public function countConfirmedByEvent(int $eventId): int;

    public function hasConfirmedForEvent(int $eventId): bool;

    public function totalConfirmedCount(): int;

    public function recentConfirmed(int $limit = 10): Collection;

    public function recentForUser(int $userId, int $limit = 5): Collection;
}
