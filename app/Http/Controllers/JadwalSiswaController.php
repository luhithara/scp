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

        /*
        |--------------------------------------------------------------------------
        | CARI DATA SISWA
        |--------------------------------------------------------------------------
        */

        $siswa = DB::table('siswa')
            ->join(
                'kelas',
                'siswa.kelasid',
                '=',
                'kelas.id'
            )
            ->leftJoin(
                'jurusan',
                'kelas.jurusanid',
                '=',
                'jurusan.id'
            )
            ->where(
                'siswa.nisn',
                $user->nisn
            )
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


        /*
        |--------------------------------------------------------------------------
        | CEK DATA SISWA
        |--------------------------------------------------------------------------
        */

        if (!$siswa) {
            abort(
                404,
                'Data siswa tidak ditemukan.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TANGGAL HARI INI
        |--------------------------------------------------------------------------
        */

        $hariIni = Carbon::today()->toDateString();


        /*
        |--------------------------------------------------------------------------
        | JADWAL KELAS SISWA
        |--------------------------------------------------------------------------
        |
        | Hanya mengambil jadwal dengan kelasid yang sama dengan kelas siswa,
        | jadi siswa tidak akan melihat jadwal kelas lain.
        |
        */

        $jadwal = DB::table('jadwal')
            ->join(
                'guru',
                'jadwal.guruid',
                '=',
                'guru.id'
            )
            ->leftJoin(
                'mapel',
                'jadwal.mapelid',
                '=',
                'mapel.id'
            )
            ->where(
                'jadwal.kelasid',
                $siswa->kelasid
            )
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
                CASE jadwal.hari
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    ELSE 6
                END
            ")
            ->orderBy(
                'jadwal.jamMulai'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TENTUKAN PUTARAN YANG SEDANG AKTIF
        |--------------------------------------------------------------------------
        |
        | putaranblok berasal dari input jadwal guru.
        |
        */

        $putaranAktif = $jadwal
            ->pluck('putaranblok')
            ->filter()
            ->unique()
            ->values();


        if ($putaranAktif->count() === 1) {

            $putaranSekarang = $putaranAktif->first();

        } elseif ($putaranAktif->count() > 1) {

            /*
            | Kalau ada lebih dari satu putaran pada periode yang sama,
            | jangan menghilangkan jadwal yang sudah diinput guru.
            */

            $putaranSekarang = null;

        } else {

            $putaranSekarang = null;
        }


        /*
        |--------------------------------------------------------------------------
        | JURNAL SESUAI KELAS SISWA
        |--------------------------------------------------------------------------
        |
        | Jurnal hanya berasal dari jadwal kelas siswa.
        |
        */

        $jurnal = DB::table('jurnal_mengajar')
            ->join(
                'jadwal',
                'jurnal_mengajar.jadwalid',
                '=',
                'jadwal.id'
            )
            ->join(
                'guru',
                'jurnal_mengajar.guruid',
                '=',
                'guru.id'
            )
            ->leftJoin(
                'mapel',
                'jadwal.mapelid',
                '=',
                'mapel.id'
            )
            ->where(
                'jadwal.kelasid',
                $siswa->kelasid
            )
            ->select(
                'jurnal_mengajar.id',

                'jurnal_mengajar.tanggal',

                'jurnal_mengajar.materi',

                'jurnal_mengajar.kegiatan',

                'jurnal_mengajar.catatan',

                'jadwal.hari',

                'jadwal.jamMulai',

                'jadwal.jamSelesai',

                'jadwal.putaranblok',

                'jadwal.blok',

                'jadwal.namamapel',

                'mapel.namamapel as namamapelmaster',

                'guru.nama as namaguru'
            )
            ->orderByDesc(
                'jurnal_mengajar.tanggal'
            )
            ->orderByDesc(
                'jurnal_mengajar.id'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | KIRIM KE VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'jadwalSiswa',
            compact(
                'siswa',
                'jadwal',
                'jurnal',
                'putaranSekarang',
                'hariIni'
            )
        );
    }
}