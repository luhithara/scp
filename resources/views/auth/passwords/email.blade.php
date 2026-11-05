@extends('layouts.guest')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;background:#f4f7fb;font-family:Inter,'Segoe UI',Arial,sans-serif;color:#172033;">
    <div style="width:100%;max-width:460px;background:#fff;padding:36px;border:1px solid #e5eaf2;border-radius:18px;box-shadow:0 15px 40px rgba(15,23,42,.08);">
        <a href="{{ route('login') }}" style="color:#2563eb;text-decoration:none;font-size:13px;font-weight:600;">&larr; Kembali ke Login</a>

        <h2 style="margin:24px 0 8px;font-size:25px;">Lupa Password?</h2>

        <p style="margin:0 0 24px;color:#64748b;line-height:1.6;font-size:14px;">
            Masukkan alamat email yang sudah terdaftar di akun SCP. Kami akan mengirimkan kode OTP 6 digit ke email tersebut.
        </p>

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

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label for="email" style="display:block;margin-bottom:8px;font-size:13px;font-weight:600;">
                Email terdaftar
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                placeholder="nama@email.com"
                style="width:100%;height:46px;padding:0 13px;border:1px solid #d8e0eb;border-radius:9px;font-size:14px;outline:none;box-sizing:border-box;"
            >

            <button
                type="submit"
                style="width:100%;height:46px;margin-top:18px;border:0;border-radius:9px;background:#2563eb;color:#fff;font-weight:700;cursor:pointer;"
            >
                Kirim Kode OTP
            </button>
        </form>
    </div>
</div>
@endsection