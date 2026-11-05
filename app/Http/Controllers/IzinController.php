<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IzinController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CEK LOGIN
    |--------------------------------------------------------------------------
    */

    private function cekLogin()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return $user;
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL DATA SISWA DARI USER
    |--------------------------------------------------------------------------
    |
    | Siswa maupun orangtua terhubung melalui users.nisn
    |
    */

    private function ambilSiswaDariUser($user)
    {
        return DB::table('siswa')
            ->leftJoin(
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
                'siswa.*',
                'kelas.namakelas as namakelas',
                'kelas.tingkat as tingkat',
                'jurusan.namajurusan as namajurusan'
            )
            ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | HALAMAN IZIN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = $this->cekLogin();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }


        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'siswa') {

            $siswa = $this->ambilSiswaDariUser($user);

            if (!$siswa) {
                abort(
                    404,
                    'Data siswa tidak ditemukan.'
                );
            }


            $riwayatIzin = DB::table('izin_siswa')
                ->where(
                    'siswaid',
                    $siswa->id
                )
                ->orderByDesc('tanggal')
                ->orderByDesc('waktu')
                ->get();


            return view(
                'izin',
                compact(
                    'user',
                    'siswa',
                    'riwayatIzin'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ORANGTUA
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'orangtua') {

            $siswa = $this->ambilSiswaDariUser($user);

            if (!$siswa) {
                abort(
                    404,
                    'Data anak tidak ditemukan.'
                );
            }


            $riwayatIzin = DB::table('izin_siswa')
                ->where(
                    'siswaid',
                    $siswa->id
                )
                ->orderByDesc('tanggal')
                ->orderByDesc('waktu')
                ->get();


            return view(
                'orangtua.izin',
                compact(
                    'user',
                    'siswa',
                    'riwayatIzin'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GURU
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'guru') {

            $guru = DB::table('guru')
                ->where(
                    'kodeguru',
                    $user->kodepegawai
                )
                ->first();

            if (!$guru) {
                abort(
                    404,
                    'Data guru tidak ditemukan.'
                );
            }


            $riwayatIzin = DB::table('izin_siswa')
                ->leftJoin(
                    'siswa',
                    'izin_siswa.siswaid',
                    '=',
                    'siswa.id'
                )
                ->leftJoin(
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
                ->leftJoin(
                    'jadwal',
                    'izin_siswa.jadwalid',
                    '=',
                    'jadwal.id'
                )
                ->where(
                    'jadwal.guruid',
                    $guru->id
                )
                ->select(
                    'izin_siswa.*',
                    'siswa.nama as namasiswa',
                    'siswa.nisn',
                    'kelas.namakelas',
                    'jurusan.namajurusan',
                    'jadwal.hari',
                    'jadwal.jamMulai',
                    'jadwal.jamSelesai',
                    'jadwal.namamapel'
                )
                ->orderByDesc(
                    'izin_siswa.tanggal'
                )
                ->orderByDesc(
                    'izin_siswa.waktu'
                )
                ->get();


            return view(
                'izin',
                compact(
                    'user',
                    'guru',
                    'riwayatIzin'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | WAKA / BK / ROLE LAIN
        |--------------------------------------------------------------------------
        */

        $riwayatIzin = DB::table('izin_siswa')
            ->leftJoin(
                'siswa',
                'izin_siswa.siswaid',
                '=',
                'siswa.id'
            )
            ->leftJoin(
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
            ->select(
                'izin_siswa.*',
                'siswa.nama as namasiswa',
                'siswa.nisn',
                'kelas.namakelas',
                'jurusan.namajurusan'
            )
            ->orderByDesc(
                'izin_siswa.tanggal'
            )
            ->orderByDesc(
                'izin_siswa.waktu'
            )
            ->get();


        return view(
            'izin',
            compact(
                'user',
                'riwayatIzin'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN PENGAJUAN IZIN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = $this->cekLogin();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }


        /*
        |--------------------------------------------------------------------------
        | HANYA SISWA DAN ORANGTUA
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $user->role,
                [
                    'siswa',
                    'orangtua'
                ]
            )
        ) {
            abort(
                403,
                'Akun ini tidak dapat mengajukan izin.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate(
            [
                'jenis' => [
                    'required',
                    'in:izin,sakit'
                ],

                'alasan' => [
                    'required',
                    'string',
                    'max:1000'
                ],

                'bukti' => [
                    'nullable',
                    'file',
                    'mimes:jpg,jpeg,png,pdf',
                    'max:5120'
                ],
            ],
            [
                'jenis.required' =>
                    'Jenis pengajuan wajib dipilih.',

                'jenis.in' =>
                    'Jenis pengajuan tidak valid.',

                'alasan.required' =>
                    'Alasan wajib diisi.',

                'alasan.max' =>
                    'Alasan maksimal 1000 karakter.',

                'bukti.mimes' =>
                    'Bukti harus berupa JPG, JPEG, PNG, atau PDF.',

                'bukti.max' =>
                    'Ukuran bukti maksimal 5 MB.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | AMBIL SISWA
        |--------------------------------------------------------------------------
        */

        $siswa = $this->ambilSiswaDariUser($user);

        if (!$siswa) {
            abort(
                404,
                'Data siswa tidak ditemukan.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CEK SUDAH ADA PENGAJUAN HARI INI
        |--------------------------------------------------------------------------
        */

        $tanggalHariIni = Carbon::today()->toDateString();


        $sudahAda = DB::table('izin_siswa')
            ->where(
                'siswaid',
                $siswa->id
            )
            ->whereDate(
                'tanggal',
                $tanggalHariIni
            )
            ->exists();


        if ($sudahAda) {

            return back()
                ->withErrors([
                    'jenis' =>
                        'Pengajuan izin untuk hari ini sudah ada.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | UPLOAD BUKTI
        |--------------------------------------------------------------------------
        */

        $namaBukti = null;

        if ($request->hasFile('bukti')) {

            $namaBukti = $request
                ->file('bukti')
                ->store(
                    'bukti-izin',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | WAKTU SEKARANG
        |--------------------------------------------------------------------------
        |
        | PENTING:
        |
        | Kolom izin_siswa.waktu pada database kamu
        | menggunakan DATETIME.
        |
        | Jadi jangan menggunakan:
        |
        | Carbon::now()->format('H:i:s')
        |
        | karena itu hanya menghasilkan jam.
        |
        | Kita langsung simpan Carbon::now().
        |
        */

        $waktuSekarang = Carbon::now();


        /*
        |--------------------------------------------------------------------------
        | SIMPAN IZIN
        |--------------------------------------------------------------------------
        |
        | STATUS:
        | menunggu
        |
        | Bukan "diajukan".
        |
        */

        DB::table('izin_siswa')
            ->insert(
                [
                    'siswaid' =>
                        $siswa->id,

                    'jenis' =>
                        $request->jenis,

                    'alasan' =>
                        $request->alasan,

                    'bukti' =>
                        $namaBukti,

                    /*
                    | Kolom waktu bertipe DATETIME
                    */
                    'waktu' =>
                        $waktuSekarang,

                    /*
                    | Tanggal pengajuan
                    */
                    'tanggal' =>
                        $tanggalHariIni,

                    /*
                    | Nilai enum yang digunakan database
                    */
                    'status' =>
                        'menunggu',

                    /*
                    | Belum menentukan jadwal
                    */
                    'jadwalid' =>
                        null,

                    /*
                    | Belum disetujui guru
                    */
                    'disetujui_oleh' =>
                        null,

                    'waktudisetuju' =>
                        null,

                    /*
                    | Catatan BK belum ada
                    */
                    'catatanbk' =>
                        null,

                    'created_at' =>
                        $waktuSekarang,

                    'updated_at' =>
                        $waktuSekarang,
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | KEMBALI
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'orangtua') {

            return redirect()
                ->route('orangtua.dashboard')
                ->with(
                    'success',
                    'Pengajuan izin berhasil dikirim dan sedang menunggu persetujuan guru.'
                );
        }


        return redirect()
            ->route('izin')
            ->with(
                'success',
                'Pengajuan izin berhasil dikirim dan sedang menunggu persetujuan guru.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GURU MENYETUJUI IZIN
    |--------------------------------------------------------------------------
    */

    public function setujui($id)
    {
        $user = $this->cekLogin();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }


        if ($user->role !== 'guru') {
            abort(
                403,
                'Hanya guru yang dapat menyetujui izin.'
            );
        }


        $guru = DB::table('guru')
            ->where(
                'kodeguru',
                $user->kodepegawai
            )
            ->first();


        if (!$guru) {
            abort(
                404,
                'Data guru tidak ditemukan.'
            );
        }


        $izin = DB::table('izin_siswa')
            ->leftJoin(
                'jadwal',
                'izin_siswa.jadwalid',
                '=',
                'jadwal.id'
            )
            ->where(
                'izin_siswa.id',
                $id
            )
            ->where(
                'jadwal.guruid',
                $guru->id
            )
            ->select(
                'izin_siswa.*'
            )
            ->first();


        if (!$izin) {

            return back()
                ->with(
                    'error',
                    'Data izin tidak ditemukan atau bukan izin siswa yang diajar.'
                );
        }


        DB::table('izin_siswa')
            ->where(
                'id',
                $id
            )
            ->update(
                [
                    'status' =>
                        'disetujui',

                    'disetujui_oleh' =>
                        $guru->id,

                    'waktudisetuju' =>
                        Carbon::now(),

                    'updated_at' =>
                        Carbon::now(),
                ]
            );


        return back()
            ->with(
                'success',
                'Pengajuan izin berhasil disetujui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | GURU MENOLAK IZIN
    |--------------------------------------------------------------------------
    */

    public function tolak(
        Request $request,
        $id
    ) {
        $user = $this->cekLogin();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }


        if ($user->role !== 'guru') {
            abort(
                403,
                'Hanya guru yang dapat menolak izin.'
            );
        }


        $request->validate(
            [
                'catatan' => [
                    'nullable',
                    'string',
                    'max:1000'
                ],
            ]
        );


        $guru = DB::table('guru')
            ->where(
                'kodeguru',
                $user->kodepegawai
            )
            ->first();


        if (!$guru) {
            abort(
                404,
                'Data guru tidak ditemukan.'
            );
        }


        $izin = DB::table('izin_siswa')
            ->leftJoin(
                'jadwal',
                'izin_siswa.jadwalid',
                '=',
                'jadwal.id'
            )
            ->where(
                'izin_siswa.id',
                $id
            )
            ->where(
                'jadwal.guruid',
                $guru->id
            )
            ->select(
                'izin_siswa.*'
            )
            ->first();


        if (!$izin) {

            return back()
                ->with(
                    'error',
                    'Data izin tidak ditemukan atau bukan izin siswa yang diajar.'
                );
        }


        DB::table('izin_siswa')
            ->where(
                'id',
                $id
            )
            ->update(
                [
                    'status' =>
                        'ditolak',

                    'disetujui_oleh' =>
                        $guru->id,

                    'waktudisetuju' =>
                        Carbon::now(),

                    'catatanbk' =>
                        $request->catatan,

                    'updated_at' =>
                        Carbon::now(),
                ]
            );


        return back()
            ->with(
                'success',
                'Pengajuan izin berhasil ditolak.'
            );
    }
}