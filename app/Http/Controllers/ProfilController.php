<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | AMBIL DATA KELAS
    |--------------------------------------------------------------------------
    */

    private function ambilDataKelas($user)
    {
        return DB::table('kelas')
            ->where('namakelas', $user->name)
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN PROFIL
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = Auth::user();

        abort_unless($user !== null, 401);

        $siswa = null;
        $guru = null;
        $waka = null;
        $bk = null;
        $kelas = null;

        /*
        |--------------------------------------------------------------------------
        | SISWA / ORANG TUA
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'siswa' ||
            $user->role === 'orangtua'
        ) {
            $siswa = DB::table('siswa')
                ->where('nisn', $user->nisn)
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | GURU
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'guru') {
            $guru = DB::table('guru')
                ->where('kodeguru', $user->kodepegawai)
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | WAKA
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'waka') {
            $waka = DB::table('waka')
                ->where('userid', $user->id)
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | BK
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'bk') {
            $bk = DB::table('bk')
                ->where('userid', $user->id)
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | AKUN KELAS
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'kelas') {
            $kelas = $this->ambilDataKelas($user);
        }

        return view('profil', compact(
            'user',
            'siswa',
            'guru',
            'waka',
            'bk',
            'kelas'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT PROFIL
    |--------------------------------------------------------------------------
    */

    public function edit()
    {
        $user = Auth::user();

        abort_unless($user !== null, 401);

        /*
        |--------------------------------------------------------------------------
        | SISWA / ORANG TUA
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'siswa' ||
            $user->role === 'orangtua'
        ) {
            $siswa = DB::table('siswa')
                ->where('nisn', $user->nisn)
                ->first();

            if (!$siswa) {
                abort(404, 'Data siswa tidak ditemukan.');
            }

            return view('profilEdit', [
                'user' => $user,
                'siswa' => $siswa,
                'guru' => null,
                'waka' => null,
                'bk' => null,
                'kelas' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | GURU
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'guru') {
            $guru = DB::table('guru')
                ->where('kodeguru', $user->kodepegawai)
                ->first();

            if (!$guru) {
                abort(404, 'Data guru tidak ditemukan.');
            }

            return view('profilEdit', [
                'user' => $user,
                'siswa' => null,
                'guru' => $guru,
                'waka' => null,
                'bk' => null,
                'kelas' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | WAKA
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'waka') {
            $waka = DB::table('waka')
                ->where('userid', $user->id)
                ->first();

            if (!$waka) {
                abort(404, 'Data Waka tidak ditemukan.');
            }

            return view('profilEdit', [
                'user' => $user,
                'siswa' => null,
                'guru' => null,
                'waka' => $waka,
                'bk' => null,
                'kelas' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | BK
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'bk') {
            $bk = DB::table('bk')
                ->where('userid', $user->id)
                ->first();

            if (!$bk) {
                abort(404, 'Data BK tidak ditemukan.');
            }

            return view('profilEdit', [
                'user' => $user,
                'siswa' => null,
                'guru' => null,
                'waka' => null,
                'bk' => $bk,
                'kelas' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | AKUN KELAS
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'kelas') {
            $kelas = $this->ambilDataKelas($user);

            return view('profilEdit', [
                'user' => $user,
                'siswa' => null,
                'guru' => null,
                'waka' => null,
                'bk' => null,
                'kelas' => $kelas,
            ]);
        }

        abort(403, 'Role pengguna tidak dikenali.');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PROFIL
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {
        $user = Auth::user();

        abort_unless($user !== null, 401);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI NAMA DAN EMAIL
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'nama' => 'required|string|max:100',

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email tersebut sudah digunakan oleh akun lain.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | SISWA / ORANG TUA
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'siswa' ||
            $user->role === 'orangtua'
        ) {
            $request->validate([
                'jeniskelamin' => 'required|in:L,P',
                'tanggallahir' => 'nullable|date',
                'tempatlahir' => 'nullable|string|max:100',
                'alamat' => 'nullable|string',
                'nomortelepon' => 'nullable|string|max:20',
            ], [
                'jeniskelamin.required' => 'Jenis kelamin wajib dipilih.',
                'jeniskelamin.in' => 'Jenis kelamin tidak valid.',
                'tanggallahir.date' => 'Tanggal lahir tidak valid.',
            ]);

            DB::table('siswa')
                ->where('nisn', $user->nisn)
                ->update([
                    'nama' => $request->nama,
                    'jeniskelamin' => $request->jeniskelamin,
                    'tanggallahir' => $request->tanggallahir,
                    'tempatlahir' => $request->tempatlahir,
                    'alamat' => $request->alamat,
                    'nomortelepon' => $request->nomortelepon,
                    'updated_at' => now(),
                ]);

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('profil')
                ->with('success', 'Profil berhasil diperbarui.');
        }

        /*
        |--------------------------------------------------------------------------
        | GURU
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'guru') {
            $request->validate([
                'nip' => 'nullable|string|max:30',
                'nomortelepon' => 'nullable|string|max:20',
                'alamat' => 'nullable|string',
            ], [
                'nip.max' => 'NIP maksimal 30 karakter.',
                'nomortelepon.max' => 'Nomor telepon maksimal 20 karakter.',
            ]);

            DB::table('guru')
                ->where('kodeguru', $user->kodepegawai)
                ->update([
                    'nama' => $request->nama,
                    'nip' => $request->nip,
                    'nomortelepon' => $request->nomortelepon,
                    'alamat' => $request->alamat,
                    'updated_at' => now(),
                ]);

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('profil')
                ->with('success', 'Profil berhasil diperbarui.');
        }

        /*
        |--------------------------------------------------------------------------
        | WAKA
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'waka') {
            DB::table('waka')
                ->where('userid', $user->id)
                ->update([
                    'nama' => $request->nama,
                    'updated_at' => now(),
                ]);

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('profil')
                ->with('success', 'Profil Waka berhasil diperbarui.');
        }

        /*
        |--------------------------------------------------------------------------
        | BK
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'bk') {
            DB::table('bk')
                ->where('userid', $user->id)
                ->update([
                    'nama' => $request->nama,
                    'updated_at' => now(),
                ]);

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('profil')
                ->with('success', 'Profil BK berhasil diperbarui.');
        }

        /*
        |--------------------------------------------------------------------------
        | AKUN KELAS
        |--------------------------------------------------------------------------
        |
        | Nama kelas tidak diubah dari halaman profil.
        | Nama akun tetap mengikuti nama kelas di tabel kelas.
        | Email akun disimpan di tabel users.
        |
        */

        elseif ($user->role === 'kelas') {
            $kelas = $this->ambilDataKelas($user);

            abort_unless(
                $kelas !== null,
                404,
                'Data kelas tidak ditemukan.'
            );

            /*
             * Akun kelas hanya boleh mengubah email.
             * Nama akun dipertahankan agar tetap cocok
             * dengan kolom namakelas pada tabel kelas.
             */
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'email' => $request->email,
                    'updated_at' => now(),
                ]);

            return redirect()
                ->route('profil')
                ->with('success', 'Profil akun kelas berhasil diperbarui.');
        }

        abort(403, 'Role pengguna tidak dikenali.');
    }
}