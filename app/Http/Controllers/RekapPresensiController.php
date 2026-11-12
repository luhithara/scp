<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RekapPresensiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CEK AKSES WAKA
    |--------------------------------------------------------------------------
    */

    private function cekAkses()
    {
        $user = auth()->user();

        if (
            !$user ||
            strtolower(trim($user->role ?? '')) !== 'waka'
        ) {
            abort(403, 'Akses hanya untuk Wakil Kepala Sekolah.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PERIODE SEMESTER
    |--------------------------------------------------------------------------
    |
    | Semester 1: Agustus - Januari
    | Semester 2: Februari - Juli
    |
    | Tahun yang dipilih adalah tahun awal periode.
    |
    */

    private function periode(Request $request): array
    {
        $tahun = (int) $request->input('tahun', now()->year);
        $semester = (int) $request->input('semester', 1);

        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = now()->year;
        }

        if (!in_array($semester, [1, 2], true)) {
            $semester = 1;
        }

        if ($semester === 1) {
            $mulai = sprintf('%04d-08-01', $tahun);
            $selesai = sprintf('%04d-01-31', $tahun + 1);
        } else {
            $mulai = sprintf('%04d-02-01', $tahun);
            $selesai = sprintf('%04d-07-31', $tahun);
        }

        return [
            $tahun,
            $semester,
            $mulai,
            $selesai
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY UTAMA REKAP PRESENSI
    |--------------------------------------------------------------------------
    */

    private function queryRekap(
        Request $request,
        string $mulai,
        string $selesai
    ) {
        $query = DB::table('siswa')
            ->join(
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
            ->leftJoin('presensi', function ($join) use (
                $mulai,
                $selesai
            ) {
                $join->on(
                    'presensi.siswaid',
                    '=',
                    'siswa.id'
                )->whereBetween(
                    'presensi.tanggal_presensi',
                    [$mulai, $selesai]
                );
            });

        /*
        |--------------------------------------------------------------------------
        | FILTER TINGKAT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tingkat')) {
            $query->where(
                'kelas.tingkat',
                $request->input('tingkat')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER JURUSAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jurusanid')) {
            $query->where(
                'kelas.jurusanid',
                $request->input('jurusanid')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER KELAS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('kelasid')) {
            $query->where(
                'kelas.id',
                $request->input('kelasid')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PENCARIAN SISWA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('cari')) {
            $cari = trim($request->input('cari'));

            $query->where(function ($q) use ($cari) {
                $q->where(
                    'siswa.nama',
                    'like',
                    '%' . $cari . '%'
                )->orWhere(
                    'siswa.nisn',
                    'like',
                    '%' . $cari . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | PERHITUNGAN PRESENSI
        |--------------------------------------------------------------------------
        */

        return $query
            ->select(
                'siswa.id',
                'siswa.nisn',
                'siswa.nama',

                'kelas.namakelas',
                'kelas.tingkat',
                'kelas.jurusanid',

                'jurusan.namajurusan',

                DB::raw("
                    COALESCE(
                        SUM(
                            CASE
                                WHEN LOWER(presensi.status) = 'hadir'
                                THEN 1 ELSE 0
                            END
                        ), 0
                    ) AS jumlah_hadir
                "),

                DB::raw("
                    COALESCE(
                        SUM(
                            CASE
                                WHEN LOWER(presensi.status) = 'izin'
                                THEN 1 ELSE 0
                            END
                        ), 0
                    ) AS jumlah_izin
                "),

                DB::raw("
                    COALESCE(
                        SUM(
                            CASE
                                WHEN LOWER(presensi.status) = 'sakit'
                                THEN 1 ELSE 0
                            END
                        ), 0
                    ) AS jumlah_sakit
                "),

                DB::raw("
                    COALESCE(
                        SUM(
                            CASE
                                WHEN LOWER(presensi.status) = 'alpa'
                                THEN 1 ELSE 0
                            END
                        ), 0
                    ) AS jumlah_alpa
                "),

                DB::raw("
                    COUNT(presensi.id) AS total_presensi
                ")
            )
            ->groupBy(
                'siswa.id',
                'siswa.nisn',
                'siswa.nama',
                'kelas.namakelas',
                'kelas.tingkat',
                'kelas.jurusanid',
                'jurusan.namajurusan'
            )
            ->orderBy('kelas.tingkat')
            ->orderBy('jurusan.namajurusan')
            ->orderBy('kelas.namakelas')
            ->orderBy('siswa.nama');
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT ANGKA REKAP
    |--------------------------------------------------------------------------
    */

    private function hitungPersentase($item): void
    {
        $item->jumlah_hadir = (int) $item->jumlah_hadir;
        $item->jumlah_izin = (int) $item->jumlah_izin;
        $item->jumlah_sakit = (int) $item->jumlah_sakit;
        $item->jumlah_alpa = (int) $item->jumlah_alpa;
        $item->total_presensi = (int) $item->total_presensi;

        $item->persentase = $item->total_presensi > 0
            ? round(
                ($item->jumlah_hadir / $item->total_presensi) * 100,
                2
            )
            : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN REKAP PRESENSI
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $this->cekAkses();

        [
            $tahun,
            $semester,
            $mulai,
            $selesai
        ] = $this->periode($request);

        $dataRekap = $this->queryRekap(
            $request,
            $mulai,
            $selesai
        )->get();

        /*
        |--------------------------------------------------------------------------
        | HITUNG PERSENTASE SETIAP SISWA
        |--------------------------------------------------------------------------
        */

        foreach ($dataRekap as $item) {
            $this->hitungPersentase($item);
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL REKAP
        |--------------------------------------------------------------------------
        */

        $totalSiswa = $dataRekap->count();

        $totalHadir = (int) $dataRekap->sum('jumlah_hadir');

        $totalIzin = (int) $dataRekap->sum('jumlah_izin');

        $totalSakit = (int) $dataRekap->sum('jumlah_sakit');

        $totalAlpa = (int) $dataRekap->sum('jumlah_alpa');

        $totalPertemuan =
            $totalHadir +
            $totalIzin +
            $totalSakit +
            $totalAlpa;

        $persentaseKehadiran = $totalPertemuan > 0
            ? round(
                ($totalHadir / $totalPertemuan) * 100,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | DAFTAR JURUSAN
        |--------------------------------------------------------------------------
        */

        $daftarJurusan = DB::table('jurusan')
            ->orderBy('namajurusan')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DAFTAR KELAS
        |--------------------------------------------------------------------------
        */

        $daftarKelas = DB::table('kelas')
            ->leftJoin(
                'jurusan',
                'kelas.jurusanid',
                '=',
                'jurusan.id'
            )
            ->select(
                'kelas.id',
                'kelas.namakelas',
                'kelas.tingkat',
                'kelas.jurusanid',
                'jurusan.namajurusan'
            )
            ->orderBy('kelas.tingkat')
            ->orderBy('kelas.namakelas')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DAFTAR TINGKAT
        |--------------------------------------------------------------------------
        */

        $daftarTingkat = DB::table('kelas')
            ->select('tingkat')
            ->distinct()
            ->orderBy('tingkat')
            ->pluck('tingkat');

        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN VIEW
        |--------------------------------------------------------------------------
        */

        return view('waka.rekap-presensi', compact(
            'dataRekap',
            'tahun',
            'semester',
            'mulai',
            'selesai',
            'totalSiswa',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlpa',
            'totalPertemuan',
            'persentaseKehadiran',
            'daftarJurusan',
            'daftarKelas',
            'daftarTingkat'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function exportExcel(Request $request)
    {
        $this->cekAkses();

        [
            $tahun,
            $semester,
            $mulai,
            $selesai
        ] = $this->periode($request);

        $data = $this->queryRekap(
            $request,
            $mulai,
            $selesai
        )->get();

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Rekap Presensi');

        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        $sheet->setCellValue('A1', 'REKAPITULASI PRESENSI SISWA');

        $sheet->mergeCells('A1:L1');

        $sheet->setCellValue(
            'A2',
            'Semester ' . $semester . ' Tahun Ajaran ' .
            $tahun . '/' . ($tahun + 1)
        );

        $sheet->mergeCells('A2:L2');

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        $header = [
            'No',
            'NISN',
            'Nama Siswa',
            'Tingkat',
            'Jurusan',
            'Kelas',
            'Hadir',
            'Izin',
            'Sakit',
            'Alpa',
            'Total Presensi',
            'Persentase Hadir'
        ];

        $sheet->fromArray(
            $header,
            null,
            'A4'
        );

        /*
        |--------------------------------------------------------------------------
        | ISI DATA
        |--------------------------------------------------------------------------
        */

        $baris = 5;

        foreach ($data as $index => $item) {
            $hadir = (int) $item->jumlah_hadir;
            $izin = (int) $item->jumlah_izin;
            $sakit = (int) $item->jumlah_sakit;
            $alpa = (int) $item->jumlah_alpa;

            $total = (int) $item->total_presensi;

            $persentase = $total > 0
                ? round(($hadir / $total) * 100, 2)
                : 0;

            $sheet->fromArray([
                [
                    $index + 1,
                    $item->nisn,
                    $item->nama,
                    $item->tingkat,
                    $item->namajurusan ?? '-',
                    $item->namakelas,
                    $hadir,
                    $izin,
                    $sakit,
                    $alpa,
                    $total,
                    $persentase . '%'
                ]
            ], null, 'A' . $baris);

            $baris++;
        }

        /*
        |--------------------------------------------------------------------------
        | FORMAT EXCEL
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A1:L1')
            ->getFont()
            ->setBold(true)
            ->setSize(14);

        $sheet->getStyle('A2:L2')
            ->getFont()
            ->setItalic(true);

        $sheet->getStyle('A4:L4')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A4:L4')
            ->getAlignment()
            ->setHorizontal('center');

        $sheet->getStyle('A4:L4')
            ->getAlignment()
            ->setVertical('center');

        $sheet->getStyle('A4:L' . max(4, $baris - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );

        foreach (range('A', 'L') as $kolom) {
            $sheet->getColumnDimension($kolom)
                ->setAutoSize(true);
        }

        $sheet->freezePane('A5');

        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD FILE
        |--------------------------------------------------------------------------
        */

        $namaFile = sprintf(
            'Rekap-Presensi-Semester-%s-%s.xlsx',
            $semester,
            $tahun
        );

        return response()->streamDownload(
            function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);

                $writer->save('php://output');
            },
            $namaFile,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

                'Cache-Control' => 'max-age=0',
            ]
        );
    }
}