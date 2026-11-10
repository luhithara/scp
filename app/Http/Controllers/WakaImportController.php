<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Smalot\PdfParser\Parser as PdfParser;
use Throwable;

class WakaImportController extends Controller
{
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
            'Akses hanya untuk Waka.'
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

        abort_unless(
            in_array($jenis, ['siswa', 'guru', 'kelas'], true),
            404
        );

        $request->validate([
            'file' => 'required|file|max:10240|mimes:xlsx,xls,csv,pdf',
        ], [
            'file.required' => 'Pilih file terlebih dahulu.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
            'file.mimes' => 'Format file harus XLSX, XLS, CSV, atau PDF.',
        ]);

        try {
            $file = $request->file('file');

            $rows = $this->bacaFile(
                $file->getRealPath(),
                strtolower($file->getClientOriginalExtension())
            );
        } catch (Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'File tidak dapat dibaca. Periksa format dan isi file.'
            );
        }

        if (count($rows) < 2) {
            return back()->with(
                'error',
                'Data kosong atau header kolom tidak ditemukan.'
            );
        }

        $headers = array_map(
            fn ($value) => $this->normalisasi($value),
            array_shift($rows)
        );

        $berhasil = 0;
        $dilewati = 0;
        $masalah = [];

        foreach ($rows as $index => $row) {
            $data = [];

            foreach ($headers as $i => $header) {
                if ($header !== '') {
                    $data[$header] = trim((string) ($row[$i] ?? ''));
                }
            }

            if (!array_filter($data, fn ($value) => $value !== '')) {
                continue;
            }

            try {
                DB::transaction(function () use ($jenis, $data) {
                    match ($jenis) {
                        'siswa' => $this->simpanSiswa($data),
                        'guru' => $this->simpanGuru($data),
                        'kelas' => $this->simpanKelas($data),
                    };
                });

                $berhasil++;
            } catch (Throwable $e) {
                $dilewati++;

                report($e);

                if (count($masalah) < 10) {
                    $masalah[] = 'Baris ' . ($index + 2) . ': ' . $e->getMessage();
                }
            }
        }

        $pesan = "Import {$jenis} selesai. Berhasil: {$berhasil}; dilewati: {$dilewati}.";

        if ($masalah) {
            $pesan .= ' Detail: ' . implode(' | ', $masalah);
        }

        return back()->with(
            $dilewati > 0 ? 'warning' : 'success',
            $pesan
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MEMBACA EXCEL, CSV, DAN PDF
    |--------------------------------------------------------------------------
    */

    private function bacaFile(string $path, string $extension): array
    {
        if ($extension === 'pdf') {
            $parser = new PdfParser();
            $text = $parser->parseFile($path)->getText();

            $rows = [];

            foreach (preg_split('/\R/u', $text) as $line) {
                $line = trim($line);

                if ($line === '') {
                    continue;
                }

                // PDF harus memakai pemisah kolom | atau tab.
                $parts = preg_split('/\s*\|\s*|\t+/u', $line);

                if (count($parts) > 1) {
                    $rows[] = $parts;
                }
            }

            return $rows;
        }

        $spreadsheet = IOFactory::load($path);

        return $spreadsheet
            ->getActiveSheet()
            ->toArray(null, true, true, false);
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALISASI HEADER EXCEL
    |--------------------------------------------------------------------------
    */

    private function normalisasi($value): string
    {
        $value = Str::lower(trim((string) $value));

        $value = preg_replace('/[\s_\-\.]+/u', '', $value);

        return match ($value) {
            'nis',
            'nomorinduksiswa' => 'nisn',

            'namasiswa',
            'namaguru',
            'namakelas',
            'namalengkap' => 'nama',

            'jk',
            'jeniskelamin' => 'jeniskelamin',

            'kelasid',
            'namakelas' => 'kelas',

            'jurusanid',
            'kodejurusan',
            'namajurusan' => 'jurusan',

            'tingkatkelas',
            'angkatan' => 'tingkat',

            'nipguru' => 'nip',

            'kodeguruid',
            'kodepengajar' => 'kodeguru',

            'telp',
            'nohp',
            'nomorhp',
            'notelp' => 'nomortelepon',

            'tglahir',
            'tanggallahir' => 'tanggallahir',

            default => $value,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL NILAI KOLOM
    |--------------------------------------------------------------------------
    */

    private function ambil(
        array $data,
        array $keys,
        bool $wajib = true
    ): ?string {
        foreach ($keys as $key) {
            $key = $this->normalisasi($key);

            if (isset($data[$key]) && trim($data[$key]) !== '') {
                return trim($data[$key]);
            }
        }

        if ($wajib) {
            throw new \RuntimeException(
                'Kolom ' . $keys[0] . ' wajib diisi.'
            );
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT SISWA
    |--------------------------------------------------------------------------
    */

    private function simpanSiswa(array $data): void
    {
        $nisn = $this->ambil($data, ['nisn']);
        $nama = $this->ambil($data, ['nama']);
        $kelasText = $this->ambil($data, ['kelas']);

        if (
            DB::table('siswa')->where('nisn', $nisn)->exists() ||
            DB::table('users')->where('nisn', $nisn)->exists()
        ) {
            throw new \RuntimeException(
                "NISN {$nisn} sudah terdaftar."
            );
        }

        $kelas = DB::table('kelas')
            ->where('namakelas', $kelasText)
            ->first();

        if (!$kelas && ctype_digit($kelasText)) {
            $kelas = DB::table('kelas')
                ->where('id', $kelasText)
                ->first();
        }

        if (!$kelas) {
            throw new \RuntimeException(
                "Kelas {$kelasText} tidak ditemukan."
            );
        }

        $gender = strtoupper(
            $this->ambil($data, ['jeniskelamin'])
        );

        if (!in_array($gender, ['L', 'P'], true)) {
            throw new \RuntimeException(
                'Jenis kelamin harus L atau P.'
            );
        }

        $tanggal = $this->ambil(
            $data,
            ['tanggallahir'],
            false
        );

        if ($tanggal) {
            $timestamp = strtotime(str_replace('/', '-', $tanggal));

            if ($timestamp === false) {
                throw new \RuntimeException(
                    'Format tanggal lahir tidak valid.'
                );
            }

            $tanggal = date('Y-m-d', $timestamp);
        }

        DB::table('siswa')->insert([
            'nisn' => $nisn,
            'nama' => $nama,
            'jeniskelamin' => $gender,
            'tempatlahir' => $this->ambil($data, ['tempatlahir'], false),
            'tanggallahir' => $tanggal,
            'alamat' => $this->ambil($data, ['alamat'], false),
            'nomortelepon' => $this->ambil($data, ['nomortelepon'], false),
            'kelasid' => $kelas->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => $nama,
            'email' => $nisn . '@siswa.scp.local',
            'nisn' => $nisn,
            'kodepegawai' => null,
            'role' => 'siswa',
            'status' => 'aktif',
            'password' => Hash::make($nisn),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT GURU
    |--------------------------------------------------------------------------
    */

    private function simpanGuru(array $data): void
    {
        $kodeGuru = $this->ambil(
            $data,
            ['kodeguru', 'nip']
        );

        $nip = $this->ambil(
            $data,
            ['nip', 'kodeguru']
        );

        $nama = $this->ambil($data, ['nama']);

        if (
            DB::table('guru')
                ->where('nip', $nip)
                ->orWhere('kodeguru', $kodeGuru)
                ->orWhere('kodeguru', $nip)
                ->orWhere('nip', $kodeGuru)
                ->exists()
        ) {
            throw new \RuntimeException(
                "Guru dengan NIP {$nip} atau kode {$kodeGuru} sudah terdaftar."
            );
        }

        if (
            DB::table('users')
                ->where('kodepegawai', $nip)
                ->orWhere('kodepegawai', $kodeGuru)
                ->exists()
        ) {
            throw new \RuntimeException(
                "Akun guru dengan kode {$kodeGuru} sudah tersedia."
            );
        }

        $email = $nip . '@guru.scp.local';

        if (DB::table('users')->where('email', $email)->exists()) {
            throw new \RuntimeException(
                "Email akun guru {$email} sudah digunakan."
            );
        }

        DB::table('guru')->insert([
            'kodeguru' => $kodeGuru,
            'nama' => $nama,
            'nip' => $nip,
            'nomortelepon' => $this->ambil($data, ['nomortelepon'], false),
            'alamat' => $this->ambil($data, ['alamat'], false),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => $nama,
            'email' => $email,
            'nisn' => null,
            'kodepegawai' => $nip,
            'role' => 'guru',
            'status' => 'aktif',
            'password' => Hash::make($nip),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT KELAS
    |--------------------------------------------------------------------------
    */

    private function simpanKelas(array $data): void
    {
        $nama = $this->ambil(
            $data,
            ['namakelas', 'nama']
        );

        $jurusanText = $this->ambil(
            $data,
            ['jurusan', 'jurusanid']
        );

        $tingkat = (int) $this->ambil(
            $data,
            ['tingkat']
        );

        if (!in_array($tingkat, [10, 11, 12], true)) {
            throw new \RuntimeException(
                'Tingkat harus 10, 11, atau 12.'
            );
        }

        $jurusan = DB::table('jurusan')
            ->where('kodejurusan', $jurusanText)
            ->orWhere('namajurusan', $jurusanText)
            ->first();

        if (!$jurusan && ctype_digit($jurusanText)) {
            $jurusan = DB::table('jurusan')
                ->where('id', $jurusanText)
                ->first();
        }

        if (!$jurusan) {
            throw new \RuntimeException(
                "Jurusan {$jurusanText} tidak ditemukan."
            );
        }

        if (
            DB::table('kelas')
                ->where('namakelas', $nama)
                ->where('jurusanid', $jurusan->id)
                ->where('tingkat', $tingkat)
                ->exists()
        ) {
            throw new \RuntimeException(
                "Kelas {$nama} tingkat {$tingkat} sudah terdaftar."
            );
        }

        $kodeKelas = strtoupper(
            preg_replace('/[^A-Za-z0-9]+/', '-', trim($nama))
        );

        $kodeKelas = trim($kodeKelas, '-');

        if ($kodeKelas === '') {
            throw new \RuntimeException(
                'Nama kelas tidak valid.'
            );
        }

        $email = strtolower($kodeKelas) . '@kelas.scp.local';

        if (
            DB::table('users')->where('kodekelas', $kodeKelas)->exists() ||
            DB::table('users')->where('email', $email)->exists()
        ) {
            throw new \RuntimeException(
                "Akun kelas {$kodeKelas} sudah ada."
            );
        }

        $kelasId = DB::table('kelas')->insertGetId([
            'namakelas' => $nama,
            'jurusanid' => $jurusan->id,
            'tingkat' => $tingkat,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => $nama,
            'email' => $email,
            'nisn' => null,
            'kodepegawai' => null,
            'kodekelas' => $kodeKelas,
            'kelasid' => $kelasId,
            'role' => 'kelas',
            'status' => 'aktif',
            'password' => Hash::make($kodeKelas),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
