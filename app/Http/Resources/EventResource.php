<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'venue' => $this->venue,
            'starts_at' => $this->starts_at?->toIso8601String(),
            'price' => $this->price,
            'available_seats' => $this->available_seats,
        ];
    }
}
