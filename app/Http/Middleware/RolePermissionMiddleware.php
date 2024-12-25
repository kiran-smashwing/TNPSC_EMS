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
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
}
