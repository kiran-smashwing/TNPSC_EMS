<?php
namespace App\Services;

use App\Models\ExamAuditLog;
use Illuminate\Support\Facades\Auth;

class ExamAuditService
{

    public function log(
        int $examId,
        string $actionType,
        string $taskType,
        ?array $beforeState = null,
        ?array $afterState = null,
        ?string $description = null,
        ?array $metadata = null
    ): ExamAuditLog {
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

        return ExamAuditLog::create([
            'exam_id' => $examId,
            'user_id' => $userId,
            'action_type' => $actionType,
            'task_type' => $taskType,
            'role' => $role,
            'department' => $role == 'headquarters' ? (($user->role ? ($user->role->role_department . " - " . $user->role->role_name) : ($user->custom_role ?? 'Unknown'))) : $role,
            'before_state' => $beforeState,
            'after_state' => $afterState,
            'description' => $description,
            'metadata' => $metadata
        ]);
    }

    public function getExamTimeline(int $examId)
    {
        return ExamAuditLog::with(['user'])
            ->where('exam_id', $examId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    public function findLog(array $criteria): ?ExamAuditLog
    {
        return ExamAuditLog::where($criteria)->first();
    }

    public function updateLog(
        int $logId,
        ?array $metadata = null,
        ?array $afterState = null,
        ?string $description = null
    ): bool {
        $log = ExamAuditLog::find($logId);
        if ($log) {
            $log->metadata = $metadata;
            $log->after_state = $afterState;
            $log->description = $description;
            return $log->save();
        }
        return false;
    }
}