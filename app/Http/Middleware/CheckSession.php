<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        
        if (Auth::check()) {
            if ($request->session()->get('ip_address') !== $request->ip() ||
                $request->session()->get('user_agent') !== $request->userAgent()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('error', 'Session expired due to security concerns.');
            }
        }
        return $next($request);
    }
}

