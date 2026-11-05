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

        if (!$user || strtolower($user->role ?? '') !== 'waka') {
            abort(403, 'Halaman ini hanya dapat diakses oleh Waka.');
        }

        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY JADWAL UNTUK DASHBOARD
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

        $hariIni = today()->toDateString();

        $jumlahSiswa = DB::table('siswa')->count();

        $jumlahGuru = DB::table('guru')->count();

        $jumlahKelas = DB::table('kelas')->count();

        $jumlahJadwal = DB::table('jadwal')->count();

        $jumlahJurnal = DB::table('jurnal_mengajar')->count();

        // PRESENSI DIHITUNG BERDASARKAN WAKTU PRESENSI
        $jumlahPresensi = DB::table('presensi')
            ->whereDate('waktu', $hariIni)
            ->count();

        // JURNAL DIHITUNG BERDASARKAN TANGGAL JURNAL
        $jumlahJurnalHariIni = DB::table('jurnal_mengajar')
            ->whereDate('tanggal', $hariIni)
            ->count();

        $jumlahHadir = DB::table('presensi')
            ->whereDate('waktu', $hariIni)
            ->where('status', 'hadir')
            ->count();

        $jumlahSakit = DB::table('presensi')
            ->whereDate('waktu', $hariIni)
            ->where('status', 'sakit')
            ->count();

        $jumlahIzinPresensi = DB::table('presensi')
            ->whereDate('waktu', $hariIni)
            ->where('status', 'izin')
            ->count();

        $jumlahAlpa = DB::table('presensi')
            ->whereDate('waktu', $hariIni)
            ->where('status', 'alpa')
            ->count();

        // JADWAL PER TINGKAT (tingkat diambil dari tabel kelas)
        $jadwalTingkat10 = $this->queryJadwalDashboard()
            ->where('kelas.tingkat', 10)
            ->get();

        $jadwalTingkat11 = $this->queryJadwalDashboard()
            ->where('kelas.tingkat', 11)
            ->get();

        $jadwalTingkat12 = $this->queryJadwalDashboard()
            ->where('kelas.tingkat', 12)
            ->get();

        // JADWAL PER PUTARAN (jika putaranblok kosong, pakai kolom blok)
        $jadwalPutaran1 = $this->queryJadwalDashboard()
            ->whereRaw('COALESCE(jadwal.putaranblok, jadwal.blok) = ?', [1])
            ->get();

        $jadwalPutaran2 = $this->queryJadwalDashboard()
            ->whereRaw('COALESCE(jadwal.putaranblok, jadwal.blok) = ?', [2])
            ->get();

        return view('waka.dashboard', compact(
            'jumlahSiswa',
            'jumlahGuru',
            'jumlahKelas',
            'jumlahJadwal',
            'jumlahJurnal',
            'jumlahPresensi',
            'jumlahJurnalHariIni',
            'jumlahHadir',
            'jumlahSakit',
            'jumlahIzinPresensi',
            'jumlahAlpa',
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

        $kataKunci = trim($request->input('cari', ''));

        $query = DB::table('guru')
            ->select(
                'guru.id',
                'guru.kodeguru',
                'guru.nama',
                'guru.nip',
                'guru.nomortelepon',
                'guru.alamat'
            );

        if ($kataKunci !== '') {
            $query->where(function ($q) use ($kataKunci) {
                $q->where('guru.kodeguru', 'like', '%' . $kataKunci . '%')
                    ->orWhere('guru.nama', 'like', '%' . $kataKunci . '%')
                    ->orWhere('guru.nip', 'like', '%' . $kataKunci . '%');
            });
        }

        $dataGuru = $query
            ->orderBy('guru.nama', 'asc')
            ->get();

        foreach ($dataGuru as $guru) {
            $guru->daftar_mapel = DB::table('guru_mapel')
                ->join('mapel', 'guru_mapel.mapelid', '=', 'mapel.id')
                ->where('guru_mapel.guruid', $guru->id)
                ->orderBy('mapel.namamapel', 'asc')
                ->pluck('mapel.namamapel')
                ->toArray();
        }

        $jumlahGuru = DB::table('guru')->count();

        return view('waka.guru', compact(
            'dataGuru',
            'jumlahGuru',
            'kataKunci'
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

        $kataKunci = trim($request->input('cari', ''));

        $query = DB::table('siswa')
            ->leftJoin('kelas', 'siswa.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->select(
                'siswa.*',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.namajurusan',
                'jurusan.kodejurusan'
            );

        if ($kataKunci !== '') {
            $query->where(function ($q) use ($kataKunci) {
                $q->where('siswa.nama', 'like', '%' . $kataKunci . '%')
                    ->orWhere('siswa.nisn', 'like', '%' . $kataKunci . '%')
                    ->orWhere('kelas.namakelas', 'like', '%' . $kataKunci . '%')
                    ->orWhere('jurusan.namajurusan', 'like', '%' . $kataKunci . '%');
            });
        }

        $dataSiswa = $query
            ->orderBy('siswa.nama', 'asc')
            ->get();

        $jumlahSiswa = DB::table('siswa')->count();

        return view('waka.siswa', compact(
            'dataSiswa',
            'jumlahSiswa',
            'kataKunci'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | DATA KELAS - HALAMAN AWAL
    |--------------------------------------------------------------------------
    */

    public function kelas(Request $request)
    {
        $this->cekAksesWaka();

        $daftarAngkatan = collect([
            (object) ['tingkat' => 10],
            (object) ['tingkat' => 11],
            (object) ['tingkat' => 12],
        ]);

        $jumlahKelas = DB::table('kelas')->count();

        $jumlahSiswa = DB::table('siswa')->count();

        return view('waka.kelas', compact(
            'daftarAngkatan',
            'jumlahKelas',
            'jumlahSiswa'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | DATA KELAS - PILIH ANGKATAN
    |--------------------------------------------------------------------------
    */

    public function kelasAngkatan($tingkat)
    {
        $this->cekAksesWaka();

        $tingkat = (int) $tingkat;

        if (!in_array($tingkat, [10, 11, 12])) {
            abort(404);
        }

        $daftarJurusan = DB::table('jurusan')
            ->join('kelas', 'kelas.jurusanid', '=', 'jurusan.id')
            ->where('kelas.tingkat', $tingkat)
            ->select(
                'jurusan.id',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->selectRaw('COUNT(kelas.id) as jumlah_kelas')
            ->groupBy(
                'jurusan.id',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->orderBy('jurusan.kodejurusan', 'asc')
            ->get();

        return view('waka.kelas-angkatan', compact(
            'tingkat',
            'daftarJurusan'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | DATA KELAS - PILIH JURUSAN
    |--------------------------------------------------------------------------
    */

    public function kelasJurusan($tingkat, $jurusanId)
    {
        $this->cekAksesWaka();

        $tingkat = (int) $tingkat;

        $jurusan = DB::table('jurusan')
            ->where('id', $jurusanId)
            ->first();

        if (!$jurusan) {
            abort(404, 'Jurusan tidak ditemukan.');
        }

        $dataKelas = DB::table('kelas')
            ->where('kelas.tingkat', $tingkat)
            ->where('kelas.jurusanid', $jurusanId)
            ->orderBy('kelas.namakelas', 'asc')
            ->get();

        foreach ($dataKelas as $kelas) {
            $kelas->jumlah_siswa = DB::table('siswa')
                ->where('kelasid', $kelas->id)
                ->count();
        }

        return view('waka.kelas-jurusan', compact(
            'tingkat',
            'jurusan',
            'dataKelas'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | LIHAT SISWA DALAM KELAS
    |--------------------------------------------------------------------------
    */

    public function siswaKelas(Request $request, $id)
    {
        $this->cekAksesWaka();

        $kelas = DB::table('kelas')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->select(
                'kelas.*',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->where('kelas.id', $id)
            ->first();

        if (!$kelas) {
            abort(404, 'Kelas tidak ditemukan.');
        }

        $kataKunci = trim($request->input('cari', ''));

        $query = DB::table('siswa')
            ->where('siswa.kelasid', $id)
            ->select(
                'siswa.id',
                'siswa.nisn',
                'siswa.nama',
                'siswa.jeniskelamin',
                'siswa.nomortelepon',
                'siswa.alamat'
            );

        if ($kataKunci !== '') {
            $query->where(function ($q) use ($kataKunci) {
                $q->where('siswa.nama', 'like', '%' . $kataKunci . '%')
                    ->orWhere('siswa.nisn', 'like', '%' . $kataKunci . '%');
            });
        }

        $dataSiswa = $query
            ->orderBy('siswa.nama', 'asc')
            ->get();

        $jumlahSiswa = DB::table('siswa')
            ->where('kelasid', $id)
            ->count();

        return view('waka.kelas-siswa', compact(
            'kelas',
            'dataSiswa',
            'jumlahSiswa',
            'kataKunci'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | JURNAL MENGAJAR
    |--------------------------------------------------------------------------
    */

    public function jurnal()
    {
        $this->cekAksesWaka();

        $dataJurnal = DB::table('jurnal_mengajar')
            ->leftJoin(
                'guru',
                'jurnal_mengajar.guruid',
                '=',
                'guru.id'
            )
            ->select(
                'jurnal_mengajar.*',
                'guru.nama as namaguru'
            )
            ->orderBy('jurnal_mengajar.tanggal', 'desc')
            ->orderBy('jurnal_mengajar.id', 'desc')
            ->get();

        return view('waka.jurnal', compact('dataJurnal'));
    }
}