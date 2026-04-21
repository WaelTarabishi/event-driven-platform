<?php

namespace App\Services;

use App\DataTransferObjects\BookingResultData;
use App\DataTransferObjects\CreateBookingData;
use App\Enums\BookingStatus;
use App\Enums\EventStatus;
use App\Exceptions\AlreadyBookedException;
use App\Exceptions\EventUnavailableException;
use App\Exceptions\PaymentFailedException;
use App\Exceptions\SoldOutException;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Support\ReferenceGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    public function __construct(
        private readonly EventRepositoryInterface $events,
        private readonly BookingRepositoryInterface $bookings,
        private readonly PaymentRepositoryInterface $payments,
        private readonly FakePaymentGatewayService $fakePayments,
        private readonly ReferenceGenerator $references,
    ) {
    }

    public function book(CreateBookingData $data): BookingResultData
    {
        try {
            return DB::transaction(function () use ($data) {
                $event = $this->events->findForUpdate($data->eventId);

                if ($event === null) {
                    throw new EventUnavailableException('The selected event could not be found.');
                }

                if ($event->status !== EventStatus::Published || $event->starts_at->isPast()) {
                    throw new EventUnavailableException();
                }

                if ($event->available_seats < 1) {
                    throw new SoldOutException();
                }

                if ($this->bookings->existsForUserEvent($data->userId, $event->id)) {
                    throw new AlreadyBookedException();
                }

                $charge = $this->fakePayments->charge(
                    paymentMethod: $data->paymentMethod,
                    paymentToken: $data->paymentToken,
                );

                $booking = $this->bookings->createConfirmed([
                    'booking_number' => $this->references->booking(),
                    'user_id' => $data->userId,
                    'event_id' => $event->id,
                    'seat_count' => 1,
                    'unit_price' => $event->price,
                    'status' => BookingStatus::Confirmed->value,
                ]);

                $payment = $this->payments->create([
                    'booking_id' => $booking->id,
                    'gateway' => $charge['gateway'],
                    'transaction_reference' => $this->references->payment(),
                    'amount' => $event->price,
                    'paid_at' => $charge['paid_at'],
                ]);

                $event = $this->events->update($event, [
                    'available_seats' => $event->available_seats - 1,
                ]);

                $result = new BookingResultData(
                    bookingNumber: $booking->booking_number,
                    eventId: $event->id,
                    status: $booking->status->value,
                    amount: number_format((float) $payment->amount, 2, '.', ''),
                    availableSeats: $event->available_seats,
                    transactionReference: $payment->transaction_reference,
                );

                Log::info('booking.confirmed', [
                    'user_id' => $data->userId,
                    'event_id' => $event->id,
                    'booking_number' => $booking->booking_number,
                    'transaction_reference' => $payment->transaction_reference,
                    'available_seats' => $event->available_seats,
                    'result' => 'confirmed',
                ]);

                return $result;
            }, 5);
        } catch (\Throwable $exception) {
            $this->logFailure($data, $exception);

            throw $exception;
        }
    }

    private function logFailure(CreateBookingData $data, \Throwable $exception): void
    {
        Log::warning('booking.failed', [
            'user_id' => $data->userId,
            'event_id' => $data->eventId,
            'payment_method' => $data->paymentMethod,
            'result' => match (true) {
                $exception instanceof SoldOutException => 'sold_out',
                $exception instanceof AlreadyBookedException => 'already_booked',
                $exception instanceof PaymentFailedException => 'payment_failed',
                $exception instanceof EventUnavailableException => 'booking_unavailable',
                default => 'error',
            },
            'exception' => $exception::class,
            'message' => $exception->getMessage(),
        ]);
    }
}
