<?php

use App\Http\Controllers\Api\EventController;
use Illuminate\Support\Facades\Route;

Route::get('events', [EventController::class, 'index'])->name('api.events.index');
