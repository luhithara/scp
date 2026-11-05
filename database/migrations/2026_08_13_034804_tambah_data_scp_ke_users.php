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
    Schema::table('users', function (Blueprint $table) {
        $table->string('nisn', 20)->nullable()->after('email');
        $table->string('kodepegawai', 30)->nullable()->after('nisn');
        $table->string('role', 20)->default('siswa')->after('password');
        $table->string('status', 20)->default('aktif')->after('role');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'nisn',
            'kodepegawai',
            'role',
            'status',
        ]);
    });
}
};
