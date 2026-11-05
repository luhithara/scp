<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | TABEL RUANGAN
        |--------------------------------------------------------------------------
        */

        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->string('koderuangan', 30)->unique();
            $table->string('namaruangan', 100);
            $table->integer('kapasitas')->nullable();
            $table->enum('status', ['aktif', 'tidakaktif'])->default('aktif');
            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | TAMBAH RUANGAN KE JADWAL
        |--------------------------------------------------------------------------
        */

        Schema::table('jadwal', function (Blueprint $table) {
            $table->foreignId('ruanganid')
                ->nullable()
                ->after('kelasid')
                ->constrained('ruangan')
                ->nullOnDelete();
        });


        /*
        |--------------------------------------------------------------------------
        | TABEL BK
        |--------------------------------------------------------------------------
        */

        Schema::create('bk', function (Blueprint $table) {
            $table->id();

            $table->foreignId('userid')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('kodebk', 30)->unique();

            $table->string('nama', 100);

            $table->enum('status', ['aktif', 'tidakaktif'])
                ->default('aktif');

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | BK MENANGANI KELAS
        |--------------------------------------------------------------------------
        */

        Schema::create('bk_kelas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bkid')
                ->constrained('bk')
                ->cascadeOnDelete();

            $table->foreignId('kelasid')
                ->constrained('kelas')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['bkid', 'kelasid']);
        });


        /*
        |--------------------------------------------------------------------------
        | WALI KELAS PER KELAS
        |--------------------------------------------------------------------------
        */

        Schema::create('wali_kelas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kelasid')
                ->unique()
                ->constrained('kelas')
                ->cascadeOnDelete();

            $table->foreignId('userid')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('kodewalikelas', 30)->unique();

            $table->string('namaakun', 100);

            $table->enum('status', ['aktif', 'tidakaktif'])
                ->default('aktif');

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | TAMBAHAN PRESENSI
        |--------------------------------------------------------------------------
        */

        Schema::table('presensi', function (Blueprint $table) {

            $table->time('waktukeluar')
                ->nullable()
                ->after('waktu');

            $table->time('waktukembali')
                ->nullable()
                ->after('waktukeluar');

            $table->text('alasankeluar')
                ->nullable()
                ->after('waktukembali');

            $table->foreignId('diubaholeh')
                ->nullable()
                ->after('alasankeluar')
                ->constrained('users')
                ->nullOnDelete();

        });


        /*
        |--------------------------------------------------------------------------
        | TAMBAHAN IZIN SISWA
        |--------------------------------------------------------------------------
        */

        Schema::table('izin_siswa', function (Blueprint $table) {

            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak'
            ])->default('menunggu')
                ->after('tanggal');

            $table->foreignId('jadwalid')
                ->nullable()
                ->after('status')
                ->constrained('jadwal')
                ->nullOnDelete();

            $table->foreignId('disetujui_oleh')
                ->nullable()
                ->after('jadwalid')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('waktudisetuju')
                ->nullable()
                ->after('disetujui_oleh');

            $table->text('catatanbk')
                ->nullable()
                ->after('waktudisetuju');

        });


        /*
        |--------------------------------------------------------------------------
        | BUKTI IZIN
        |--------------------------------------------------------------------------
        */

        Schema::create('izin_bukti', function (Blueprint $table) {
            $table->id();

            $table->foreignId('izinid')
                ->constrained('izin_siswa')
                ->cascadeOnDelete();

            $table->string('jenisfile', 30)
                ->nullable();

            $table->string('file');

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | TAMBAHAN JURNAL
        |--------------------------------------------------------------------------
        */

        Schema::table('jurnal_mengajar', function (Blueprint $table) {

            $table->string('fotobukti')
                ->nullable()
                ->after('catatan');

            $table->foreignId('diubaholeh')
                ->nullable()
                ->after('fotobukti')
                ->constrained('users')
                ->nullOnDelete();

        });


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI
        |--------------------------------------------------------------------------
        */

        Schema::create('notifikasi', function (Blueprint $table) {

            $table->id();

            $table->foreignId('userid')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('judul', 150);

            $table->text('pesan');

            $table->string('jenis', 50)
                ->nullable();

            $table->string('url')
                ->nullable();

            $table->boolean('sudahdibaca')
                ->default(false);

            $table->timestamp('waktudibaca')
                ->nullable();

            $table->timestamps();

        });


        /*
        |--------------------------------------------------------------------------
        | RIWAYAT AKTIVITAS
        |--------------------------------------------------------------------------
        */

        Schema::create('riwayat_aktivitas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('userid')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('aktivitas', 150);

            $table->string('tabel')
                ->nullable();

            $table->unsignedBigInteger('dataid')
                ->nullable();

            $table->longText('datasebelum')
                ->nullable();

            $table->longText('datasesudah')
                ->nullable();

            $table->string('ipaddress', 50)
                ->nullable();

            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('riwayat_aktivitas');

        Schema::dropIfExists('notifikasi');

        Schema::table('jurnal_mengajar', function (Blueprint $table) {
            $table->dropForeign(['diubaholeh']);
            $table->dropColumn([
                'fotobukti',
                'diubaholeh'
            ]);
        });

        Schema::dropIfExists('izin_bukti');

        Schema::table('izin_siswa', function (Blueprint $table) {

            $table->dropForeign(['jadwalid']);
            $table->dropForeign(['disetujui_oleh']);

            $table->dropColumn([
                'status',
                'jadwalid',
                'disetujui_oleh',
                'waktudisetuju',
                'catatanbk'
            ]);
        });

        Schema::table('presensi', function (Blueprint $table) {

            $table->dropForeign(['diubaholeh']);

            $table->dropColumn([
                'waktukeluar',
                'waktukembali',
                'alasankeluar',
                'diubaholeh'
            ]);
        });

        Schema::dropIfExists('wali_kelas');

        Schema::dropIfExists('bk_kelas');

        Schema::dropIfExists('bk');

        Schema::table('jadwal', function (Blueprint $table) {

            $table->dropForeign(['ruanganid']);

            $table->dropColumn('ruanganid');

        });

        Schema::dropIfExists('ruangan');
    }
};