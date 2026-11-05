<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ForgotPasswordController extends Controller
{
    /**
     * Menampilkan halaman untuk memasukkan email.
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Mencocokkan email dengan database dan mengirim OTP.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $email = Str::lower(trim($request->email));

        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$user) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Email tersebut tidak terdaftar di akun SCP.',
                ]);
        }

        // Membuat kode OTP 6 digit.
        $otp = (string) random_int(100000, 999999);

        // Hapus kode sebelumnya agar hanya ada satu OTP aktif.
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        // Simpan OTP dalam bentuk hash, bukan teks biasa.
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($otp),
            'created_at' => now(),
        ]);

        try {
            Mail::raw(
                "Halo {$user->name},\n\n"
                . "Kami menerima permintaan untuk mengatur ulang password akun SCP kamu.\n\n"
                . "Kode verifikasi OTP kamu adalah:\n\n"
                . "{$otp}\n\n"
                . "Kode ini berlaku selama 10 menit.\n"
                . "Jangan berikan kode ini kepada siapa pun.\n\n"
                . "Jika kamu tidak merasa meminta pengaturan ulang password, abaikan email ini.\n\n"
                . "Salam,\nSistem SCP",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Kode OTP Reset Password SCP');
                }
            );
        } catch (Throwable $e) {
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Email gagal dikirim. Periksa pengaturan SMTP aplikasi.',
                ]);
        }

        session([
            'password_reset_email' => $user->email,
        ]);

        return redirect()
            ->route('password.otp')
            ->with('status', 'Kode OTP sudah dikirim ke email terdaftar kamu.');
    }

    /**
     * Menampilkan halaman verifikasi OTP dan password baru.
     */
    public function showOtpForm()
    {
        if (!session()->has('password_reset_email')) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Silakan masukkan email terlebih dahulu.',
                ]);
        }

        return view('auth.passwords.otp', [
            'email' => session('password_reset_email'),
        ]);
    }

    /**
     * Memeriksa OTP dan menyimpan password baru.
     */
    public function verifyOtpAndReset(Request $request)
    {
        $email = session('password_reset_email');

        if (!$email) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Sesi reset password sudah berakhir. Silakan ulangi.',
                ]);
        }

        $request->validate([
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus terdiri dari 6 angka.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $resetData = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetData) {
            return back()->withErrors([
                'otp' => 'Kode OTP tidak ditemukan. Silakan minta kode baru.',
            ]);
        }

        // OTP hanya berlaku selama 10 menit.
        if (
            !$resetData->created_at ||
            Carbon::parse($resetData->created_at)->addMinutes(10)->isPast()
        ) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.',
                ]);
        }

        if (!Hash::check($request->otp, $resetData->token)) {
            return back()->withErrors([
                'otp' => 'Kode OTP salah. Silakan periksa kembali email kamu.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()
                ->route('password.request')
                ->withErrors([
                    'email' => 'Akun tidak ditemukan.',
                ]);
        }

        // Perbarui password akun.
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // OTP langsung dihapus agar tidak bisa dipakai ulang.
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        session()->forget('password_reset_email');

        return redirect()
            ->route('login')
            ->with('status', 'Password berhasil diperbarui. Silakan login dengan password baru.');
    }
}