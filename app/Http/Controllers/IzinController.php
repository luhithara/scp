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
    | users.nis = siswa.nis
    */

    private function ambilSiswaDariUser($user)
    {
        if (!$user || empty($user->nis)) {
            return null;
        }

        return DB::table('siswa')
            ->leftJoin('kelas', 'siswa.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->where('siswa.nis', trim($user->nis))
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
    | AMBIL DATA GURU DARI USER
    |--------------------------------------------------------------------------
    */

    private function ambilGuruDariUser($user)
    {
        return DB::table('guru')
            ->where('kodeguru', $user->kodepegawai)
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | CEK APAKAH GURU MENGAJAR KELAS SISWA
    |--------------------------------------------------------------------------
    */

    private function guruMengajarKelas($guruId, $kelasId)
    {
        if (!$guruId || !$kelasId) {
            return false;
        }

        return DB::table('jadwal')
            ->where('guruid', $guruId)
            ->where('kelasid', $kelasId)
            ->exists();
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

        $role = strtolower(trim($user->role ?? ''));

        /*
        |--------------------------------------------------------------------------
        | SISWA
        |--------------------------------------------------------------------------
        */

        if ($role === 'siswa') {
            $siswa = $this->ambilSiswaDariUser($user);

            if (!$siswa) {
                abort(404, 'Data siswa tidak ditemukan berdasarkan NIS akun yang login.');
            }

            $riwayatIzin = DB::table('izin_siswa')
                ->where('siswaid', $siswa->id)
                ->orderByDesc('tanggal')
                ->orderByDesc('waktu')
                ->get();

            return view(
                'izin',
                compact('user', 'siswa', 'riwayatIzin')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ORANG TUA
        |--------------------------------------------------------------------------
        */

        if ($role === 'orangtua') {
            $siswa = $this->ambilSiswaDariUser($user);

            if (!$siswa) {
                abort(404, 'Data anak tidak ditemukan berdasarkan NIS akun orang tua.');
            }

            $riwayatIzin = DB::table('izin_siswa')
                ->where('siswaid', $siswa->id)
                ->orderByDesc('tanggal')
                ->orderByDesc('waktu')
                ->get();

            return view(
                'orangtua.izin',
                compact('user', 'siswa', 'riwayatIzin')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GURU
        |--------------------------------------------------------------------------
        */

        if ($role === 'guru') {
            $guru = $this->ambilGuruDariUser($user);

            if (!$guru) {
                abort(404, 'Data guru tidak ditemukan.');
            }

            /*
             * Ambil jadwal guru berdasarkan kelas.
             * Satu kelas bisa memiliki beberapa jadwal.
             * Karena itu jadwal diringkas agar satu pengajuan
             * izin tidak muncul berkali-kali.
             */

            $jadwalGuru = DB::table('jadwal')
                ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
                ->where('jadwal.guruid', $guru->id)
                ->select(
                    'jadwal.kelasid',
                    DB::raw('MIN(jadwal.hari) as hari'),
                    DB::raw('MIN(jadwal.jamMulai) as jamMulai'),
                    DB::raw('MIN(jadwal.jamSelesai) as jamSelesai'),
                    DB::raw('MIN(mapel.namamapel) as namamapel')
                )
                ->groupBy('jadwal.kelasid');

            /*
             * Pengajuan izin diambil berdasarkan kelas siswa
             * yang diajar guru, bukan berdasarkan jadwalid.
             *
             * Ini penting karena pengajuan siswa disimpan
             * dengan jadwalid = NULL.
             */

            $riwayatIzin = DB::table('izin_siswa')
                ->join('siswa', 'izin_siswa.siswaid', '=', 'siswa.id')
                ->leftJoin('kelas', 'siswa.kelasid', '=', 'kelas.id')
                ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
                ->leftJoinSub(
                    $jadwalGuru,
                    'jadwal_guru',
                    function ($join) {
                        $join->on(
                            'jadwal_guru.kelasid',
                            '=',
                            'siswa.kelasid'
                        );
                    }
                )
                ->whereNotNull('jadwal_guru.kelasid')
                ->select(
                    'izin_siswa.*',
                    'siswa.nama as namasiswa',
                    'siswa.nisn',
                    'kelas.namakelas',
                    'kelas.tingkat',
                    'jurusan.namajurusan',
                    'jadwal_guru.hari',
                    'jadwal_guru.jamMulai',
                    'jadwal_guru.jamSelesai',
                    'jadwal_guru.namamapel'
                )
                ->orderByDesc('izin_siswa.tanggal')
                ->orderByDesc('izin_siswa.waktu')
                ->get();

            return view(
                'izin',
                compact('user', 'guru', 'riwayatIzin')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | WAKA / BK / ROLE LAIN
        |--------------------------------------------------------------------------
        */

        $riwayatIzin = DB::table('izin_siswa')
            ->leftJoin('siswa', 'izin_siswa.siswaid', '=', 'siswa.id')
            ->leftJoin('kelas', 'siswa.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->select(
                'izin_siswa.*',
                'siswa.nama as namasiswa',
                'siswa.nisn',
                'kelas.namakelas',
                'jurusan.namajurusan'
            )
            ->orderByDesc('izin_siswa.tanggal')
            ->orderByDesc('izin_siswa.waktu')
            ->get();

        return view(
            'izin',
            compact('user', 'riwayatIzin')
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

        $role = strtolower(trim($user->role ?? ''));

        /*
        |--------------------------------------------------------------------------
        | HANYA SISWA DAN ORANG TUA
        |--------------------------------------------------------------------------
        */

        if (!in_array($role, ['siswa', 'orangtua'])) {
            abort(403, 'Akun ini tidak dapat mengajukan izin.');
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate(
            [
                'jenis' => 'required|in:izin,sakit',
                'alasan' => 'required|string|max:1000',
                'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ],
            [
                'jenis.required' => 'Jenis pengajuan wajib dipilih.',
                'jenis.in' => 'Jenis pengajuan tidak valid.',
                'alasan.required' => 'Alasan wajib diisi.',
                'alasan.max' => 'Alasan maksimal 1000 karakter.',
                'bukti.mimes' => 'Bukti harus berupa JPG, JPEG, PNG, atau PDF.',
                'bukti.max' => 'Ukuran bukti maksimal 5 MB.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | AMBIL SISWA
        |--------------------------------------------------------------------------
        */

        $siswa = $this->ambilSiswaDariUser($user);

        if (!$siswa) {
            abort(404, 'Data siswa tidak ditemukan berdasarkan NIS akun yang login.');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK PENGAJUAN HARI INI
        |--------------------------------------------------------------------------
        */

        $tanggalHariIni = Carbon::today()->toDateString();

        $sudahAda = DB::table('izin_siswa')
            ->where('siswaid', $siswa->id)
            ->whereDate('tanggal', $tanggalHariIni)
            ->exists();

        if ($sudahAda) {
            return back()
                ->withErrors([
                    'jenis' => 'Pengajuan izin untuk hari ini sudah ada.'
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
            $namaBukti = $request->file('bukti')
                ->store('bukti-izin', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA IZIN
        |--------------------------------------------------------------------------
        */

        $waktuSekarang = Carbon::now();

        DB::table('izin_siswa')->insert([
            'siswaid' => $siswa->id,
            'jenis' => $request->jenis,
            'alasan' => $request->alasan,
            'bukti' => $namaBukti,
            'waktu' => $waktuSekarang,
            'tanggal' => $tanggalHariIni,
            'status' => 'menunggu',
            'jadwalid' => null,
            'disetujui_oleh' => null,
            'waktudisetuju' => null,
            'catatanbk' => null,
            'created_at' => $waktuSekarang,
            'updated_at' => $waktuSekarang,
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        if ($role === 'orangtua') {
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

        if (strtolower(trim($user->role ?? '')) !== 'guru') {
            abort(403, 'Hanya guru yang dapat menyetujui izin.');
        }

        $guru = $this->ambilGuruDariUser($user);

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        /*
         * Ambil izin beserta kelas siswa.
         * Tidak menggunakan jadwalid karena nilainya NULL
         * pada pengajuan yang dibuat siswa.
         */

        $izin = DB::table('izin_siswa')
            ->join('siswa', 'izin_siswa.siswaid', '=', 'siswa.id')
            ->where('izin_siswa.id', $id)
            ->select(
                'izin_siswa.*',
                'siswa.kelasid as kelas_siswa'
            )
            ->first();

        if (!$izin) {
            return back()->with(
                'error',
                'Data izin tidak ditemukan.'
            );
        }

        /*
         * Pastikan guru mengajar kelas siswa tersebut.
         */

        if (!$this->guruMengajarKelas($guru->id, $izin->kelas_siswa)) {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui izin siswa ini.');
        }

        if ($izin->status !== 'menunggu') {
            return back()->with(
                'error',
                'Pengajuan izin ini sudah diproses sebelumnya.'
            );
        }

        DB::table('izin_siswa')
            ->where('id', $id)
            ->update([
                'status' => 'disetujui',
                'disetujui_oleh' => $guru->id,
                'waktudisetuju' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        return back()->with(
            'success',
            'Pengajuan izin berhasil disetujui.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GURU MENOLAK IZIN
    |--------------------------------------------------------------------------
    */

    public function tolak(Request $request, $id)
    {
        $user = $this->cekLogin();

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        if (strtolower(trim($user->role ?? '')) !== 'guru') {
            abort(403, 'Hanya guru yang dapat menolak izin.');
        }

        $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);

        $guru = $this->ambilGuruDariUser($user);

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        $izin = DB::table('izin_siswa')
            ->join('siswa', 'izin_siswa.siswaid', '=', 'siswa.id')
            ->where('izin_siswa.id', $id)
            ->select(
                'izin_siswa.*',
                'siswa.kelasid as kelas_siswa'
            )
            ->first();

        if (!$izin) {
            return back()->with(
                'error',
                'Data izin tidak ditemukan.'
            );
        }

        /*
         * Pastikan guru mengajar kelas siswa tersebut.
         */

        if (!$this->guruMengajarKelas($guru->id, $izin->kelas_siswa)) {
            abort(403, 'Anda tidak memiliki akses untuk menolak izin siswa ini.');
        }

        if ($izin->status !== 'menunggu') {
            return back()->with(
                'error',
                'Pengajuan izin ini sudah diproses sebelumnya.'
            );
        }

        DB::table('izin_siswa')
            ->where('id', $id)
            ->update([
                'status' => 'ditolak',
                'disetujui_oleh' => $guru->id,
                'waktudisetuju' => Carbon::now(),
                'catatanbk' => $request->catatan,
                'updated_at' => Carbon::now(),
            ]);

        return back()->with(
            'success',
            'Pengajuan izin berhasil ditolak.'
        );
    }
}