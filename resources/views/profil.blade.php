@extends('layouts.app')

@section('title', 'Profil Saya - SCP')

@section('content')

<style>
    /* =========================================================
       PROFIL SISWA / PENGGUNA - SCP
    ========================================================= */

    .profil-page {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .profil-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;

        margin-bottom: 24px;
    }

    .profil-header-text h1 {
        margin: 0 0 7px;

        color: #172033;

        font-size: 28px;
        line-height: 1.25;
        font-weight: 800;
        letter-spacing: -0.4px;
    }

    .profil-header-text p {
        margin: 0;

        color: #718096;

        font-size: 13px;
        line-height: 1.6;
    }

    .profil-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        min-height: 42px;

        padding: 0 16px;

        border: 1px solid #d7e1ee;
        border-radius: 10px;

        background: #ffffff;
        color: #2563eb;

        text-decoration: none;

        font-size: 13px;
        font-weight: 650;

        transition:
            background 0.2s ease,
            border-color 0.2s ease,
            transform 0.2s ease;
    }

    .profil-edit-btn:hover {
        background: #f5f8ff;
        border-color: #bfd1eb;

        transform: translateY(-1px);
    }

    .profil-edit-btn i {
        font-size: 13px;
    }

    /* =========================================================
       SUCCESS
    ========================================================= */

    .profil-success {
        display: flex;
        align-items: center;
        gap: 10px;

        margin-bottom: 20px;
        padding: 13px 15px;

        border: 1px solid #bbf7d0;
        border-radius: 11px;

        background: #f0fdf4;
        color: #166534;

        font-size: 13px;
        line-height: 1.5;
    }

    .profil-success i {
        font-size: 15px;
    }

    /* =========================================================
       PROFILE HERO
    ========================================================= */

    .profil-hero {
        position: relative;
        overflow: hidden;

        display: flex;
        align-items: center;
        gap: 22px;

        margin-bottom: 22px;
        padding: 26px 30px;

        border-radius: 20px;

        background:
            linear-gradient(
                135deg,
                #0f3c88 0%,
                #1554b5 55%,
                #2184d8 100%
            );

        color: #ffffff;

        box-shadow:
            0 10px 26px rgba(15, 60, 136, 0.14);
    }

    .profil-hero::after {
        content: "";

        position: absolute;

        width: 230px;
        height: 230px;

        right: -100px;
        top: -120px;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.07);
    }

    .profil-avatar {
        position: relative;
        z-index: 2;

        width: 68px;
        height: 68px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 1px solid rgba(255, 255, 255, 0.20);
        border-radius: 18px;

        background: rgba(255, 255, 255, 0.12);

        backdrop-filter: blur(8px);
    }

    .profil-avatar i {
        font-size: 28px;

        color: #ffffff;
    }

    .profil-hero-content {
        position: relative;
        z-index: 2;

        min-width: 0;
    }

    .profil-hero-content span {
        display: block;

        margin-bottom: 5px;

        color: rgba(255, 255, 255, 0.72);

        font-size: 12px;
        font-weight: 600;
    }

    .profil-hero-content h2 {
        margin: 0 0 5px;

        color: #ffffff;

        font-size: 22px;
        line-height: 1.3;
        font-weight: 750;
    }

    .profil-hero-content p {
        margin: 0;

        color: rgba(255, 255, 255, 0.80);

        font-size: 13px;
    }

    /* =========================================================
       GRID
    ========================================================= */

    .profil-grid {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            minmax(0, 1fr);

        gap: 20px;
    }

    /* =========================================================
       PANEL
    ========================================================= */

    .profil-card {
        overflow: hidden;

        border: 1px solid #e3eaf2;
        border-radius: 18px;

        background: #ffffff;

        box-shadow:
            0 4px 14px rgba(15, 23, 42, 0.035);
    }

    .profil-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;

        padding: 21px 22px;

        border-bottom: 1px solid #edf1f6;
    }

    .profil-card-title {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .profil-card-title-icon {
        width: 38px;
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: 10px;

        background: #edf4ff;
        color: #2563eb;
    }

    .profil-card-title-icon i {
        font-size: 15px;
    }

    .profil-card-header h3 {
        margin: 0;

        color: #172033;

        font-size: 15px;
        font-weight: 750;
    }

    .profil-card-header p {
        margin: 3px 0 0;

        color: #8a97a8;

        font-size: 11px;
    }

    /* =========================================================
       DATA LIST
    ========================================================= */

    .profil-data {
        padding: 4px 22px 10px;
    }

    .profil-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 25px;

        padding: 17px 0;

        border-bottom: 1px solid #eef2f6;
    }

    .profil-row:last-child {
        border-bottom: 0;
    }

    .profil-label {
        flex: 0 0 42%;

        color: #7b8797;

        font-size: 12px;
        line-height: 1.5;
    }

    .profil-value {
        flex: 1;

        color: #273449;

        font-size: 13px;
        line-height: 1.55;
        font-weight: 600;

        text-align: right;

        word-break: break-word;
    }

    .profil-value.muted {
        color: #9aa5b4;
        font-weight: 500;
    }

    /* =========================================================
       ROLE BADGE
    ========================================================= */

    .profil-role {
        display: inline-flex;
        align-items: center;

        padding: 5px 10px;

        border-radius: 999px;

        background: #edf4ff;
        color: #2563eb;

        font-size: 11px;
        font-weight: 700;
    }

    /* =========================================================
       EMPTY
    ========================================================= */

    .profil-empty {
        padding: 45px 25px;

        text-align: center;
    }

    .profil-empty-icon {
        width: 52px;
        height: 52px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin: 0 auto 14px;

        border-radius: 15px;

        background: #f1f5f9;
        color: #94a3b8;
    }

    .profil-empty-icon i {
        font-size: 21px;
    }

    .profil-empty strong {
        display: block;

        margin-bottom: 5px;

        color: #374151;

        font-size: 14px;
    }

    .profil-empty span {
        color: #8a97a8;

        font-size: 12px;
    }

    /* =========================================================
       BK INFORMATION
    ========================================================= */

    .profil-bk-card {
        display: flex;
        align-items: flex-start;
        gap: 15px;

        margin-top: 20px;
        padding: 20px 22px;

        border: 1px solid #dbe7fb;
        border-radius: 18px;

        background:
            linear-gradient(
                135deg,
                #f5f8ff,
                #ffffff
            );
    }

    .profil-bk-icon {
        width: 45px;
        height: 45px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background: #e8f0ff;
        color: #2563eb;
    }

    .profil-bk-icon i {
        font-size: 18px;
    }

    .profil-bk-content {
        min-width: 0;
    }

    .profil-bk-content strong {
        display: block;

        margin-bottom: 5px;

        color: #24344d;

        font-size: 14px;
    }

    .profil-bk-content p {
        margin: 0;

        color: #718096;

        font-size: 12px;
        line-height: 1.7;
    }

    .profil-bk-code {
        color: #2563eb;
        font-weight: 700;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {

        .profil-grid {
            grid-template-columns: 1fr;
        }

    }

    @media (max-width: 650px) {

        .profil-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .profil-edit-btn {
            width: 100%;
        }

        .profil-hero {
            padding: 22px;
        }

        .profil-avatar {
            width: 58px;
            height: 58px;

            border-radius: 15px;
        }

        .profil-avatar i {
            font-size: 23px;
        }

        .profil-hero-content h2 {
            font-size: 19px;
        }

        .profil-card-header {
            padding: 18px;
        }

        .profil-data {
            padding-left: 18px;
            padding-right: 18px;
        }

        .profil-row {
            gap: 15px;
        }

        .profil-label {
            flex-basis: 40%;
        }

        .profil-bk-card {
            padding: 18px;
        }

    }

    @media (max-width: 480px) {

        .profil-row {
            display: block;
        }

        .profil-label {
            display: block;

            margin-bottom: 5px;
        }

        .profil-value {
            display: block;

            text-align: left;
        }

    }
</style>


<div class="profil-page">


    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="profil-header">

        <div class="profil-header-text">

            <h1>
                Profil Saya
            </h1>

            <p>
                Informasi akun dan data pribadi Anda.
            </p>

        </div>


        <a
            href="{{ route('profil.edit') }}"
            class="profil-edit-btn"
        >

            <i class="fa-solid fa-pen"></i>

            <span>
                Edit Profil
            </span>

        </a>

    </div>



    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="profil-success">

            <i class="fa-solid fa-circle-check"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif



    {{-- =========================================================
         DATA UTAMA
    ========================================================== --}}

    @php

        $data = $siswa
            ?? $guru
            ?? $waka
            ?? $bk
            ?? null;

    @endphp



    {{-- =========================================================
         PROFILE HERO
    ========================================================== --}}

    <div class="profil-hero">

        <div class="profil-avatar">

            <i class="fa-solid fa-user"></i>

        </div>


        <div class="profil-hero-content">

            <span>
                Akun School Class Progress
            </span>

            <h2>
                {{ $user->name ?? 'Pengguna' }}
            </h2>

            <p>

                @switch($user->role ?? '')

                    @case('siswa')
                        Siswa
                        @break

                    @case('orangtua')
                        Orang Tua
                        @break

                    @case('guru')
                        Guru
                        @break

                    @case('waka')
                        Wakil Kepala Sekolah
                        @break

                    @case('bk')
                        Bimbingan Konseling
                        @break

                    @default
                        {{ ucfirst($user->role ?? 'Pengguna') }}

                @endswitch

                &nbsp; · &nbsp;

                Akun aktif

            </p>

        </div>

    </div>



    {{-- =========================================================
         GRID DATA
    ========================================================== --}}

    <div class="profil-grid">


        {{-- =====================================================
             INFORMASI AKUN
        ====================================================== --}}

        <div class="profil-card">

            <div class="profil-card-header">

                <div class="profil-card-title">

                    <div class="profil-card-title-icon">

                        <i class="fa-solid fa-user-lock"></i>

                    </div>

                    <div>

                        <h3>
                            Informasi Akun
                        </h3>

                        <p>
                            Data akses akun Anda
                        </p>

                    </div>

                </div>

            </div>


            <div class="profil-data">


                {{-- NAMA --}}

                <div class="profil-row">

                    <div class="profil-label">
                        Nama
                    </div>

                    <div class="profil-value">
                        {{ $user->name ?? '-' }}
                    </div>

                </div>


                {{-- ROLE --}}

                <div class="profil-row">

                    <div class="profil-label">
                        Role
                    </div>

                    <div class="profil-value">

                        <span class="profil-role">

                            @switch($user->role ?? '')

                                @case('siswa')
                                    Siswa
                                    @break

                                @case('orangtua')
                                    Orang Tua
                                    @break

                                @case('guru')
                                    Guru
                                    @break

                                @case('waka')
                                    Waka
                                    @break

                                @case('bk')
                                    BK
                                    @break

                                @default
                                    {{ ucfirst($user->role ?? '-') }}

                            @endswitch

                        </span>

                    </div>

                </div>


                {{-- EMAIL --}}

                <div class="profil-row">

                    <div class="profil-label">
                        Email
                    </div>

                    <div class="profil-value">

                        {{ $user->email ?? '-' }}

                    </div>

                </div>


                {{-- IDENTITAS --}}

                <div class="profil-row">

                    <div class="profil-label">
                        Identitas Akun
                    </div>

                    <div class="profil-value">

                        @if(($user->role ?? '') === 'siswa' || ($user->role ?? '') === 'orangtua')

                            NISN:
                            {{ $user->nisn ?? '-' }}

                        @elseif(($user->role ?? '') === 'guru')

                            Kode Guru:
                            {{ $user->kodepegawai ?? '-' }}

                        @elseif(($user->role ?? '') === 'waka')

                            Kode Waka:
                            {{ $waka->kodewaka ?? '-' }}

                        @elseif(($user->role ?? '') === 'bk')

                            Kode BK:
                            {{ $bk->kodebk ?? '-' }}

                        @else

                            -

                        @endif

                    </div>

                </div>


            </div>

        </div>



        {{-- =====================================================
             DATA PRIBADI
        ====================================================== --}}

        <div class="profil-card">

            <div class="profil-card-header">

                <div class="profil-card-title">

                    <div class="profil-card-title-icon">

                        <i class="fa-regular fa-id-card"></i>

                    </div>

                    <div>

                        <h3>
                            Data Pribadi
                        </h3>

                        <p>
                            Informasi pribadi pengguna
                        </p>

                    </div>

                </div>

            </div>


            @if($data)

                <div class="profil-data">


                    {{-- NAMA LENGKAP --}}

                    <div class="profil-row">

                        <div class="profil-label">
                            Nama Lengkap
                        </div>

                        <div class="profil-value">

                            {{ $data->nama ?? '-' }}

                        </div>

                    </div>



                    {{-- NIP --}}

                    @if($guru || $waka)

                        <div class="profil-row">

                            <div class="profil-label">
                                NIP
                            </div>

                            <div class="profil-value">

                                {{ $data->nip ?? '-' }}

                            </div>

                        </div>

                    @endif



                    {{-- JABATAN WAKA --}}

                    @if($waka)

                        <div class="profil-row">

                            <div class="profil-label">
                                Jabatan
                            </div>

                            <div class="profil-value">

                                {{ $waka->jabatan ?? '-' }}

                            </div>

                        </div>

                    @endif



                    {{-- JENIS KELAMIN SISWA --}}

                    @if($siswa)

                        <div class="profil-row">

                            <div class="profil-label">
                                Jenis Kelamin
                            </div>

                            <div class="profil-value">

                                @if($siswa->jeniskelamin === 'L')

                                    Laki-laki

                                @elseif($siswa->jeniskelamin === 'P')

                                    Perempuan

                                @else

                                    -

                                @endif

                            </div>

                        </div>

                    @endif



                    {{-- NOMOR TELEPON --}}

                    <div class="profil-row">

                        <div class="profil-label">
                            Nomor Telepon
                        </div>

                        <div class="profil-value">

                            {{ $data->nomortelepon ?? '-' }}

                        </div>

                    </div>



                    {{-- ALAMAT --}}

                    <div class="profil-row">

                        <div class="profil-label">
                            Alamat
                        </div>

                        <div class="profil-value">

                            {{ $data->alamat ?? '-' }}

                        </div>

                    </div>



                    {{-- TEMPAT TANGGAL LAHIR SISWA --}}

                    @if($siswa)

                        <div class="profil-row">

                            <div class="profil-label">
                                Tempat, Tanggal Lahir
                            </div>

                            <div class="profil-value">

                                {{ $siswa->tempatlahir ?? '-' }}

                                @if($siswa->tanggallahir)

                                    ,
                                    {{ \Carbon\Carbon::parse(
                                        $siswa->tanggallahir
                                    )->format('d-m-Y') }}

                                @endif

                            </div>

                        </div>

                    @endif


                </div>

            @else


                <div class="profil-empty">

                    <div class="profil-empty-icon">

                        <i class="fa-regular fa-user"></i>

                    </div>

                    <strong>
                        Data profil belum lengkap
                    </strong>

                    <span>
                        Silakan lengkapi informasi profil Anda.
                    </span>

                </div>


            @endif

        </div>


    </div>



    {{-- =========================================================
         KHUSUS BK
    ========================================================== --}}

    @if($bk)

        <div class="profil-bk-card">

            <div class="profil-bk-icon">

                <i class="fa-solid fa-user-shield"></i>

            </div>


            <div class="profil-bk-content">

                <strong>
                    Akun Bimbingan Konseling
                </strong>

                <p>

                    Anda masuk sebagai BK dengan kode

                    <span class="profil-bk-code">
                        {{ $bk->kodebk }}
                    </span>.

                    Data kelas yang menjadi tanggung jawab
                    BK dapat dikelola melalui menu
                    <strong>Kelas Tanggung Jawab</strong>.

                </p>

            </div>

        </div>

    @endif


</div>

@endsection