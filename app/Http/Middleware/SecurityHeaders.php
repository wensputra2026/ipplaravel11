<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and attach hardened HTTP security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Mencegah Clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Mencegah MIME Type Sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Mengatur Kebijakan Referrer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Mencegah akses fitur sensitif perangkat browser jika tidak dibutuhkan
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // XSS Filter untuk browser lawas
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Paksa HSTS jika koneksi HTTPS / di production
        if ($request->isSecure() || app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
