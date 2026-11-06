
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PresensiSiswaController;
use App\Http\Controllers\JadwalSiswaController;
use App\Http\Controllers\JurnalMengajarController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\WakaController;
use App\Http\Controllers\BKController;
use App\Http\Controllers\OrangtuaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\WakaDataController;
use App\Http\Controllers\KelasAkunController;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', [LoginController::class, 'tampil'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.proses');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| FORGOT PASSWORD DENGAN OTP EMAIL
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('/forgot-password/otp', [ForgotPasswordController::class, 'showOtpForm'])
    ->name('password.otp');

Route::post('/forgot-password/otp', [ForgotPasswordController::class, 'verifyOtpAndReset'])
    ->name('password.verify');


/*
|--------------------------------------------------------------------------
| DASHBOARD UMUM
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| SEMUA USER LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PROFIL
    |--------------------------------------------------------------------------
    */

    Route::get('/profil', [ProfilController::class, 'index'])
        ->name('profil');

    Route::get('/profil/edit', [ProfilController::class, 'edit'])
        ->name('profil.edit');

    Route::put('/profil', [ProfilController::class, 'update'])
        ->name('profil.update');


    /*
    |--------------------------------------------------------------------------
    | IZIN
    |--------------------------------------------------------------------------
    */

    Route::get('/izin', [IzinController::class, 'index'])
        ->name('izin');

    Route::post('/izin', [IzinController::class, 'store'])
        ->name('izin.store');


    /*
    |--------------------------------------------------------------------------
    | GURU - DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/guru/dashboard', [GuruController::class, 'dashboard'])
        ->name('guru.dashboard');


    /*
    |--------------------------------------------------------------------------
    | GURU - JADWAL
    |--------------------------------------------------------------------------
    */

    Route::get('/guru/jadwal', [JadwalController::class, 'index'])
        ->name('guru.jadwal');

    Route::post('/guru/jadwal', [JadwalController::class, 'store'])
        ->name('guru.jadwal.simpan');

    Route::delete('/guru/jadwal/{id}', [JadwalController::class, 'destroy'])
    ->name('guru.jadwal.hapus');


    /*
    |--------------------------------------------------------------------------
    | GURU - IZIN
    |--------------------------------------------------------------------------
    */

    Route::get('/guru/izin', [GuruController::class, 'izin'])
        ->name('guru.izin');


    /*
    |--------------------------------------------------------------------------
    | PRESENSI GURU
    |--------------------------------------------------------------------------
    */

    Route::get('/presensi', [PresensiController::class, 'index'])
        ->name('presensi');

    Route::get('/presensi/{jadwalid}', [PresensiController::class, 'create'])
        ->name('presensi.form');

    Route::post('/presensi/{jadwalid}', [PresensiController::class, 'store'])
        ->name('presensi.store');


    /*
    |--------------------------------------------------------------------------
    | JURNAL GURU
    |--------------------------------------------------------------------------
    */

    Route::get('/jurnal-guru', [JurnalMengajarController::class, 'index'])
        ->name('jurnal.guru');

    Route::get('/jurnal-guru/create', [JurnalMengajarController::class, 'create'])
        ->name('jurnal.guru.create');

    Route::post('/jurnal-guru', [JurnalMengajarController::class, 'store'])
        ->name('jurnal.guru.store');

    Route::get('/jurnal-guru/{id}/edit', [JurnalMengajarController::class, 'edit'])
        ->name('jurnal.guru.edit');

    Route::put('/jurnal-guru/{id}', [JurnalMengajarController::class, 'update'])
        ->name('jurnal.guru.update');

    Route::delete('/jurnal-guru/{id}', [JurnalMengajarController::class, 'destroy'])
        ->name('jurnal.guru.destroy');


    /*
    |--------------------------------------------------------------------------
    | NOTIFIKASI
    |--------------------------------------------------------------------------
    */

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])
        ->name('notifikasi.index');

    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])
        ->name('notifikasi.bacaSemua');

    Route::post('/notifikasi/{id}/baca', [NotifikasiController::class, 'baca'])
        ->name('notifikasi.baca');


    /*
    |--------------------------------------------------------------------------
    | PRESENSI SISWA
    |--------------------------------------------------------------------------
    */

    Route::get('/presensi-siswa', [PresensiSiswaController::class, 'index'])
        ->name('presensi.siswa');


    /*
    |--------------------------------------------------------------------------
    | JADWAL SISWA
    |--------------------------------------------------------------------------
    */

    Route::get('/jadwal-siswa', [JadwalSiswaController::class, 'index'])
        ->name('jadwal.siswa');


    /*
    |--------------------------------------------------------------------------
    | WAKA - DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/waka/dashboard', [WakaController::class, 'dashboard'])
        ->name('waka.dashboard');


    /*
    |--------------------------------------------------------------------------
    | WAKA - JADWAL
    |--------------------------------------------------------------------------
    */

    Route::get('/waka/jadwal', [JadwalController::class, 'wakaIndex'])
        ->name('waka.jadwal');

    Route::get('/waka/jadwal/{tingkat}', [JadwalController::class, 'wakaAngkatan'])
        ->name('waka.jadwal.angkatan');

    Route::get('/waka/jadwal/{tingkat}/{jurusanId}', [JadwalController::class, 'wakaJurusan'])
        ->name('waka.jadwal.jurusan');

    Route::get('/waka/jadwal/{tingkat}/{jurusanId}/{kelasId}', [JadwalController::class, 'wakaKelas'])
        ->name('waka.jadwal.kelas');

    Route::post('/waka/jadwal/kunci', [JadwalController::class, 'ubahKunciJadwal'])
        ->name('waka.jadwal.kunci');

    Route::put('/waka/jadwal/{id}/ruangan', [JadwalController::class, 'aturRuangan'])
        ->name('waka.jadwal.ruangan');

    Route::delete('/waka/jadwal/{id}', [JadwalController::class, 'hapusJadwal'])
        ->name('waka.jadwal.hapus');


    /*
    |--------------------------------------------------------------------------
    | WAKA - JURNAL
    |--------------------------------------------------------------------------
    */

    Route::get('/waka/jurnal', [WakaController::class, 'jurnal'])
        ->name('waka.jurnal');


    /*
    |--------------------------------------------------------------------------
    | WAKA - DATA GURU
    |--------------------------------------------------------------------------
    */

    Route::get('/waka/guru', [WakaController::class, 'guru'])
        ->name('waka.guru');

    Route::post('/waka/data/guru', [WakaDataController::class, 'simpanGuru'])
        ->name('waka.guru.simpan');

    Route::get('/waka/guru/{id}/edit', [WakaDataController::class, 'editGuru'])
        ->name('waka.guru.edit');

    Route::put('/waka/guru/{id}', [WakaDataController::class, 'updateGuru'])
        ->name('waka.guru.update');

    Route::delete('/waka/guru/{id}', [WakaDataController::class, 'hapusGuru'])
        ->name('waka.guru.hapus');


    /*
    |--------------------------------------------------------------------------
    | WAKA - DATA SISWA
    |--------------------------------------------------------------------------
    */

    Route::get('/waka/siswa', [WakaController::class, 'siswa'])
        ->name('waka.siswa');

    Route::post('/waka/data/siswa', [WakaDataController::class, 'simpanSiswa'])
        ->name('waka.siswa.simpan');

    Route::get('/waka/siswa/{id}/edit', [WakaDataController::class, 'editSiswa'])
        ->name('waka.siswa.edit');

    Route::put('/waka/siswa/{id}', [WakaDataController::class, 'updateSiswa'])
        ->name('waka.siswa.update');

    Route::delete('/waka/siswa/{id}', [WakaDataController::class, 'hapusSiswa'])
        ->name('waka.siswa.hapus');


    /*
    |--------------------------------------------------------------------------
    | WAKA - DATA KELAS
    |--------------------------------------------------------------------------
    */

    Route::get('/waka/kelas', [WakaController::class, 'kelas'])
        ->name('waka.kelas');

    Route::get('/waka/kelas/angkatan/{tingkat}', [WakaController::class, 'kelasAngkatan'])
        ->name('waka.kelas.angkatan');

    Route::get('/waka/kelas/angkatan/{tingkat}/jurusan/{jurusanId}', [WakaController::class, 'kelasJurusan'])
        ->name('waka.kelas.jurusan');

    Route::get('/waka/kelas/{id}/siswa', [WakaController::class, 'siswaKelas'])
        ->name('waka.kelas.siswa');

    Route::post('/waka/data/kelas', [WakaDataController::class, 'simpanKelas'])
        ->name('waka.kelas.simpan');

    Route::get('/waka/kelas/{id}/edit', [WakaDataController::class, 'editKelas'])
        ->name('waka.kelas.edit');

    Route::put('/waka/kelas/{id}', [WakaDataController::class, 'updateKelas'])
        ->name('waka.kelas.update');

    Route::delete('/waka/kelas/{id}', [WakaDataController::class, 'hapusKelas'])
        ->name('waka.kelas.hapus');


    /*
    |--------------------------------------------------------------------------
    | WAKA - DATA SEKOLAH
    |--------------------------------------------------------------------------
    */

    Route::get('/waka/data', [WakaDataController::class, 'index'])
        ->name('waka.data');


    /*
    |--------------------------------------------------------------------------
    | WAKA - ORANG TUA
    |--------------------------------------------------------------------------
    */

    Route::get('/waka/orangtua', [WakaDataController::class, 'orangtua'])
        ->name('waka.orangtua');

    Route::post('/waka/data/orangtua', [WakaDataController::class, 'simpanOrangtua'])
        ->name('waka.orangtua.simpan');


    /*
    |--------------------------------------------------------------------------
    | BK
    |--------------------------------------------------------------------------
    */

    Route::get('/bk/dashboard', [BKController::class, 'dashboard'])
        ->name('bk.dashboard');

    Route::get('/bk/kelas', [BKController::class, 'kelas'])
        ->name('bk.kelas');

    Route::post('/bk/kelas', [BKController::class, 'simpanKelas'])
        ->name('bk.kelas.simpan');

    Route::get('/bk/siswa', [BKController::class, 'siswa'])
        ->name('bk.siswa');

    Route::get('/bk/siswa/{id}', [BKController::class, 'detailSiswa'])
        ->name('bk.siswa.detail');

    Route::get('/bk/presensi', [BKController::class, 'presensi'])
        ->name('bk.presensi');

    Route::get('/bk/butuh-penanganan', [BKController::class, 'butuhPenanganan'])
        ->name('bk.butuh-penanganan');

    // HALAMAN PERIZINAN BK
    Route::get('/bk/izin', [BKController::class, 'izin'])
        ->name('bk.izin');

    // SETUJUI PERIZINAN
    Route::post('/bk/izin/{id}/setujui', [BKController::class, 'setujuiIzin'])
        ->name('bk.izin.setujui');

    // TOLAK PERIZINAN
    Route::post('/bk/izin/{id}/tolak', [BKController::class, 'tolakIzin'])
        ->name('bk.izin.tolak');


    /*
    |--------------------------------------------------------------------------
    | ORANG TUA
    |--------------------------------------------------------------------------
    */

    Route::get('/orangtua/dashboard', [OrangtuaController::class, 'dashboard'])
        ->name('orangtua.dashboard');

    Route::get('/orangtua/jadwal', [OrangtuaController::class, 'jadwal'])
        ->name('orangtua.jadwal');

    Route::get('/orangtua/presensi', [OrangtuaController::class, 'presensi'])
        ->name('orangtua.presensi');

    Route::get('/orangtua/profil', [OrangtuaController::class, 'profil'])
        ->name('orangtua.profil');

    Route::get('/orangtua/profil/edit', [OrangtuaController::class, 'editProfil'])
        ->name('orangtua.profil.edit');

    Route::put('/orangtua/profil', [OrangtuaController::class, 'updateProfil'])
        ->name('orangtua.profil.update');


    /*
    |--------------------------------------------------------------------------
    | AKUN KELAS
    |--------------------------------------------------------------------------
    */

    Route::get('/kelas/dashboard', [KelasAkunController::class, 'dashboard'])
        ->middleware('auth.kelas')
        ->name('kelas.dashboard');

    Route::post('/kelas/izin/{id}/setujui', [KelasAkunController::class, 'setujuiIzin'])
        ->middleware('auth.kelas')
        ->name('kelas.izin.setujui');

    Route::post('/kelas/izin/{id}/tolak', [KelasAkunController::class, 'tolakIzin'])
        ->middleware('auth.kelas')
        ->name('kelas.izin.tolak');

});