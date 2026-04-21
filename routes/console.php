<?php

use App\DataTransferObjects\CreateBookingData;
use App\Exceptions\AlreadyBookedException;
use App\Exceptions\EventUnavailableException;
use App\Exceptions\PaymentFailedException;
use App\Exceptions\SoldOutException;
use App\Models\Event;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('booking:attempt {eventId} {userId} {paymentToken=tok_demo_success}', function () {
    $eventId = (int) $this->argument('eventId');
    $userId = (int) $this->argument('userId');
    $paymentToken = (string) $this->argument('paymentToken');

    try {
        $result = app(BookingService::class)->book(new CreateBookingData(
            userId: $userId,
            eventId: $eventId,
            paymentMethod: 'fake_card',
            paymentToken: $paymentToken,
        ));

        $this->line(json_encode([
            'status' => 'confirmed',
            'data' => $result->toArray(),
        ]));

        return 0;
    } catch (SoldOutException $exception) {
        $status = 'sold_out';
        $message = $exception->getMessage();
    } catch (AlreadyBookedException $exception) {
        $status = 'already_booked';
        $message = $exception->getMessage();
    } catch (PaymentFailedException $exception) {
        $status = 'payment_failed';
        $message = $exception->getMessage();
    } catch (EventUnavailableException $exception) {
        $status = 'booking_unavailable';
        $message = $exception->getMessage();
    } catch (\Throwable $exception) {
        $status = 'error';
        $message = $exception->getMessage();
    }

    $this->line(json_encode([
        'status' => $status,
        'message' => $message,
    ]));

    return 1;
})->purpose('Perform a single booking attempt for concurrency verification');

Artisan::command('booking:stress-test {eventId} {--attempts=50}', function () {
    $eventId = (int) $this->argument('eventId');
    $attempts = max(1, (int) $this->option('attempts'));

    Event::query()->findOrFail($eventId);

    $users = User::factory()->count($attempts)->create();

    $processes = $users->map(function (User $user) use ($eventId) {
        return Process::path(base_path())->start([
            PHP_BINARY,
            'artisan',
            'booking:attempt',
            (string) $eventId,
            (string) $user->id,
            'tok_demo_success',
        ]);
    });

    $results = $processes->map(function ($process) {
        $process->wait();

        $output = trim($process->output());
        $decoded = $output !== '' ? json_decode($output, true) : null;

        return is_array($decoded)
            ? $decoded
            : ['status' => 'error', 'message' => trim($process->errorOutput()) ?: 'Unable to parse child process output.'];
    });

    $event = Event::query()->findOrFail($eventId);

    $confirmed = $results->where('status', 'confirmed')->count();
    $rejected = $results->count() - $confirmed;

    $this->table(
        ['event_id', 'attempts', 'confirmed', 'rejected', 'available_seats'],
        [[
            'event_id' => $eventId,
            'attempts' => $attempts,
            'confirmed' => $confirmed,
            'rejected' => $rejected,
            'available_seats' => $event->available_seats,
        ]],
    );

    return 0;
})->purpose('Run concurrent booking attempts against a single event');
