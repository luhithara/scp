<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan kolom hanya jika belum tersedia.
        if (!Schema::hasColumn('presensi', 'tanggal_presensi')) {
            Schema::table('presensi', function (Blueprint $table) {
                $table->date('tanggal_presensi')->nullable()->after('jadwalid');
            });
        }

        // Isi tanggal untuk data lama yang belum memiliki tanggal.
        DB::table('presensi')
            ->whereNull('tanggal_presensi')
            ->select('id', 'waktu')
            ->orderBy('id')
            ->chunkById(500, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('presensi')
                        ->where('id', $row->id)
                        ->update([
                            'tanggal_presensi' => date(
                                'Y-m-d',
                                strtotime($row->waktu)
                            ),
                        ]);
                }
            });

        // Pastikan index pendukung foreign key tersedia.
        $indexes = collect(Schema::getIndexes('presensi'))
            ->pluck('name')
            ->all();

        if (!in_array('presensi_siswaid_fk_idx', $indexes)) {
            Schema::table('presensi', function (Blueprint $table) {
                $table->index('siswaid', 'presensi_siswaid_fk_idx');
            });
        }

        if (!in_array('presensi_jadwalid_fk_idx', $indexes)) {
            Schema::table('presensi', function (Blueprint $table) {
                $table->index('jadwalid', 'presensi_jadwalid_fk_idx');
            });
        }

        // Hapus unique index lama jika masih tersedia.
        $indexes = collect(Schema::getIndexes('presensi'))
            ->pluck('name')
            ->all();

        if (in_array('presensi_siswaid_jadwalid_unique', $indexes)) {
            Schema::table('presensi', function (Blueprint $table) {
                $table->dropUnique('presensi_siswaid_jadwalid_unique');
            });
        }

        // Buat unique index baru jika belum tersedia.
        $indexes = collect(Schema::getIndexes('presensi'))
            ->pluck('name')
            ->all();

        if (!in_array('presensi_siswa_jadwal_tanggal_unique', $indexes)) {
            Schema::table('presensi', function (Blueprint $table) {
                $table->unique(
                    ['siswaid', 'jadwalid', 'tanggal_presensi'],
                    'presensi_siswa_jadwal_tanggal_unique'
                );
            });
        }
    }

    public function down(): void
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->dropUnique('presensi_siswa_jadwal_tanggal_unique');
        });

        Schema::table('presensi', function (Blueprint $table) {
            $table->unique(
                ['siswaid', 'jadwalid'],
                'presensi_siswaid_jadwalid_unique'
            );

            $table->dropColumn('tanggal_presensi');
        });
    }
};