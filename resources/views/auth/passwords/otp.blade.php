@extends('layouts.guest')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;background:#f4f7fb;font-family:Inter,'Segoe UI',Arial,sans-serif;color:#172033;">
    <div style="width:100%;max-width:460px;background:#fff;padding:36px;border:1px solid #e5eaf2;border-radius:18px;box-shadow:0 15px 40px rgba(15,23,42,.08);">

        <a href="{{ route('password.request') }}" style="color:#2563eb;text-decoration:none;font-size:13px;font-weight:600;">
            &larr; Kembali
        </a>

        <h2 style="margin:24px 0 8px;font-size:25px;">Verifikasi Email</h2>

        <p style="margin:0 0 20px;color:#64748b;line-height:1.6;font-size:14px;">
            Masukkan kode OTP yang kami kirim ke email berikut:
        </p>

        <div style="padding:12px 14px;margin-bottom:20px;border-radius:10px;background:#eff6ff;color:#1d4ed8;font-size:14px;font-weight:600;word-break:break-word;">
            {{ $email }}
        </div>

        <div style="padding:12px 14px;margin-bottom:18px;border-radius:10px;background:#fffbeb;color:#92400e;font-size:13px;">
            Kode OTP berlaku selama 10 menit.
        </div>

        @if (session('status'))
            <div style="padding:12px 14px;margin-bottom:18px;border-radius:10px;background:#ecfdf3;color:#166534;font-size:13px;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="padding:12px 14px;margin-bottom:18px;border-radius:10px;background:#fff1f2;color:#be123c;font-size:13px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.verify') }}">
            @csrf

            <label for="otp" style="display:block;margin-bottom:8px;font-size:13px;font-weight:600;">
                Kode OTP
            </label>

            <input
                id="otp"
                name="otp"
                type="text"
                inputmode="numeric"
                pattern="[0-9]{6}"
                maxlength="6"
                minlength="6"
                required
                autofocus
                autocomplete="one-time-code"
                placeholder="Masukkan 6 digit kode"
                style="width:100%;height:48px;padding:0 13px;border:1px solid #d8e0eb;border-radius:9px;font-size:20px;letter-spacing:8px;text-align:center;box-sizing:border-box;"
            >

            <label for="password" style="display:block;margin:18px 0 8px;font-size:13px;font-weight:600;">
                Password Baru
            </label>

            <input
                id="password"
                name="password"
                type="password"
                required
                minlength="8"
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
                style="width:100%;height:46px;padding:0 13px;border:1px solid #d8e0eb;border-radius:9px;font-size:14px;box-sizing:border-box;"
            >

            <label for="password_confirmation" style="display:block;margin:18px 0 8px;font-size:13px;font-weight:600;">
                Konfirmasi Password Baru
            </label>

            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                minlength="8"
                autocomplete="new-password"
                placeholder="Ulangi password baru"
                style="width:100%;height:46px;padding:0 13px;border:1px solid #d8e0eb;border-radius:9px;font-size:14px;box-sizing:border-box;"
            >

            <button
                type="submit"
                style="width:100%;height:46px;margin-top:22px;border:0;border-radius:9px;background:#2563eb;color:#fff;font-weight:700;cursor:pointer;"
            >
                Verifikasi dan Ubah Password
            </button>
        </form>
    </div>
</div>
@endsection