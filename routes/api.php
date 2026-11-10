<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WakaDashboardController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\JadwalController;


/*
|--------------------------------------------------------------------------
| API LOGIN
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/lupa-password', [AuthController::class, 'lupaPassword']);


/*
|--------------------------------------------------------------------------
| API AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/ganti-password', [AuthController::class, 'gantiPassword']);


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD WAKA
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/waka', [WakaDashboardController::class, 'index']);


    /*
    |--------------------------------------------------------------------------
    | DATA KELAS
    |--------------------------------------------------------------------------
    */

    Route::get('/kelas', [KelasController::class, 'index']);


    /*
    |--------------------------------------------------------------------------
    | JADWAL
    |--------------------------------------------------------------------------
    */

    Route::get('/jadwal/kunci', [JadwalController::class, 'kunci']);

    Route::put('/jadwal/kunci', [JadwalController::class, 'setKunci']);

    Route::get('/jadwal', [JadwalController::class, 'index']);

    Route::post('/jadwal', [JadwalController::class, 'store']);

    Route::put('/jadwal/{id}/ruangan', [JadwalController::class, 'setRuangan']);

    Route::put('/jadwal/{id}', [JadwalController::class, 'update']);

    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy']);

});


/*
|--------------------------------------------------------------------------
| CATATAN
|--------------------------------------------------------------------------
|
| API guru, siswa, presensi, izin, jurnal, notifikasi, dan riwayat
| belum diaktifkan karena controller API-nya belum tersedia.
|
| Route tersebut perlu dikembalikan setelah controller dibuat.
|
|--------------------------------------------------------------------------
*/