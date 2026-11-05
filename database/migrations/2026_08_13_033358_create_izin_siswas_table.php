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
    Schema::create('izin_siswa', function (Blueprint $table) {
        $table->id();

        $table->foreignId('siswaid')
            ->constrained('siswa')
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

        $table->enum('jenis', [
            'izin',
            'sakit'
        ]);

        $table->text('alasan');

        $table->timestamp('waktu')->useCurrent();

        $table->date('tanggal')->useCurrent();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_siswa');
    }
};
