<?php

namespace App\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;

final readonly class DashboardMetricsData implements Arrayable
{
    public function __construct(
        public int $totalEvents,
        public int $publishedEvents,
        public int $totalBookings,
        public int $soldOutEvents,
        public string $revenue,
        public array $recentBookings,
    ) {
    }

    public function toArray(): array
    {
        return [
            'total_events' => $this->totalEvents,
            'published_events' => $this->publishedEvents,
            'total_bookings' => $this->totalBookings,
            'sold_out_events' => $this->soldOutEvents,
            'revenue' => $this->revenue,
            'recent_bookings' => $this->recentBookings,
        ];
    }
}
