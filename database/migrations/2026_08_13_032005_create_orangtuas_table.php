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
    Schema::create('orangtuas', function (Blueprint $table) {
        $table->id();

        $table->foreignId('siswaid')
            ->constrained('siswa')
            ->cascadeOnUpdate()
            ->cascadeOnDelete();

        $table->string('nama', 100);
        $table->enum('hubungan', ['ayah', 'ibu', 'wali']);
        $table->string('nomortelepon', 20)->nullable();
        $table->text('alamat')->nullable();

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orangtuas');
    }
};
