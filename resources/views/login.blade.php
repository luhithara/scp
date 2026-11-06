@extends('layouts.guest')

@section('content')

<style>
    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100%;
        font-family: Inter, "Segoe UI", Arial, sans-serif;
    }

    body {
        background:
            radial-gradient(circle at top left, rgba(37, 99, 235, 0.12), transparent 35%),
            radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.10), transparent 35%),
            #f4f7fb;
        color: #172033;
    }

    /* WRAPPER UTAMA */

    .login-page {
        min-height: 100vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px;
    }

    .login-container {
        width: 100%;
        max-width: 1080px;
        min-height: 650px;
        display: grid;
        grid-template-columns: 1.05fr 0.95fr;
        background: #ffffff;
        border-radius: 28px;
        overflow: hidden;
        box-shadow:
            0 25px 60px rgba(15, 23, 42, 0.12),
            0 8px 20px rgba(15, 23, 42, 0.06);
    }

    /* BAGIAN KIRI - BACKGROUND FOTO SEKOLAH */

    .login-visual {
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px;

        background:
            linear-gradient(
                145deg,
                rgba(15, 45, 100, 0.86) 0%,
                rgba(21, 84, 181, 0.76) 48%,
                rgba(22, 119, 210, 0.70) 100%
            ),
            url("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ7httwH_3tuHWTi45GxEZZH3iby8omAQEy1fOhMLOHDw&s=10");

        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: #ffffff;
    }

    .login-visual::before {
        content: "";
        position: absolute;
        width: 360px;
        height: 360px;
        top: -180px;
        right: -120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        pointer-events: none;
    }

    .login-visual::after {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        bottom: -150px;
        left: -100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
        pointer-events: none;
    }

    .visual-content {
        position: relative;
        z-index: 2;
    }

    /* LOGO */

    .brand {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 55px;
    }

    .brand-logo {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.25);
        font-size: 18px;
        font-weight: 800;
        letter-spacing: 1px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .brand-name {
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    /* TEXT KIRI */

    .login-visual h1 {
        margin: 0 0 18px;
        max-width: 500px;
        font-size: 42px;
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .login-visual > .visual-content > p {
        margin: 0;
        max-width: 480px;
        color: rgba(255, 255, 255, 0.90);
        font-size: 16px;
        line-height: 1.8;
    }

    /* FITUR KIRI */

    .feature-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-top: 42px;
        max-width: 480px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.10);
        border: 1px solid rgba(255, 255, 255, 0.10);
        color: rgba(255, 255, 255, 0.95);
        font-size: 13px;
        font-weight: 500;
    }

    .feature-icon {
        width: 28px;
        height: 28px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.14);
    }

    .feature-icon svg {
        width: 15px;
        height: 15px;
    }

    /* BAGIAN KANAN */

    .login-form-section {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px;
        background: #ffffff;
    }

    .login-form-box {
        width: 100%;
        max-width: 390px;
    }

    /* HEADER FORM */

    .form-header {
        margin-bottom: 32px;
    }

    .form-header h2 {
        margin: 0 0 10px;
        color: #172033;
        font-size: 30px;
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .form-header p {
        margin: 0;
        color: #718096;
        font-size: 14px;
        line-height: 1.7;
    }

    /* ALERT */

    .alert {
        margin-bottom: 22px;
        padding: 13px 15px;
        border: 1px solid #fecaca;
        border-radius: 10px;
        background: #fef2f2;
        color: #b91c1c;
        font-size: 13px;
        line-height: 1.5;
    }

    /* FORM GROUP */

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #263248;
        font-size: 13px;
        font-weight: 650;
    }

    /* INPUT & SELECT */

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        top: 50%;
        left: 14px;
        width: 18px;
        height: 18px;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }

    .input,
    .select {
        width: 100%;
        height: 48px;
        padding: 0 14px;
        border: 1px solid #dbe2ea;
        border-radius: 11px;
        outline: none;
        background: #ffffff;
        color: #172033;
        font-family: inherit;
        font-size: 14px;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
    }

    .input.with-icon {
        padding-left: 44px;
    }

    .input::placeholder {
        color: #a0aec0;
    }

    .select {
        cursor: pointer;
    }

    .input:hover,
    .select:hover {
        border-color: #b9c7d9;
    }

    .input:focus,
    .select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
        background: #ffffff;
    }

    /* PASSWORD */

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .input {
        padding-right: 48px;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 12px;
        width: 30px;
        height: 30px;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 0;
        background: transparent;
        color: #94a3b8;
        cursor: pointer;
        border-radius: 8px;
        transition:
            color 0.2s ease,
            background 0.2s ease;
    }

    .toggle-password:hover {
        color: #2563eb;
        background: #f1f5f9;
    }

    .toggle-password svg {
        width: 17px;
        height: 17px;
    }

    /* BUTTON */

    .btn-login {
        width: 100%;
        height: 49px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 0;
        border-radius: 11px;
        background: linear-gradient(135deg, #2563eb, #1554b5);
        color: #ffffff;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 8px 18px rgba(37, 99, 235, 0.20);
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            opacity 0.2s ease;
    }

    .btn-login:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(37, 99, 235, 0.25);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .btn-login svg {
        width: 17px;
        height: 17px;
    }

    /* FOOTER */

    .login-footer {
        margin-top: 28px;
        text-align: center;
        color: #94a3b8;
        font-size: 12px;
        line-height: 1.6;
    }

    /* RESPONSIVE TABLET */

    @media (max-width: 850px) {
        .login-page {
            padding: 20px;
        }

        .login-container {
            grid-template-columns: 1fr;
            max-width: 520px;
        }

        .login-visual {
            padding: 42px;
            min-height: 420px;
        }

        .brand {
            margin-bottom: 35px;
        }

        .login-visual h1 {
            font-size: 34px;
        }

        .feature-list {
            margin-top: 30px;
        }

        .login-form-section {
            padding: 42px;
        }
    }

    /* RESPONSIVE MOBILE */

    @media (max-width: 520px) {
        .login-page {
            padding: 12px;
        }

        .login-container {
            border-radius: 20px;
        }

        .login-visual {
            padding: 30px 24px;
            min-height: 390px;
        }

        .brand {
            margin-bottom: 28px;
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            font-size: 16px;
        }

        .brand-name {
            font-size: 18px;
        }

        .login-visual h1 {
            font-size: 29px;
            letter-spacing: -0.5px;
        }

        .login-visual > .visual-content > p {
            font-size: 14px;
            line-height: 1.7;
        }

        .feature-list {
            grid-template-columns: 1fr;
            gap: 9px;
        }

        .login-form-section {
            padding: 32px 24px;
        }

        .form-header h2 {
            font-size: 26px;
        }
    }
</style>


<div class="login-page">

    <div class="login-container">

        {{-- BAGIAN KIRI --}}

        <div class="login-visual">

            <div class="visual-content">

                <div class="brand">

                    <div class="brand-logo">
                        SCP
                    </div>

                    <div class="brand-name">
                        School Class Progress
                    </div>

                </div>

                <h1>
                    Belajar lebih terarah bersama SCP.
                </h1>

                <p>
                    Kelola jadwal, presensi, jurnal mengajar,
                    dan perizinan dalam satu sistem yang rapi
                    dan mudah digunakan.
                </p>

                <div class="feature-list">

                    {{-- Jadwal --}}
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="17" rx="2"/>
                                <path d="M16 2v4M8 2v4M3 10h18"/>
                            </svg>
                        </div>
                        <span>Jadwal Terintegrasi</span>
                    </div>

                    {{-- Presensi --}}
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 11l3 3L21 5"/>
                                <path d="M21 12v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h11"/>
                            </svg>
                        </div>
                        <span>Presensi Siswa</span>
                    </div>

                    {{-- Jurnal --}}
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            </svg>
                        </div>
                        <span>Jurnal Mengajar</span>
                    </div>

                    {{-- Perizinan --}}
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6"/>
                                <path d="M8 13h8M8 17h5"/>
                            </svg>
                        </div>
                        <span>Perizinan</span>
                    </div>

                </div>

            </div>

        </div>


        {{-- BAGIAN KANAN --}}

        <div class="login-form-section">

            <div class="login-form-box">

                {{-- HEADER --}}
                <div class="form-header">
                    <h2>Selamat datang</h2>
                    <p>Masuk ke akun School Class Progress Anda.</p>
                </div>

                @if (session('status'))
                    <div class="alert" style="margin-bottom:16px;">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- ERROR --}}
                @if($errors->any())
                    <div class="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- FORM LOGIN --}}
                <form action="{{ route('login.proses') }}" method="POST">

                    @csrf

                    {{-- JENIS AKUN --}}
                    <div class="form-group">

                        <label for="jenisakun">
                            Masuk sebagai
                        </label>

                        <select
                            class="select"
                            name="jenisakun"
                            id="jenisakun"
                            onchange="ubahIdentitas()"
                            required
                        >

                            <option value="">Pilih jenis akun</option>

                            <option value="siswa" @selected(old('jenisakun') === 'siswa')>
                                Siswa
                            </option>

                            <option value="orangtua" @selected(old('jenisakun') === 'orangtua')>
                                Orang Tua
                            </option>

                            <option value="guru" @selected(old('jenisakun') === 'guru')>
                                Guru
                            </option>

                            <option value="bk" @selected(old('jenisakun') === 'bk')>
                                BK
                            </option>

                            <option value="waka" @selected(old('jenisakun') === 'waka')>
                                Waka
                            </option>

                            <option value="kelas" @selected(old('jenisakun') === 'kelas')>
                                Kelas
                            </option>

                        </select>

                    </div>

                    {{-- IDENTITAS --}}
                    <div class="form-group">

                        <label id="labelIdentitas" for="identitas">
                            NISN
                        </label>

                        <div class="input-wrapper">

                            <svg class="input-icon" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M4 21c0-4 3-7 8-7s8 3 8 7"/>
                            </svg>

                            <input
                                class="input with-icon"
                                type="text"
                                name="identitas"
                                id="identitas"
                                value="{{ old('identitas') }}"
                                placeholder="Masukkan NISN"
                                autocomplete="username"
                                required
                            >

                        </div>

                    </div>

                    {{-- PASSWORD --}}
                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>

                        <div class="password-wrapper">

                            <input
                                class="input"
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="toggle-password"
                                onclick="togglePassword()"
                                aria-label="Tampilkan password"
                            >

                                <svg id="eyeIcon" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"/>
                                    <circle cx="12" cy="12" r="2.5"/>
                                </svg>

                            </button>

                        </div>

                    </div>

                    {{-- TOMBOL LOGIN --}}
                    <button class="btn-login" type="submit">

                        <span>Masuk ke Sistem</span>

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/>
                            <path d="M13 6l6 6-6 6"/>
                        </svg>

                    </button>

                </form>

                <div style="margin-top:16px;text-align:right;">
                    <a href="{{ route('password.request') }}"
                        style="color:#2563eb;font-size:13px;font-weight:600;text-decoration:none;">
                        Lupa Password?
                    </a>
                </div>

                {{-- FOOTER --}}
                <div class="login-footer">
                    School Class Progress &copy; {{ date('Y') }}
                </div>

            </div>

        </div>

    </div>

