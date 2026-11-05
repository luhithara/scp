@extends('layouts.guest')

@section('content')
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;background:#f4f7fb;font-family:Inter,'Segoe UI',Arial,sans-serif;color:#172033;">
    <div style="width:100%;max-width:460px;background:#fff;padding:36px;border:1px solid #e5eaf2;border-radius:18px;box-shadow:0 15px 40px rgba(15,23,42,.08);">
        <h2 style="margin:0 0 8px;font-size:25px;">Buat Password Baru</h2>
        <p style="margin:0 0 24px;color:#64748b;line-height:1.6;font-size:14px;">Buat password baru untuk akun SCP kamu.</p>

        @if ($errors->any())
            <div style="padding:12px 14px;margin-bottom:18px;border-radius:10px;background:#fff1f2;color:#be123c;font-size:13px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <label for="email" style="display:block;margin-bottom:8px;font-size:13px;font-weight:600;">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email" style="width:100%;height:44px;padding:0 12px;border:1px solid #d8e0eb;border-radius:9px;font-size:14px;margin-bottom:16px;">

            <label for="password" style="display:block;margin-bottom:8px;font-size:13px;font-weight:600;">Password Baru</label>
            <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" style="width:100%;height:44px;padding:0 12px;border:1px solid #d8e0eb;border-radius:9px;font-size:14px;margin-bottom:16px;">

            <label for="password_confirmation" style="display:block;margin-bottom:8px;font-size:13px;font-weight:600;">Konfirmasi Password Baru</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" style="width:100%;height:44px;padding:0 12px;border:1px solid #d8e0eb;border-radius:9px;font-size:14px;">

            <button type="submit" style="width:100%;height:46px;margin-top:20px;border:0;border-radius:9px;background:#2563eb;color:#fff;font-weight:700;cursor:pointer;">Simpan Password Baru</button>
        </form>
    </div>
</div>
@endsection
