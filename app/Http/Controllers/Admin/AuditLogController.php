<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Inertia\Inertia;
use Inertia\Response;

class AuditLogController extends Controller
{
    public function index(): Response
    {
        $logs = AuditLog::query()
            ->with('user:id,name,email')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/audit-logs/index', [
            'logs' => [
                'data' => collect($logs->items())->map(fn (AuditLog $log) => [
                    'id' => $log->id,
                    'action' => $log->action,
                    'entity_type' => $log->entity_type,
                    'entity_id' => $log->entity_id,
                    'metadata' => $log->metadata,
                    'request_id' => $log->request_id,
                    'created_at' => $log->created_at?->toIso8601String(),
                    'user' => $log->user ? [
                        'id' => $log->user->id,
                        'name' => $log->user->name,
                        'email' => $log->user->email,
                    ] : null,
                ])->all(),
                'meta' => [
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                    'total' => $logs->total(),
                    'next_page_url' => $logs->nextPageUrl(),
                    'prev_page_url' => $logs->previousPageUrl(),
                ],
            ],
        ]);
    }
}
