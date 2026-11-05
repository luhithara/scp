<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WakaPasswordController extends Controller
{
    /**
     * ============================================================
     * CEK AKSES WAKA
     * ============================================================
     */
    private function cekAksesWaka()
    {
        if (!Auth::check()) {
            abort(403, 'Anda belum login.');
        }

        if (strtolower(Auth::user()->role ?? '') !== 'waka') {
            abort(403, 'Akses hanya untuk Waka.');
        }
    }

    /**
     * ============================================================
     * UBAH PASSWORD AKUN
     *
     * Berlaku untuk:
     * - siswa
     * - orangtua
     * - guru
     * - kelas
     *
     * Parameter:
     * user_id
     * password
     * password_confirmation
     * ============================================================
     */
    public function update(Request $request)
    {
        $this->cekAksesWaka();

        $validated = $request->validate(
            [
                'user_id' => 'required|integer|exists:users,id',
                'password' => 'required|string|min:6|max:100|confirmed',
            ],
            [
                'user_id.required' => 'Akun tidak ditemukan.',
                'user_id.exists' => 'Akun tidak ditemukan.',

                'password.required' => 'Password baru wajib diisi.',
                'password.min' => 'Password minimal 6 karakter.',
                'password.max' => 'Password maksimal 100 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak sama.',
            ]
        );

        $user = DB::table('users')
            ->where('id', $validated['user_id'])
            ->first();

        if (!$user) {
            return back()
                ->with('error', 'Akun pengguna tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | WAKA TIDAK BOLEH MENGUBAH PASSWORD AKUN WAKA LAIN
        |--------------------------------------------------------------------------
        */
        if (strtolower($user->role ?? '') === 'waka') {
            return back()
                ->with('error', 'Password akun Waka tidak dapat diubah dari menu ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | HANYA AKUN YANG DIDUKUNG
        |--------------------------------------------------------------------------
        */
        $roleDiizinkan = [
            'siswa',
            'orangtua',
            'guru',
            'kelas',
        ];

        if (!in_array(strtolower($user->role ?? ''), $roleDiizinkan)) {
            return back()
                ->with('error', 'Jenis akun tersebut tidak dapat diubah dari menu ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE PASSWORD
        |--------------------------------------------------------------------------
        */
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($validated['password']),
                'updated_at' => now(),
            ]);

        return back()->with(
            'success',
            'Password akun berhasil diubah.'
        );
    }
}