<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\Process\Process;
use Throwable;

class WakaImportController extends Controller
{
    private array $cacheKolom = [];

    private const PYTHON_PATH =
        'C:\\Users\\Asus\\AppData\\Local\\Programs\\Python\\Python312\\python.exe';

    private const PARSER_PATH = 'scripts/parse_jadwal_pdf.py';

    /*
    |--------------------------------------------------------------------------
    | CEK AKSES WAKA
    |--------------------------------------------------------------------------
    */

    private function cekAksesWaka(): void
    {
        abort_unless(
            auth()->check() &&
            strtolower(trim(auth()->user()->role ?? '')) === 'waka',
            403,
            'Anda tidak memiliki akses untuk melakukan import.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES IMPORT
    |--------------------------------------------------------------------------
    */

    public function import(Request $request, string $jenis)
    {
        $this->cekAksesWaka();

        set_time_limit(300);
        $this->cacheKolom = [];

        abort_unless(
            in_array($jenis, ['siswa', 'guru', 'jadwal_kelas'], true),
            404
        );

        if ($jenis === 'jadwal_kelas') {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:20480',
                'putaranblok' => 'nullable|in:1,2',
            ]);
        } else {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:20480',
            ]);
        }

        try {
            $path = $request->file('file')->getRealPath();

            if ($jenis === 'siswa') {
                return $this->importSiswa($path);
            }

            if ($jenis === 'guru') {
                return $this->importGuru($path);
            }

            return $this->importJadwalPdf(
                $path,
                (int) $request->input('putaranblok', 1)
            );

        } catch (Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Import gagal: ' . $e->getMessage()
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MEMBACA SEL EXCEL
    |--------------------------------------------------------------------------
    */

    private function nilaiSel(
        Worksheet $sheet,
        int $kolom,
        int $baris
    ) {
        $koordinat = Coordinate::stringFromColumnIndex($kolom) . $baris;

        return $sheet->getCell($koordinat)->getFormattedValue();
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER KOLOM DATABASE
    |--------------------------------------------------------------------------
    */

    private function filterKolom(string $tabel, array $data): array
    {
        if (!isset($this->cacheKolom[$tabel])) {
            if (!Schema::hasTable($tabel)) {
                throw new \RuntimeException(
                    "Tabel database {$tabel} tidak ditemukan."
                );
            }

            $this->cacheKolom[$tabel] =
                Schema::getColumnListing($tabel);
        }

        return array_intersect_key(
            $data,
            array_flip($this->cacheKolom[$tabel])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT SISWA
    |--------------------------------------------------------------------------
    */

    private function importSiswa(string $path)
    {
        $spreadsheet = IOFactory::load($path);

        $berhasil = 0;
        $diperbarui = 0;
        $dilewati = 0;
        $gagal = 0;
        $jumlahBlok = 0;

        $daftarError = [];
        $cacheKelas = [];

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $namaKelas = null;
            $kelasId = null;

            $barisTerakhir = $sheet->getHighestRow();

            $kolomTerakhir = Coordinate::columnIndexFromString(
                $sheet->getHighestDataColumn()
            );

            for ($baris = 1; $baris <= $barisTerakhir; $baris++) {
                $judulKelas = null;

                for ($kolom = 1; $kolom <= $kolomTerakhir; $kolom++) {
                    $nilai = trim((string) $this->nilaiSel(
                        $sheet,
                        $kolom,
                        $baris
                    ));

                    if (preg_match(
                        '/^KELAS\s*:\s*(.+)$/iu',
                        $nilai,
                        $match
                    )) {
                        $judulKelas = $match[1];
                        break;
                    }
                }

                if ($judulKelas !== null) {
                    $jumlahBlok++;

                    try {
                        $namaKelas = $this->normalisasiNamaKelas(
                            $judulKelas
                        );

                        $kunci = $this->kunciNamaKelas($namaKelas);

                        if (!isset($cacheKelas[$kunci])) {
                            $cacheKelas[$kunci] =
                                $this->cariAtauBuatKelas($namaKelas);
                        }

                        $kelasId = $cacheKelas[$kunci];

                    } catch (Throwable $e) {
                        $namaKelas = null;
                        $kelasId = null;
                        $gagal++;

                        if (count($daftarError) < 50) {
                            $daftarError[] =
                                $sheet->getTitle() .
                                ' baris ' . $baris .
                                ': ' . $e->getMessage();
                        }
                    }

                    continue;
                }

                if (!$namaKelas || !$kelasId) {
                    continue;
                }

                $nis = $this->formatNomor(
                    $sheet->getCell('B' . $baris)->getFormattedValue()
                );

                $nama = trim((string) $sheet
                    ->getCell('C' . $baris)
                    ->getFormattedValue());

                $genderMentah = strtoupper(trim((string) $sheet
                    ->getCell('D' . $baris)
                    ->getFormattedValue()));

                if (!$nis || $nama === '') {
                    continue;
                }

                $gender = $this->normalisasiGender($genderMentah);

                if (!$gender) {
                    $gagal++;

                    if (count($daftarError) < 50) {
                        $daftarError[] =
                            $sheet->getTitle() .
                            ' baris ' . $baris .
                            ': Jenis kelamin tidak valid untuk NIS ' . $nis;
                    }

                    continue;
                }

                try {
                    $hasil = DB::transaction(function () use (
                        $nis,
                        $nama,
                        $gender,
                        $kelasId
                    ) {
                        $sekarang = now();

                        $siswa = DB::table('siswa')
                            ->where('nis', $nis)
                            ->first();

                        $akun = DB::table('users')
                            ->where('nis', $nis)
                            ->first();

                        if (
                            $akun &&
                            strtolower(trim($akun->role ?? '')) !== 'siswa'
                        ) {
                            throw new \RuntimeException(
                                "NIS {$nis} sudah digunakan akun dengan role lain."
                            );
                        }

                        if ($siswa) {
                            $dataSiswa = [
                                'nama' => $nama,
                                'jeniskelamin' => $gender,
                                'kelasid' => $kelasId,
                                'updated_at' => $sekarang,
                            ];

                            DB::table('siswa')
                                ->where('id', $siswa->id)
                                ->update(
                                    $this->filterKolom('siswa', $dataSiswa)
                                );

                        } else {
                            $dataSiswa = [
                                'nis' => $nis,
                                'nisn' => null,
                                'nama' => $nama,
                                'jeniskelamin' => $gender,
                                'kelasid' => $kelasId,
                                'tanggallahir' => null,
                                'tempatlahir' => null,
                                'alamat' => null,
                                'nomortelepon' => null,
                                'created_at' => $sekarang,
                                'updated_at' => $sekarang,
                            ];

                            DB::table('siswa')->insert(
                                $this->filterKolom('siswa', $dataSiswa)
                            );
                        }

                        if ($akun) {
                            $dataUser = [
                                'name' => $nama,
                                'updated_at' => $sekarang,
                            ];

                            if (Schema::hasColumn('users', 'kelasid')) {
                                $dataUser['kelasid'] = $kelasId;
                            }

                            DB::table('users')
                                ->where('id', $akun->id)
                                ->update(
                                    $this->filterKolom('users', $dataUser)
                                );

                        } else {
                            $dataUser = [
                                'name' => $nama,
                                'email' => $this->buatEmailUnik($nis, 'siswa'),
                                'nis' => $nis,
                                'nisn' => null,
                                'kodepegawai' => null,
                                'kelasid' => $kelasId,
                                'kodekelas' => null,
                                'password' => Hash::make($nis),
                                'role' => 'siswa',
                                'status' => 'aktif',
                                'created_at' => $sekarang,
                                'updated_at' => $sekarang,
                            ];

                            DB::table('users')->insert(
                                $this->filterKolom('users', $dataUser)
                            );
                        }

                        return $siswa ? 'diperbarui' : 'berhasil';
                    });

                    if ($hasil === 'berhasil') {
                        $berhasil++;
                    } else {
                        $diperbarui++;
                    }

                } catch (Throwable $e) {
                    $gagal++;

                    if (count($daftarError) < 50) {
                        $daftarError[] =
                            $sheet->getTitle() .
                            ' baris ' . $baris .
                            ' (' . $nama . ', NIS ' . $nis . '): ' .
                            $e->getMessage();
                    }

                    report($e);
                }
            }
        }

        if ($jumlahBlok === 0) {
            return back()->with(
                'error',
                'Tidak ditemukan judul kelas dengan format KELAS : X RPL 1.'
            );
        }

        $pesan = "Import siswa selesai. ";
        $pesan .= "{$berhasil} siswa baru ditambahkan, ";
        $pesan .= "{$diperbarui} siswa diperbarui, ";
        $pesan .= "{$dilewati} dilewati, ";
        $pesan .= "{$gagal} gagal diproses. ";
        $pesan .= "{$jumlahBlok} blok kelas terbaca.";

        return back()
            ->with($gagal > 0 ? 'warning' : 'success', $pesan)
            ->with('import_errors', $daftarError);
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALISASI JENIS KELAMIN
    |--------------------------------------------------------------------------
    */

    private function normalisasiGender(string $gender): ?string
    {
        $gender = strtoupper(trim($gender));

        if (in_array($gender, [
            'L', 'LAKI-LAKI', 'LAKI LAKI', 'LAKI', 'LK',
        ], true)) {
            return 'L';
        }

        if (in_array($gender, [
            'P', 'PEREMPUAN', 'WANITA', 'PR',
        ], true)) {
            return 'P';
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALISASI NAMA KELAS
    |--------------------------------------------------------------------------
    */

    private function normalisasiNamaKelas(string $nama): string
    {
        $nama = strtoupper(trim($nama));
        $nama = preg_replace('/\s+/', ' ', $nama);
        $nama = preg_replace('/^KELAS\s*:\s*/i', '', $nama);

        $nama = str_replace(['-', '_'], ' ', $nama);
        $nama = preg_replace('/\s+/', ' ', $nama);

        if (!preg_match(
            '/^(XII|XI|X)\s+([A-Z]+)(?:\s*(\d+))?$/i',
            $nama,
            $match
        )) {
            throw new \RuntimeException(
                "Format nama kelas tidak dikenali: {$nama}"
            );
        }

        $tingkat = strtoupper($match[1]);
        $jurusan = strtoupper($match[2]);
        $nomor = $match[3] ?? '';

        $jurusan = $this->normalisasiKodeJurusan($jurusan);

        return trim($tingkat . ' ' . $jurusan . ' ' . $nomor);
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALISASI KODE JURUSAN
    |--------------------------------------------------------------------------
    */

    private function normalisasiKodeJurusan(string $kode): string
    {
        $kode = strtoupper(trim($kode));

        return [
            'AK' => 'AKL',
            'MP' => 'MPLB',
        ][$kode] ?? $kode;
    }

    /*
    |--------------------------------------------------------------------------
    | KUNCI NAMA KELAS
    |--------------------------------------------------------------------------
    */

    private function kunciNamaKelas(string $nama): string
    {
        return preg_replace(
            '/[^A-Z0-9]/',
            '',
            strtoupper(trim($nama))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CARI ATAU BUAT KELAS
    |--------------------------------------------------------------------------
    */

    private function cariAtauBuatKelas(string $namaKelas): int
    {
        $normal = $this->kunciNamaKelas($namaKelas);

        $kelas = DB::table('kelas')->get()->first(
            function ($item) use ($normal) {
                return $this->kunciNamaKelas(
                    $item->namakelas
                ) === $normal;
            }
        );

        if ($kelas) {
            return (int) $kelas->id;
        }

        if (!preg_match(
            '/^(XII|XI|X)\s+([A-Z]+)(?:\s*(\d+))?$/i',
            $namaKelas,
            $match
        )) {
            throw new \RuntimeException(
                "Nama kelas {$namaKelas} tidak dapat diproses."
            );
        }

        $romawi = strtoupper($match[1]);

        $kodeJurusan = $this->normalisasiKodeJurusan(
            strtoupper($match[2])
        );

        $tingkat = [
            'X' => 10,
            'XI' => 11,
            'XII' => 12,
        ][$romawi];

        $jurusanId = DB::table('jurusan')
            ->whereRaw(
                'UPPER(TRIM(kodejurusan)) = ?',
                [$kodeJurusan]
            )
            ->value('id');

        if (!$jurusanId) {
            throw new \RuntimeException(
                "Jurusan {$kodeJurusan} belum tersedia di database."
            );
        }

        $dataKelas = [
            'namakelas' => $namaKelas,
            'jurusanid' => $jurusanId,
            'tingkat' => $tingkat,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return (int) DB::table('kelas')->insertGetId(
            $this->filterKolom('kelas', $dataKelas)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT GURU
    |--------------------------------------------------------------------------
    */

    private function importGuru(string $path)
    {
        $spreadsheet = IOFactory::load($path);

        $sheet = null;

        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            if (strtoupper(trim($worksheet->getTitle())) === 'GURU') {
                $sheet = $worksheet;
                break;
            }
        }

        if (!$sheet) {
            return back()->with(
                'error',
                'Sheet Guru tidak ditemukan pada file Excel.'
            );
        }

        $barisHeader = null;
        $kolomNip = null;
        $kolomNama = null;

        $barisPencarian = min(20, $sheet->getHighestRow());

        $kolomTerakhir = Coordinate::columnIndexFromString(
            $sheet->getHighestDataColumn()
        );

        for ($baris = 1; $baris <= $barisPencarian; $baris++) {
            $nipDitemukan = null;
            $namaDitemukan = null;

            for ($kolom = 1; $kolom <= $kolomTerakhir; $kolom++) {
                $isi = strtoupper(trim((string) $this->nilaiSel(
                    $sheet,
                    $kolom,
                    $baris
                )));

                $isiBersih = preg_replace('/[^A-Z]/', '', $isi);

                if (
                    $isiBersih === 'NIP' ||
                    str_contains($isiBersih, 'NIP')
                ) {
                    $nipDitemukan = $kolom;
                }

                if (
                    $isiBersih === 'NAMA' ||
                    str_contains($isiBersih, 'NAMAGURU')
                ) {
                    $namaDitemukan = $kolom;
                }
            }

            if ($nipDitemukan && $namaDitemukan) {
                $barisHeader = $baris;
                $kolomNip = $nipDitemukan;
                $kolomNama = $namaDitemukan;
                break;
            }
        }

        if (!$barisHeader) {
            $barisHeader = 3;
            $kolomNip = 2;
            $kolomNama = 3;
        }

        $barisMulai = $barisHeader + 1;

        $berhasil = 0;
        $diperbarui = 0;
        $dilewati = 0;
        $gagal = 0;

        $daftarError = [];

        $emailTerdaftar = array_fill_keys(
            DB::table('users')->pluck('email')->filter()->all(),
            true
        );

        for (
            $baris = $barisMulai;
            $baris <= $sheet->getHighestRow();
            $baris++
        ) {
            $nip = $this->formatNomor(
                $this->nilaiSel($sheet, $kolomNip, $baris)
            );

            $nama = trim((string) $this->nilaiSel(
                $sheet,
                $kolomNama,
                $baris
            ));

            if (!$nip || $nama === '') {
                continue;
            }

            try {
                $hasil = DB::transaction(function () use (
                    $nip,
                    $nama,
                    &$emailTerdaftar
                ) {
                    $sekarang = now();

                    $guru = DB::table('guru')
                        ->where('nip', $nip)
                        ->orWhere('kodeguru', $nip)
                        ->first();

                    $akun = DB::table('users')
                        ->where('kodepegawai', $nip)
                        ->first();

                    if (
                        $akun &&
                        strtolower(trim($akun->role ?? '')) !== 'guru'
                    ) {
                        throw new \RuntimeException(
                            "NIP {$nip} sudah digunakan akun dengan role lain."
                        );
                    }

                    if ($guru) {
                        DB::table('guru')
                            ->where('id', $guru->id)
                            ->update(
                                $this->filterKolom('guru', [
                                    'nama' => $nama,
                                    'updated_at' => $sekarang,
                                ])
                            );

                    } else {
                        $dataGuru = [
                            'kodeguru' => $nip,
                            'nama' => $nama,
                            'nip' => $nip,
                            'nomortelepon' => null,
                            'alamat' => null,
                            'created_at' => $sekarang,
                            'updated_at' => $sekarang,
                        ];

                        DB::table('guru')->insert(
                            $this->filterKolom('guru', $dataGuru)
                        );
                    }

                    if ($akun) {
                        DB::table('users')
                            ->where('id', $akun->id)
                            ->update(
                                $this->filterKolom('users', [
                                    'name' => $nama,
                                    'updated_at' => $sekarang,
                                ])
                            );

                    } else {
                        $email = $this->buatEmailUnikCache(
                            $nip,
                            'guru',
                            $emailTerdaftar
                        );

                        $dataUser = [
                            'name' => $nama,
                            'email' => $email,
                            'nis' => null,
                            'nisn' => null,
                            'kodepegawai' => $nip,
                            'kelasid' => null,
                            'kodekelas' => null,
                            'password' => Hash::make($nip),
                            'role' => 'guru',
                            'status' => 'aktif',
                            'created_at' => $sekarang,
                            'updated_at' => $sekarang,
                        ];

                        DB::table('users')->insert(
                            $this->filterKolom('users', $dataUser)
                        );
                    }

                    if (!$guru) {
                        return 'berhasil';
                    }

                    if (!$akun) {
                        return 'diperbarui';
                    }

                    return 'dilewati';
                });

                if ($hasil === 'berhasil') {
                    $berhasil++;
                } elseif ($hasil === 'diperbarui') {
                    $diperbarui++;
                } else {
                    $dilewati++;
                }

            } catch (Throwable $e) {
                $gagal++;

                if (count($daftarError) < 50) {
                    $daftarError[] =
                        "Baris {$baris}, NIP {$nip}: " .
                        $e->getMessage();
                }

                report($e);
            }
        }

        $pesan = "Import guru selesai. ";
        $pesan .= "{$berhasil} guru baru ditambahkan, ";
        $pesan .= "{$diperbarui} data guru dilengkapi, ";
        $pesan .= "{$dilewati} sudah terdaftar, ";
        $pesan .= "{$gagal} gagal diproses.";

        return back()
            ->with($gagal > 0 ? 'warning' : 'success', $pesan)
            ->with('import_errors', $daftarError);
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT JADWAL DARI PDF
    |--------------------------------------------------------------------------
    */

    private function importJadwalPdf(string $path, int $putaran)
    {
        if (
            Schema::hasTable('pengaturan_jadwal') &&
            DB::table('pengaturan_jadwal')
                ->where('id', 1)
                ->value('terkunci')
        ) {
            return back()->with(
                'error',
                'Import dibatalkan. Jadwal sedang dikunci oleh Waka.'
            );
        }

        $script = base_path(self::PARSER_PATH);

        if (!file_exists(self::PYTHON_PATH)) {
            throw new \RuntimeException(
                'Python tidak ditemukan: ' . self::PYTHON_PATH
            );
        }

        if (!file_exists($script)) {
            throw new \RuntimeException(
                'File parser tidak ditemukan: ' . $script
            );
        }

        $process = new Process([
            self::PYTHON_PATH,
            $script,
            $path,
        ]);

        $process->setTimeout(240);
        $process->run();

        $output = trim($process->getOutput());

        if (!$process->isSuccessful()) {
            $jsonError = json_decode($output, true);

            throw new \RuntimeException(
                ($jsonError['pesan'] ?? null) ?:
                (trim($process->getErrorOutput()) ?:
                'Parser PDF gagal dijalankan.')
            );
        }

        $hasil = json_decode($output, true);

        if (
            !is_array($hasil) ||
            empty($hasil['berhasil']) ||
            !isset($hasil['data']) ||
            !is_array($hasil['data'])
        ) {
            throw new \RuntimeException(
                $hasil['pesan'] ??
                'Hasil pembacaan PDF tidak valid.'
            );
        }

        if (count($hasil['data']) === 0) {
            throw new \RuntimeException(
                'Tidak ada data kelas yang berhasil dibaca dari PDF.'
            );
        }

        return $this->simpanJadwalHasilParser(
            $hasil['data'],
            $putaran,
            (int) ($hasil['jumlah_kelas'] ?? count($hasil['data']))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SINKRONISASI GURU DARI USERS KE TABEL GURU
    |--------------------------------------------------------------------------
    */

    private function cariAtauBuatGuruDariAkun(
        string $namaGuru,
        array $akunGuruGrup,
        array &$guruGrup
    ): int {
        $kunci = $this->kunciNama($namaGuru);

        if ($kunci === '') {
            throw new \RuntimeException('Nama guru tidak valid.');
        }

        $daftarGuru = array_values(array_unique(
            $guruGrup[$kunci] ?? []
        ));

        if (count($daftarGuru) === 1) {
            return (int) $daftarGuru[0];
        }

        if (count($daftarGuru) > 1) {
            throw new \RuntimeException(
                "Nama guru {$namaGuru} memiliki lebih dari satu data master."
            );
        }

        $daftarAkun = $akunGuruGrup[$kunci] ?? [];

        if (count($daftarAkun) === 0) {
            throw new \RuntimeException(
                "Guru tidak ditemukan pada tabel guru maupun users: {$namaGuru}"
            );
        }

        if (count($daftarAkun) > 1) {
            throw new \RuntimeException(
                "Nama guru {$namaGuru} cocok dengan beberapa akun. Periksa data master."
            );
        }

        $akun = $daftarAkun[0];

        $kodeGuru = trim((string) ($akun->kodepegawai ?? ''));

        if ($kodeGuru === '') {
            $kodeGuru = 'USR' . $akun->id;
        }

        $guruLama = DB::table('guru')
            ->where(function ($query) use ($kodeGuru) {
                $query->where('kodeguru', $kodeGuru)
                    ->orWhere('nip', $kodeGuru);
            })
            ->first();

        if ($guruLama) {
            $guruId = (int) $guruLama->id;
        } else {
            $dataGuru = [
                'kodeguru' => $kodeGuru,
                'nama' => trim((string) $akun->name),
                'nip' => !str_starts_with($kodeGuru, 'USR')
                    ? $kodeGuru
                    : null,
                'nomortelepon' => null,
                'alamat' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $guruId = (int) DB::table('guru')->insertGetId(
                $this->filterKolom('guru', $dataGuru)
            );
        }

        $guruGrup[$kunci] = [$guruId];

        return $guruId;
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN HASIL PARSER KE DATABASE
    |--------------------------------------------------------------------------
    */

    private function simpanJadwalHasilParser(
        array $dataKelas,
        int $putaran,
        int $jumlahKelas
    ) {
        $berhasil = 0;
        $duplikat = 0;
        $bentrok = 0;
        $gagal = 0;

        $daftarError = [];

        $cacheGuru = [];
        $cacheKelas = [];
        $cacheMapel = [];
        $cacheRuangan = [];

        /*
         * KELOMPOKKAN GURU DARI TABEL GURU.
         */

        $guruGrup = [];

        foreach (
            DB::table('guru')->select('id', 'nama')->get()
            as $guru
        ) {
            $kunci = $this->kunciNama($guru->nama);

            if ($kunci !== '') {
                $guruGrup[$kunci][] = (int) $guru->id;
            }
        }

        /*
         * KELOMPOKKAN AKUN GURU DARI TABEL USERS.
         */

        $akunGuruGrup = [];

        foreach (
            DB::table('users')
                ->select('id', 'name', 'kodepegawai', 'role')
                ->whereRaw('LOWER(TRIM(role)) = ?', ['guru'])
                ->get()
            as $akun
        ) {
            $kunci = $this->kunciNama($akun->name);

            if ($kunci !== '') {
                $akunGuruGrup[$kunci][] = $akun;
            }
        }

        /*
         * KELOMPOKKAN MAPEL.
         */

        $mapelGrup = [];

        if (Schema::hasTable('mapel')) {
            foreach (
                DB::table('mapel')->select('id', 'namamapel')->get()
                as $mapel
            ) {
                $kunci = $this->kunciNama($mapel->namamapel);

                if ($kunci !== '') {
                    $mapelGrup[$kunci][] = (int) $mapel->id;
                }
            }
        }

        /*
         * MUAT JADWAL LAMA.
         * Gunakan select * agar tidak gagal jika ada kolom opsional.
         */

        $jadwalQuery = DB::table('jadwal');

        if (Schema::hasColumn('jadwal', 'putaranblok')) {
            $jadwalQuery->where(function ($query) use ($putaran) {
                $query->where('putaranblok', $putaran)
                    ->orWhereNull('putaranblok');
            });
        }

        $jadwalTerpakai = $jadwalQuery->get()
            ->map(function ($item) {
                return [
                    'guruid' => (int) ($item->guruid ?? 0),
                    'kelasid' => (int) ($item->kelasid ?? 0),
                    'ruanganid' => !empty($item->ruanganid)
                        ? (int) $item->ruanganid
                        : null,
                    'hari' => strtolower((string) ($item->hari ?? '')),
                    'jamMulai' => substr(
                        (string) ($item->jamMulai ?? ''),
                        0,
                        5
                    ),
                    'jamSelesai' => substr(
                        (string) ($item->jamSelesai ?? ''),
                        0,
                        5
                    ),
                    'namamapel' => $item->namamapel ?? '',
                    'mapelid' => !empty($item->mapelid)
                        ? (int) $item->mapelid
                        : null,
                    'putaranblok' => $item->putaranblok ?? null,
                ];
            })
            ->all();

        DB::beginTransaction();

        try {
            foreach ($dataKelas as $itemKelas) {
                $namaKelasPdf = trim(
                    (string) ($itemKelas['kelas'] ?? '')
                );

                try {
                    $namaKelas = $this->normalisasiNamaKelas(
                        $namaKelasPdf
                    );

                    $kunciKelas = $this->kunciNamaKelas($namaKelas);

                    if (!isset($cacheKelas[$kunciKelas])) {
                        $cacheKelas[$kunciKelas] =
                            $this->cariAtauBuatKelas($namaKelas);
                    }

                    $kelasId = $cacheKelas[$kunciKelas];

                } catch (Throwable $e) {
                    $gagal++;

                    if (count($daftarError) < 100) {
                        $daftarError[] =
                            'Kelas ' . $namaKelasPdf . ': ' .
                            $e->getMessage();
                    }

                    continue;
                }

                foreach (($itemKelas['jadwal'] ?? []) as $item) {
                    try {
                        $hari = strtolower(trim(
                            (string) ($item['hari'] ?? '')
                        ));

                        $mulai = trim(
                            (string) ($item['jamMulai'] ?? '')
                        );

                        $selesai = trim(
                            (string) ($item['jamSelesai'] ?? '')
                        );

                        $namaGuru = trim(
                            (string) ($item['namaguru'] ?? '')
                        );

                        $namaMapel = trim(
                            (string) ($item['namamapel'] ?? '')
                        );

                        $namaRuangan = trim(
                            (string) ($item['ruangan'] ?? '')
                        );

                        if (
                            !in_array($hari, [
                                'senin',
                                'selasa',
                                'rabu',
                                'kamis',
                                'jumat',
                            ], true) ||
                            !$this->waktuValid($mulai) ||
                            !$this->waktuValid($selesai) ||
                            $mulai >= $selesai ||
                            $namaGuru === '' ||
                            $namaMapel === ''
                        ) {
                            throw new \RuntimeException(
                                'Data hari, jam, guru, atau mata pelajaran tidak valid.'
                            );
                        }

                        /*
                         * PENCOCOKAN GURU OTOMATIS.
                         */

                        $kunciGuru = $this->kunciNama($namaGuru);

                        if ($kunciGuru === '') {
                            throw new \RuntimeException(
                                'Nama guru kosong setelah dinormalisasi.'
                            );
                        }

                        if (!array_key_exists($kunciGuru, $cacheGuru)) {
                            $daftarId = array_values(array_unique(
                                $guruGrup[$kunciGuru] ?? []
                            ));

                            if (count($daftarId) === 1) {
                                $cacheGuru[$kunciGuru] = $daftarId[0];

                            } elseif (count($daftarId) > 1) {
                                $cacheGuru[$kunciGuru] = null;

                            } else {
                                $cacheGuru[$kunciGuru] =
                                    $this->cariAtauBuatGuruDariAkun(
                                        $namaGuru,
                                        $akunGuruGrup,
                                        $guruGrup
                                    );
                            }
                        }

                        $guruId = $cacheGuru[$kunciGuru];

                        if (!$guruId) {
                            throw new \RuntimeException(
                                'Guru tidak ditemukan atau memiliki nama yang ambigu: ' .
                                $namaGuru
                            );
                        }

                        /*
                         * PENCOCOKAN MATA PELAJARAN.
                         */

                        $kunciMapel = $this->kunciNama($namaMapel);

                        if (!array_key_exists($kunciMapel, $cacheMapel)) {
                            $daftarId = $mapelGrup[$kunciMapel] ?? [];

                            $cacheMapel[$kunciMapel] =
                                count($daftarId) === 1
                                    ? $daftarId[0]
                                    : null;
                        }

                        $mapelId = $cacheMapel[$kunciMapel];

                        /*
                         * COCOKKAN ATAU BUAT RUANGAN.
                         */

                        $ruanganId = null;

                        if (
                            $namaRuangan !== '' &&
                            Schema::hasTable('ruangan')
                        ) {
                            $kunciRuangan = $this->kunciNama(
                                $namaRuangan
                            );

                            if (!array_key_exists(
                                $kunciRuangan,
                                $cacheRuangan
                            )) {
                                $kolomRuangan = Schema::getColumnListing(
                                    'ruangan'
                                );

                                $ruangan = null;

                                if (in_array('koderuangan', $kolomRuangan)) {
                                    $ruangan = DB::table('ruangan')
                                        ->whereRaw(
                                            'UPPER(TRIM(koderuangan)) = ?',
                                            [strtoupper($namaRuangan)]
                                        )
                                        ->first();
                                }

                                if (
                                    !$ruangan &&
                                    in_array('namaruangan', $kolomRuangan)
                                ) {
                                    $ruangan = DB::table('ruangan')
                                        ->whereRaw(
                                            'UPPER(TRIM(namaruangan)) = ?',
                                            [strtoupper($namaRuangan)]
                                        )
                                        ->first();
                                }

                                if ($ruangan) {
                                    $ruanganId = (int) $ruangan->id;

                                } elseif (
                                    in_array('koderuangan', $kolomRuangan)
                                ) {
                                    $dataRuangan = [
                                        'koderuangan' => strtoupper(
                                            $namaRuangan
                                        ),
                                        'namaruangan' => strtoupper(
                                            $namaRuangan
                                        ),
                                        'status' => 'aktif',
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ];

                                    $ruanganId = (int) DB::table('ruangan')
                                        ->insertGetId(
                                            $this->filterKolom(
                                                'ruangan',
                                                $dataRuangan
                                            )
                                        );
                                }

                                $cacheRuangan[$kunciRuangan] = $ruanganId;
                            }

                            $ruanganId = $cacheRuangan[$kunciRuangan];
                        }

                        /*
                         * DATA JADWAL BARU.
                         */

                        $jadwalBaru = [
                            'guruid' => $guruId,
                            'kelasid' => $kelasId,
                            'ruanganid' => $ruanganId,
                            'hari' => $hari,
                            'jamMulai' => $mulai,
                            'jamSelesai' => $selesai,
                            'namamapel' => $namaMapel,
                            'mapelid' => $mapelId,
                        ];

                        if (Schema::hasColumn('jadwal', 'putaranblok')) {
                            $jadwalBaru['putaranblok'] = $putaran;
                        }

                        /*
                         * CEK DUPLIKAT DAN BENTROK.
                         */

                        $statusBentrok = $this->cekBentrokJadwal(
                            $jadwalBaru,
                            $jadwalTerpakai
                        );

                        if ($statusBentrok === 'duplikat') {
                            $duplikat++;
                            continue;
                        }

                        if ($statusBentrok !== null) {
                            $bentrok++;

                            if (count($daftarError) < 100) {
                                $daftarError[] =
                                    $namaKelas . ' ' .
                                    ucfirst($hari) . ' ' .
                                    $mulai . '-' . $selesai .
                                    ': ' . $statusBentrok;
                            }

                            continue;
                        }

                        $kelas = DB::table('kelas')
                            ->where('id', $kelasId)
                            ->first();

                        $dataSimpan = [
                            'guruid' => $guruId,
                            'mapelid' => $mapelId,
                            'namamapel' => $namaMapel,
                            'tingkat' => $kelas->tingkat ?? null,
                            'jurusanid' => $kelas->jurusanid ?? null,
                            'kelasid' => $kelasId,
                            'ruanganid' => $ruanganId,
                            'hari' => $hari,
                            'jamke' => isset($item['jamke'])
                                ? (int) $item['jamke']
                                : null,
                            'jamMulai' => $mulai,
                            'jamSelesai' => $selesai,
                            'blok' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        if (Schema::hasColumn('jadwal', 'putaranblok')) {
                            $dataSimpan['putaranblok'] = $putaran;
                        }

                        if (Schema::hasColumn('jadwal', 'tanggalMulaiBlok')) {
                            $dataSimpan['tanggalMulaiBlok'] = null;
                        }

                        if (Schema::hasColumn('jadwal', 'tanggalSelesaiBlok')) {
                            $dataSimpan['tanggalSelesaiBlok'] = null;
                        }

                        DB::table('jadwal')->insert(
                            $this->filterKolom('jadwal', $dataSimpan)
                        );

                        $jadwalTerpakai[] = $jadwalBaru;

                        $berhasil++;

                    } catch (Throwable $e) {
                        $gagal++;

                        if (count($daftarError) < 100) {
                            $daftarError[] =
                                $namaKelas . ': ' . $e->getMessage();
                        }

                        report($e);
                    }
                }
            }

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        $pesan = "Import PDF Putaran {$putaran} selesai. ";
        $pesan .= "{$jumlahKelas} kelas terbaca, ";
        $pesan .= "{$berhasil} jadwal ditambahkan, ";
        $pesan .= "{$duplikat} duplikat dilewati, ";
        $pesan .= "{$bentrok} jadwal bentrok dilewati, ";
        $pesan .= "{$gagal} gagal diproses.";

        return back()
            ->with(
                ($gagal > 0 || $bentrok > 0) ? 'warning' : 'success',
                $pesan
            )
            ->with('import_errors', $daftarError);
    }

    /*
    |--------------------------------------------------------------------------
    | PEMERIKSAAN DUPLIKAT DAN BENTROK
    |--------------------------------------------------------------------------
    */

    private function cekBentrokJadwal(
        array $baru,
        array $jadwalTerpakai
    ): ?string {
        foreach ($jadwalTerpakai as $lama) {
            if (
                strtolower($lama['hari']) !== strtolower($baru['hari']) ||
                !$this->waktuBeririsan(
                    $baru['jamMulai'],
                    $baru['jamSelesai'],
                    $lama['jamMulai'],
                    $lama['jamSelesai']
                )
            ) {
                continue;
            }

            $kelasSama = $lama['kelasid'] === $baru['kelasid'];
            $guruSama = $lama['guruid'] === $baru['guruid'];

            $ruanganSama = (
                $baru['ruanganid'] !== null &&
                $lama['ruanganid'] === $baru['ruanganid']
            );

            $mapelSama = (
                ($lama['mapelid'] !== null &&
                    $baru['mapelid'] !== null &&
                    $lama['mapelid'] === $baru['mapelid']) ||
                $this->kunciNama($lama['namamapel'] ?? '') ===
                    $this->kunciNama($baru['namamapel'] ?? '')
            );

            $waktuSama =
                $lama['jamMulai'] === $baru['jamMulai'] &&
                $lama['jamSelesai'] === $baru['jamSelesai'];

            if (
                $kelasSama &&
                $guruSama &&
                $mapelSama &&
                $waktuSama
            ) {
                return 'duplikat';
            }

            if ($kelasSama) {
                return 'Bentrok dengan jadwal lain pada kelas yang sama.';
            }

            if ($guruSama) {
                return 'Guru ' . $baru['guruid'] .
                    ' sudah memiliki jadwal pada waktu tersebut.';
            }

            if ($ruanganSama) {
                return 'Ruangan sudah digunakan pada waktu tersebut.';
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | CEK IRISAN JAM
    |--------------------------------------------------------------------------
    */

    private function waktuBeririsan(
        string $mulaiA,
        string $selesaiA,
        string $mulaiB,
        string $selesaiB
    ): bool {
        return $mulaiA < $selesaiB && $selesaiA > $mulaiB;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI FORMAT JAM
    |--------------------------------------------------------------------------
    */

    private function waktuValid(string $waktu): bool
    {
        return (bool) preg_match(
            '/^(?:[01]\d|2[0-3]):[0-5]\d$/',
            $waktu
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALISASI NAMA GURU DAN MAPEL
    |--------------------------------------------------------------------------
    */

    private function kunciNama(?string $nama): string
    {
        $nama = strtoupper(trim((string) $nama));

        if ($nama === '') {
            return '';
        }

        // Hilangkan tanda baca dan variasi apostrof.
        $nama = str_replace(
            ['’', '‘', "'", '"', '`'],
            '',
            $nama
        );

        // Pisahkan nama berdasarkan spasi dan tanda baca.
        $kata = preg_split('/[\s,\.\/]+/u', $nama);

        $kata = array_values(array_filter(array_map(
            function ($item) {
                return preg_replace('/[^A-Z0-9]/', '', $item);
            },
            $kata
        )));

        /*
         * Gelar belakang yang dihilangkan.
         * Termasuk variasi gelar yang sering muncul di Excel/PDF.
         */

        $gelarBelakang = [
            'SPD',
            'MPD',
            'SPDI',
            'MPDI',
            'SPDSD',
            'SAG',
            'MAG',
            'SH',
            'MH',
            'SE',
            'MM',
            'SIP',
            'MSI',
            'MKOM',
            'SKOM',
            'ST',
            'MT',
            'SST',
            'MST',
            'SKM',
            'MKM',
            'SKEP',
            'MKES',
            'SP',
            'MP',
            'SPSI',
            'MPSI',
            'SARS',
            'MARS',
            'MENG',
            'PHD',
            'SS',
            'SAS',
            'SDS',
            'SFil',
            'SFA',
            'STh',
            'MTh',
            'MHum',
            'MKom',
            'MSc',
            'MA',
            'MBA',
            'LLM',
            'SIP',
            'SStat',
            'STr',
            'MTr',
        ];

        /*
         * Gelar yang terpecah menjadi beberapa token.
         * Contoh: S.Pd.Si menjadi S + PD + SI.
         */

        $polaGelar = [
            ['S', 'PD', 'I'],
            ['M', 'PD', 'I'],
            ['S', 'PD', 'SI'],
            ['M', 'PD', 'SI'],
            ['S', 'PD', 'SD'],
            ['M', 'PD', 'SD'],
            ['S', 'PD'],
            ['M', 'PD'],
            ['S', 'KOM'],
            ['M', 'KOM'],
            ['S', 'SI'],
            ['M', 'SI'],
            ['S', 'TI'],
            ['M', 'TI'],
            ['S', 'S'],
            ['M', 'M'],
            ['S', 'E'],
            ['M', 'E'],
            ['S', 'H'],
            ['M', 'H'],
            ['S', 'T'],
            ['M', 'T'],
            ['S', 'IP'],
            ['M', 'SI'],
            ['S', 'AG'],
            ['M', 'AG'],
            ['S', 'Psi'],
            ['M', 'Psi'],
        ];

        do {
            $berubah = false;

            foreach ($polaGelar as $pola) {
                $jumlah = count($pola);

                if (
                    count($kata) >= $jumlah &&
                    array_slice($kata, -$jumlah) === $pola
                ) {
                    array_splice($kata, -$jumlah);
                    $berubah = true;
                    break;
                }
            }

            if ($berubah) {
                continue;
            }

            if (!empty($kata)) {
                $terakhir = end($kata);

                if (in_array($terakhir, $gelarBelakang, true)) {
                    array_pop($kata);
                    $berubah = true;
                }
            }

        } while ($berubah);

        /*
         * Hilangkan gelar depan.
         */

        while (!empty($kata)) {
            $pertama = reset($kata);

            if (in_array($pertama, [
                'DR',
                'DRS',
                'DRA',
                'PROF',
                'IR',
                'H',
                'HJ',
            ], true)) {
                array_shift($kata);
            } else {
                break;
            }
        }

        return implode('', $kata);
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT NIS / NIP
    |--------------------------------------------------------------------------
    */

    private function formatNomor($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $value = preg_replace('/\.0+$/', '', $value);

        return preg_match('/^\d+$/', $value)
            ? $value
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | EMAIL UNIK
    |--------------------------------------------------------------------------
    */

    private function buatEmailUnik(
        string $identitas,
        string $jenis
    ): string {
        $emailTerdaftar = array_fill_keys(
            DB::table('users')->pluck('email')->filter()->all(),
            true
        );

        return $this->buatEmailUnikCache(
            $identitas,
            $jenis,
            $emailTerdaftar
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EMAIL UNIK DENGAN CACHE
    |--------------------------------------------------------------------------
    */

    private function buatEmailUnikCache(
        string $identitas,
        string $jenis,
        array &$emailTerdaftar
    ): string {
        $identitas = preg_replace('/[^A-Za-z0-9]/', '', $identitas);

        $email = $identitas . '@' . $jenis . '.scp.local';
        $nomor = 1;

        while (isset($emailTerdaftar[$email])) {
            $email = $identitas .
                $nomor .
                '@' . $jenis . '.scp.local';

            $nomor++;
        }

        $emailTerdaftar[$email] = true;

        return $email;
    }
}