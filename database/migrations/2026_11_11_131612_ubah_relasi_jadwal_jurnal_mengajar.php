<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jurnal_mengajar', function (Blueprint $table) {
            // Hapus relasi lama yang menghapus jurnal otomatis.
            $table->dropForeign(['jadwalid']);

            // Jadwal boleh kosong jika jadwal asal dihapus.
            $table->unsignedBigInteger('jadwalid')
                ->nullable()
                ->change();

            // Jurnal tetap tersimpan ketika jadwal dihapus.
            $table->foreign('jadwalid')
                ->references('id')
                ->on('jadwal')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jurnal_mengajar', function (Blueprint $table) {
            $table->dropForeign(['jadwalid']);

            $table->foreign('jadwalid')
                ->references('id')
                ->on('jadwal')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }
};