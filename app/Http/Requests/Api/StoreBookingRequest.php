<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'event_id' => [
                'required',
                'integer',
                Rule::exists('events', 'id')->where(fn ($query) => $query->whereNull('deleted_at')),
            ],
            'payment_method' => ['required', 'string', Rule::in(['fake_card'])],
            'payment_token' => ['required', 'string', 'max:255'],
        ];
    }
}
