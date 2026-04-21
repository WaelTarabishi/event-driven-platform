<?php

namespace App\Services;

use App\DataTransferObjects\DashboardMetricsData;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;

class AdminDashboardService
{
    public function __construct(
        private readonly EventRepositoryInterface $events,
        private readonly BookingRepositoryInterface $bookings,
        private readonly PaymentRepositoryInterface $payments,
    ) {
    }

    public function getMetrics(): DashboardMetricsData
    {
        $recentBookings = $this->bookings
            ->recentConfirmed()
            ->map(fn (Booking $booking) => [
                'booking_number' => $booking->booking_number,
                'customer_name' => $booking->user->name,
                'event_title' => $booking->event->title,
                'venue' => $booking->event->venue,
                'amount' => number_format((float) $booking->unit_price, 2, '.', ''),
                'status' => $booking->status->value,
                'created_at' => $booking->created_at?->toIso8601String(),
            ])
            ->all();

        return new DashboardMetricsData(
            totalEvents: $this->events->totalCount(),
            publishedEvents: $this->events->publishedCount(),
            totalBookings: $this->bookings->totalConfirmedCount(),
            soldOutEvents: $this->events->soldOutCount(),
            revenue: $this->payments->totalRevenue(),
            recentBookings: $recentBookings,
        );
    }
}
