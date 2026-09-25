<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware stub kept for compatibility.
 * Inertia has been removed; this middleware is no longer registered.
 */
class HandleInertiaRequests
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
