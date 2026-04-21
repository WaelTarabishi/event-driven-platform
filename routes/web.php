<?php

use App\Http\Controllers\Admin\DashboardController;
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
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('events', AdminEventController::class)->except('show');
    Route::patch('events/{event}/cancel', [AdminEventController::class, 'cancel'])->name('events.cancel');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
