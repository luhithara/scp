<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
     * Proses login semua jenis akun SCP.
     */
    public function login(Request $request)
    {
        $request->validate([
            'identitas' => 'required|string',
            'password' => 'required|string',
            'jenisakun' => 'required|in:siswa,orangtua,guru,bk,waka,kelas',
        ]);

        $identitas = trim($request->identitas);
        $jenisAkun = $request->jenisakun;

        $user = null;

        /*
        |--------------------------------------------------------------------------
        | LOGIN SISWA
        |--------------------------------------------------------------------------
        */
        if ($jenisAkun === 'siswa') {
            $user = User::where('nisn', $identitas)
                ->where('role', 'siswa')
                ->where('status', 'aktif')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN ORANG TUA
        |--------------------------------------------------------------------------
        */
        elseif ($jenisAkun === 'orangtua') {
            $user = User::where('nisn', $identitas)
                ->where('role', 'orangtua')
                ->where('status', 'aktif')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN GURU
        |--------------------------------------------------------------------------
        */
        elseif ($jenisAkun === 'guru') {
            $user = User::where('kodepegawai', $identitas)
                ->where('role', 'guru')
                ->where('status', 'aktif')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN BK
        |--------------------------------------------------------------------------
        */
        elseif ($jenisAkun === 'bk') {
            $user = User::where('kodepegawai', $identitas)
                ->where('role', 'bk')
                ->where('status', 'aktif')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN WAKA
        |--------------------------------------------------------------------------
        */
        elseif ($jenisAkun === 'waka') {
            $user = User::where('kodepegawai', $identitas)
                ->where('role', 'waka')
                ->where('status', 'aktif')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN KELAS
        |--------------------------------------------------------------------------
        | Identitas login menggunakan kodekelas.
        */
        elseif ($jenisAkun === 'kelas') {
            $user = User::where('kodekelas', $identitas)
                ->where('role', 'kelas')
                ->where('status', 'aktif')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI PASSWORD
        |--------------------------------------------------------------------------
        */
        if (!$user || !Hash::check($request->password, $user->password)) {
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
        if ($user->role === 'kelas') {
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
        switch ($user->role) {
            case 'guru':
                return redirect()->route('guru.dashboard');

            case 'waka':
                return redirect()->route('waka.dashboard');

            case 'siswa':
                return redirect()->route('dashboard');

            case 'orangtua':
                return redirect()->route('orangtua.dashboard');

            case 'bk':
                return redirect()->route('bk.dashboard');

            case 'kelas':
                return redirect()->route('kelas.dashboard');

            default:
                Auth::logout();

                return redirect()->route('login')
                    ->withErrors([
                        'identitas' => 'Jenis akun tidak dikenali.',
                    ]);
        }
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}