<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    // Izinkan jika role sesuai ATAU jika dia adalah admin (Admin bebas akses)
    if (Auth::user()->role === 'admin' || Auth::user()->role === $role) {

        // Tetap cek status aktif
        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun nonaktif.');
        }

        return $next($request);
    }

    abort(403, 'Akses ditolak.');
}



}
