<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
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
        ], [
            'identitas.required' => 'NIS atau kode identitas wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'jenisakun.required' => 'Silakan pilih jenis akun.',
            'jenisakun.in' => 'Jenis akun tidak valid.',
        ]);

        $identitas = trim($request->identitas);
        $password = $request->password;
        $jenisAkun = strtolower(trim($request->jenisakun));

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN KOLOM IDENTITAS
        |--------------------------------------------------------------------------
        */

        $kolomIdentitas = match ($jenisAkun) {
            'siswa', 'orangtua' => 'nis',
            'guru', 'bk', 'waka' => 'kodepegawai',
            'kelas' => 'kodekelas',
            default => null,
        };

        if (!$kolomIdentitas) {
            return back()
                ->withErrors([
                    'identitas' => 'Jenis akun tidak valid.',
                ])
                ->withInput($request->only('identitas', 'jenisakun'));
        }

        /*
        |--------------------------------------------------------------------------
        | CARI AKUN DI TABEL USERS
        |--------------------------------------------------------------------------
        |
        | Role dan status dibuat tidak sensitif terhadap huruf besar/kecil.
        | Siswa dan orang tua menggunakan users.nis.
        |
        */

        $user = User::query()
            ->where($kolomIdentitas, $identitas)
            ->whereRaw('LOWER(TRIM(role)) = ?', [$jenisAkun])
            ->whereRaw('LOWER(TRIM(status)) = ?', ['aktif'])
            ->first();

        /*
        |--------------------------------------------------------------------------
        | VALIDASI AKUN DAN PASSWORD
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            return back()
                ->withErrors([
                    'identitas' => 'Akun tidak ditemukan atau belum aktif. Periksa NIS/kode identitas dan jenis akun.',
                ])
                ->withInput($request->only('identitas', 'jenisakun'));
        }

        if (!Hash::check($password, $user->password)) {
            return back()
                ->withErrors([
                    'password' => 'Password yang kamu masukkan salah.',
                ])
                ->withInput($request->only('identitas', 'jenisakun'));
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI KHUSUS AKUN KELAS
        |--------------------------------------------------------------------------
        */

        if ($jenisAkun === 'kelas') {
            if (empty($user->kelasid)) {
                return back()
                    ->withErrors([
                        'identitas' => 'Akun kelas belum terhubung dengan data kelas.',
                    ])
                    ->withInput($request->only('identitas', 'jenisakun'));
            }

            $kelasAda = DB::table('kelas')
                ->where('id', $user->kelasid)
                ->exists();

            if (!$kelasAda) {
                return back()
                    ->withErrors([
                        'identitas' => 'Data kelas untuk akun ini tidak ditemukan.',
                    ])
                    ->withInput($request->only('identitas', 'jenisakun'));
            }
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN
        |--------------------------------------------------------------------------
        */

        Auth::login($user);
        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | REDIRECT BERDASARKAN ROLE
        |--------------------------------------------------------------------------
        */

        $routeDashboard = match ($jenisAkun) {
            'guru' => 'guru.dashboard',
            'waka' => 'waka.dashboard',
            'siswa' => 'dashboard',
            'orangtua' => 'orangtua.dashboard',
            'bk' => 'bk.dashboard',
            'kelas' => 'kelas.dashboard',
            default => null,
        };

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN ROUTE DASHBOARD TERSEDIA
        |--------------------------------------------------------------------------
        */

        if (!$routeDashboard || !Route::has($routeDashboard)) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'identitas' => 'Route dashboard untuk akun ini belum tersedia. Periksa nama route di web.php.',
                ]);
        }

        return redirect()->route($routeDashboard);
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}