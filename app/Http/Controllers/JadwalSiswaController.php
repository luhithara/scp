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
        $user = Auth::user();

        // Pastikan hanya akun siswa yang bisa mengakses.
        if (!$user || strtolower(trim($user->role ?? '')) !== 'siswa') {
            abort(403, 'Halaman ini hanya untuk siswa.');
        }

        // Cari data siswa berdasarkan NISN akun login.
        $siswa = DB::table('siswa')
            ->join('kelas', 'siswa.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->where('siswa.nisn', $user->nisn)
            ->select(
                'siswa.id',
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
            abort(404, 'Data siswa tidak ditemukan.');
        }

        // Tanggal hari ini.
        $hariIni = Carbon::today()->toDateString();

        // Ambil jadwal khusus kelas siswa.
        $jadwal = DB::table('jadwal')
            ->join('guru', 'jadwal.guruid', '=', 'guru.id')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->where('jadwal.kelasid', $siswa->kelasid)
            ->select(
                'jadwal.id',
                'jadwal.kelasid',
                'jadwal.guruid',
                'jadwal.mapelid',
                'jadwal.namamapel',
                'jadwal.hari',
                'jadwal.jamke',
                'jadwal.jamMulai',
                'jadwal.jamSelesai',
                'jadwal.putaranblok',
                'jadwal.blok',
                'guru.nama as namaguru',
                'mapel.namamapel as namamapelmaster'
            )
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

        // Tentukan putaran yang ditemukan pada jadwal kelas.
        $putaranAktif = $jadwal
            ->pluck('putaranblok')
            ->filter()
            ->unique()
            ->values();

        $putaranSekarang = $putaranAktif->count() === 1
            ? $putaranAktif->first()
            : null;

        return view('jadwalSiswa', compact(
            'siswa',
            'jadwal',
            'putaranSekarang',
            'hariIni'
        ));
    }
}