<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CEK AKSES GURU
    |--------------------------------------------------------------------------
    */

    private function ambilGuru()
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

        return $guru;
    }

    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL NAMA HARI SEKARANG
    |--------------------------------------------------------------------------
    */

    private function hariIni()
    {
        $daftarHari = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'minggu',
        ];

        return $daftarHari[now()->dayOfWeekIso];
    }

    /*
    |--------------------------------------------------------------------------
    | KONVERSI JENIS IZIN KE STATUS PRESENSI
    |--------------------------------------------------------------------------
    */

    private function statusDariIzin($jenis)
    {
        $jenis = strtolower(trim($jenis ?? ''));

        return $jenis === 'sakit' ? 'sakit' : 'izin';
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL IZIN SISWA YANG SUDAH DISETUJUI
    |--------------------------------------------------------------------------
    */

    private function ambilIzinDisetujui($jadwalid, $kelasid, $tanggal)
    {
        $siswaIds = DB::table('siswa')
            ->where('kelasid', $kelasid)
            ->pluck('id');

        if ($siswaIds->isEmpty()) {
            return collect();
        }

        $izin = DB::table('izin_siswa')
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'disetujui')
            ->whereIn('jenis', ['izin', 'sakit'])
            ->whereIn('siswaid', $siswaIds)
            ->where(function ($query) use ($jadwalid) {
                $query->whereNull('jadwalid')
                    ->orWhere('jadwalid', $jadwalid);
            })
            ->orderByRaw(
                'CASE WHEN jadwalid = ? THEN 1 ELSE 0 END DESC',
                [$jadwalid]
            )
            ->orderByDesc('id')
            ->get();

        return $izin->unique('siswaid')->keyBy('siswaid');
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN DAFTAR JADWAL PRESENSI GURU
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = $this->ambilGuru();

        $hariIni = $this->hariIni();

        $tanggalHariIni = now()->toDateString();

        /*
         * Daftar tingkat.
         */

        $tingkatList = DB::table('jadwal')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->where('jadwal.guruid', $guru->id)
            ->whereNotNull('kelas.tingkat')
            ->select('kelas.tingkat')
            ->distinct()
            ->orderBy('kelas.tingkat')
            ->pluck('tingkat');

        /*
         * Daftar jurusan.
         */

        $jurusanList = DB::table('jadwal')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->where('jadwal.guruid', $guru->id)
            ->whereNotNull('kelas.jurusanid')
            ->select('jurusan.id', 'jurusan.namajurusan')
            ->distinct()
            ->orderBy('jurusan.namajurusan')
            ->get();

        /*
         * Daftar mapel.
         * Mengambil jadwal.namamapel jika mapelid NULL.
         */

        $mapelList = DB::table('jadwal')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->where('jadwal.guruid', $guru->id)
            ->whereRaw(
                "COALESCE(NULLIF(mapel.namamapel, ''), NULLIF(jadwal.namamapel, '')) IS NOT NULL"
            )
            ->selectRaw(
                "COALESCE(NULLIF(mapel.namamapel, ''), NULLIF(jadwal.namamapel, '')) AS namamapel"
            )
            ->distinct()
            ->orderBy('namamapel')
            ->pluck('namamapel');

        /*
         * Daftar hari.
         */

        $hariList = DB::table('jadwal')
            ->where('guruid', $guru->id)
            ->whereNotNull('hari')
            ->select('hari')
            ->distinct()
            ->orderByRaw("
                CASE LOWER(TRIM(hari))
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    WHEN 'sabtu' THEN 6
                    WHEN 'minggu' THEN 7
                    ELSE 8
                END
            ")
            ->pluck('hari');

        /*
         * Daftar jam.
         */

        $jamList = DB::table('jadwal')
            ->where('guruid', $guru->id)
            ->select('jamMulai', 'jamSelesai')
            ->distinct()
            ->orderBy('jamMulai')
            ->get();

        /*
         * Query utama jadwal.
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
            $query->whereRaw(
                "COALESCE(NULLIF(mapel.namamapel, ''), NULLIF(jadwal.namamapel, '')) = ?",
                [$request->namamapel]
            );
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

        /*
         * Hitung jumlah siswa per kelas.
         */

        $jumlahSiswaPerKelas = DB::table('siswa')
            ->select(
                'kelasid',
                DB::raw('COUNT(*) as jumlah_siswa')
            )
            ->groupBy('kelasid');

        /*
         * Ambil seluruh jadwal guru.
         * Jadwal hari lain tetap ditampilkan, tetapi tombolnya dikunci di Blade.
         */

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
                DB::raw(
                    "COALESCE(NULLIF(mapel.namamapel, ''), NULLIF(jadwal.namamapel, '')) AS namamapel"
                ),
                'kelas.namakelas',
                'kelas.tingkat',
                'kelas.jurusanid',
                'jurusan.namajurusan',
                DB::raw(
                    'COALESCE(jumlah_siswa_kelas.jumlah_siswa, 0) as jumlah_siswa'
                )
            )
            ->orderByRaw("
                CASE LOWER(TRIM(jadwal.hari))
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    WHEN 'sabtu' THEN 6
                    WHEN 'minggu' THEN 7
                    ELSE 8
                END
            ")
            ->orderBy('jadwal.jamMulai')
            ->get();

        /*
         * Rekap presensi hari ini.
         */

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
            'tanggalHariIni',
            'hariIni'
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
        $guru = $this->ambilGuru();

        $hariIni = $this->hariIni();

        /*
         * Hanya jadwal hari ini yang bisa dibuka.
         */

        $jadwal = DB::table('jadwal')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->where('jadwal.id', $jadwalid)
            ->where('jadwal.guruid', $guru->id)
            ->whereRaw(
                'LOWER(TRIM(jadwal.hari)) = ?',
                [$hariIni]
            )
            ->select(
                'jadwal.*',
                DB::raw(
                    "COALESCE(NULLIF(mapel.namamapel, ''), NULLIF(jadwal.namamapel, '')) AS namamapel"
                ),
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.namajurusan'
            )
            ->first();

        if (!$jadwal) {
            abort(403, 'Presensi hanya dapat diisi sesuai jadwal hari ini.');
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

        $izinDisetujui = $this->ambilIzinDisetujui(
            $jadwal->id,
            $jadwal->kelasid,
            $tanggalHariIni
        );

        /*
         * Masukkan izin yang disetujui ke data presensi.
         */

        foreach ($izinDisetujui as $siswaid => $izin) {
            $statusIzin = $this->statusDariIzin($izin->jenis);

            $presensi->put($siswaid, (object) [
                'id' => null,
                'siswaid' => $siswaid,
                'jadwalid' => $jadwal->id,
                'tanggal_presensi' => $tanggalHariIni,
                'status' => $statusIzin,
                'keterangan' => $izin->alasan ?: ucfirst($statusIzin),
                'waktu' => null,
            ]);
        }

        return view('presensi_form', compact(
            'user',
            'guru',
            'jadwal',
            'siswa',
            'presensi',
            'izinDisetujui',
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
        $guru = $this->ambilGuru();

        $tanggalHariIni = now()->toDateString();

        $validated = $request->validate([
            'tanggal_presensi' => [
                'required',
                'date',
                'date_equals:' . $tanggalHariIni,
            ],
            'status' => [
                'nullable',
                'array',
            ],
            'status.*' => [
                'nullable',
                'in:hadir,sakit,izin,alpa',
            ],
            'keterangan' => [
                'nullable',
                'array',
            ],
            'keterangan.*' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        if ($validated['tanggal_presensi'] !== now()->toDateString()) {
            return back()->withErrors([
                'tanggal_presensi' => 'Tanggal presensi tidak sesuai dengan hari ini.',
            ]);
        }

        /*
         * Hanya jadwal hari ini yang boleh disimpan.
         */

        $jadwal = DB::table('jadwal')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->where('jadwal.id', $jadwalid)
            ->where('jadwal.guruid', $guru->id)
            ->whereRaw(
                'LOWER(TRIM(jadwal.hari)) = ?',
                [$this->hariIni()]
            )
            ->select(
                'jadwal.*',
                DB::raw(
                    "COALESCE(NULLIF(mapel.namamapel, ''), NULLIF(jadwal.namamapel, '')) AS namamapel"
                ),
                'kelas.tingkat'
            )
            ->first();

        if (!$jadwal) {
            abort(403, 'Presensi hanya dapat disimpan pada jadwal hari ini.');
        }

        if (empty($jadwal->kelasid)) {
            return back()->withErrors([
                'jadwal' => 'Jadwal ini belum memiliki kelas.',
            ])->withInput();
        }

        $statusInput = $validated['status'] ?? [];

        /*
         * Ambil seluruh siswa di kelas jadwal.
         */

        $daftarSiswa = DB::table('siswa')
            ->where('kelasid', $jadwal->kelasid)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        /*
         * Validasi agar tidak ada siswa dari kelas lain.
         */

        $siswaInput = array_map(
            'strval',
            array_keys($statusInput)
        );

        foreach ($siswaInput as $idSiswa) {
            if (!in_array($idSiswa, $daftarSiswa, true)) {
                return back()->withErrors([
                    'status' => 'Terdapat siswa yang tidak sesuai dengan kelas jadwal.',
                ])->withInput();
            }
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
            $user,
            $daftarSiswa
        ) {
            /*
             * Cek ulang izin yang sudah disetujui.
             */

            $izinDisetujui = $this->ambilIzinDisetujui(
                $jadwal->id,
                $jadwal->kelasid,
                $tanggalHariIni
            );

            foreach ($daftarSiswa as $siswaid) {
                $izin = $izinDisetujui->get($siswaid);

                if ($izin) {
                    /*
                     * Izin disetujui tidak boleh diubah oleh guru.
                     */

                    $status = $this->statusDariIzin($izin->jenis);
                    $keterangan = trim($izin->alasan ?? '');

                    if ($keterangan === '') {
                        $keterangan = ucfirst($status);
                    }
                } else {
                    if (!isset($statusInput[$siswaid])) {
                        continue;
                    }

                    $status = $statusInput[$siswaid];

                    if (!$status) {
                        continue;
                    }

                    $keterangan = $request->input(
                        'keterangan.' . $siswaid
                    );
                }

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

            /*
             * Kirim notifikasi kepada WAKA.
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