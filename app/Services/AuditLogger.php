<?php

namespace App\Services;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log($event, $auditableType = null, $auditableId = null, $oldValues = null, $newValues = null)
    {
        Audit::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => $auditableType,
            'auditable_id' => $auditableId,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($newValues),
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}