<?php

namespace App\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;

final readonly class BookingResultData implements Arrayable
{
    public function __construct(
        public string $bookingNumber,
        public int $eventId,
        public string $status,
        public string $amount,
        public int $availableSeats,
        public string $transactionReference,
    ) {
    }

    public function toArray(): array
    {
        return [
            'booking_number' => $this->bookingNumber,
            'event_id' => $this->eventId,
            'status' => $this->status,
            'amount' => $this->amount,
            'available_seats' => $this->availableSeats,
            'transaction_reference' => $this->transactionReference,
        ];
    }
}
