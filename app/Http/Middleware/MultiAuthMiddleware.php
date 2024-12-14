<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MultiAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $role = session('auth_role');
        $userId = session('auth_id');

        if (!$role || !$userId || !Auth::guard($role)->check()) {
            Log::warning('Auth middleware failed', [
                'role' => $role,
                'user_id' => $userId,
                'session_data' => session()->all(),
                'auth_check' => $role ? Auth::guard($role)->check() : false
            ]);

            return redirect('/login');
        }

        // Verify the logged-in user matches the session user
        $user = Auth::guard($role)->user();
        $currentUserId = match ($role) {
            'district' => $user->district_id,
            'treasury' => $user->tre_off_id,
            'center' => $user->center_id,
            'mobile_team_staffs' => $user->mobile_id,
            'venue' => $user->venue_id,
            'headquarters' => $user->dept_off_id,
            'ci' => $user->ci_id,
            default => null
        };

        if ($currentUserId != $userId) {
            Auth::guard($role)->logout();
            return redirect('/login');
        }

        // Check for department officer role when accessing create and store methods in CurrentExamController
        if ($request->is('current-exam/create') || $request->is('current-exam/store')) {
            if ($role !== 'headquarters' || $user->role->role_department != 'RND') {
                return redirect('/login')->with('error', 'Unauthorized access.',);
            }
        }

        return $next($request);
    }
}