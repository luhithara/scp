<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthKelas
{
    /**
     * Memastikan hanya akun kelas yang dapat mengakses halaman kelas.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika belum login, arahkan ke halaman login.
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Pastikan pengguna memiliki role kelas.
        if (strtolower(Auth::user()->role ?? '') !== 'kelas') {
            abort(403, 'Akses hanya untuk akun Kelas.');
        }

        return $next($request);
    }
}