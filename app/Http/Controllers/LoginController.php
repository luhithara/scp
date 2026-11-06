<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function tampil()
    {
        return view('login');
    }

    /**
     * Proses login seluruh jenis akun SCP.
     */
    public function login(Request $request)
    {
        $request->validate([
            'identitas' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string'],
            'jenisakun' => [
                'required',
                'in:siswa,orangtua,guru,bk,waka,kelas',
            ],
        ]);

        $identitas = trim($request->input('identitas'));
        $password = $request->input('password');
        $jenisAkun = $request->input('jenisakun');

        // Pastikan identitas tidak hanya berisi spasi.
        if ($identitas === '') {
            return back()
                ->withErrors([
                    'identitas' => 'Identitas wajib diisi.',
                ])
                ->withInput([
                    'jenisakun' => $jenisAkun,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | MENENTUKAN KOLOM IDENTITAS
        |--------------------------------------------------------------------------
        */

        $kolomIdentitas = match ($jenisAkun) {
            'siswa', 'orangtua' => 'nisn',
            'guru', 'bk', 'waka' => 'kodepegawai',
            'kelas' => 'kodekelas',
            default => null,
        };

        if (!$kolomIdentitas) {
            return back()
                ->withErrors([
                    'identitas' => 'Jenis akun tidak valid.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | MENCARI AKUN
        |--------------------------------------------------------------------------
        */

        $user = User::query()
            ->where($kolomIdentitas, $identitas)
            ->where('role', $jenisAkun)
            ->where('status', 'aktif')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI PASSWORD
        |--------------------------------------------------------------------------
        */

        if (!$user || !Hash::check($password, $user->password)) {
            return back()
                ->withErrors([
                    'identitas' => 'Identitas atau password salah.',
                ])
                ->withInput([
                    'identitas' => $identitas,
                    'jenisakun' => $jenisAkun,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI HUBUNGAN AKUN KELAS
        |--------------------------------------------------------------------------
        */

        if ($jenisAkun === 'kelas') {
            if (!$user->kelasid || !$user->kelas) {
                return back()
                    ->withErrors([
                        'identitas' => 'Akun Kelas belum terhubung dengan data kelas.',
                    ])
                    ->withInput([
                        'identitas' => $identitas,
                        'jenisakun' => $jenisAkun,
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN DAN REGENERASI SESSION
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | REDIRECT SESUAI ROLE
        |--------------------------------------------------------------------------
        */

        $routeDashboard = match ($user->role) {
            'guru' => 'guru.dashboard',
            'waka' => 'waka.dashboard',
            'siswa' => 'dashboard',
            'orangtua' => 'orangtua.dashboard',
            'bk' => 'bk.dashboard',
            'kelas' => 'kelas.dashboard',
            default => null,
        };

        // Jika route dashboard tidak dikenali.
        if (!$routeDashboard) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors([
                    'identitas' => 'Jenis akun tidak dikenali.',
                ]);
        }

        return redirect()->route($routeDashboard);
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}