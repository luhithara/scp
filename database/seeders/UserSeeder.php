<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Siswa Percobaan',
            'email' => 'siswa@scp.test',
            'nisn' => '1234567890',
            'kodepegawai' => null,
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
            'status' => 'aktif',
        ]);

        User::create([
            'name' => 'Orang Tua Percobaan',
            'email' => 'orangtua@scp.test',
            'nisn' => '1234567890',
            'kodepegawai' => null,
            'password' => Hash::make('ortu123'),
            'role' => 'orangtua',
            'status' => 'aktif',
        ]);
    }
}