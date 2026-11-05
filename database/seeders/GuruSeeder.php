<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        // Data guru
        DB::table('guru')->updateOrInsert(
            [
                'kodeguru' => 'G001',
            ],
            [
                'nama' => 'Guru Percobaan',
                'nip' => '198001012000011001',
                'nomortelepon' => '081234567891',
                'alamat' => 'Yogyakarta',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // Akun login guru
        DB::table('users')->updateOrInsert(
            [
                'kodepegawai' => 'G001',
            ],
            [
                'name' => 'Guru Percobaan',
                'email' => 'guru@scp.test',
                'nisn' => null,
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'status' => 'aktif',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}