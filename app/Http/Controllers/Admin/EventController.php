<?php

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\UpsertEventData;
use App\Enums\EventStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use App\Models\Event;
use App\Services\AuditLogService;
use App\Services\EventService;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function __construct(
        private readonly EventService $events,
        private readonly AuditLogService $auditLogs,
    ) {
    }

    public function index(): Response
    {
        $events = $this->events->paginateForAdmin();

        return Inertia::render('admin/events/index', [
            'events' => [
                'data' => collect($events->items())->map(fn (Event $event) => $this->serializeEvent($event))->all(),
                'meta' => [
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage(),
                    'per_page' => $events->perPage(),
                    'total' => $events->total(),
                    'next_page_url' => $events->nextPageUrl(),
                    'prev_page_url' => $events->previousPageUrl(),
                ],
            ],
            'statuses' => EventStatus::values(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/events/create', [
            'statuses' => EventStatus::values(),
        ]);
    }

    public function store(StoreEventRequest $request)
    {
        $event = $this->events->create(
            data: UpsertEventData::fromArray($request->validated()),
            createdBy: $request->user()->id,
        );

        $this->auditLogs->record(
            userId: $request->user()?->id,
            action: 'admin.event.created',
            entityType: 'event',
            entityId: $event->id,
            metadata: [
                'title' => $event->title,
                'status' => $event->status->value,
                'starts_at' => $event->starts_at?->toIso8601String(),
                'available_seats' => $event->available_seats,
            ],
            requestId: (string) $request->attributes->get('request_id'),
        );

        return to_route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event): Response
    {
        return Inertia::render('admin/events/edit', [
            'event' => $this->serializeEvent($event),
            'statuses' => EventStatus::values(),
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $before = [
            'title' => $event->title,
            'status' => $event->status->value,
            'starts_at' => $event->starts_at?->toIso8601String(),
            'available_seats' => $event->available_seats,
            'total_seats' => $event->total_seats,
        ];

        $updated = $this->events->update($event, UpsertEventData::fromArray($request->validated()));

        $this->auditLogs->record(
            userId: $request->user()?->id,
            action: 'admin.event.updated',
            entityType: 'event',
            entityId: $updated->id,
            metadata: [
                'before' => $before,
                'after' => [
                    'title' => $updated->title,
                    'status' => $updated->status->value,
                    'starts_at' => $updated->starts_at?->toIso8601String(),
                    'available_seats' => $updated->available_seats,
                    'total_seats' => $updated->total_seats,
                ],
            ],
            requestId: (string) $request->attributes->get('request_id'),
        );

        return to_route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $snapshot = [
            'title' => $event->title,
            'status' => $event->status->value,
            'starts_at' => $event->starts_at?->toIso8601String(),
            'available_seats' => $event->available_seats,
            'total_seats' => $event->total_seats,
        ];

        $this->events->delete($event);

        $this->auditLogs->record(
            userId: request()->user()?->id,
            action: 'admin.event.deleted',
            entityType: 'event',
            entityId: $event->id,
            metadata: $snapshot,
            requestId: (string) request()->attributes->get('request_id'),
        );

        return to_route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    public function cancel(Event $event)
    {
        $cancelled = $this->events->cancel($event);

        $this->auditLogs->record(
            userId: request()->user()?->id,
            action: 'admin.event.cancelled',
            entityType: 'event',
            entityId: $cancelled->id,
            metadata: [
                'title' => $cancelled->title,
                'status' => $cancelled->status->value,
                'starts_at' => $cancelled->starts_at?->toIso8601String(),
            ],
            requestId: (string) request()->attributes->get('request_id'),
        );

        return to_route('admin.events.index')->with('success', 'Event cancelled successfully.');
    }

    private function serializeEvent(Event $event): array
    {
        return [
            'id' => $event->id,
            'title' => $event->title,
            'slug' => $event->slug,
            'description' => $event->description,
            'venue' => $event->venue,
            'starts_at' => $event->starts_at?->format('Y-m-d\TH:i'),
            'ends_at' => $event->ends_at?->format('Y-m-d\TH:i'),
            'total_seats' => $event->total_seats,
            'available_seats' => $event->available_seats,
            'price' => $event->price,
            'status' => $event->status->value,
            'created_by' => $event->created_by,
            'creator_name' => $event->creator?->name,
            'confirmed_bookings_count' => $event->confirmed_bookings_count ?? 0,
        ];
    }
}
