<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\CreateBookingData;
use App\Exceptions\AlreadyBookedException;
use App\Exceptions\BookingCancellationException;
use App\Exceptions\EventUnavailableException;
use App\Exceptions\PaymentFailedException;
use App\Exceptions\SoldOutException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookings,
    ) {
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            $result = $this->bookings->book(
                CreateBookingData::fromArray($request->validated(), $request->user()->id),
            );

            return response()->json([
                'message' => 'Booking confirmed',
                'data' => BookingResource::make($result)->resolve(),
            ], 201);
        } catch (SoldOutException $exception) {
            return $this->errorResponse('sold_out', $exception->getMessage(), 409);
        } catch (AlreadyBookedException $exception) {
            return $this->errorResponse('already_booked', $exception->getMessage(), 409);
        } catch (PaymentFailedException $exception) {
            return $this->errorResponse('payment_failed', $exception->getMessage(), 422);
        } catch (EventUnavailableException $exception) {
            return $this->errorResponse('booking_unavailable', $exception->getMessage(), 422);
        }
    }

    public function destroy(string $bookingNumber): JsonResponse
    {
        try {
            $result = $this->bookings->cancelByBookingNumber($bookingNumber, request()->user()->id);

            return response()->json([
                'message' => 'Booking cancelled',
                'data' => BookingResource::make($result)->resolve(),
            ]);
        } catch (BookingCancellationException $exception) {
            return $this->errorResponse('cannot_cancel', $exception->getMessage(), 422);
        }
    }

    private function errorResponse(string $code, string $message, int $status): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'code' => $code,
        ], $status);
    }
}
