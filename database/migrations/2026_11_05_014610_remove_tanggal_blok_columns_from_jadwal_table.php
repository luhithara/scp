
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            if (Schema::hasColumn('jadwal', 'tanggalMulaiBlok')) {
                $table->dropColumn('tanggalMulaiBlok');
            }

            if (Schema::hasColumn('jadwal', 'tanggalSelesaiBlok')) {
                $table->dropColumn('tanggalSelesaiBlok');
            }
        });
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