<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mengarahkan user ke dashboard sesuai role akun.
     *
     * Route yang digunakan semuanya SUDAH ADA
     * di project SCP.
     */
    public function index()
    {
        $user = Auth::user();

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

            default:
                return view('dashboard', compact('user'));
        }
    }
}