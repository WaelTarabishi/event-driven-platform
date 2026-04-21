<?php

namespace App\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;

final readonly class CreateBookingData implements Arrayable
{
    public function __construct(
        public int $userId,
        public int $eventId,
        public string $paymentMethod,
        public string $paymentToken,
    ) {
    }

    public static function fromArray(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            eventId: (int) $data['event_id'],
            paymentMethod: $data['payment_method'],
            paymentToken: $data['payment_token'],
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'event_id' => $this->eventId,
            'payment_method' => $this->paymentMethod,
            'payment_token' => $this->paymentToken,
        ];
    }
}
