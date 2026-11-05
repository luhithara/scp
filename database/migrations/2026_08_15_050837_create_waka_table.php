<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waka', function (Blueprint $table) {
            $table->id();

            $table->foreignId('userid')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('kodewaka', 30)->unique();
            $table->string('nama', 100);
            $table->string('nip', 30)->nullable()->unique();
            $table->string('nomortelepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('jabatan', 100)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waka');
    }
};