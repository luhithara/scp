<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasColumn('jadwal', 'tanggalMulaiBlok') ||
            Schema::hasColumn('jadwal', 'tanggalSelesaiBlok')
        ) {
            Schema::table('jadwal', function (Blueprint $table) {
                $columns = [];

                if (Schema::hasColumn('jadwal', 'tanggalMulaiBlok')) {
                    $columns[] = 'tanggalMulaiBlok';
                }

                if (Schema::hasColumn('jadwal', 'tanggalSelesaiBlok')) {
                    $columns[] = 'tanggalSelesaiBlok';
                }

                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            if (!Schema::hasColumn('jadwal', 'tanggalMulaiBlok')) {
                $table->date('tanggalMulaiBlok')->nullable();
            }

            if (!Schema::hasColumn('jadwal', 'tanggalSelesaiBlok')) {
                $table->date('tanggalSelesaiBlok')->nullable();
            }
        });
    }
};