<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WakaDashboardController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\GuruSiswaController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\IzinController;
use App\Http\Controllers\Api\JurnalController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\RiwayatController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/lupa-password', [AuthController::class, 'lupaPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/ganti-password', [AuthController::class, 'gantiPassword']);

    // Dashboard Waka
    Route::get('/dashboard/waka', [WakaDashboardController::class, 'index']);

    // Data master
    Route::get('/kelas', [KelasController::class, 'index']);
    Route::get('/guru', [GuruSiswaController::class, 'guru']);
    Route::post('/guru/{id}/sk', [GuruSiswaController::class, 'unggahSk']);
    Route::get('/siswa', [GuruSiswaController::class, 'siswa']);

    // Jadwal
    Route::get('/jadwal/kunci', [JadwalController::class, 'kunci']);
    Route::put('/jadwal/kunci', [JadwalController::class, 'setKunci']);
    Route::get('/jadwal', [JadwalController::class, 'index']);
    Route::post('/jadwal', [JadwalController::class, 'store']);
    Route::put('/jadwal/{id}/ruangan', [JadwalController::class, 'setRuangan']);
    Route::put('/jadwal/{id}', [JadwalController::class, 'update']);
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy']);

    // Presensi
    Route::get('/presensi', [PresensiController::class, 'index']);
    Route::post('/presensi/sesi', [PresensiController::class, 'simpanSesi']);

    // Izin
    Route::get('/izin', [IzinController::class, 'index']);
    Route::post('/izin', [IzinController::class, 'store']);
    Route::get('/izin/info-guru', [IzinController::class, 'infoGuru']);
    Route::put('/izin/{id}/proses', [IzinController::class, 'proses']);

    // Jurnal
    Route::get('/jurnal', [JurnalController::class, 'index']);
    Route::post('/jurnal', [JurnalController::class, 'store']);
    Route::get('/jurnal/belum-diisi', [JurnalController::class, 'belumDiisi']);

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);
    Route::put('/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua']);
    Route::put('/notifikasi/{id}/baca', [NotifikasiController::class, 'baca']);
    Route::post('/notifikasi/peringatan', [NotifikasiController::class, 'peringatan']);

    // Riwayat aktivitas
    Route::get('/riwayat', [RiwayatController::class, 'index']);
});