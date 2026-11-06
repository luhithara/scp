<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mengarahkan pengguna ke dashboard sesuai role akun.
     */
    public function index()
    {
        $user = Auth::user();

        // Pastikan pengguna sudah login.
        if (!$user) {
            return redirect()->route('login');
        }

        $role = strtolower(trim($user->role ?? ''));

        switch ($role) {
            case 'waka':
                return redirect()->route('waka.dashboard');

            case 'guru':
                return redirect()->route('guru.dashboard');

            case 'bk':
                return redirect()->route('bk.dashboard');

            case 'orangtua':
                return redirect()->route('orangtua.dashboard');

            case 'siswa':
                return view('dashboard', compact('user'));

            case 'kelas':
                return redirect()->route('kelas.dashboard');

            default:
                abort(403, 'Role akun tidak dikenali.');
        }
    }
}