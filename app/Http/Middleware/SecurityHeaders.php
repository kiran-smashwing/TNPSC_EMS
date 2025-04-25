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
        $this->validateHostHeader($request);
        $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];

        if (!in_array(strtoupper($request->method()), $allowedMethods)) {
            abort(405, 'Method Not Allowed');
        }

        $response = $next($request);
        // Perform additional security checks
        $input = $request->all();
        $patterns = [
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/\b(union\s+select|select\s+from|insert\s+into|update\s+\w+\s+set|delete\s+from|drop\s+table|create\s+table|alter\s+table|rename\s+table|truncate\s+table|load\s+data|call\s+\w+|declare\s+\w+|exec\s+\w+|execute\s+\w+)\b/i',
            '/(\b(and|or)\b\s+[^\s]+?\s*?=|--|#|\/\*|\*\/|;)/i'
        ];

        /**
         * Check if the given string is a base64 image.
         */
        function isBase64Image($value)
        {
            return is_string($value) && preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $value);
        }

        /**
         * Recursively check values for prohibited patterns.
         */
        function checkValue($value, $patterns)
        {
            if (is_array($value)) {
                foreach ($value as $subValue) {
                    checkValue($subValue, $patterns);
                }
                return;
            }

            foreach ($patterns as $pattern) {
                if (is_string($value) && preg_match($pattern, $value)) {
                    abort(403, 'Forbidden');
                }
            }
        }

        foreach ($input as $key => $value) {
            if (is_string($value)) {
                // Decode HTML entities only if the value is a string
                $decodedValue = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $jsonDecoded = json_decode($decodedValue, true);
            } else {
                // Skip decoding if the value is not a string
                $decodedValue = $value;
                $jsonDecoded = is_array($value) ? $value : null;
            }

            if (is_array($jsonDecoded)) {
                // Skip direct checks if it's valid JSON; process recursively
                checkValue($jsonDecoded, $patterns);
                continue;
            }
            // Skip Base64 images
            if (isBase64Image($value)) {
                continue;
            }
            // Check plain values
            checkValue($decodedValue, $patterns);
        }

        // Get the request host (works both for local and production)
        $host = $request->getHost();
        $scheme = $request->isSecure() ? 'wss' : 'ws';

        // Allow other domains if needed (optional, useful for local dev tools or services)
        $connectSources = "'self' https: http: {$scheme}://{$host}:6001 {$scheme}://{$host}:8080";
        // Add security headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy (CSP) - Permissive to avoid breaking frontend
        $response->headers->set('Content-Security-Policy', "default-src 'self' https: http: data:; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http: blob:; " .
            "style-src 'self' 'unsafe-inline' https: http:; " .
            "img-src 'self' data: https: http:; " .
            "font-src 'self' data: https: http:; " .
            "connect-src {$connectSources}; " .
            "object-src 'none'; " .
            "base-uri 'self'; " .
            "form-action 'self' https:; " .
            "frame-ancestors 'self';");

        $response->headers->set('Permissions-Policy', 'camera=self, microphone=(), geolocation=self, fullscreen=self');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        $response->headers->set('Expect-CT', 'max-age=86400, enforce');
        $response->headers->set('Cache-Control', 'private, no-cache, must-revalidate');
        return $response;
    }
    /**
     * Validate the Host header to prevent Host Header Injection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateHostHeader(Request $request): void
    {
        // Define allowed hosts (e.g., from config or environment)
        $allowedHosts = [
            parse_url(config('app.url'), PHP_URL_HOST), // our primary app URL 
            'localhost',       // For local development
        ];

        // Get the Host header from the request
        $host = strtolower($request->header('Host'));

        // Remove port if present (e.g., "example.com:80" -> "example.com")
        $host = preg_replace('/:\d+$/', '', $host);

        // Check if the Host header is in the allowed list
        if (!in_array($host, array_map('strtolower', $allowedHosts), true)) {
            // Log the suspicious request
            \Log::warning('Host Header Injection attempt detected', [
                'host' => $host,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            // Reject the request with a 400 Bad Request status
            abort(400, 'Invalid Host header');
        }
    }
}
