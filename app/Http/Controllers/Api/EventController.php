<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    public function __construct(
        private readonly EventRepositoryInterface $events,
    ) {
    }

    public function index(): AnonymousResourceCollection
    {
        $perPage = min(max((int) request()->integer('per_page', 10), 1), 50);

        return EventResource::collection($this->events->paginatePublished($perPage));
    }
}
