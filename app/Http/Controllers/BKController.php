<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BK;
use App\Models\BkKelas;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\IzinSiswa;

class BKController extends Controller
{
    /**
     * MENGAMBIL DATA BK YANG SEDANG LOGIN
     */
    private function getBK()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Anda belum login.');
        }

        if (strtolower($user->role ?? '') !== 'bk') {
            abort(403, 'Akun ini bukan akun BK.');
        }

        $bk = DB::table('bk')
            ->where('userid', $user->id)
            ->where('status', 'aktif')
            ->first();

        if (!$bk) {
            abort(403, 'Data BK tidak ditemukan atau akun BK tidak aktif.');
        }

        return $bk;
    }

    /**
     * MENGAMBIL ID KELAS TANGGUNG JAWAB BK
     */
    private function kelasBK($bkid)
    {
        return DB::table('bk_kelas')
            ->where('bkid', $bkid)
            ->pluck('kelasid')
            ->map(function ($id) {
                return (int) $id;
            })
            ->toArray();
    }

    /**
     * DASHBOARD BK
     */
    public function dashboard()
    {
        $bk = $this->getBK();
        $kelasIds = $this->kelasBK($bk->id);

        $jumlahSiswa = Siswa::whereIn(
            'kelasid',
            $kelasIds ?: [0]
        )->count();

        $jumlahPresensi = DB::table('presensi')
            ->join('siswa', 'presensi.siswaid', '=', 'siswa.id')
            ->whereIn('siswa.kelasid', $kelasIds ?: [0])
            ->whereDate('presensi.waktu', now()->toDateString())
            ->count();

        $jumlahHadir = DB::table('presensi')
            ->join('siswa', 'presensi.siswaid', '=', 'siswa.id')
            ->whereIn('siswa.kelasid', $kelasIds ?: [0])
            ->whereDate('presensi.waktu', now()->toDateString())
            ->where('presensi.status', 'hadir')
            ->count();

        $jumlahSakit = DB::table('presensi')
            ->join('siswa', 'presensi.siswaid', '=', 'siswa.id')
            ->whereIn('siswa.kelasid', $kelasIds ?: [0])
            ->whereDate('presensi.waktu', now()->toDateString())
            ->where('presensi.status', 'sakit')
            ->count();

        $jumlahIzin = DB::table('presensi')
            ->join('siswa', 'presensi.siswaid', '=', 'siswa.id')
            ->whereIn('siswa.kelasid', $kelasIds ?: [0])
            ->whereDate('presensi.waktu', now()->toDateString())
            ->where('presensi.status', 'izin')
            ->count();

        $jumlahAlpa = DB::table('presensi')
            ->join('siswa', 'presensi.siswaid', '=', 'siswa.id')
            ->whereIn('siswa.kelasid', $kelasIds ?: [0])
            ->whereDate('presensi.waktu', now()->toDateString())
            ->where('presensi.status', 'alpa')
            ->count();

        $jumlahJurnal = DB::table('jurnal_mengajar')
            ->join('jadwal', 'jurnal_mengajar.jadwalid', '=', 'jadwal.id')
            ->whereIn('jadwal.kelasid', $kelasIds ?: [0])
            ->whereDate('jurnal_mengajar.tanggal', now()->toDateString())
            ->count();

        $kelas = Kelas::query()
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->whereIn('kelas.id', $kelasIds ?: [0])
            ->select(
                'kelas.*',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->orderBy('kelas.tingkat')
            ->orderBy('kelas.namakelas')
            ->get();

        $dataSiswa = Siswa::query()
            ->join('kelas', 'siswa.kelasid', '=', 'kelas.id')
            ->whereIn('siswa.kelasid', $kelasIds ?: [0])
            ->select('siswa.*', 'kelas.namakelas')
            ->orderBy('siswa.nama')
            ->limit(10)
            ->get();

        return view(
            'bk.dashboard',
            compact(
                'bk',
                'jumlahSiswa',
                'jumlahPresensi',
                'jumlahJurnal',
                'jumlahHadir',
                'jumlahSakit',
                'jumlahIzin',
                'jumlahAlpa',
                'kelas',
                'dataSiswa'
            )
        );
    }

    /**
     * HALAMAN KELAS TANGGUNG JAWAB
     */
    public function kelas()
    {
        $bk = $this->getBK();
        $kelasIds = $this->kelasBK($bk->id);

        $semuaKelas = Kelas::query()
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->select(
                'kelas.*',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->orderBy('kelas.tingkat')
            ->orderBy('kelas.namakelas')
            ->get();

        $semuaKelas = $semuaKelas->map(function ($item) use ($kelasIds) {
            $item->dipilih = in_array((int) $item->id, $kelasIds);
            return $item;
        });

        return view(
            'bk.kelas',
            compact('bk', 'semuaKelas', 'kelasIds')
        );
    }

    /**
     * SIMPAN KELAS TANGGUNG JAWAB BK
     */
    public function simpanKelas(Request $request)
    {
        $bk = $this->getBK();

        $request->validate([
            'kelasid' => 'nullable|array',
            'kelasid.*' => 'exists:kelas,id',
        ]);

        DB::transaction(function () use ($request, $bk) {
            DB::table('bk_kelas')
                ->where('bkid', $bk->id)
                ->delete();

            $kelasIds = $request->input('kelasid', []);

            foreach ($kelasIds as $kelasid) {
                DB::table('bk_kelas')->insert([
                    'bkid' => $bk->id,
                    'kelasid' => $kelasid,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('bk.kelas')
            ->with('success', 'Kelas tanggung jawab berhasil diperbarui.');
    }

    /**
     * DATA SISWA
     */
    public function siswa(Request $request)
    {
        $bk = $this->getBK();
        $kelasIds = $this->kelasBK($bk->id);

        $query = Siswa::query()
            ->join('kelas', 'siswa.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->whereIn('siswa.kelasid', $kelasIds ?: [0]);

        if ($request->filled('cari')) {
            $cari = $request->cari;

            $query->where(function ($q) use ($cari) {
                $q->where('siswa.nama', 'like', '%' . $cari . '%')
                    ->orWhere('siswa.nisn', 'like', '%' . $cari . '%');
            });
        }

        if ($request->filled('jurusan')) {
            $query->where('kelas.jurusanid', $request->jurusan);
        }

        if ($request->filled('tingkat')) {
            $query->where('kelas.tingkat', $request->tingkat);
        }

        $dataSiswa = $query
            ->select(
                'siswa.*',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->orderBy('siswa.nama')
            ->get();

        $jurusan = DB::table('jurusan')
            ->orderBy('kodejurusan')
            ->get();

        return view('bk.siswa', compact('bk', 'dataSiswa', 'jurusan'));
    }

    /**
     * DETAIL SISWA
     */
    public function detailSiswa($id)
    {
        $bk = $this->getBK();
        $kelasIds = $this->kelasBK($bk->id);

        $siswa = Siswa::query()
            ->join('kelas', 'siswa.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->where('siswa.id', $id)
            ->whereIn('siswa.kelasid', $kelasIds ?: [0])
            ->select(
                'siswa.*',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->first();

        if (!$siswa) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        $presensi = DB::table('presensi')
            ->join('jadwal', 'presensi.jadwalid', '=', 'jadwal.id')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->leftJoin('guru', 'jadwal.guruid', '=', 'guru.id')
            ->where('presensi.siswaid', $siswa->id)
            ->select(
                'presensi.*',
                'jadwal.hari',
                'jadwal.jamMulai',
                'jadwal.jamSelesai',
                'jadwal.namamapel',
                'mapel.namamapel as mapel_nama',
                'guru.nama as nama_guru'
            )
            ->orderByDesc('presensi.waktu')
            ->get();

        $izin = IzinSiswa::where('siswaid', $siswa->id)
            ->orderByDesc('tanggal')
            ->get();

        return view(
            'bk.siswa-detail',
            compact('bk', 'siswa', 'presensi', 'izin')
        );
    }

    /**
     * PRESENSI SISWA
     */
    public function presensi(Request $request)
    {
        $bk = $this->getBK();
        $kelasIds = $this->kelasBK($bk->id);

        $tanggal = $request->input('tanggal', now()->toDateString());

        $query = DB::table('presensi')
            ->join('siswa', 'presensi.siswaid', '=', 'siswa.id')
            ->join('kelas', 'siswa.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->join('jadwal', 'presensi.jadwalid', '=', 'jadwal.id')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->leftJoin('guru', 'jadwal.guruid', '=', 'guru.id')
            ->whereIn('siswa.kelasid', $kelasIds ?: [0])
            ->whereDate('presensi.waktu', $tanggal);

        if ($request->filled('cari')) {
            $cari = $request->cari;

            $query->where(function ($q) use ($cari) {
                $q->where('siswa.nama', 'like', '%' . $cari . '%')
                    ->orWhere('siswa.nisn', 'like', '%' . $cari . '%');
            });
        }

        if ($request->filled('jurusan')) {
            $query->where('kelas.jurusanid', $request->jurusan);
        }

        if ($request->filled('tingkat')) {
            $query->where('kelas.tingkat', $request->tingkat);
        }

        if ($request->filled('status')) {
            $query->where('presensi.status', $request->status);
        }

        $dataPresensi = $query
            ->select(
                'presensi.*',
                'siswa.nisn',
                'siswa.nama as nama_siswa',
                'siswa.jeniskelamin',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.kodejurusan',
                'jurusan.namajurusan',
                'jadwal.hari',
                'jadwal.jamMulai',
                'jadwal.jamSelesai',
                'jadwal.namamapel',
                'mapel.namamapel as mapel_nama',
                'guru.nama as nama_guru'
            )
            ->orderBy('kelas.namakelas')
            ->orderBy('siswa.nama')
            ->orderBy('jadwal.jamMulai')
            ->get();

        $jurusan = DB::table('jurusan')
            ->orderBy('kodejurusan')
            ->get();

        $total = $dataPresensi->count();
        $hadir = $dataPresensi->where('status', 'hadir')->count();
        $sakit = $dataPresensi->where('status', 'sakit')->count();
        $izin = $dataPresensi->where('status', 'izin')->count();
        $alpa = $dataPresensi->where('status', 'alpa')->count();

        return view(
            'bk.presensi',
            compact(
                'bk',
                'dataPresensi',
                'jurusan',
                'tanggal',
                'total',
                'hadir',
                'sakit',
                'izin',
                'alpa'
            )
        );
    }

    /**
     * BUTUH PENANGANAN
     */
    public function butuhPenanganan(Request $request)
    {
        $bk = $this->getBK();
        $kelasIds = $this->kelasBK($bk->id);

        $tanggal = $request->input('tanggal', now()->toDateString());

        $data = DB::table('presensi')
            ->join('siswa', 'presensi.siswaid', '=', 'siswa.id')
            ->join('kelas', 'siswa.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->whereIn('siswa.kelasid', $kelasIds ?: [0])
            ->whereDate('presensi.waktu', $tanggal)
            ->where('presensi.status', '<>', 'hadir')
            ->select(
                'siswa.id',
                'siswa.nisn',
                'siswa.nama',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.kodejurusan',
                DB::raw('COUNT(DISTINCT presensi.jadwalid) as jumlah_tidak_hadir')
            )
            ->groupBy(
                'siswa.id',
                'siswa.nisn',
                'siswa.nama',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.kodejurusan'
            )
            ->having('jumlah_tidak_hadir', '>', 2)
            ->orderByDesc('jumlah_tidak_hadir')
            ->orderBy('siswa.nama')
            ->get();

        foreach ($data as $item) {
            $item->mataPelajaran = DB::table('presensi')
                ->join('jadwal', 'presensi.jadwalid', '=', 'jadwal.id')
                ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
                ->leftJoin('guru', 'jadwal.guruid', '=', 'guru.id')
                ->where('presensi.siswaid', $item->id)
                ->whereDate('presensi.waktu', $tanggal)
                ->where('presensi.status', '<>', 'hadir')
                ->select(
                    'presensi.status',
                    'jadwal.jamMulai',
                    'jadwal.jamSelesai',
                    'jadwal.namamapel',
                    'mapel.namamapel as mapel_nama',
                    'guru.nama as nama_guru'
                )
                ->orderBy('jadwal.jamMulai')
                ->get();
        }

        return view('bk.butuh-penanganan', compact('bk', 'data', 'tanggal'));
    }

    /**
     * PERIZINAN SISWA
     */
    public function izin(Request $request)
    {
        $bk = $this->getBK();
        $kelasIds = $this->kelasBK($bk->id);

        $query = DB::table('izin_siswa')
            ->join('siswa', 'izin_siswa.siswaid', '=', 'siswa.id')
            ->join('kelas', 'siswa.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->whereIn('siswa.kelasid', $kelasIds ?: [0]);

        if ($request->filled('cari')) {
            $cari = trim($request->input('cari'));

            $query->where(function ($q) use ($cari) {
                $q->where('siswa.nama', 'like', '%' . $cari . '%')
                    ->orWhere('siswa.nisn', 'like', '%' . $cari . '%');
            });
        }

        if ($request->filled('jenis')) {
            $query->where('izin_siswa.jenis', $request->input('jenis'));
        }

        if ($request->filled('status')) {
            $query->where('izin_siswa.status', $request->input('status'));
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('izin_siswa.tanggal', $request->input('tanggal'));
        }

        $dataIzin = $query
            ->select(
                'izin_siswa.*',
                'siswa.nisn',
                'siswa.nama as nama',
                'siswa.jeniskelamin',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->orderByDesc('izin_siswa.tanggal')
            ->orderByDesc('izin_siswa.waktu')
            ->orderByDesc('izin_siswa.id')
            ->get();

        $adaTabelBukti = DB::getSchemaBuilder()->hasTable('izin_bukti');

        foreach ($dataIzin as $item) {
            $item->buktiTambahan = $adaTabelBukti
                ? DB::table('izin_bukti')
                    ->where('izinid', $item->id)
                    ->orderBy('id')
                    ->get()
                : collect();
        }

        $total = $dataIzin->count();
        $jumlahIzin = $dataIzin->where('jenis', 'izin')->count();
        $jumlahSakit = $dataIzin->where('jenis', 'sakit')->count();

        return view('bk.izin', [
            'bk' => $bk,
            'data' => $dataIzin,
            'dataIzin' => $dataIzin,
            'kelasIds' => $kelasIds,
            'total' => $total,
            'jumlahIzin' => $jumlahIzin,
            'jumlahSakit' => $jumlahSakit,
        ]);
    }

    /**
     * =========================================================
     * MENYETUJUI IZIN SISWA
     * =========================================================
     */
    public function setujuiIzin($id)
    {
        $bk = $this->getBK();
        $kelasIds = $this->kelasBK($bk->id);

        if (empty($kelasIds)) {
            return back()->withErrors([
                'izin' => 'BK belum memiliki kelas tanggung jawab.'
            ]);
        }

        $hasil = DB::transaction(function () use ($id, $kelasIds) {

            $izin = DB::table('izin_siswa')
                ->join(
                    'siswa',
                    'siswa.id',
                    '=',
                    'izin_siswa.siswaid'
                )
                ->where('izin_siswa.id', $id)
                ->whereIn('siswa.kelasid', $kelasIds)
                ->select('izin_siswa.*')
                ->lockForUpdate()
                ->first();

            if (!$izin) {
                return 'not_found';
            }

            if ($izin->status !== 'menunggu') {
                return 'processed';
            }

            $updated = DB::table('izin_siswa')
                ->where('id', $id)
                ->where('status', 'menunggu')
                ->update([
                    'status' => 'disetujui',
                    'disetujui_oleh' => Auth::id(),
                    'waktudisetuju' => now(),
                    'updated_at' => now(),
                ]);

            return $updated === 1 ? 'success' : 'processed';
        });

        if ($hasil === 'not_found') {
            return back()->withErrors([
                'izin' => 'Pengajuan izin tidak ditemukan atau bukan tanggung jawab BK Anda.'
            ]);
        }

        if ($hasil === 'processed') {
            return back()->withErrors([
                'izin' => 'Pengajuan izin ini sudah diproses oleh BK atau wali kelas.'
            ]);
        }

        return redirect()
            ->route('bk.izin')
            ->with('success', 'Pengajuan izin berhasil disetujui.');
    }

    /**
     * =========================================================
     * MENOLAK IZIN SISWA
     * =========================================================
     */
    public function tolakIzin(Request $request, $id)
    {
        $bk = $this->getBK();
        $kelasIds = $this->kelasBK($bk->id);

        if (empty($kelasIds)) {
            return back()->withErrors([
                'izin' => 'BK belum memiliki kelas tanggung jawab.'
            ]);
        }

        $validated = $request->validate([
            'catatanbk' => ['nullable', 'string', 'max:1000'],
        ]);

        $hasil = DB::transaction(function () use (
            $id,
            $kelasIds,
            $validated
        ) {

            $izin = DB::table('izin_siswa')
                ->join(
                    'siswa',
                    'siswa.id',
                    '=',
                    'izin_siswa.siswaid'
                )
                ->where('izin_siswa.id', $id)
                ->whereIn('siswa.kelasid', $kelasIds)
                ->select('izin_siswa.*')
                ->lockForUpdate()
                ->first();

            if (!$izin) {
                return 'not_found';
            }

            if ($izin->status !== 'menunggu') {
                return 'processed';
            }

            $updated = DB::table('izin_siswa')
                ->where('id', $id)
                ->where('status', 'menunggu')
                ->update([
                    'status' => 'ditolak',
                    'disetujui_oleh' => Auth::id(),
                    'waktudisetuju' => now(),
                    'catatanbk' => $validated['catatanbk'] ?? null,
                    'updated_at' => now(),
                ]);

            return $updated === 1 ? 'success' : 'processed';
        });

        if ($hasil === 'not_found') {
            return back()->withErrors([
                'izin' => 'Pengajuan izin tidak ditemukan atau bukan tanggung jawab BK Anda.'
            ]);
        }

        if ($hasil === 'processed') {
            return back()->withErrors([
                'izin' => 'Pengajuan izin ini sudah diproses oleh BK atau wali kelas.'
            ]);
        }

        return redirect()
            ->route('bk.izin')
            ->with('success', 'Pengajuan izin berhasil ditolak.');
    }
}