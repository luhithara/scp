<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil jurusan RPL
        $jurusan = DB::table('jurusan')
            ->where('kodejurusan', 'RPL')
            ->first();

        if (!$jurusan) {
            return;
        }

        // Cek apakah kelas sudah ada
        $kelas = DB::table('kelas')
            ->where('namakelas', 'XI RPL 1')
            ->where('jurusanid', $jurusan->id)
            ->first();

        // Kalau belum ada, buat kelas
        if (!$kelas) {
            $kelasId = DB::table('kelas')->insertGetId([
                'namakelas' => 'XI RPL 1',
                'jurusanid' => $jurusan->id,
                'tingkat' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $kelas = DB::table('kelas')
                ->where('id', $kelasId)
                ->first();
        }

        // Buat data siswa
        DB::table('siswa')->updateOrInsert(
            [
                'nisn' => '1234567890',
            ],
            [
                'nama' => 'Siswa Percobaan',
                'jeniskelamin' => 'L',
                'tempatlahir' => 'Yogyakarta',
                'tanggallahir' => '2010-01-01',
                'alamat' => 'Yogyakarta',
                'nomortelepon' => '081234567890',
                'kelasid' => $kelas->id,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}