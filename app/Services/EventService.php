<?php

namespace App\Services;

use App\DataTransferObjects\UpsertEventData;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EventService
{
    public function __construct(
        private readonly EventRepositoryInterface $events,
        private readonly BookingRepositoryInterface $bookings,
    ) {
    }

    public function paginateForAdmin(int $perPage = 10): LengthAwarePaginator
    {
        return $this->events->paginateForAdmin($perPage);
    }

    public function create(UpsertEventData $data, int $createdBy): Event
    {
        return $this->events->create($this->buildAttributes(
            data: $data,
            soldSeats: 0,
            createdBy: $createdBy,
        ));
    }

    public function update(Event $event, UpsertEventData $data): Event
    {
        $soldSeats = $this->bookings->countConfirmedByEvent($event->id);

        if ($data->totalSeats < $soldSeats) {
            throw ValidationException::withMessages([
                'total_seats' => 'Total seats cannot be lower than the number of confirmed bookings.',
            ]);
        }

        return $this->events->update($event, $this->buildAttributes(
            data: $data,
            soldSeats: $soldSeats,
            createdBy: $event->created_by,
            existingEvent: $event,
        ));
    }

    public function cancel(Event $event): Event
    {
        return $this->events->update($event, [
            'status' => EventStatus::Cancelled->value,
        ]);
    }

    public function delete(Event $event): void
    {
        if ($this->bookings->hasConfirmedForEvent($event->id)) {
            throw ValidationException::withMessages([
                'event' => 'Events with confirmed bookings cannot be deleted. Cancel the event instead.',
            ]);
        }

        $this->events->delete($event);
    }

    private function buildAttributes(UpsertEventData $data, int $soldSeats, int $createdBy, ?Event $existingEvent = null): array
    {
        return [
            'title' => $data->title,
            'slug' => $this->generateUniqueSlug($data->title, $existingEvent),
            'description' => $data->description,
            'venue' => $data->venue,
            'starts_at' => $data->startsAt,
            'ends_at' => $data->endsAt,
            'total_seats' => $data->totalSeats,
            'available_seats' => $data->totalSeats - $soldSeats,
            'price' => $data->price,
            'status' => $data->status,
            'created_by' => $createdBy,
        ];
    }

    private function generateUniqueSlug(string $title, ?Event $existingEvent = null): string
    {
        $baseSlug = Str::slug($title);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'event';

        $slug = $baseSlug;
        $suffix = 1;

        while ($this->events->slugExists($slug, $existingEvent?->id)) {
            $suffix++;
            $slug = sprintf('%s-%d', $baseSlug, $suffix);
        }

        return $slug;
    }
}
