<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
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
