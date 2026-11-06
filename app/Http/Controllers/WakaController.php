<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WakaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CEK AKSES WAKA
    |--------------------------------------------------------------------------
    */

    private function cekAksesWaka()
    {
        $user = auth()->user();

        if (!$user || strtolower(trim($user->role ?? '')) !== 'waka') {
            abort(403, 'Akses hanya untuk Wakil Kepala Sekolah.');
        }

        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY JADWAL DASHBOARD
    |--------------------------------------------------------------------------
    */

    private function queryJadwalDashboard()
    {
        return DB::table('jadwal')
            ->leftJoin('guru', 'jadwal.guruid', '=', 'guru.id')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->select(
                'jadwal.*',
                'guru.nama as namaguru',
                'kelas.namakelas',
                DB::raw('COALESCE(jadwal.tingkat, kelas.tingkat) as tingkat'),
                DB::raw('COALESCE(jadwal.putaranblok, jadwal.blok) as putaranblok'),
                DB::raw('COALESCE(mapel.namamapel, jadwal.namamapel) as namamapel')
            )
            ->orderBy('jadwal.hari')
            ->orderBy('jadwal.jamMulai');
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD WAKA
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $this->cekAksesWaka();

        $hariIni = now()->toDateString();

        $totalSiswa = DB::table('siswa')->count();
        $totalGuru = DB::table('guru')->count();
        $totalKelas = DB::table('kelas')->count();
        $totalJadwal = DB::table('jadwal')->count();
        $totalJurnal = DB::table('jurnal_mengajar')->count();

        $presensiHariIni = DB::table('presensi')
            ->whereDate('waktu', $hariIni);

        $hadirHariIni = (clone $presensiHariIni)
            ->where('status', 'hadir')
            ->count();

        $sakitHariIni = (clone $presensiHariIni)
            ->where('status', 'sakit')
            ->count();

        $izinHariIni = (clone $presensiHariIni)
            ->where('status', 'izin')
            ->count();

        $alpaHariIni = (clone $presensiHariIni)
            ->where('status', 'alpa')
            ->count();

        $totalPresensiHariIni = (clone $presensiHariIni)->count();

        $jurnalHariIni = DB::table('jurnal_mengajar')
            ->whereDate('tanggal', $hariIni)
            ->count();

        $jadwal = $this->queryJadwalDashboard()->get();

        $jadwalTingkat10 = $jadwal->where('tingkat', 10)->values();
        $jadwalTingkat11 = $jadwal->where('tingkat', 11)->values();
        $jadwalTingkat12 = $jadwal->where('tingkat', 12)->values();

        $jadwalPutaran1 = $jadwal->where('putaranblok', 1)->values();
        $jadwalPutaran2 = $jadwal->where('putaranblok', 2)->values();

        return view('waka.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'totalJadwal',
            'totalJurnal',
            'hadirHariIni',
            'sakitHariIni',
            'izinHariIni',
            'alpaHariIni',
            'totalPresensiHariIni',
            'jurnalHariIni',
            'jadwal',
            'jadwalTingkat10',
            'jadwalTingkat11',
            'jadwalTingkat12',
            'jadwalPutaran1',
            'jadwalPutaran2'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | DATA GURU
    |--------------------------------------------------------------------------
    */

    public function guru(Request $request)
    {
        $this->cekAksesWaka();

        // PENCARIAN GURU
        $kataKunci = trim($request->input('cari', ''));

        $query = DB::table('guru');

        if ($kataKunci !== '') {
            $query->where(function ($q) use ($kataKunci) {
                $q->where('kodeguru', 'like', "%{$kataKunci}%")
                    ->orWhere('nama', 'like', "%{$kataKunci}%")
                    ->orWhere('nip', 'like', "%{$kataKunci}%");
            });
        }

        // DATA GURU DENGAN PAGINATION
        $dataGuru = $query
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        // DAFTAR MAPEL MASING-MASING GURU
        foreach ($dataGuru as $guru) {
            $guru->daftar_mapel = DB::table('guru_mapel')
                ->join(
                    'mapel',
                    'guru_mapel.mapelid',
                    '=',
                    'mapel.id'
                )
                ->where('guru_mapel.guruid', $guru->id)
                ->pluck('mapel.namamapel');
        }

        // STATISTIK SELURUH GURU
        $totalGuru = DB::table('guru')->count();

        // GURU YANG SUDAH MEMILIKI MAPEL
        $guruDenganMapel = DB::table('guru_mapel')
            ->join('guru', 'guru_mapel.guruid', '=', 'guru.id')
            ->join('mapel', 'guru_mapel.mapelid', '=', 'mapel.id')
            ->distinct('guru_mapel.guruid')
            ->count('guru_mapel.guruid');

        // GURU YANG BELUM MEMILIKI MAPEL
        $guruTanpaMapel = max(0, $totalGuru - $guruDenganMapel);

        // TOTAL MAPEL YANG MEMILIKI GURU
        $totalMapelDiampu = DB::table('guru_mapel')
            ->join('guru', 'guru_mapel.guruid', '=', 'guru.id')
            ->join('mapel', 'guru_mapel.mapelid', '=', 'mapel.id')
            ->distinct('mapel.id')
            ->count('mapel.id');

        // SEBARAN GURU BERDASARKAN MAPEL
        $sebaranMapel = DB::table('guru_mapel')
            ->join('guru', 'guru_mapel.guruid', '=', 'guru.id')
            ->join('mapel', 'guru_mapel.mapelid', '=', 'mapel.id')
            ->select(
                'mapel.id',
                'mapel.namamapel',
                DB::raw('COUNT(DISTINCT guru_mapel.guruid) as total_guru')
            )
            ->groupBy('mapel.id', 'mapel.namamapel')
            ->orderByDesc('total_guru')
            ->orderBy('mapel.namamapel')
            ->get();

        return view('waka.guru', compact(
            'dataGuru',
            'kataKunci',
            'totalGuru',
            'guruDenganMapel',
            'guruTanpaMapel',
            'totalMapelDiampu',
            'sebaranMapel'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | DATA SISWA
    |--------------------------------------------------------------------------
    */

    public function siswa(Request $request)
    {
        $this->cekAksesWaka();

        $query = DB::table('siswa')
            ->leftJoin('kelas', 'siswa.idkelas', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.idjurusan', '=', 'jurusan.id')
            ->select(
                'siswa.*',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.namajurusan'
            );

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('siswa.nama', 'like', "%{$search}%")
                    ->orWhere('siswa.nisn', 'like', "%{$search}%")
                    ->orWhere('kelas.namakelas', 'like', "%{$search}%")
                    ->orWhere('jurusan.namajurusan', 'like', "%{$search}%");
            });
        }

        $dataSiswa = $query
            ->orderBy('siswa.nama')
            ->get();

        return view('waka.siswa', compact('dataSiswa'));
    }

    /*
    |--------------------------------------------------------------------------
    | DATA KELAS
    |--------------------------------------------------------------------------
    */

    public function kelas()
    {
        $this->cekAksesWaka();

        return view('waka.kelas');
    }

    /*
    |--------------------------------------------------------------------------
    | KELAS BERDASARKAN ANGKATAN
    |--------------------------------------------------------------------------
    */

    public function kelasAngkatan($tingkat)
    {
        $this->cekAksesWaka();

        if (!in_array((int) $tingkat, [10, 11, 12])) {
            abort(404);
        }

        $dataJurusan = DB::table('jurusan')
            ->leftJoin('kelas', 'jurusan.id', '=', 'kelas.idjurusan')
            ->select(
                'jurusan.*',
                DB::raw('COUNT(kelas.id) as total_kelas')
            )
            ->where('kelas.tingkat', $tingkat)
            ->groupBy('jurusan.id')
            ->orderBy('jurusan.namajurusan')
            ->get();

        return view('waka.kelas-angkatan', compact(
            'dataJurusan',
            'tingkat'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | KELAS BERDASARKAN JURUSAN
    |--------------------------------------------------------------------------
    */

    public function kelasJurusan($tingkat, $jurusanId)
    {
        $this->cekAksesWaka();

        if (!in_array((int) $tingkat, [10, 11, 12])) {
            abort(404);
        }

        $jurusan = DB::table('jurusan')
            ->where('id', $jurusanId)
            ->first();

        if (!$jurusan) {
            abort(404);
        }

        $dataKelas = DB::table('kelas')
            ->where('tingkat', $tingkat)
            ->where('idjurusan', $jurusanId)
            ->orderBy('namakelas')
            ->get();

        foreach ($dataKelas as $kelas) {
            $kelas->total_siswa = DB::table('siswa')
                ->where('idkelas', $kelas->id)
                ->count();
        }

        return view('waka.kelas-jurusan', compact(
            'dataKelas',
            'jurusan',
            'tingkat'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | DATA SISWA PER KELAS
    |--------------------------------------------------------------------------
    */

    public function siswaKelas(Request $request, $id)
    {
        $this->cekAksesWaka();

        $kelas = DB::table('kelas')
            ->leftJoin('jurusan', 'kelas.idjurusan', '=', 'jurusan.id')
            ->select(
                'kelas.*',
                'jurusan.namajurusan'
            )
            ->where('kelas.id', $id)
            ->first();

        if (!$kelas) {
            abort(404);
        }

        $query = DB::table('siswa')
            ->where('idkelas', $id);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $dataSiswa = $query
            ->orderBy('nama')
            ->get();

        return view('waka.kelas-siswa', compact(
            'kelas',
            'dataSiswa'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | JURNAL MENGAJAR WAKA
    |--------------------------------------------------------------------------
    */

    public function jurnal(Request $request)
    {
        $this->cekAksesWaka();

        $query = DB::table('jurnal_mengajar')
            ->leftJoin(
                'guru',
                'jurnal_mengajar.guruid',
                '=',
                'guru.id'
            )
            ->select(
                'jurnal_mengajar.*',
                'guru.nama as namaguru',
                'guru.kodeguru'
            );

        // PENCARIAN NAMA GURU DAN ISI JURNAL
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('guru.nama', 'like', "%{$search}%")
                    ->orWhere('jurnal_mengajar.materi', 'like', "%{$search}%")
                    ->orWhere('jurnal_mengajar.kegiatan', 'like', "%{$search}%")
                    ->orWhere('jurnal_mengajar.catatan', 'like', "%{$search}%");
            });
        }

        // FILTER TANGGAL
        if ($request->filled('tanggal')) {
            $query->whereDate(
                'jurnal_mengajar.tanggal',
                $request->tanggal
            );
        }

        // PAGINATION 10 DATA TERBARU
        $dataJurnal = $query
            ->orderByDesc('jurnal_mengajar.tanggal')
            ->orderByDesc('jurnal_mengajar.id')
            ->paginate(10)
            ->withQueryString();

        return view('waka.jurnal', compact('dataJurnal'));
    }
}