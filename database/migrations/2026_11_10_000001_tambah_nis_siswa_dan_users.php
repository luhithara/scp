
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan NIS terpisah dari NISN.
     */
    public function up(): void
    {
        // Tambahkan NIS pada tabel siswa.
        if (!Schema::hasColumn('siswa', 'nis')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->string('nis', 20)
                    ->nullable()
                    ->unique('siswa_nis_unique')
                    ->after('id');
            });
        }

        // NISN boleh kosong sampai data resmi tersedia.
        if (Schema::hasColumn('siswa', 'nisn')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->string('nisn', 20)
                    ->nullable()
                    ->change();
            });
        }

        // Tambahkan NIS pada tabel users untuk login.
        if (!Schema::hasColumn('users', 'nis')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('nis', 20)
                    ->nullable()
                    ->unique('users_nis_unique')
                    ->after('email');
            });
        }
    }

    /**
     * Menghapus kolom NIS jika migration dibatalkan.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'nis')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_nis_unique');
                $table->dropColumn('nis');
            });
        }

        if (Schema::hasColumn('siswa', 'nis')) {
            Schema::table('siswa', function (Blueprint $table) {
                $table->dropUnique('siswa_nis_unique');
                $table->dropColumn('nis');
            });
        }

        // NISN sengaja tetap nullable agar data yang sudah
        // diimpor tanpa NISN tidak menyebabkan kehilangan data.
    }
};
