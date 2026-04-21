<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Services\AdminDashboardService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(AdminDashboardService $dashboard, BookingRepositoryInterface $bookings): Response
    {
        $user = request()->user();

        if ($user->is_admin) {
            return Inertia::render('dashboard', [
                'isAdmin' => true,
                'metrics' => $dashboard->getMetrics()->toArray(),
                'userBookings' => [],
            ]);
        }

        $userBookings = $bookings->recentForUser($user->id)
            ->map(fn (Booking $booking) => [
                'booking_number' => $booking->booking_number,
                'status' => $booking->status->value,
                'amount' => number_format((float) $booking->unit_price, 2, '.', ''),
                'event_title' => $booking->event->title,
                'venue' => $booking->event->venue,
                'starts_at' => $booking->event->starts_at?->toIso8601String(),
                'created_at' => $booking->created_at?->toIso8601String(),
            ])
            ->all();

        return Inertia::render('dashboard', [
            'isAdmin' => false,
            'metrics' => null,
            'userBookings' => $userBookings,
        ]);
    }
}
