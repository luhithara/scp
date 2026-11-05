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
    Schema::create('jadwal', function (Blueprint $table) {
        $table->id();

        $table->foreignId('guruid')
            ->constrained('guru')
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

        $table->foreignId('mapelid')
            ->constrained('mapel')
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

        $table->foreignId('kelasid')
            ->constrained('kelas')
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

        $table->enum('hari', [
            'senin',
            'selasa',
            'rabu',
            'kamis',
            'jumat'
        ]);

        $table->time('jamMulai');
        $table->time('jamSelesai');

        $table->integer('blok')->default(1);

        $table->date('tanggalMulaiBlok');
        $table->date('tanggalSelesaiBlok');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
