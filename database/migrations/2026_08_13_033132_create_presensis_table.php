<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
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

        $table->enum('status', [
            'hadir',
            'sakit',
            'izin',
            'alpa'
        ]);

        $table->text('keterangan')->nullable();

        $table->timestamp('waktu')->useCurrent();

        $table->timestamps();

        $table->unique(['siswaid', 'jadwalid']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
