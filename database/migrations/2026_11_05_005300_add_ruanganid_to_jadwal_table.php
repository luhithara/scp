
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('jadwal', 'ruanganid')) {
            Schema::table('jadwal', function (Blueprint $table) {
                $table->foreignId('ruanganid')
                    ->nullable()
                    ->after('kelasid')
                    ->constrained('ruangan')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('jadwal', 'ruanganid')) {
            Schema::table('jadwal', function (Blueprint $table) {
                $table->dropForeign(['ruanganid']);
                $table->dropColumn('ruanganid');
            });
        }
    }
};