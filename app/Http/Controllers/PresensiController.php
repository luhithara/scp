<?php

namespace App\Http\Controllers;

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

        if (!$user || strtolower(trim($user->role ?? '')) !== 'guru') {
            abort(403, 'Akses hanya untuk Guru.');
        }

        $guru = DB::table('guru')
            ->where('kodeguru', $user->kodepegawai)
            ->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        $tingkatList = DB::table('jadwal')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->where('jadwal.guruid', $guru->id)
            ->whereNotNull('kelas.tingkat')
            ->select('kelas.tingkat')
            ->distinct()
            ->orderBy('kelas.tingkat')
            ->pluck('tingkat');

        $jurusanList = DB::table('jadwal')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->where('jadwal.guruid', $guru->id)
            ->whereNotNull('kelas.jurusanid')
            ->select('jurusan.id', 'jurusan.namajurusan')
            ->distinct()
            ->orderBy('jurusan.namajurusan')
            ->get();

        $mapelList = DB::table('jadwal')
            ->join('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->where('jadwal.guruid', $guru->id)
            ->whereNotNull('mapel.namamapel')
            ->where('mapel.namamapel', '!=', '')
            ->select('mapel.namamapel')
            ->distinct()
            ->orderBy('mapel.namamapel')
            ->pluck('namamapel');

        $hariList = DB::table('jadwal')
            ->where('guruid', $guru->id)
            ->whereNotNull('hari')
            ->select('hari')
            ->distinct()
            ->orderByRaw("
                CASE LOWER(hari)
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    WHEN 'sabtu' THEN 6
                    ELSE 7
                END
            ")
            ->pluck('hari');

        $jamList = DB::table('jadwal')
            ->where('guruid', $guru->id)
            ->select('jamMulai', 'jamSelesai')
            ->distinct()
            ->orderBy('jamMulai')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | QUERY JADWAL
        |--------------------------------------------------------------------------
        */

        $query = DB::table('jadwal')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->where('jadwal.guruid', $guru->id);

        if ($request->filled('tingkat')) {
            $query->where('kelas.tingkat', $request->tingkat);
        }

        if ($request->filled('jurusanid')) {
            $query->where('kelas.jurusanid', $request->jurusanid);
        }

        if ($request->filled('namamapel')) {
            $query->where('mapel.namamapel', $request->namamapel);
        }

        if ($request->filled('hari')) {
            $query->where('jadwal.hari', $request->hari);
        }

        if ($request->filled('jamMulai')) {
            $query->where('jadwal.jamMulai', $request->jamMulai);
        }

        if ($request->filled('jamSelesai')) {
            $query->where('jadwal.jamSelesai', $request->jamSelesai);
        }

        $jumlahSiswaPerKelas = DB::table('siswa')
            ->select(
                'kelasid',
                DB::raw('COUNT(*) as jumlah_siswa')
            )
            ->groupBy('kelasid');

        $jadwal = $query
            ->leftJoinSub(
                $jumlahSiswaPerKelas,
                'jumlah_siswa_kelas',
                function ($join) {
                    $join->on(
                        'jadwal.kelasid',
                        '=',
                        'jumlah_siswa_kelas.kelasid'
                    );
                }
            )
            ->select(
                'jadwal.*',
                'mapel.namamapel',
                'kelas.namakelas',
                'kelas.tingkat',
                'kelas.jurusanid',
                'jurusan.namajurusan',
                DB::raw('COALESCE(jumlah_siswa_kelas.jumlah_siswa, 0) as jumlah_siswa')
            )
            ->orderByRaw("
                CASE LOWER(jadwal.hari)
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    WHEN 'sabtu' THEN 6
                    ELSE 7
                END
            ")
            ->orderBy('jadwal.jamMulai')
            ->get();

        $tanggalHariIni = now()->toDateString();

        $rekapPresensi = DB::table('presensi')
            ->whereIn('jadwalid', $jadwal->pluck('id'))
            ->where('tanggal_presensi', $tanggalHariIni)
            ->select(
                'jadwalid',
                DB::raw("SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as jumlah_hadir"),
                DB::raw("SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as jumlah_sakit"),
                DB::raw("SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as jumlah_izin"),
                DB::raw("SUM(CASE WHEN status = 'alpa' THEN 1 ELSE 0 END) as jumlah_alpa"),
                DB::raw('COUNT(*) as jumlah_terisi')
            )
            ->groupBy('jadwalid')
            ->get()
            ->keyBy('jadwalid');

        $tingkat = $request->tingkat;
        $jurusan = $request->jurusanid;
        $namamapel = $request->namamapel;
        $hari = $request->hari;
        $jamMulai = $request->jamMulai;
        $jamSelesai = $request->jamSelesai;

        return view('presensi', compact(
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
            'jadwal',
            'rekapPresensi',
            'tanggalHariIni'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM PRESENSI SISWA
    |--------------------------------------------------------------------------
    */

    public function create($jadwalid)
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

        $jadwal = DB::table('jadwal')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->where('jadwal.id', $jadwalid)
            ->where('jadwal.guruid', $guru->id)
            ->select(
                'jadwal.*',
                'mapel.namamapel',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.namajurusan'
            )
            ->first();

        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan.');
        }

        $siswa = collect();

        if (!empty($jadwal->kelasid)) {
            $siswa = DB::table('siswa')
                ->where('kelasid', $jadwal->kelasid)
                ->orderBy('nama')
                ->get();
        }

        $tanggalHariIni = now()->toDateString();

        $presensi = DB::table('presensi')
            ->where('jadwalid', $jadwal->id)
            ->where('tanggal_presensi', $tanggalHariIni)
            ->get()
            ->keyBy('siswaid');

        return view('presensi_form', compact(
            'user',
            'guru',
            'jadwal',
            'siswa',
            'presensi',
            'tanggalHariIni'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN DAN UBAH PRESENSI
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, $jadwalid)
    {
        $user = Auth::user();

        if (!$user || strtolower(trim($user->role ?? '')) !== 'guru') {
            abort(403, 'Akses hanya untuk Guru.');
        }

        $tanggalHariIni = now()->toDateString();

        $validated = $request->validate([
            'tanggal_presensi' => [
                'required',
                'date',
                'date_equals:' . $tanggalHariIni,
            ],
            'status' => [
                'required',
                'array',
                'min:1'
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

        if ($validated['tanggal_presensi'] !== now()->toDateString()) {
            return back()->withErrors([
                'tanggal_presensi' => 'Waktu pengisian presensi sudah berakhir.'
            ]);
        }

        $guru = DB::table('guru')
            ->where('kodeguru', $user->kodepegawai)
            ->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        $jadwal = DB::table('jadwal')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->where('jadwal.id', $jadwalid)
            ->where('jadwal.guruid', $guru->id)
            ->select('jadwal.*', 'mapel.namamapel')
            ->first();

        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan atau bukan milik Guru.');
        }

        if (empty($jadwal->kelasid)) {
            return back()->withErrors([
                'jadwal' => 'Jadwal ini belum memiliki kelas.'
            ])->withInput();
        }

        $statusInput = $validated['status'];
        $siswaIds = array_keys($statusInput);

        $siswaValid = DB::table('siswa')
            ->where('kelasid', $jadwal->kelasid)
            ->whereIn('id', $siswaIds)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        $siswaInput = array_map('strval', $siswaIds);

        if (
            count($siswaValid) !== count($siswaInput) ||
            count(array_unique($siswaInput)) !== count($siswaInput)
        ) {
            return back()->withErrors([
                'status' => 'Terdapat siswa yang tidak sesuai dengan kelas jadwal.'
            ])->withInput();
        }

        $waktuSekarang = now();
        $jumlahTersimpan = 0;

        DB::transaction(function () use (
            $statusInput,
            $request,
            $jadwal,
            $tanggalHariIni,
            $waktuSekarang,
            &$jumlahTersimpan,
            $guru,
            $user
        ) {
            foreach ($statusInput as $siswaid => $status) {

                $keterangan = $request->input(
                    'keterangan.' . $siswaid
                );

                $dataPresensi = [
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'waktu' => $waktuSekarang,
                    'updated_at' => $waktuSekarang,
                ];

                $presensiLama = DB::table('presensi')
                    ->where('siswaid', $siswaid)
                    ->where('jadwalid', $jadwal->id)
                    ->where('tanggal_presensi', $tanggalHariIni)
                    ->first();

                if ($presensiLama) {
                    DB::table('presensi')
                        ->where('id', $presensiLama->id)
                        ->update($dataPresensi);
                } else {
                    DB::table('presensi')->insert([
                        'siswaid' => $siswaid,
                        'jadwalid' => $jadwal->id,
                        'tanggal_presensi' => $tanggalHariIni,
                        'status' => $status,
                        'keterangan' => $keterangan,
                        'waktu' => $waktuSekarang,
                        'created_at' => $waktuSekarang,
                        'updated_at' => $waktuSekarang,
                    ]);
                }

                $jumlahTersimpan++;
            }

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
                        'created_at' => $waktuSekarang,
                        'updated_at' => $waktuSekarang,
                    ]);
                }
            }
        });

        return redirect()
            ->route('presensi')
            ->with('success', 'Presensi berhasil disimpan.');
    }
}