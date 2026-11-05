<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login aplikasi mobile SCP.
     */
    public function login(Request $request)
    {
        $identitas = trim(
            $request->input('username')
            ?? $request->input('identitas')
            ?? ''
        );

        $password = $request->input('password', '');
        $jenisAkun = strtolower(trim($request->input('jenisakun', '')));

        if ($identitas === '' || $password === '' || $jenisAkun === '') {
            return response()->json([
                'success' => false,
                'message' => 'Jenis akun, identitas, dan password wajib diisi.',
            ], 422);
        }

        // Normalisasi jenis akun.
        if ($jenisAkun === 'ortu' || $jenisAkun === 'orang tua') {
            $jenisAkun = 'orangtua';
        }

        if ($jenisAkun === 'walikelas' || $jenisAkun === 'wali kelas') {
            $jenisAkun = 'kelas';
        }

        // Cari akun berdasarkan jenis akun.
        $query = User::query();

        switch ($jenisAkun) {
            case 'siswa':
            case 'orangtua':
                $query->where('nisn', $identitas);
                break;

            case 'guru':
            case 'bk':
            case 'waka':
                $query->where('kodepegawai', $identitas);
                break;

            case 'kelas':
                $query->where('kodekelas', $identitas);
                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis akun tidak valid.',
                ], 422);
        }

        $user = $query->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Identitas atau password salah.',
            ], 401);
        }

        // Pastikan role akun sesuai dengan pilihan pengguna.
        $roleUser = strtolower($user->role ?? '');

        if ($roleUser === 'walikelas') {
            $roleUser = 'kelas';
        }

        if ($roleUser !== $jenisAkun) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis akun tidak sesuai dengan akun yang dimasukkan.',
            ], 403);
        }

        // Periksa status akun jika status bernilai nonaktif.
        if (
            $user->status !== null &&
            in_array(strtolower((string) $user->status), ['0', 'nonaktif', 'inactive'], true)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Akun sedang tidak aktif. Silakan hubungi administrator.',
            ], 403);
        }

        // Hapus token lama dari perangkat ini jika ada.
        $user->tokens()
            ->where('name', 'scp-mobile')
            ->delete();

        $token = $user->createToken('scp-mobile')->plainTextToken;

        // Identitas yang dikirim kembali ke Flutter.
        $username = match ($roleUser) {
            'siswa', 'orangtua' => $user->nisn,
            'guru', 'bk', 'waka' => $user->kodepegawai,
            'kelas' => $user->kodekelas,
            default => '',
        };

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => [
                'id' => (string) $user->id,
                'nama' => $user->name,
                'username' => $username,
                'role' => $roleUser,
                'kelas' => $user->kelas?->namakelas,
                'mapel' => null,
            ],
        ]);
    }

    /**
     * Logout aplikasi mobile.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Ganti password setelah login.
     */
    public function gantiPassword(Request $request)
    {
        $request->validate([
            'passwordLama' => ['required', 'string'],
            'passwordBaru' => ['required', 'string', 'min:6'],
        ], [
            'passwordLama.required' => 'Password lama wajib diisi.',
            'passwordBaru.required' => 'Password baru wajib diisi.',
            'passwordBaru.min' => 'Password baru minimal 6 karakter.',
        ]);

        $user = $request->user();

        if (!Hash::check($request->passwordLama, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai.',
            ], 422);
        }

        $user->password = Hash::make($request->passwordBaru);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diganti.',
        ]);
    }

    /**
     * Lupa password.
     *
     * Reset password tidak dilakukan hanya berdasarkan NISN
     * atau kode pegawai karena identitas tersebut bukan bukti
     * kepemilikan akun.
     */
    public function lupaPassword(Request $request)
    {
        $identitas = trim(
            $request->input('username')
            ?? $request->input('identitas')
            ?? ''
        );

        if ($identitas === '') {
            return response()->json([
                'success' => false,
                'message' => 'Masukkan NISN, kode pegawai, atau kode kelas.',
            ], 422);
        }

        $user = User::where('nisn', $identitas)
            ->orWhere('kodepegawai', $identitas)
            ->orWhere('kodekelas', $identitas)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Silakan hubungi administrator sekolah untuk proses pemulihan password akun.',
        ]);
    }
}