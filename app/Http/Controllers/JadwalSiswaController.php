<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalSiswaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN JADWAL SISWA
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | CEK LOGIN DAN ROLE
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (strtolower(trim($user->role ?? '')) !== 'siswa') {
            abort(403, 'Halaman ini hanya untuk siswa.');
        }

        /*
        |--------------------------------------------------------------------------
        | CARI DATA SISWA BERDASARKAN NIS
        |--------------------------------------------------------------------------
        */

        if (empty($user->nis)) {
            abort(404, 'NIS akun siswa tidak ditemukan.');
        }

        $siswa = DB::table('siswa')
            ->join('kelas', 'siswa.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->where('siswa.nis', $user->nis)
            ->select(
                'siswa.id',
                'siswa.nis',
                'siswa.nisn',
                'siswa.nama',
                'siswa.kelasid',
                'kelas.namakelas',
                'kelas.tingkat',
                'kelas.jurusanid',
                'jurusan.namajurusan'
            )
            ->first();

        if (!$siswa) {
            abort(404, 'Data siswa tidak ditemukan. Periksa kesesuaian NIS akun dengan data siswa.');
        }

        /*
        |--------------------------------------------------------------------------
        | TANGGAL DAN HARI
        |--------------------------------------------------------------------------
        */

        $hariIni = Carbon::today()->toDateString();

        /*
        |--------------------------------------------------------------------------
        | AMBIL JADWAL SESUAI KELAS SISWA
        |--------------------------------------------------------------------------
        */

        $jadwal = DB::table('jadwal')
            ->leftJoin('guru', 'jadwal.guruid', '=', 'guru.id')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->where('jadwal.kelasid', $siswa->kelasid)
            ->select(
                'jadwal.id',
                'jadwal.kelasid',
                'jadwal.guruid',
                'jadwal.mapelid',
                'jadwal.hari',
                'jadwal.jamke',
                'jadwal.jamMulai',
                'jadwal.jamSelesai',
                'jadwal.putaranblok',
                'jadwal.blok',
                'guru.nama as namaguru'
            )
            ->selectRaw("
                COALESCE(
                    NULLIF(TRIM(mapel.namamapel), ''),
                    NULLIF(TRIM(jadwal.namamapel), ''),
                    'Mata Pelajaran Belum Diatur'
                ) AS namamapel
            ")
            ->orderByRaw("
                CASE LOWER(jadwal.hari)
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    ELSE 6
                END
            ")
            ->orderBy('jadwal.jamMulai')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PUTARAN BLOK
        |--------------------------------------------------------------------------
        */

        $putaranAktif = $jadwal
            ->pluck('putaranblok')
            ->filter()
            ->unique()
            ->values();

        $putaranSekarang = $putaranAktif->count() === 1
            ? $putaranAktif->first()
            : null;

        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN HALAMAN
        |--------------------------------------------------------------------------
        */

        return view('jadwalSiswa', [
            'siswa' => $siswa,
            'jadwal' => $jadwal,
            'putaranSekarang' => $putaranSekarang,
            'hariIni' => $hariIni,
        ]);
    }
}