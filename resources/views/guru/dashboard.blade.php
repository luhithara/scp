@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')

<style>
    .guru-dashboard {
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
    }

    /* =========================================================
       HEADER
       ========================================================= */

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .header-left {
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .header-icon {
        width: 50px;
        height: 50px;
        flex-shrink: 0;
        border-radius: 14px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header-icon svg {
        width: 26px;
        height: 26px;
    }

    .breadcrumb {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .breadcrumb span {
        color: #2563eb;
        font-weight: 650;
    }

    .dashboard-title {
        margin: 0;
        color: #0f172a;
        font-size: 28px;
        line-height: 1.25;
        font-weight: 750;
    }

    .dashboard-subtitle {
        margin: 7px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .date-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 13px;
        padding: 12px 15px;
        color: #475569;
        font-size: 13px;
        white-space: nowrap;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .04);
    }

    .date-card strong {
        display: block;
        color: #0f172a;
        font-size: 14px;
        margin-top: 3px;
    }

    /* =========================================================
       ALERT
       ========================================================= */

    .alert {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 14px 16px;
        border-radius: 13px;
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.5;
        border: 1px solid;
    }

    .alert svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .alert-success {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
    }

    .alert-error {
        background: #fef2f2;
        border-color: #fecaca;
        color: #991b1b;
    }

    /* =========================================================
       PROFIL SINGKAT
       ========================================================= */

    .welcome-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(
            135deg,
            #1d4ed8 0%,
            #2563eb 55%,
            #3b82f6 100%
        );
        color: white;
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 22px;
        box-shadow: 0 10px 28px rgba(37, 99, 235, .18);
    }

    .welcome-card::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 50%;
        right: -70px;
        top: -90px;
    }

    .welcome-card::before {
        content: "";
        position: absolute;
        width: 130px;
        height: 130px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
        right: 70px;
        bottom: -100px;
    }

    .welcome-content {
        position: relative;
        z-index: 1;
    }

    .welcome-label {
        font-size: 13px;
        opacity: .82;
        margin-bottom: 5px;
    }

    .welcome-name {
        font-size: 24px;
        font-weight: 750;
        margin: 0;
    }

    .welcome-info {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
        margin-top: 14px;
    }

    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 8px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.18);
        font-size: 12px;
    }

    .info-badge svg {
        width: 15px;
        height: 15px;
    }

    /* =========================================================
       STATISTIK
       ========================================================= */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .stat-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 19px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .04);
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon svg {
        width: 23px;
        height: 23px;
    }

    .stat-label {
        color: #64748b;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .stat-value {
        color: #0f172a;
        font-size: 24px;
        font-weight: 750;
        line-height: 1;
    }

    /* =========================================================
       QUICK ACTION
       ========================================================= */

    .section-title {
        color: #0f172a;
        font-size: 17px;
        font-weight: 720;
        margin: 0 0 13px;
    }

    .quick-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .quick-card {
        text-decoration: none;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 15px;
        padding: 17px;
        display: flex;
        align-items: center;
        gap: 13px;
        transition: .2s ease;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .03);
    }

    .quick-card:hover {
        transform: translateY(-2px);
        border-color: #bfdbfe;
        box-shadow: 0 7px 20px rgba(37, 99, 235, .08);
    }

    .quick-icon {
        width: 42px;
        height: 42px;
        border-radius: 11px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .quick-icon svg {
        width: 21px;
        height: 21px;
    }

    .quick-title {
        color: #0f172a;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .quick-description {
        color: #64748b;
        font-size: 12px;
        line-height: 1.4;
    }

    /* =========================================================
       KONTEN BAWAH
       ========================================================= */

    .content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(300px, .8fr);
        gap: 18px;
    }

    .panel {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .04);
    }

    .panel-header {
        padding: 18px 19px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .panel-title {
        margin: 0;
        color: #0f172a;
        font-size: 16px;
        font-weight: 720;
    }

    .panel-subtitle {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 12px;
    }

    .panel-link {
        color: #2563eb;
        font-size: 12px;
        font-weight: 650;
        text-decoration: none;
    }

    .panel-link:hover {
        text-decoration: underline;
    }

    /* =========================================================
       IZIN
       ========================================================= */

    .izin-list {
        padding: 5px 19px 10px;
    }

    .izin-item {
        padding: 15px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .izin-item:last-child {
        border-bottom: none;
    }

    .izin-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .izin-name {
        color: #0f172a;
        font-size: 14px;
        font-weight: 680;
    }

    .izin-nisn {
        color: #94a3b8;
        font-size: 11px;
        margin-top: 3px;
    }

    .izin-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 8px;
    }

    .izin-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 8px;
        border-radius: 7px;
        background: #f1f5f9;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
    }

    .izin-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 7px;
        background: #fff7ed;
        color: #c2410c;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .izin-alasan {
        margin-top: 8px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
    }

    /* =========================================================
       INFO GURU
       ========================================================= */

    .profile-list {
        padding: 6px 19px 16px;
    }

    .profile-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 13px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .profile-row:last-child {
        border-bottom: none;
    }

    .profile-label {
        color: #64748b;
        font-size: 12px;
    }

    .profile-value {
        color: #0f172a;
        font-size: 13px;
        font-weight: 650;
        text-align: right;
    }

    /* =========================================================
       EMPTY
       ========================================================= */

    .empty-state {
        padding: 38px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 11px;
        border-radius: 12px;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon svg {
        width: 23px;
        height: 23px;
    }

    .empty-title {
        color: #334155;
        font-size: 14px;
        font-weight: 680;
        margin-bottom: 4px;
    }

    .empty-text {
        color: #94a3b8;
        font-size: 12px;
        line-height: 1.5;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1000px) {

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .quick-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {

        .guru-dashboard {
            padding: 16px;
        }

        .dashboard-header {
            flex-direction: column;
        }

        .date-card {
            width: 100%;
            box-sizing: border-box;
        }

        .dashboard-title {
            font-size: 23px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .quick-grid {
            grid-template-columns: 1fr;
        }

        .welcome-card {
            padding: 20px;
        }

        .welcome-name {
            font-size: 21px;
        }

        .panel-header {
            align-items: flex-start;
        }

        .profile-row {
            flex-direction: column;
            gap: 4px;
        }

        .profile-value {
            text-align: left;
        }
    }
</style>


<div class="guru-dashboard">

    {{-- =====================================================
         HEADER
         ===================================================== --}}

    <div class="dashboard-header">

        <div class="header-left">

            <div class="header-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M3 21h18"/>
                    <path d="M5 21V9l7-5 7 5v12"/>
                    <path d="M9 21v-6h6v6"/>
                    <path d="M9 10h.01"/>
                    <path d="M12 10h.01"/>
                    <path d="M15 10h.01"/>
                </svg>

            </div>

            <div>

                <div class="breadcrumb">
                    Guru / <span>Dashboard</span>
                </div>

                <h1 class="dashboard-title">
                    Dashboard Guru
                </h1>

                <p class="dashboard-subtitle">
                    Pantau jadwal mengajar, jurnal, presensi, dan pengajuan izin siswa.
                </p>

            </div>

        </div>


        <div class="date-card">

            Hari ini

            <strong>
                {{ now()->translatedFormat('l, d F Y') }}
            </strong>

        </div>

    </div>


    {{-- =====================================================
         ALERT
         ===================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="M20 6 9 17l-5-5"/>
            </svg>

            <div>
                {{ session('success') }}
            </div>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-error">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 8v5"/>
                <path d="M12 16h.01"/>
            </svg>

            <div>

                @foreach($errors->all() as $error)

                    <div>
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        </div>

    @endif


    {{-- =====================================================
         WELCOME
         ===================================================== --}}

    <div class="welcome-card">

        <div class="welcome-content">

            <div class="welcome-label">
                Selamat datang kembali
            </div>

            <h2 class="welcome-name">
                {{ $guru->nama ?? $user->name ?? 'Guru' }}
            </h2>

            <div class="welcome-info">

                <div class="info-badge">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>

                    Guru

                </div>


                @if(!empty($guru->kodeguru))

                    <div class="info-badge">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <rect x="3" y="4" width="18" height="16" rx="2"/>
                            <path d="M7 8h10"/>
                            <path d="M7 12h6"/>
                            <path d="M7 16h4"/>
                        </svg>

                        {{ $guru->kodeguru }}

                    </div>

                @endif


                @if(!empty($guru->nip))

                    <div class="info-badge">

                        NIP {{ $guru->nip }}

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =====================================================
         STATISTIK
         ===================================================== --}}

    <div class="stats-grid">

        {{-- JADWAL --}}
        <div class="stat-card">

            <div class="stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <rect x="3" y="4" width="18" height="17" rx="2"/>
                    <path d="M16 2v4"/>
                    <path d="M8 2v4"/>
                    <path d="M3 10h18"/>
                    <path d="M8 14h3"/>
                    <path d="M8 17h5"/>
                </svg>

            </div>

            <div>

                <div class="stat-label">
                    Total Jadwal
                </div>

                <div class="stat-value">
                    {{ $jumlahJadwal ?? 0 }}
                </div>

            </div>

        </div>


        {{-- JURNAL --}}
        <div class="stat-card">

            <div class="stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <path d="M14 2v6h6"/>
                    <path d="M8 13h8"/>
                    <path d="M8 17h6"/>
                </svg>

            </div>

            <div>

                <div class="stat-label">
                    Jurnal Mengajar
                </div>

                <div class="stat-value">
                    {{ $jumlahJurnal ?? 0 }}
                </div>

            </div>

        </div>


        {{-- IZIN --}}
        <div class="stat-card">

            <div class="stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M4 4h16v16H4z"/>
                    <path d="M8 9h8"/>
                    <path d="M8 13h5"/>
                    <path d="M8 17h4"/>
                </svg>

            </div>

            <div>

                <div class="stat-label">
                    Pengajuan Izin
                </div>

                <div class="stat-value">
                    {{ $jumlahIzin ?? 0 }}
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MENU CEPAT
         ===================================================== --}}

    <h2 class="section-title">
        Akses Cepat
    </h2>

    <div class="quick-grid">

        {{-- JADWAL --}}
        <a
            href="{{ route('guru.jadwal') }}"
            class="quick-card"
        >

            <div class="quick-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <rect x="3" y="4" width="18" height="17" rx="2"/>
                    <path d="M16 2v4"/>
                    <path d="M8 2v4"/>
                    <path d="M3 10h18"/>
                </svg>

            </div>

            <div>

                <div class="quick-title">
                    Jadwal Mengajar
                </div>

                <div class="quick-description">
                    Lihat dan kelola jadwal mengajar.
                </div>

            </div>

        </a>


        {{-- PRESENSI --}}
        <a
            href="{{ route('presensi') }}"
            class="quick-card"
        >

            <div class="quick-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M9 11l3 3L20 6"/>
                    <path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h9"/>
                </svg>

            </div>

            <div>

                <div class="quick-title">
                    Presensi
                </div>

                <div class="quick-description">
                    Buka presensi siswa berdasarkan jadwal.
                </div>

            </div>

        </a>


        {{-- JURNAL --}}
        <a
            href="{{ route('jurnal.guru') }}"
            class="quick-card"
        >

            <div class="quick-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <path d="M14 2v6h6"/>
                    <path d="M8 13h8"/>
                    <path d="M8 17h5"/>
                </svg>

            </div>

            <div>

                <div class="quick-title">
                    Jurnal Mengajar
                </div>

                <div class="quick-description">
                    Isi jurnal pembelajaran dan bukti kegiatan.
                </div>

            </div>

        </a>


        {{-- IZIN --}}
        <a
            href="{{ route('guru.izin') }}"
            class="quick-card"
        >

            <div class="quick-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M4 4h16v16H4z"/>
                    <path d="M8 9h8"/>
                    <path d="M8 13h6"/>
                    <path d="M8 17h4"/>
                </svg>

            </div>

            <div>

                <div class="quick-title">
                    Perizinan Siswa
                </div>

                <div class="quick-description">
                    Lihat pengajuan izin siswa.
                </div>

            </div>

        </a>


        {{-- PROFIL --}}
        <a
            href="{{ route('profil') }}"
            class="quick-card"
        >

            <div class="quick-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 21a8 8 0 0 1 16 0"/>
                </svg>

            </div>

            <div>

                <div class="quick-title">
                    Profil
                </div>

                <div class="quick-description">
                    Lihat dan perbarui profil akun.
                </div>

            </div>

        </a>

    </div>


    {{-- =====================================================
         KONTEN BAWAH
         ===================================================== --}}

    <div class="content-grid">

        {{-- =================================================
             IZIN HARI INI
             ================================================= --}}

        <div class="panel">

            <div class="panel-header">

                <div>

                    <h2 class="panel-title">
                        Pengajuan Izin Hari Ini
                    </h2>

                    <p class="panel-subtitle">
                        Pengajuan izin siswa yang masuk ke sistem.
                    </p>

                </div>

                <a
                    href="{{ route('guru.izin') }}"
                    class="panel-link"
                >
                    Lihat Semua
                </a>

            </div>


            @if(isset($izinHariIni) && $izinHariIni->count() > 0)

                <div class="izin-list">

                    @foreach($izinHariIni->take(5) as $izin)

                        <div class="izin-item">

                            <div class="izin-top">

                                <div>

                                    <div class="izin-name">
                                        {{ $izin->namasiswa ?? 'Siswa' }}
                                    </div>

                                    <div class="izin-nisn">
                                        NISN:
                                        {{ $izin->nisn ?? '-' }}
                                    </div>

                                </div>


                                <span class="izin-status">
                                    {{ ucfirst($izin->status ?? 'menunggu') }}
                                </span>

                            </div>


                            <div class="izin-meta">

                                @if(!empty($izin->jenis))

                                    <span class="izin-badge">
                                        {{ ucfirst($izin->jenis) }}
                                    </span>

                                @endif


                                @if(!empty($izin->tanggal))

                                    <span class="izin-badge">
                                        {{ \Carbon\Carbon::parse($izin->tanggal)->translatedFormat('d F Y') }}
                                    </span>

                                @endif

                            </div>


                            @if(!empty($izin->alasan))

                                <div class="izin-alasan">
                                    {{ $izin->alasan }}
                                </div>

                            @endif

                        </div>

                    @endforeach

                </div>

            @else

                <div class="empty-state">

                    <div class="empty-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M4 4h16v16H4z"/>
                            <path d="M8 9h8"/>
                            <path d="M8 13h6"/>
                        </svg>

                    </div>

                    <div class="empty-title">
                        Belum ada pengajuan izin
                    </div>

                    <div class="empty-text">
                        Belum ada pengajuan izin siswa untuk hari ini.
                    </div>

                </div>

            @endif

        </div>


        {{-- =================================================
             INFORMASI GURU
             ================================================= --}}

        <div class="panel">

            <div class="panel-header">

                <div>

                    <h2 class="panel-title">
                        Informasi Akun
                    </h2>

                    <p class="panel-subtitle">
                        Informasi guru yang sedang login.
                    </p>

                </div>

                <a
                    href="{{ route('profil') }}"
                    class="panel-link"
                >
                    Profil
                </a>

            </div>


            <div class="profile-list">

                <div class="profile-row">

                    <div class="profile-label">
                        Nama
                    </div>

                    <div class="profile-value">
                        {{ $guru->nama ?? $user->name ?? '-' }}
                    </div>

                </div>


                <div class="profile-row">

                    <div class="profile-label">
                        Kode Guru
                    </div>

                    <div class="profile-value">
                        {{ $guru->kodeguru ?? $user->kodepegawai ?? '-' }}
                    </div>

                </div>


                <div class="profile-row">

                    <div class="profile-label">
                        NIP
                    </div>

                    <div class="profile-value">
                        {{ $guru->nip ?? '-' }}
                    </div>

                </div>


                <div class="profile-row">

                    <div class="profile-label">
                        Nomor Telepon
                    </div>

                    <div class="profile-value">
                        {{ $guru->nomortelepon ?? '-' }}
                    </div>

                </div>


                <div class="profile-row">

                    <div class="profile-label">
                        Status Akun
                    </div>

                    <div class="profile-value">
                        {{ ucfirst($user->status ?? 'aktif') }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection