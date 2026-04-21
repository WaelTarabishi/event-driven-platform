<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Api\BookingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::post('api/bookings', [BookingController::class, 'store'])->name('api.bookings.store');
    Route::delete('api/bookings/{bookingNumber}', [BookingController::class, 'destroy'])->name('api.bookings.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('events', AdminEventController::class);
    Route::patch('events/{event}/cancel', [AdminEventController::class, 'cancel'])->name('events.cancel');
    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
