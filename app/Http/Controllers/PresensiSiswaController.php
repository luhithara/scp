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
        /*
        |--------------------------------------------------------------------------
        | CEK LOGIN
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK ROLE
        |--------------------------------------------------------------------------
        */

        if (strtolower(trim($user->role ?? '')) !== 'siswa') {
            abort(403, 'Halaman ini hanya untuk siswa.');
        }

        /*
        |--------------------------------------------------------------------------
        | CARI SISWA BERDASARKAN NIS
        |--------------------------------------------------------------------------
        */

        if (empty($user->nis)) {
            abort(404, 'NIS akun siswa tidak ditemukan.');
        }

        $siswa = Siswa::where('nis', $user->nis)->first();

        if (!$siswa) {
            abort(
                404,
                'Data siswa tidak ditemukan. Periksa kesesuaian NIS akun dengan data siswa.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL RIWAYAT PRESENSI
        |--------------------------------------------------------------------------
        */

        $presensi = DB::table('presensi')
            ->leftJoin(
                'jadwal',
                'jadwal.id',
                '=',
                'presensi.jadwalid'
            )
            ->leftJoin(
                'guru',
                'guru.id',
                '=',
                'jadwal.guruid'
            )
            ->leftJoin(
                'mapel',
                'mapel.id',
                '=',
                'jadwal.mapelid'
            )
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
                'guru.nama as namaguru'
            )
            ->selectRaw("
                COALESCE(
                    NULLIF(TRIM(mapel.namamapel), ''),
                    NULLIF(TRIM(jadwal.namamapel), ''),
                    'Mata Pelajaran'
                ) AS namamapel
            ")
            ->where('presensi.siswaid', $siswa->id)
            ->orderByDesc('presensi.tanggal_presensi')
            ->orderByDesc('presensi.waktu')
            ->orderByDesc('presensi.id')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | AMBIL TANGGAL PRESENSI
        |--------------------------------------------------------------------------
        */

        $tanggalPresensi = $presensi
            ->getCollection()
            ->pluck('tanggal_presensi')
            ->filter()
            ->map(function ($tanggal) {
                return date('Y-m-d', strtotime($tanggal));
            })
            ->unique()
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA IZIN SISWA
        |--------------------------------------------------------------------------
        */

        $izin = collect();

        if (!empty($tanggalPresensi)) {
            $izin = DB::table('izin_siswa')
                ->where('siswaid', $siswa->id)
                ->whereIn('tanggal', $tanggalPresensi)
                ->orderByDesc('id')
                ->get()
                ->groupBy(function ($item) {
                    return date(
                        'Y-m-d',
                        strtotime($item->tanggal)
                    );
                });
        }

        /*
        |--------------------------------------------------------------------------
        | GABUNGKAN KETERANGAN IZIN
        |--------------------------------------------------------------------------
        */

        foreach ($presensi as $item) {

            $tanggal = date(
                'Y-m-d',
                strtotime($item->tanggal_presensi)
            );

            $izinHariIni = $izin->get(
                $tanggal,
                collect()
            )->first();

            // Pertahankan keterangan yang sudah tersimpan.
            // Jika kosong, ambil alasan izin siswa.
            if (
                empty($item->keterangan) &&
                $izinHariIni
            ) {
                $item->keterangan = $izinHariIni->alasan;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE VIEW
        |--------------------------------------------------------------------------
        */

        return view('presensi.siswa', [
            'presensi' => $presensi,
            'siswa' => $siswa,
        ]);
    }
}