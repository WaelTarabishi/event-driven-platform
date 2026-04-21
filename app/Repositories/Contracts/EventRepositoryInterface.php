<?php

namespace App\Repositories\Contracts;

use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EventRepositoryInterface
{
    public function paginatePublished(int $perPage = 10): LengthAwarePaginator;

    public function paginateForAdmin(int $perPage = 10): LengthAwarePaginator;

    public function create(array $attributes): Event;

    public function update(Event $event, array $attributes): Event;

    public function delete(Event $event): void;

    public function findById(int $eventId): ?Event;

    public function findForUpdate(int $eventId): ?Event;

    public function slugExists(string $slug, ?int $ignoreEventId = null): bool;

    public function totalCount(): int;

    public function publishedCount(): int;

    public function soldOutCount(): int;
}
