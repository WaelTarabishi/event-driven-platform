<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogService
{
    public function record(
        ?int $userId,
        string $action,
        string $entityType,
        ?int $entityId = null,
        array $metadata = [],
        ?string $requestId = null,
    ): AuditLog {
        return AuditLog::query()->create([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'metadata' => $metadata,
            'request_id' => $requestId,
        ]);
    }
}
