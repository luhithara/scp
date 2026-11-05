<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('namajabatan', 50)->unique();
            $table->timestamps();
        });

        Schema::create('userjabatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userid')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('jabatanid')
                ->constrained('jabatan')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['userid', 'jabatanid']);
        });

        DB::table('jabatan')->insert([
            [
                'namajabatan' => 'siswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'namajabatan' => 'orangtua',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'namajabatan' => 'guru',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'namajabatan' => 'waka',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $users = DB::table('users')->get();

        foreach ($users as $user) {
            $jabatan = DB::table('jabatan')
                ->where('namajabatan', $user->role)
                ->first();

            if ($jabatan) {
                DB::table('userjabatan')->insert([
                    'userid' => $user->id,
                    'jabatanid' => $jabatan->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('userjabatan');
        Schema::dropIfExists('jabatan');
    }
};