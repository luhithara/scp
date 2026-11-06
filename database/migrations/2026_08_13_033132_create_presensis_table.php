<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('siswaid')
                ->constrained('siswa')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('jadwalid')
                ->constrained('jadwal')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->date('tanggal_presensi');

            $table->enum('status', [
                'hadir',
                'sakit',
                'izin',
                'alpa'
            ]);

            $table->text('keterangan')->nullable();

            $table->timestamp('waktu')->useCurrent();

            $table->timestamps();

            $table->unique(
                ['siswaid', 'jadwalid', 'tanggal_presensi'],
                'presensi_siswa_jadwal_tanggal_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};