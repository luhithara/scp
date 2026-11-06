<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthKelas
{
    /**
     * Memastikan hanya pengguna dengan role kelas
     * yang dapat mengakses halaman khusus akun kelas.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil pengguna yang sedang login.
        $user = $request->user();

        // Jika belum login, arahkan ke halaman login.
        if (!$user) {
            return redirect()->route('login');
        }

        // Normalisasi role pengguna.
        $role = strtolower(trim($user->role ?? ''));

        // Pastikan role adalah kelas.
        if ($role !== 'kelas') {
            abort(403, 'Akses hanya untuk akun Kelas.');
        }

        return $next($request);
    }
}