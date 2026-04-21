<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;

final readonly class UpsertEventData implements Arrayable
{
    public function __construct(
        public string $title,
        public ?string $description,
        public string $venue,
        public CarbonInterface $startsAt,
        public CarbonInterface $endsAt,
        public int $totalSeats,
        public string $price,
        public string $status,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'] ?? null,
            venue: $data['venue'],
            startsAt: Carbon::parse($data['starts_at']),
            endsAt: Carbon::parse($data['ends_at']),
            totalSeats: (int) $data['total_seats'],
            price: number_format((float) $data['price'], 2, '.', ''),
            status: $data['status'],
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'venue' => $this->venue,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
            'total_seats' => $this->totalSeats,
            'price' => $this->price,
            'status' => $this->status,
        ];
    }
}