</div>


<script>
function ubahIdentitas() {
    const jenisAkun = document.getElementById('jenisakun').value;

    const labelIdentitas = document.getElementById('labelIdentitas');
    const inputIdentitas = document.getElementById('identitas');

    if (jenisAkun === 'siswa' || jenisAkun === 'orangtua') {
        labelIdentitas.textContent = 'NISN';
        inputIdentitas.placeholder = 'Masukkan NISN';

    } else if (
        jenisAkun === 'guru' ||
        jenisAkun === 'bk' ||
        jenisAkun === 'waka'
    ) {
        labelIdentitas.textContent = 'Kode Pegawai';
        inputIdentitas.placeholder = 'Masukkan kode pegawai';

    } else if (jenisAkun === 'kelas') {
        labelIdentitas.textContent = 'Kode Kelas';
        inputIdentitas.placeholder = 'Masukkan kode kelas';

    } else {
        labelIdentitas.textContent = 'NISN';
        inputIdentitas.placeholder = 'Masukkan NISN';
    }
}


/* TOGGLE PASSWORD */

function togglePassword() {

    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (password.type === 'password') {

        password.type = 'text';

        eyeIcon.innerHTML = `
            <path d="M3 3l18 18"/>
            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
            <path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a17.7 17.7 0 0 1-3.1 4.2"/>
            <path d="M6.6 6.6C3.7 8.4 2 12 2 12s3.5 8 10 8a10.6 10.6 0 0 0 3.8-.7"/>
        `;

    } else {

        password.type = 'password';

        eyeIcon.innerHTML = `
            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"/>
            <circle cx="12" cy="12" r="2.5"/>
        `;

    }
}


/* JALANKAN SAAT HALAMAN DIBUKA */

document.addEventListener('DOMContentLoaded', function () {
    ubahIdentitas();
});
</script>

@endsection
