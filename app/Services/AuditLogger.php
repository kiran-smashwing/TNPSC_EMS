<?php

namespace App\Services;

use App\Models\Audit;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log($event, $auditableType = null, $auditableId = null, $oldValues = null, $newValues = null)
    {
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;
        
        // Get the correct user ID based on role
        $userId = null;
        if ($user) {
            switch ($role) {
                case 'district':
                    $userId = $user->district_id;
                    break;
                case 'treasury':
                    $userId = $user->tre_off_id;
                    break;
                case 'mobile_team_staffs':
                    $userId = $user->mobile_id;
                    break;
                case 'venue':
                    $userId = $user->venue_id;
                    break;
                case 'center':
                    $userId = $user->center_id;
                    break;
                case 'headquarters':
                    $userId = $user->dept_off_id;
                    break;
                case 'ci':
                    $userId = $user->ci_id;
                    break;
                default:
                    $userId = $user->id;
                    break;
            }
        }

        try {
            return Audit::create([
                'user_id' => $userId,
                'role' => $role,
                'event' => $event,
                'auditable_type' => $auditableType,
                'auditable_id' => $auditableId,
                'old_values' => is_array($oldValues) ? json_encode($oldValues) : $oldValues,
                'new_values' => is_array($newValues) ? json_encode($newValues) : $newValues,
                'url' => request()->fullUrl(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            \Log::error('Audit Log Error: ' . $e->getMessage(), [
                'event' => $event,
                'user_id' => $userId,
                'role' => $role
            ]);
            return null;
        }
    }
}