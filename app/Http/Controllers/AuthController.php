<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])
                    ->orWhere('email', $credentials['username'])
                    ->first();

        if ($user && password_verify($credentials['password'], $user->password)) {
            if (!$user->is_active) {
                return back()->withInput()->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi Administrator.');
            }

            Auth::login($user, $request->has('remember'));
            $user->update(['last_login' => now()]);

            ActivityLog::record('login', 'auth', "Pengguna {$user->nama} ({$user->username}) berhasil masuk ke sistem");

            return redirect()->intended(route('dashboard'));
        }

        return back()->withInput()->with('error', 'Username atau password yang Anda masukkan salah.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::record('logout', 'auth', "Pengguna {$user->nama} telah logout");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}