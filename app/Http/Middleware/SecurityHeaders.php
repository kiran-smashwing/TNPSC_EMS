<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        // Perform additional security checks
        $input = $request->all();
        $patterns = [
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/(union\s+select|select\s+from|insert\s+into|update\s+set|delete\s+from|drop\s+table|create\s+table|alter\s+table|rename\s+table|truncate\s+table|load\s+data|call\s+procedure|declare\s+|exec\s+|execute\s+)/i',
            '/(\b(and|or)\b\s*?[\w\W]*?=|--|#|\/\*|\*\/|;)/i'
        ];

        foreach ($input as $value) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    abort(403, 'Forbidden');
                }
            }
        }

        // Add security headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        // Content Security Policy (CSP)
        // $response->headers->set('Content-Security-Policy', "default-src 'self'; img-src 'self' data: https://*; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://*; style-src 'self' 'unsafe-inline' https://*;");
        // $response->headers->set('Content-Security-Policy',"default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data:;object-src 'none';base-uri 'self';form-action 'self'; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com");
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), fullscreen=(), payment=()');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        $response->headers->set('Expect-CT', 'max-age=86400, enforce');
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        return $response;
    }
}
