<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jurusan')->insert([
            [
                'kodejurusan' => 'AKL',
                'namajurusan' => 'Akuntansi dan Keuangan Lembaga',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kodejurusan' => 'LPS',
                'namajurusan' => 'Layanan Perbankan Syariah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kodejurusan' => 'MPLB',
                'namajurusan' => 'Manajemen Perkantoran dan Layanan Bisnis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kodejurusan' => 'PM',
                'namajurusan' => 'Pemasaran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kodejurusan' => 'DKV',
                'namajurusan' => 'Desain Komunikasi Visual',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kodejurusan' => 'RPL',
                'namajurusan' => 'Rekayasa Perangkat Lunak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}