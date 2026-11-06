<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresensiSiswaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | RIWAYAT PRESENSI SISWA
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = Auth::user();

        // Pastikan hanya siswa yang bisa mengakses.
        if (!$user || strtolower(trim($user->role ?? '')) !== 'siswa') {
            abort(403, 'Halaman ini hanya untuk siswa.');
        }

        // Cari data siswa berdasarkan NISN akun login.
        $siswa = Siswa::where('nisn', $user->nisn)->first();

        if (!$siswa) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL RIWAYAT PRESENSI
        |--------------------------------------------------------------------------
        */

        $presensi = DB::table('presensi')
            ->leftJoin('jadwal', 'jadwal.id', '=', 'presensi.jadwalid')
            ->leftJoin('guru', 'guru.id', '=', 'jadwal.guruid')
            ->select(
                'presensi.id',
                'presensi.siswaid',
                'presensi.jadwalid',
                'presensi.tanggal_presensi',
                'presensi.waktu',
                'presensi.status',
                'presensi.keterangan',
                'jadwal.hari',
                'jadwal.jamMulai',
                'jadwal.jamSelesai',
                'jadwal.namamapel',
                'guru.nama as namaguru'
            )
            ->where('presensi.siswaid', $siswa->id)
            ->orderByDesc('presensi.tanggal_presensi')
            ->orderByDesc('presensi.waktu')
            ->orderByDesc('presensi.id')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | AMBIL ALASAN IZIN
        |--------------------------------------------------------------------------
        */

        $tanggalPresensi = $presensi
            ->getCollection()
            ->pluck('tanggal_presensi')
            ->filter()
            ->unique()
            ->values();

        $izin = DB::table('izin_siswa')
            ->where('siswaid', $siswa->id)
            ->whereIn('tanggal', $tanggalPresensi)
            ->orderByDesc('id')
            ->get()
            ->groupBy(function ($item) {
                return date('Y-m-d', strtotime($item->tanggal));
            });

        /*
        |--------------------------------------------------------------------------
        | GABUNGKAN KETERANGAN PRESENSI DAN IZIN
        |--------------------------------------------------------------------------
        */

        foreach ($presensi as $item) {
            $tanggal = $item->tanggal_presensi;

            $izinHariIni = $izin->get($tanggal, collect())->first();

            $item->keterangan = $item->keterangan
                ?: ($izinHariIni->alasan ?? null);
        }

        /*
        |--------------------------------------------------------------------------
        | KIRIM KE VIEW
        |--------------------------------------------------------------------------
        */

        return view('presensi.siswa', compact(
            'presensi',
            'siswa'
        ));
    }
}