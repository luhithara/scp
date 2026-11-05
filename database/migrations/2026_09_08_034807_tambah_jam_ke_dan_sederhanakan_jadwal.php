<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {

            if (!Schema::hasColumn('jadwal', 'jamke')) {

                $table
                    ->unsignedTinyInteger('jamke')
                    ->nullable()
                    ->after('hari');

            }

        });


        /*
        |--------------------------------------------------------------------------
        | JAM LAMA TIDAK DIGUNAKAN LAGI
        |--------------------------------------------------------------------------
        */

        try {

            DB::statement("
                ALTER TABLE jadwal
                MODIFY jamMulai TIME NULL
            ");

        } catch (\Exception $e) {
        }


        try {

            DB::statement("
                ALTER TABLE jadwal
                MODIFY jamSelesai TIME NULL
            ");

        } catch (\Exception $e) {
        }


        /*
        |--------------------------------------------------------------------------
        | TANGGAL BLOK TIDAK WAJIB
        |--------------------------------------------------------------------------
        */

        try {

            DB::statement("
                ALTER TABLE jadwal
                MODIFY tanggalMulai DATE NULL
            ");

        } catch (\Exception $e) {
        }


        try {

            DB::statement("
                ALTER TABLE jadwal
                MODIFY tanggalSelesai DATE NULL
            ");

        } catch (\Exception $e) {
        }
    }


    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {

            if (Schema::hasColumn('jadwal', 'jamke')) {

                $table->dropColumn('jamke');

            }

        });
    }
};