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
    Schema::create('jurnal_mengajar', function (Blueprint $table) {
        $table->id();

        $table->foreignId('jadwalid')
            ->constrained('jadwal')
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

        $table->foreignId('guruid')
            ->constrained('guru')
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

        $table->date('tanggal')->useCurrent();

        $table->text('materi');
        $table->text('kegiatan')->nullable();
        $table->text('catatan')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_mengajar');
    }
};
