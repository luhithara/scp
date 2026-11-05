<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('pengaturan_jadwal', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();

            // Status penguncian seluruh jadwal
            $table->boolean('terkunci')->default(false);

            // Waka yang terakhir mengubah status
            $table->foreignId('diubah_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });

        // Membuat pengaturan awal: jadwal tidak terkunci
        DB::table('pengaturan_jadwal')->insert([
            'id' => 1,
            'terkunci' => false,
            'diubah_oleh' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_jadwal');
    }
};