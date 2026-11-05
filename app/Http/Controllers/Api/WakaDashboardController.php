<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WakaDashboardController extends Controller
{
    /**
     * Dashboard Waka untuk aplikasi Flutter.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        abort_unless(
            $user && strtolower($user->role ?? '') === 'waka',
            403,
            'Akses hanya untuk Waka.'
        );

        $hariIni = Carbon::today();
        $nomorHari = $hariIni->dayOfWeekIso; // 1 Senin - 7 Minggu

        // Mengambil jadwal yang aktif hari ini.
        $jadwalAktif = DB::table('jadwal')
            ->join('guru', 'guru.id', '=', 'jadwal.guruid')
            ->join('kelas', 'kelas.id', '=', 'jadwal.kelasid')
            ->leftJoin('mapel', 'mapel.id', '=', 'jadwal.mapelid')
            ->leftJoin('ruangan', 'ruangan.id', '=', 'jadwal.ruanganid')
            ->where('jadwal.hari', $nomorHari)
            ->select(
                'jadwal.id',
                'jadwal.guruid',
                'jadwal.hari',
                'jadwal.jamMulai',
                'jadwal.jamSelesai',
                'guru.nama as guruNama',
                'kelas.namakelas as kelas',
                DB::raw('COALESCE(mapel.namamapel, jadwal.namamapel) as mapel'),
                'ruangan.namaruangan as ruangan'
            )
            ->get();

        $totalSesi = $jadwalAktif->count();

        $sesiPresensi = 0;
        $sesiJurnal = 0;
        $belumIsi = [];

        foreach ($jadwalAktif as $jadwal) {
            $presensiOk = DB::table('presensi')
                ->where('jadwalid', $jadwal->id)
                ->whereDate('waktu', $hariIni)
                ->exists();

            $jurnalOk = DB::table('jurnal_mengajar')
                ->where('jadwalid', $jadwal->id)
                ->whereDate('tanggal', $hariIni)
                ->exists();

            if ($presensiOk) {
                $sesiPresensi++;
            }

            if ($jurnalOk) {
                $sesiJurnal++;
            }

            if (!$presensiOk || !$jurnalOk) {
                $belumIsi[] = [
                    'jadwal' => [
                        'id' => (string) $jadwal->id,
                        'kelas' => $jadwal->kelas,
                        'hari' => $nomorHari,
                        'jamMulai' => $jadwal->jamMulai,
                        'jamSelesai' => $jadwal->jamSelesai,
                        'mapel' => $jadwal->mapel,
                        'guruId' => (string) $jadwal->guruid,
                        'guruNama' => $jadwal->guruNama,
                        'ruangan' => $jadwal->ruangan,
                    ],
                    'presensiOk' => $presensiOk,
                    'jurnalOk' => $jurnalOk,
                ];
            }
        }

        // Menghitung persentase kehadiran hari ini.
        $jumlahPresensi = DB::table('presensi')
            ->whereDate('waktu', $hariIni)
            ->count();

        $jumlahHadir = DB::table('presensi')
            ->whereDate('waktu', $hariIni)
            ->whereRaw('LOWER(status) = ?', ['hadir'])
            ->count();

        $persenHadir = $jumlahPresensi > 0
            ? (int) round(($jumlahHadir / $jumlahPresensi) * 100)
            : 0;

        // Menghitung izin yang masih menunggu persetujuan.
        $izinMenunggu = DB::table('izin_siswa')
            ->whereRaw('LOWER(status) = ?', ['menunggu'])
            ->count();

        return response()->json([
            'totalSiswa' => DB::table('siswa')->count(),
            'totalGuru' => DB::table('guru')->count(),
            'totalSesi' => $totalSesi,
            'sesiPresensi' => $sesiPresensi,
            'sesiJurnal' => $sesiJurnal,
            'persenHadir' => $persenHadir,
            'izinMenunggu' => $izinMenunggu,
            'belumIsi' => $belumIsi,
        ]);
    }
}