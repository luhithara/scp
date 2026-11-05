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
    Schema::create('guru', function (Blueprint $table) {
        $table->id();
        $table->string('kodeguru', 30)->unique();
        $table->string('nama', 100);
        $table->string('nip', 30)->nullable()->unique();
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
        Schema::dropIfExists('guru');
    }
};
