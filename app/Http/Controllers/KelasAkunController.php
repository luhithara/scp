<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;

class KelasAkunController extends Controller
{
    /**
     * Validasi akun wali kelas.
     */
    private function getKelasUser()
    {
        $user = Auth::user();

        abort_unless(
            $user && strtolower(trim($user->role ?? '')) === 'kelas',
            403,
            'Akses hanya untuk akun wali kelas.'
        );

        abort_unless(
            $user->kelasid,
            403,
            'Akun belum terhubung dengan kelas.'
        );

        return $user;
    }

    /**
     * Dashboard akun kelas.
     */
    public function dashboard(Request $request)
    {
        $user = $this->getKelasUser();

        $kelas = Kelas::findOrFail($user->kelasid);

        $request->validate([
            'tanggal' => ['nullable', 'date'],
            'cari' => ['nullable', 'string', 'max:100'],
            'status_izin' => ['nullable', 'string', 'max:50'],
            'tab' => ['nullable', 'in:siswa,presensi,perizinan,jadwal'],
            'siswa_id' => ['nullable', 'integer'],
        ]);

        $tanggal = $request->input('tanggal');
        $cari = trim((string) $request->input('cari', ''));
        $statusIzin = trim((string) $request->input('status_izin', ''));
        $tabAktif = $request->input('tab', 'siswa');

        /*
        |--------------------------------------------------------------------------
        | DAFTAR SISWA
        |--------------------------------------------------------------------------
        */

        $querySiswa = DB::table('siswa')
            ->where('siswa.kelasid', $kelas->id);

        if ($cari !== '') {
            $querySiswa->where(function ($query) use ($cari) {
                $query->where('siswa.nama', 'like', "%{$cari}%")
                    ->orWhere('siswa.nisn', 'like', "%{$cari}%");
            });
        }

        $daftarSiswa = $querySiswa
            ->select(
                'siswa.id',
                'siswa.nisn',
                'siswa.nama',
                'siswa.jeniskelamin'
            )
            ->orderBy('siswa.nama')
            ->get();

        $jumlahSiswa = DB::table('siswa')
            ->where('kelasid', $kelas->id)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | DETAIL ABSENSI SISWA
        |--------------------------------------------------------------------------
        */

        $siswaDipilih = null;
        $absensiSiswa = collect();
        $izinSiswa = collect();

        $rekapAbsensiSiswa = [
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
            'alpa' => 0,
            'lainnya' => 0,
        ];

        if ($request->filled('siswa_id')) {

            $siswaDipilih = DB::table('siswa')
                ->where('id', $request->integer('siswa_id'))
                ->where('kelasid', $kelas->id)
                ->first();

            abort_unless(
                $siswaDipilih !== null,
                404,
                'Data siswa tidak ditemukan di kelas Anda.'
            );

            $queryAbsensiSiswa = DB::table('presensi')
                ->join(
                    'jadwal',
                    'jadwal.id',
                    '=',
                    'presensi.jadwalid'
                )
                ->where('presensi.siswaid', $siswaDipilih->id)
                ->where('jadwal.kelasid', $kelas->id);

            if ($tanggal) {
                $queryAbsensiSiswa->whereDate(
                    'presensi.waktu',
                    $tanggal
                );
            }

            $absensiSiswa = $queryAbsensiSiswa
                ->select(
                    'presensi.id',
                    'presensi.status',
                    'presensi.keterangan',
                    'presensi.waktu',
                    'jadwal.namamapel',
                    'jadwal.hari',
                    'jadwal.jamke',
                    'jadwal.jamMulai',
                    'jadwal.jamSelesai'
                )
                ->orderByDesc('presensi.waktu')
                ->orderBy('jadwal.jamke')
                ->get();

            foreach ($absensiSiswa as $absensi) {

                $status = strtolower(
                    trim((string) $absensi->status)
                );

                if (in_array($status, ['hadir', 'h', 'present'], true)) {
                    $rekapAbsensiSiswa['hadir']++;
                } elseif (in_array($status, ['izin', 'i'], true)) {
                    $rekapAbsensiSiswa['izin']++;
                } elseif (in_array($status, ['sakit', 's'], true)) {
                    $rekapAbsensiSiswa['sakit']++;
                } elseif (in_array(
                    $status,
                    ['alpa', 'alpha', 'a', 'tanpa keterangan'],
                    true
                )) {
                    $rekapAbsensiSiswa['alpa']++;
                } else {
                    $rekapAbsensiSiswa['lainnya']++;
                }
            }

            $queryIzinSiswa = DB::table('izin_siswa')
                ->where('izin_siswa.siswaid', $siswaDipilih->id);

            if ($tanggal) {
                $queryIzinSiswa->whereDate(
                    'izin_siswa.tanggal',
                    $tanggal
                );
            }

            $izinSiswa = $queryIzinSiswa
                ->select(
                    'izin_siswa.id',
                    'izin_siswa.jenis',
                    'izin_siswa.alasan',
                    'izin_siswa.tanggal',
                    'izin_siswa.waktu',
                    'izin_siswa.status',
                    'izin_siswa.catatanbk'
                )
                ->orderByDesc('izin_siswa.tanggal')
                ->orderByDesc('izin_siswa.waktu')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | DATA PRESENSI SEMUA SISWA
        |--------------------------------------------------------------------------
        */

        $queryPresensi = DB::table('presensi')
            ->join('siswa', 'siswa.id', '=', 'presensi.siswaid')
            ->join('jadwal', 'jadwal.id', '=', 'presensi.jadwalid')
            ->where('siswa.kelasid', $kelas->id)
            ->where('jadwal.kelasid', $kelas->id);

        if ($tanggal) {
            $queryPresensi->whereDate('presensi.waktu', $tanggal);
        }

        if ($cari !== '') {
            $queryPresensi->where(function ($query) use ($cari) {
                $query->where('siswa.nama', 'like', "%{$cari}%")
                    ->orWhere('siswa.nisn', 'like', "%{$cari}%");
            });
        }

        $daftarPresensi = $queryPresensi
            ->select(
                'presensi.id',
                'presensi.siswaid',
                'presensi.status',
                'presensi.keterangan',
                'presensi.waktu',
                'siswa.nama as namasiswa',
                'siswa.nisn',
                'jadwal.namamapel',
                'jadwal.hari',
                'jadwal.jamke'
            )
            ->orderByDesc('presensi.waktu')
            ->orderBy('siswa.nama')
            ->get();

        $rekap = [
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
            'alpa' => 0,
            'lainnya' => 0,
        ];

        foreach ($daftarPresensi as $presensi) {

            $status = strtolower(trim((string) $presensi->status));

            if (in_array($status, ['hadir', 'h', 'present'], true)) {
                $rekap['hadir']++;
            } elseif (in_array($status, ['izin', 'i'], true)) {
                $rekap['izin']++;
            } elseif (in_array($status, ['sakit', 's'], true)) {
                $rekap['sakit']++;
            } elseif (in_array(
                $status,
                ['alpa', 'alpha', 'a', 'tanpa keterangan'],
                true
            )) {
                $rekap['alpa']++;
            } else {
                $rekap['lainnya']++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DATA PERIZINAN KELAS
        |--------------------------------------------------------------------------
        */

        $queryIzin = DB::table('izin_siswa')
            ->join('siswa', 'siswa.id', '=', 'izin_siswa.siswaid')
            ->where('siswa.kelasid', $kelas->id);

        if ($tanggal) {
            $queryIzin->whereDate('izin_siswa.tanggal', $tanggal);
        }

        if ($cari !== '') {
            $queryIzin->where(function ($query) use ($cari) {
                $query->where('siswa.nama', 'like', "%{$cari}%")
                    ->orWhere('siswa.nisn', 'like', "%{$cari}%");
            });
        }

        if ($statusIzin !== '') {
            $queryIzin->where('izin_siswa.status', $statusIzin);
        }

        $daftarIzin = $queryIzin
            ->select(
                'izin_siswa.id',
                'izin_siswa.siswaid',
                'izin_siswa.jenis',
                'izin_siswa.alasan',
                'izin_siswa.bukti',
                'izin_siswa.waktu',
                'izin_siswa.tanggal',
                'izin_siswa.status',
                'izin_siswa.jadwalid',
                'izin_siswa.waktudisetuju',
                'izin_siswa.catatanbk',
                'izin_siswa.disetujui_oleh',
                'siswa.nama as namasiswa',
                'siswa.nisn'
            )
            ->orderByDesc('izin_siswa.tanggal')
            ->orderByDesc('izin_siswa.waktu')
            ->get();

        $jumlahIzin = $daftarIzin->count();

        /*
        |--------------------------------------------------------------------------
        | JADWAL KELAS
        |--------------------------------------------------------------------------
        */

        $daftarJadwal = DB::table('jadwal')
            ->leftJoin('guru', 'guru.id', '=', 'jadwal.guruid')
            ->leftJoin('ruangan', 'ruangan.id', '=', 'jadwal.ruanganid')
            ->where('jadwal.kelasid', $kelas->id)
            ->select(
                'jadwal.id',
                'jadwal.hari',
                'jadwal.jamke',
                'jadwal.jamMulai',
                'jadwal.jamSelesai',
                'jadwal.namamapel',
                'jadwal.mapelid',
                'jadwal.blok',
                'guru.nama as namaguru',
                'ruangan.namaruangan'
            )
            ->orderByRaw("
                CASE LOWER(jadwal.hari)
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    WHEN 'jum''at' THEN 5
                    WHEN 'sabtu' THEN 6
                    WHEN 'minggu' THEN 7
                    ELSE 8
                END
            ")
            ->orderBy('jadwal.jamke')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | REKAP STATUS IZIN
        |--------------------------------------------------------------------------
        */

        $rekapIzin = [
            'menunggu' => 0,
            'disetujui' => 0,
            'ditolak' => 0,
            'lainnya' => 0,
        ];

        foreach ($daftarIzin as $izin) {

            $status = strtolower(trim((string) $izin->status));

            if (in_array(
                $status,
                ['menunggu', 'pending', 'diajukan'],
                true
            )) {
                $rekapIzin['menunggu']++;
            } elseif (in_array(
                $status,
                ['disetujui', 'diterima', 'approved'],
                true
            )) {
                $rekapIzin['disetujui']++;
            } elseif (in_array(
                $status,
                ['ditolak', 'reject', 'rejected'],
                true
            )) {
                $rekapIzin['ditolak']++;
            } else {
                $rekapIzin['lainnya']++;
            }
        }

        return view('kelas.dashboard', compact(
            'kelas',
            'user',
            'tanggal',
            'cari',
            'statusIzin',
            'tabAktif',
            'jumlahSiswa',
            'daftarSiswa',
            'daftarPresensi',
            'rekap',
            'daftarIzin',
            'jumlahIzin',
            'rekapIzin',
            'daftarJadwal',
            'siswaDipilih',
            'absensiSiswa',
            'izinSiswa',
            'rekapAbsensiSiswa'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | MENYETUJUI IZIN
    |--------------------------------------------------------------------------
    */

    public function setujuiIzin($id)
    {
        $user = $this->getKelasUser();

        $hasil = DB::transaction(function () use ($id, $user) {

            $izin = DB::table('izin_siswa')
                ->join('siswa', 'siswa.id', '=', 'izin_siswa.siswaid')
                ->where('izin_siswa.id', $id)
                ->where('siswa.kelasid', $user->kelasid)
                ->select('izin_siswa.*')
                ->lockForUpdate()
                ->first();

            if (!$izin) {
                return 'not_found';
            }

            if (strtolower(trim($izin->status ?? '')) !== 'menunggu') {
                return 'processed';
            }

            $updated = DB::table('izin_siswa')
                ->where('id', $id)
                ->where('status', $izin->status)
                ->update([
                    'status' => 'disetujui',
                    'disetujui_oleh' => $user->id,
                    'waktudisetuju' => now(),
                    'updated_at' => now(),
                ]);

            return $updated === 1 ? 'success' : 'processed';
        });

        if ($hasil === 'not_found') {
            return back()->with('error', 'Pengajuan izin tidak ditemukan di kelas Anda.');
        }

        if ($hasil === 'processed') {
            return back()->with('error', 'Pengajuan izin sudah diproses oleh BK atau Wali Kelas.');
        }

        return redirect()
            ->route('kelas.dashboard', ['tab' => 'perizinan'])
            ->with('success', 'Pengajuan izin berhasil disetujui.');
    }

    /*
    |--------------------------------------------------------------------------
    | MENOLAK IZIN
    |--------------------------------------------------------------------------
    */

    public function tolakIzin(Request $request, $id)
    {
        $user = $this->getKelasUser();

        $validated = $request->validate([
            'catatanbk' => ['nullable', 'string', 'max:1000'],
        ]);

        $hasil = DB::transaction(function () use ($id, $user, $validated) {

            $izin = DB::table('izin_siswa')
                ->join('siswa', 'siswa.id', '=', 'izin_siswa.siswaid')
                ->where('izin_siswa.id', $id)
                ->where('siswa.kelasid', $user->kelasid)
                ->select('izin_siswa.*')
                ->lockForUpdate()
                ->first();

            if (!$izin) {
                return 'not_found';
            }

            if (strtolower(trim($izin->status ?? '')) !== 'menunggu') {
                return 'processed';
            }

            $updated = DB::table('izin_siswa')
                ->where('id', $id)
                ->where('status', $izin->status)
                ->update([
                    'status' => 'ditolak',
                    'disetujui_oleh' => $user->id,
                    'waktudisetuju' => now(),
                    'catatanbk' => $validated['catatanbk'] ?? null,
                    'updated_at' => now(),
                ]);

            return $updated === 1 ? 'success' : 'processed';
        });

        if ($hasil === 'not_found') {
            return back()->with('error', 'Pengajuan izin tidak ditemukan di kelas Anda.');
        }

        if ($hasil === 'processed') {
            return back()->with('error', 'Pengajuan izin sudah diproses oleh BK atau Wali Kelas.');
        }

        return redirect()
            ->route('kelas.dashboard', ['tab' => 'perizinan'])
            ->with('success', 'Pengajuan izin berhasil ditolak.');
    }
}