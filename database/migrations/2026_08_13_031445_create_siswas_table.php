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
    Schema::create('siswa', function (Blueprint $table) {
        $table->id();
        $table->string('nisn', 20)->unique();
        $table->string('nama', 100);
        $table->enum('jeniskelamin', ['L', 'P']);
        $table->string('tempatlahir', 100)->nullable();
        $table->date('tanggallahir')->nullable();
        $table->text('alamat')->nullable();
        $table->string('nomortelepon', 20)->nullable();

        $table->foreignId('kelasid')
            ->constrained('kelas')
            ->cascadeOnUpdate()
            ->restrictOnDelete();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
