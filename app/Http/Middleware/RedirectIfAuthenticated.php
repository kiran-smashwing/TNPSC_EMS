<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next)
    {
        $role = session('auth_role');

        // Check if user is logged in through any guard
        if ($role && Auth::guard($role)->check()) {
            return redirect()->intended('/dashboard');
        }

        return $next($request);
    }
}
