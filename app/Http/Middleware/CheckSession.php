<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\HeaderBag;

class CheckSession
{
    protected $timeout = 1200; // 20 minutes in seconds

    public function handle(Request $request, Closure $next)
    {
      
        $role = session('auth_role');
        $userId = session('auth_id');
        if (!$role || !$userId || !Auth::guard($role)->check()) {
            return redirect('/login');
        }

        // Track the last activity timestamp
        if ($request->session()->has('lastActivityTime')) {
            $lastActivity = $request->session()->get('lastActivityTime');
            if (Carbon::now()->diffInSeconds($lastActivity) > $this->timeout) {
                Auth::guard($role)->logout();
                $request->session()->flush(); // Clears entire session data
                return redirect('/login')->with('status', 'Session expired due to inactivity.');
            }
        }

        if ($role && Auth::guard($role)->check()) {
            if (
                $request->session()->get('ip_address') !== $request->ip() ||
                $request->session()->get('user_agent') !== $request->userAgent()
            ) {
                Auth::guard($role)->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/login')->with('status', 'Session expired due to security concerns.');
            }
            // $cookieFp = $_COOKIE['device_fingerprint'] ?? null;
            // $sessionFp = session('device_fingerprint');
            // if (!$cookieFp || !$sessionFp || $cookieFp !== $sessionFp) {
            //     // Invalidate session and logout
            //     Auth::guard($role)->logout();
            //     session()->invalidate();
            //     session()->regenerateToken();

            //     return redirect('/login')->with('status', 'Session verification failed. You have been logged out.');
            // }
        }
        return $next($request);
    }
}

