<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN PRESENSI GURU
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = Auth::user();

        // Hanya guru
        if (!$user || $user->role !== 'guru') {
            abort(403);
        }

        // DATA GURU
        $guru = DB::table('guru')
            ->where('kodeguru', $user->kodepegawai)
            ->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        // DAFTAR TINGKAT
        $tingkatList = DB::table('jadwal')
            ->where('guruid', $guru->id)
            ->whereNotNull('tingkat')
            ->select('tingkat')
            ->distinct()
            ->orderBy('tingkat')
            ->pluck('tingkat');

        // DAFTAR JURUSAN
        $jurusanList = DB::table('jadwal')
            ->join('jurusan', 'jadwal.jurusanid', '=', 'jurusan.id')
            ->where('jadwal.guruid', $guru->id)
            ->whereNotNull('jadwal.jurusanid')
            ->select('jurusan.id', 'jurusan.namajurusan')
            ->distinct()
            ->orderBy('jurusan.namajurusan')
            ->get();

        // DAFTAR MAPEL
        $mapelList = DB::table('jadwal')
            ->where('guruid', $guru->id)
            ->whereNotNull('namamapel')
            ->where('namamapel', '!=', '')
            ->select('namamapel')
            ->distinct()
            ->orderBy('namamapel')
            ->pluck('namamapel');

        // DAFTAR HARI
        $hariList = DB::table('jadwal')
            ->where('guruid', $guru->id)
            ->whereNotNull('hari')
            ->select('hari')
            ->distinct()
            ->orderByRaw("
                CASE hari
                    WHEN 'Senin' THEN 1
                    WHEN 'Selasa' THEN 2
                    WHEN 'Rabu' THEN 3
                    WHEN 'Kamis' THEN 4
                    WHEN 'Jumat' THEN 5
                    WHEN 'Sabtu' THEN 6
                    ELSE 7
                END
            ")
            ->pluck('hari');

        // DAFTAR JAM
        $jamList = DB::table('jadwal')
            ->where('guruid', $guru->id)
            ->select('jamMulai', 'jamSelesai')
            ->distinct()
            ->orderBy('jamMulai')
            ->get();

        // QUERY JADWAL
        $query = DB::table('jadwal')
            ->leftJoin('jurusan', 'jadwal.jurusanid', '=', 'jurusan.id')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->where('jadwal.guruid', $guru->id);

        // FILTER TINGKAT
        if ($request->filled('tingkat')) {
            $query->where('jadwal.tingkat', $request->tingkat);
        }

        // FILTER JURUSAN
        if ($request->filled('jurusanid')) {
            $query->where('jadwal.jurusanid', $request->jurusanid);
        }

        // FILTER MAPEL
        if ($request->filled('namamapel')) {
            $query->where('jadwal.namamapel', $request->namamapel);
        }

        // FILTER HARI
        if ($request->filled('hari')) {
            $query->where('jadwal.hari', $request->hari);
        }

        // FILTER JAM MULAI
        if ($request->filled('jamMulai')) {
            $query->where('jadwal.jamMulai', $request->jamMulai);
        }

        // FILTER JAM SELESAI
        if ($request->filled('jamSelesai')) {
            $query->where('jadwal.jamSelesai', $request->jamSelesai);
        }

        // AMBIL JADWAL
        $jadwal = $query
            ->select(
                'jadwal.*',
                'jurusan.namajurusan',
                'kelas.namakelas'
            )
            ->orderByRaw("
                CASE jadwal.hari
                    WHEN 'Senin' THEN 1
                    WHEN 'Selasa' THEN 2
                    WHEN 'Rabu' THEN 3
                    WHEN 'Kamis' THEN 4
                    WHEN 'Jumat' THEN 5
                    WHEN 'Sabtu' THEN 6
                    ELSE 7
                END
            ")
            ->orderBy('jadwal.jamMulai')
            ->get();

        // VARIABEL BLADE
        $tingkat = $request->tingkat;
        $jurusan = $request->jurusanid;
        $namamapel = $request->namamapel;
        $hari = $request->hari;
        $jamMulai = $request->jamMulai;
        $jamSelesai = $request->jamSelesai;

        // RETURN VIEW
        return view(
            'presensi',
            compact(
                'user',
                'guru',
                'tingkat',
                'tingkatList',
                'jurusan',
                'jurusanList',
                'namamapel',
                'mapelList',
                'hari',
                'hariList',
                'jamMulai',
                'jamSelesai',
                'jamList',
                'jadwal'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM PRESENSI SISWA
    |--------------------------------------------------------------------------
    */

    public function create($jadwalid)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'guru') {
            abort(403);
        }

        // DATA GURU
        $guru = DB::table('guru')
            ->where('kodeguru', $user->kodepegawai)
            ->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        // CARI JADWAL MILIK GURU
        $jadwal = DB::table('jadwal')
            ->leftJoin('jurusan', 'jadwal.jurusanid', '=', 'jurusan.id')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->where('jadwal.id', $jadwalid)
            ->where('jadwal.guruid', $guru->id)
            ->select(
                'jadwal.*',
                'jurusan.namajurusan',
                'kelas.namakelas'
            )
            ->first();

        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan.');
        }

        // AMBIL SISWA SESUAI KELAS
        $siswa = collect();

        if (!empty($jadwal->kelasid)) {
            $siswa = DB::table('siswa')
                ->where('kelasid', $jadwal->kelasid)
                ->orderBy('nama')
                ->get();
        }

        // PRESENSI HARI INI
        $presensi = DB::table('presensi')
            ->where('jadwalid', $jadwal->id)
            ->whereDate('waktu', now()->toDateString())
            ->get()
            ->keyBy('siswaid');

        // RETURN FORM
        return view(
            'presensi_form',
            compact(
                'user',
                'guru',
                'jadwal',
                'siswa',
                'presensi'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PRESENSI
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, $jadwalid)
    {
        // VALIDASI
        $request->validate([
            'status' => [
                'required',
                'array'
            ],
            'status.*' => [
                'required',
                'in:hadir,sakit,izin,alpa'
            ],
            'keterangan' => [
                'nullable',
                'array'
            ],
            'keterangan.*' => [
                'nullable',
                'string',
                'max:500'
            ],
        ]);

        // USER
        $user = Auth::user();

        if (!$user || $user->role !== 'guru') {
            abort(403);
        }

        // GURU
        $guru = DB::table('guru')
            ->where('kodeguru', $user->kodepegawai)
            ->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        // JADWAL
        $jadwal = DB::table('jadwal')
            ->where('id', $jadwalid)
            ->where('guruid', $guru->id)
            ->first();

        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan atau bukan milik guru.');
        }

        // CEK SISWA DALAM KELAS
        if (empty($jadwal->kelasid)) {
            return back()
                ->withErrors([
                    'jadwal' => 'Jadwal ini belum memiliki kelas.'
                ]);
        }

        // SIMPAN / UPDATE PRESENSI
        $jumlahTersimpan = 0;

        foreach ($request->status as $siswaid => $status) {

            // Pastikan siswa memang berada di kelas jadwal
            $siswa = DB::table('siswa')
                ->where('id', $siswaid)
                ->where('kelasid', $jadwal->kelasid)
                ->first();

            if (!$siswa) {
                continue;
            }

            // Update kalau sudah ada, insert kalau belum ada
            Presensi::updateOrCreate(
                [
                    'siswaid' => $siswaid,
                    'jadwalid' => $jadwal->id,
                ],
                [
                    'status' => $status,
                    'keterangan' => $request->keterangan[$siswaid] ?? null,
                    'waktu' => now(),
                ]
            );

            $jumlahTersimpan++;
        }

        /*
        |--------------------------------------------------------------------------
        | KIRIM NOTIFIKASI KE WAKA
        |--------------------------------------------------------------------------
        */

        if ($jumlahTersimpan > 0) {

            $wakaIds = DB::table('users')
                ->whereRaw('LOWER(role) = ?', ['waka'])
                ->pluck('id');

            foreach ($wakaIds as $wakaId) {
                DB::table('notifikasi')->insert([
                    'userid' => $wakaId,
                    'judul' => 'Presensi siswa diperbarui',
                    'pesan' => ($guru->nama ?? $user->name)
                        . ' mengisi presensi untuk '
                        . ($jadwal->namamapel ?? 'jadwal mengajar')
                        . ' pada kelas '
                        . ($jadwal->tingkat ?? '')
                        . '.',
                    'jenis' => 'presensi',
                    'url' => route('waka.dashboard'),
                    'sudahdibaca' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // KEMBALI
        return redirect()
            ->route('presensi')
            ->with('success', 'Presensi berhasil disimpan.');
    }
}