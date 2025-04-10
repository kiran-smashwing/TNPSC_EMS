<?php

namespace App\Http\Middleware;
use Closure;
use App\Services\AuthorizationService;

class RolePermissionMiddleware
{
    public function handle($request, Closure $next, $permission)
    {
        $role = session('auth_role');
        $authService = app(AuthorizationService::class);

        if (!$authService->hasPermission($role, $permission)) {
            // Log the unauthorized access attempt for auditing purposes
            \Log::warning('Unauthorized access attempt', [
                'user_id' => auth()->id(),
                'role' => $role,
                'permission' => $permission,
                'ip' => request()->ip(),
                'url' => request()->fullUrl(),
            ]);

            // Flush the session and log the user out
            session()->flush();
            auth()->logout();

            // Redirect to the login page with an error message
            return redirect()->route('login')
                ->with('status', 'You do not have permission to access this resource. Please log in again.');

        }

        return $next($request);
    }
}
