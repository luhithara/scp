<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresensiSiswaController extends Controller
{
    /**
     * ============================================================
     * RIWAYAT PRESENSI SISWA
     * ============================================================
     *
     * Halaman ini digunakan oleh siswa untuk melihat:
     *
     * - Hari
     * - Tanggal
     * - Status kehadiran
     * - Keterangan
     *
     * Keterangan diambil dari:
     *
     * 1. presensi.keterangan
     * 2. Jika kosong, ambil dari izin_siswa.alasan
     *
     * ============================================================
     */
    public function index()
    {
        /*
         * ========================================================
         * CEK USER LOGIN
         * ========================================================
         */

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }


        /*
         * ========================================================
         * AMBIL DATA SISWA
         * ========================================================
         *
         * Sistem SCP menggunakan NISN pada users
         * untuk menghubungkan akun dengan data siswa.
         */

        $siswa = Siswa::where('nisn', $user->nisn)->first();


        /*
         * Kalau data siswa tidak ditemukan,
         * jangan biarkan halaman error.
         */

        if (!$siswa) {

            return view(
                'presensi.siswa',
                [
                    'siswa'    => null,
                    'presensi' => collect(),
                ]
            );
        }


        /*
         * ========================================================
         * AMBIL RIWAYAT PRESENSI
         * ========================================================
         *
         * presensi
         *      ↓
         * jadwal
         *      ↓
         * guru
         *
         * Kemudian dihubungkan dengan izin_siswa
         * berdasarkan siswa dan tanggal.
         */

        $presensi = DB::table('presensi')

            /*
             * ----------------------------------------------------
             * JADWAL
             * ----------------------------------------------------
             */

            ->leftJoin(
                'jadwal',
                'jadwal.id',
                '=',
                'presensi.jadwalid'
            )


            /*
             * ----------------------------------------------------
             * GURU
             * ----------------------------------------------------
             */

            ->leftJoin(
                'guru',
                'guru.id',
                '=',
                'jadwal.guruid'
            )


            /*
             * ----------------------------------------------------
             * IZIN SISWA
             * ----------------------------------------------------
             *
             * Cocokkan:
             *
             * izin_siswa.siswaid
             * dengan
             * siswa yang sedang login.
             *
             * Dan tanggal izin dengan tanggal presensi.
             */

            ->leftJoin(
                'izin_siswa',
                function ($join) {

                    $join->on(
                        'izin_siswa.siswaid',
                        '=',
                        'presensi.siswaid'
                    )

                    ->whereRaw(
                        'DATE(izin_siswa.tanggal) = DATE(presensi.waktu)'
                    );
                }
            )


            /*
             * ====================================================
             * FILTER SISWA
             * ====================================================
             */

            ->where(
                'presensi.siswaid',
                $siswa->id
            )


            /*
             * ====================================================
             * SELECT DATA
             * ====================================================
             */

            ->select(

                /*
                 * Semua data presensi.
                 */

                'presensi.*',


                /*
                 * Informasi jadwal.
                 */

                'jadwal.hari',

                'jadwal.jamMulai',

                'jadwal.jamSelesai',

                'jadwal.namamapel',


                /*
                 * Informasi guru.
                 *
                 * Kolom guru yang benar adalah `nama`,
                 * bukan `namaguru`.
                 */

                'guru.nama as namaguru',


                /*
                 * =================================================
                 * KETERANGAN
                 * =================================================
                 *
                 * Prioritas:
                 *
                 * 1. presensi.keterangan
                 * 2. izin_siswa.alasan
                 *
                 * Jadi kalau guru mengisi keterangan,
                 * keterangan guru yang digunakan.
                 *
                 * Kalau kosong dan siswa punya izin/sakit,
                 * alasan izin akan ditampilkan.
                 */

                DB::raw("
                    COALESCE(
                        NULLIF(presensi.keterangan, ''),
                        izin_siswa.alasan
                    ) AS keterangan
                ")

            )


            /*
             * ====================================================
             * URUTKAN DATA
             * ====================================================
             */

            ->orderByDesc(
                'presensi.waktu'
            )


            /*
             * ====================================================
             * JALANKAN QUERY
             * ====================================================
             */

            ->get();


        /*
         * ========================================================
         * KIRIM DATA KE BLADE
         * ========================================================
         */

        return view(
            'presensi.siswa',
            compact(
                'presensi',
                'siswa'
            )
        );
    }
}