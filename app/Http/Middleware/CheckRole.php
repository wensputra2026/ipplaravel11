<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi Administrator.');
        }

        if (empty($roles)) {
            return $next($request);
        }

        $userLevel = strtolower(trim($user->level));
        foreach ($roles as $role) {
            $r = strtolower(trim($role));
            if ($r === $userLevel) {
                return $next($request);
            }
            if ($r === 'wali' && in_array($userLevel, ['wali', 'walikelas', 'wali kelas'])) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }
}