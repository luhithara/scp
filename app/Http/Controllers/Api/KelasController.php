<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\JsonResponse;

class KelasController extends Controller
{
    /**
     * Mengambil daftar nama kelas untuk aplikasi Flutter.
     */
    public function index(): JsonResponse
    {
        $kelas = Kelas::query()
            ->orderBy('namakelas')
            ->pluck('namakelas');

        return response()->json($kelas);
    }
}