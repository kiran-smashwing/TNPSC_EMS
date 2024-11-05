<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MultiAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $role = session('auth_role');
        $userId = session('auth_id');
        
        if (!$role || !$userId || !Auth::guard($role)->check()) {
            \Log::warning('Auth middleware failed', [
                'role' => $role,
                'user_id' => $userId,
                'session_data' => session()->all(),
                'auth_check' => $role ? Auth::guard($role)->check() : false
            ]);
            
            return redirect('/login');
        }

        // Verify the logged-in user matches the session user
        $user = Auth::guard($role)->user();
        $currentUserId = match($role) {
            'district' => $user->district_id,
            'treasury' => $user->tre_off_id,
            'mobile_team_staffs' => $user->mobile_team_id,
            default => null
        };

        if ($currentUserId != $userId) {
            Auth::guard($role)->logout();
            return redirect('/login');
        }

        return $next($request);
    }
}