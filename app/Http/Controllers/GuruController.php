<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD GURU
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user || strtolower(trim($user->role ?? '')) !== 'guru') {
            abort(403, 'Akses hanya untuk Guru.');
        }

        $guru = DB::table('guru')
            ->where('kodeguru', $user->kodepegawai)
            ->first();

        if (!$guru) {
            return redirect()
                ->route('dashboard')
                ->withErrors([
                    'guru' => 'Data guru tidak ditemukan.'
                ]);
        }

        // Mengambil presensi siswa dari jadwal milik guru hari ini.
        $presensiHariIni = DB::table('presensi')
            ->join(
                'jadwal',
                'presensi.jadwalid',
                '=',
                'jadwal.id'
            )
            ->where('jadwal.guruid', $guru->id)
            ->whereDate(
                'presensi.waktu',
                now()->toDateString()
            )
            ->select(
                'presensi.status',
                'presensi.siswaid',
                'presensi.jadwalid'
            )
            ->get();

        $jumlahHadir = $presensiHariIni
            ->where('status', 'hadir')
            ->count();

        $jumlahIzinPresensi = $presensiHariIni
            ->where('status', 'izin')
            ->count();

        $jumlahSakit = $presensiHariIni
            ->where('status', 'sakit')
            ->count();

        $jumlahAlpa = $presensiHariIni
            ->where('status', 'alpa')
            ->count();

        // Mengambil izin siswa yang terhubung dengan jadwal guru ini.
        $izinHariIni = DB::table('izin_siswa')
            ->join(
                'siswa',
                'izin_siswa.siswaid',
                '=',
                'siswa.id'
            )
            ->join(
                'jadwal',
                'izin_siswa.jadwalid',
                '=',
                'jadwal.id'
            )
            ->leftJoin(
                'kelas',
                'siswa.kelasid',
                '=',
                'kelas.id'
            )
            ->where('jadwal.guruid', $guru->id)
            ->whereDate(
                'izin_siswa.tanggal',
                now()->toDateString()
            )
            ->select(
                'izin_siswa.*',
                'siswa.nama as namasiswa',
                'siswa.nisn',
                'kelas.namakelas'
            )
            ->orderByDesc('izin_siswa.waktu')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK KARTU DASHBOARD
        |--------------------------------------------------------------------------
        */

        $jumlahJadwal = DB::table('jadwal')
            ->where('guruid', $guru->id)
            ->count();

        $jumlahJurnal = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->count();

        $jumlahIzin = $izinHariIni->count();

        return view(
            'guru.dashboard',
            compact(
                'user',
                'guru',
                'jumlahJadwal',
                'jumlahJurnal',
                'jumlahHadir',
                'jumlahIzin',
                'jumlahIzinPresensi',
                'jumlahSakit',
                'jumlahAlpa',
                'izinHariIni'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS JADWAL GURU
    |--------------------------------------------------------------------------
    */

    public function hapusJadwal($id)
    {
        $user = Auth::user();

        if (!$user || strtolower(trim($user->role ?? '')) !== 'guru') {
            abort(403, 'Akses hanya untuk Guru.');
        }

        // Memastikan jadwal tidak sedang dikunci oleh Waka.
        $terkunci = DB::table('pengaturan_jadwal')
            ->where('id', 1)
            ->value('terkunci');

        if ($terkunci) {
            return back()->with(
                'error',
                'Jadwal sedang dikunci oleh Waka. Penghapusan jadwal belum dapat dilakukan.'
            );
        }

        $guru = DB::table('guru')
            ->where('kodeguru', $user->kodepegawai)
            ->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        // Memastikan jadwal benar-benar milik guru yang login.
        $jadwal = DB::table('jadwal')
            ->where('id', $id)
            ->where('guruid', $guru->id)
            ->first();

        if (!$jadwal) {
            return back()->withErrors([
                'jadwal' => 'Jadwal tidak ditemukan atau bukan jadwal guru ini.'
            ]);
        }

        DB::table('jadwal')
            ->where('id', $id)
            ->where('guruid', $guru->id)
            ->delete();

        return back()->with(
            'success',
            'Jadwal berhasil dihapus.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | IZIN SISWA UNTUK GURU
    |--------------------------------------------------------------------------
    */

    public function izin(Request $request)
    {
        $user = Auth::user();

        if (!$user || strtolower(trim($user->role ?? '')) !== 'guru') {
            abort(403, 'Akses hanya untuk Guru.');
        }

        $guru = DB::table('guru')
            ->where('kodeguru', $user->kodepegawai)
            ->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        // Data kelas untuk filter.
        $kelas = DB::table('kelas')
            ->orderBy('tingkat')
            ->orderBy('namakelas')
            ->get();

        /*
         * Hanya mengambil izin siswa yang mempunyai jadwal
         * dengan guru yang sedang login.
         */
        $query = DB::table('izin_siswa')
            ->join(
                'siswa',
                'izin_siswa.siswaid',
                '=',
                'siswa.id'
            )
            ->join(
                'jadwal',
                'izin_siswa.jadwalid',
                '=',
                'jadwal.id'
            )
            ->leftJoin(
                'kelas',
                'siswa.kelasid',
                '=',
                'kelas.id'
            )
            ->where('jadwal.guruid', $guru->id);

        // Filter berdasarkan kelas.
        if ($request->filled('kelasid')) {
            $query->where(
                'siswa.kelasid',
                $request->kelasid
            );
        }

        $izin = $query
            ->select(
                'izin_siswa.*',
                'siswa.nama as namasiswa',
                'siswa.nisn',
                'kelas.namakelas'
            )
            ->orderByDesc('izin_siswa.tanggal')
            ->orderByDesc('izin_siswa.waktu')
            ->get();

        return view(
            'guru.izin',
            compact(
                'user',
                'guru',
                'kelas',
                'izin'
            )
        );
    }
}