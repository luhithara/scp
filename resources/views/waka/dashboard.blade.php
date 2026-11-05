@extends('layouts.app')

@section('title', 'Dashboard Waka - SCP')

@section('page-title', 'Dashboard Waka')
@section('page-subtitle', 'Ringkasan informasi School Class Progress')

@push('styles')
<style>
    /* =========================================================
       DASHBOARD WAKA
    ========================================================= */

    .waka-dashboard {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;

        display: flex;
        flex-direction: column;

        gap: 20px;
    }


    /* =========================================================
       WELCOME
    ========================================================= */

    .waka-welcome {
        position: relative;
        overflow: hidden;

        padding: 27px 30px;

        border-radius: 18px;

        background: linear-gradient(
            135deg,
            #0f4c81 0%,
            #1d5fa7 52%,
            #2563eb 100%
        );

        color: #ffffff;

        box-shadow:
            0 12px 30px rgba(15, 76, 129, .15);
    }

    .waka-welcome::after {
        content: "";

        position: absolute;

        width: 190px;
        height: 190px;

        right: -70px;
        top: -80px;

        border-radius: 50%;

        border: 1px solid rgba(255,255,255,.12);

        box-shadow:
            0 0 0 25px rgba(255,255,255,.035),
            0 0 0 50px rgba(255,255,255,.025);
    }

    .waka-welcome-content {
        position: relative;
        z-index: 1;
    }

    .waka-welcome-label {
        display: inline-flex;
        align-items: center;

        gap: 7px;

        margin-bottom: 10px;

        font-size: 11px;
        font-weight: 700;

        letter-spacing: .4px;

        text-transform: uppercase;

        color: rgba(255,255,255,.78);
    }

    .waka-welcome-label i {
        font-size: 10px;
    }

    .waka-welcome h1 {
        margin: 0;

        font-size: 27px;
        line-height: 1.25;

        font-weight: 750;

        letter-spacing: -.35px;
    }

    .waka-welcome p {
        max-width: 680px;

        margin: 8px 0 0;

        color: rgba(255,255,255,.84);

        font-size: 13px;
        line-height: 1.6;
    }


    /* =========================================================
       STATISTIK
    ========================================================= */

    .waka-stats {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 15px;
    }

    .waka-stat {
        min-width: 0;

        padding: 19px;

        border: 1px solid #e5eaf1;

        border-radius: 15px;

        background: #ffffff;

        box-shadow:
            0 5px 18px rgba(15, 23, 42, .035);

        transition:
            transform .2s ease,
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .waka-stat:hover {
        transform: translateY(-2px);

        border-color: #bfdbfe;

        box-shadow:
            0 10px 25px rgba(37, 99, 235, .07);
    }

    .waka-stat-top {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 12px;
    }

    .waka-stat-label {
        margin: 0;

        color: #64748b;

        font-size: 12px;

        font-weight: 650;
    }

    .waka-stat-icon {
        width: 40px;
        height: 40px;

        flex: 0 0 40px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 11px;

        background: #eff6ff;

        color: #2563eb;

        font-size: 15px;
    }

    .waka-stat-value {
        margin-top: 15px;

        color: #0f172a;

        font-size: 28px;

        line-height: 1;

        font-weight: 750;

        letter-spacing: -.5px;
    }

    .waka-stat-description {
        margin-top: 9px;

        color: #94a3b8;

        font-size: 11px;
    }


    /* =========================================================
       MAIN GRID
    ========================================================= */

    .waka-main-grid {
        display: grid;

        grid-template-columns:
            minmax(0, 1.45fr)
            minmax(290px, .55fr);

        gap: 18px;

        align-items: start;
    }

    .waka-column {
        min-width: 0;

        display: flex;

        flex-direction: column;

        gap: 18px;
    }


    /* =========================================================
       CARD
    ========================================================= */

    .waka-card {
        min-width: 0;

        overflow: hidden;

        border: 1px solid #e5eaf1;

        border-radius: 15px;

        background: #ffffff;

        box-shadow:
            0 5px 18px rgba(15, 23, 42, .035);
    }

    .waka-card-header {
        padding: 17px 20px;

        border-bottom: 1px solid #edf1f6;
    }

    .waka-card-header h2 {
        margin: 0;

        color: #0f172a;

        font-size: 15px;

        font-weight: 750;

        letter-spacing: -.1px;
    }

    .waka-card-header p {
        margin: 5px 0 0;

        color: #64748b;

        font-size: 11px;

        line-height: 1.5;
    }

    .waka-card-body {
        padding: 19px 20px;
    }


    /* =========================================================
       QUICK ACCESS
    ========================================================= */

    .waka-quick-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 11px;
    }

    .waka-quick {
        min-width: 0;

        display: flex;

        align-items: flex-start;

        gap: 12px;

        padding: 14px;

        border: 1px solid #e5eaf1;

        border-radius: 12px;

        background: #ffffff;

        text-decoration: none;

        transition:
            transform .2s ease,
            border-color .2s ease,
            background .2s ease,
            box-shadow .2s ease;
    }

    .waka-quick:hover {
        transform: translateY(-2px);

        border-color: #93c5fd;

        background: #f8fbff;

        box-shadow:
            0 7px 18px rgba(37, 99, 235, .06);
    }

    .waka-quick-icon {
        width: 39px;
        height: 39px;

        flex: 0 0 39px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 10px;

        background: #eff6ff;

        color: #2563eb;

        font-size: 14px;
    }

    .waka-quick-content {
        min-width: 0;
    }

    .waka-quick-title {
        display: block;

        margin: 1px 0 4px;

        color: #1e293b;

        font-size: 12px;

        font-weight: 700;
    }

    .waka-quick-description {
        display: block;

        color: #64748b;

        font-size: 10.5px;

        line-height: 1.55;
    }


    /* =========================================================
       SUMMARY
    ========================================================= */

    .waka-summary {
        display: flex;

        flex-direction: column;
    }

    .waka-summary-row {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        padding: 12px 0;

        border-bottom: 1px solid #eef2f7;
    }

    .waka-summary-row:first-child {
        padding-top: 0;
    }

    .waka-summary-row:last-child {
        padding-bottom: 0;

        border-bottom: 0;
    }

    .waka-summary-left {
        display: flex;

        align-items: center;

        gap: 9px;

        min-width: 0;
    }

    .waka-summary-dot {
        width: 7px;
        height: 7px;

        flex: 0 0 7px;

        border-radius: 50%;

        background: #2563eb;
    }

    .waka-summary-label {
        color: #64748b;

        font-size: 11px;
    }

    .waka-summary-value {
        color: #0f172a;

        font-size: 13px;

        font-weight: 750;
    }


    /* =========================================================
       INFO
    ========================================================= */

    .waka-info {
        padding: 14px;

        border: 1px solid #dbeafe;

        border-radius: 11px;

        background: #f8fbff;
    }

    .waka-info-title {
        display: flex;

        align-items: center;

        gap: 8px;

        margin-bottom: 7px;

        color: #1d4ed8;

        font-size: 11px;

        font-weight: 750;
    }

    .waka-info-text {
        color: #64748b;

        font-size: 10.5px;

        line-height: 1.65;
    }


    /* =========================================================
       TODAY
    ========================================================= */

    .waka-today-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 10px;
    }

    .waka-today-item {
        padding: 13px;

        border: 1px solid #e5eaf1;

        border-radius: 11px;

        background: #fafcff;
    }

    .waka-today-label {
        margin: 0;

        color: #64748b;

        font-size: 10px;
    }

    .waka-today-value {
        margin-top: 7px;

        color: #0f172a;

        font-size: 19px;

        font-weight: 750;
    }


    /* =========================================================
       BUTTON
    ========================================================= */

    .waka-button {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        min-height: 36px;

        padding: 8px 13px;

        border: 1px solid #2563eb;

        border-radius: 9px;

        background: #2563eb;

        color: #ffffff;

        text-decoration: none;

        font-size: 10.5px;

        font-weight: 700;

        transition:
            background .2s ease,
            border-color .2s ease,
            transform .2s ease;
    }

    .waka-button:hover {
        transform: translateY(-1px);

        background: #1d4ed8;

        border-color: #1d4ed8;

        color: #ffffff;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1050px) {

        .waka-stats {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .waka-main-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 720px) {

        .waka-quick-grid,
        .waka-today-grid {
            grid-template-columns: 1fr;
        }

        .waka-welcome {
            padding: 23px;
        }

        .waka-welcome h1 {
            font-size: 23px;
        }

    }


    @media (max-width: 520px) {

        .waka-dashboard {
            gap: 15px;
        }

        .waka-stats {
            grid-template-columns: 1fr;
        }

        .waka-stat {
            padding: 16px;
        }

        .waka-card-header {
            padding: 15px 16px;
        }

        .waka-card-body {
            padding: 16px;
        }

        .waka-quick {
            padding: 13px;
        }

        .waka-welcome {
            padding: 20px;
        }

    }

</style>
@endpush


@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | DATA DARI WAKA CONTROLLER
    |--------------------------------------------------------------------------
    */

    $siswaCount = (int) ($jumlahSiswa ?? 0);

    $guruCount = (int) ($jumlahGuru ?? 0);

    $kelasCount = (int) ($jumlahKelas ?? 0);

    $jadwalCount = (int) ($jumlahJadwal ?? 0);


    $jumlahPresensiHariIni =
        (int) ($jumlahPresensi ?? 0);

    $jumlahJurnalHariIni =
        (int) ($jumlahJurnalHariIni ?? 0);


    $jumlahHadirHariIni =
        (int) ($jumlahHadir ?? 0);

    $jumlahSakitHariIni =
        (int) ($jumlahSakit ?? 0);

    $jumlahIzinHariIni =
        (int) ($jumlahIzinPresensi ?? 0);

    $jumlahAlpaHariIni =
        (int) ($jumlahAlpa ?? 0);

@endphp


<div class="waka-dashboard">


    {{-- =====================================================
         WELCOME
    ====================================================== --}}

    <section class="waka-welcome">

        <div class="waka-welcome-content">

            <div class="waka-welcome-label">

                <i class="fa-solid fa-chart-line"></i>

                School Class Progress

            </div>


            <h1>
                Dashboard Waka
            </h1>


            <p>
                Pantau data sekolah, jadwal pembelajaran,
                presensi, dan jurnal mengajar melalui satu
                dashboard terintegrasi.
            </p>

        </div>

    </section>



    {{-- =====================================================
         STATISTIK UTAMA
    ====================================================== --}}

    <section class="waka-stats">


        {{-- SISWA --}}

        <div class="waka-stat">

            <div class="waka-stat-top">

                <p class="waka-stat-label">
                    Jumlah Siswa
                </p>

                <div class="waka-stat-icon">

                    <i class="fa-solid fa-users"></i>

                </div>

            </div>


            <div class="waka-stat-value">
                {{ number_format($siswaCount) }}
            </div>


            <div class="waka-stat-description">
                Siswa terdaftar dalam sistem
            </div>

        </div>



        {{-- GURU --}}

        <div class="waka-stat">

            <div class="waka-stat-top">

                <p class="waka-stat-label">
                    Jumlah Guru
                </p>

                <div class="waka-stat-icon">

                    <i class="fa-solid fa-chalkboard-user"></i>

                </div>

            </div>


            <div class="waka-stat-value">
                {{ number_format($guruCount) }}
            </div>


            <div class="waka-stat-description">
                Guru terdaftar dalam sistem
            </div>

        </div>



        {{-- KELAS --}}

        <div class="waka-stat">

            <div class="waka-stat-top">

                <p class="waka-stat-label">
                    Jumlah Kelas
                </p>

                <div class="waka-stat-icon">

                    <i class="fa-solid fa-school"></i>

                </div>

            </div>


            <div class="waka-stat-value">
                {{ number_format($kelasCount) }}
            </div>


            <div class="waka-stat-description">
                Kelas yang tersedia
            </div>

        </div>



        {{-- JADWAL --}}

        <div class="waka-stat">

            <div class="waka-stat-top">

                <p class="waka-stat-label">
                    Jumlah Jadwal
                </p>

                <div class="waka-stat-icon">

                    <i class="fa-solid fa-calendar-days"></i>

                </div>

            </div>


            <div class="waka-stat-value">
                {{ number_format($jadwalCount) }}
            </div>


            <div class="waka-stat-description">
                Jadwal pembelajaran
            </div>

        </div>

    </section>



    {{-- =====================================================
         MAIN GRID
    ====================================================== --}}

    <section class="waka-main-grid">


        {{-- =================================================
             KOLOM KIRI
        ================================================== --}}

        <div class="waka-column">


            {{-- =============================================
                 AKSES CEPAT
            ============================================== --}}

            <div class="waka-card">

                <div class="waka-card-header">

                    <h2>
                        Akses Cepat
                    </h2>

                    <p>
                        Menu utama yang paling sering digunakan Waka.
                    </p>

                </div>


                <div class="waka-card-body">

                    <div class="waka-quick-grid">


                        {{-- DATA GURU --}}

                        <a
                            href="{{ route('waka.guru') }}"
                            class="waka-quick"
                        >

                            <div class="waka-quick-icon">

                                <i class="fa-solid fa-chalkboard-user"></i>

                            </div>


                            <div class="waka-quick-content">

                                <span class="waka-quick-title">
                                    Data Guru
                                </span>

                                <span class="waka-quick-description">
                                    Kelola data guru dan informasi pengajar.
                                </span>

                            </div>

                        </a>



                        {{-- DATA KELAS --}}

                        <a
                            href="{{ route('waka.kelas') }}"
                            class="waka-quick"
                        >

                            <div class="waka-quick-icon">

                                <i class="fa-solid fa-school"></i>

                            </div>


                            <div class="waka-quick-content">

                                <span class="waka-quick-title">
                                    Data Kelas
                                </span>

                                <span class="waka-quick-description">
                                    Kelola kelas dan melihat data siswa
                                    berdasarkan kelas.
                                </span>

                            </div>

                        </a>



                        {{-- JADWAL --}}

                        <a
                            href="{{ route('waka.jadwal') }}"
                            class="waka-quick"
                        >

                            <div class="waka-quick-icon">

                                <i class="fa-solid fa-calendar-days"></i>

                            </div>


                            <div class="waka-quick-content">

                                <span class="waka-quick-title">
                                    Jadwal
                                </span>

                                <span class="waka-quick-description">
                                    Pantau dan kelola jadwal pembelajaran
                                    seluruh sekolah.
                                </span>

                            </div>

                        </a>



                        {{-- JURNAL --}}

                        <a
                            href="{{ route('waka.jurnal') }}"
                            class="waka-quick"
                        >

                            <div class="waka-quick-icon">

                                <i class="fa-solid fa-book-open"></i>

                            </div>


                            <div class="waka-quick-content">

                                <span class="waka-quick-title">
                                    Jurnal Mengajar
                                </span>

                                <span class="waka-quick-description">
                                    Pantau jurnal mengajar guru.
                                </span>

                            </div>

                        </a>


                    </div>

                </div>

            </div>



            {{-- =============================================
                 AKTIVITAS HARI INI
            ============================================== --}}

            <div class="waka-card">

                <div class="waka-card-header">

                    <h2>
                        Aktivitas Hari Ini
                    </h2>

                    <p>
                        Ringkasan presensi dan jurnal yang tercatat hari ini.
                    </p>

                </div>


                <div class="waka-card-body">

                    <div class="waka-today-grid">


                        <div class="waka-today-item">

                            <p class="waka-today-label">
                                Total Presensi
                            </p>

                            <div class="waka-today-value">
                                {{ number_format($jumlahPresensiHariIni) }}
                            </div>

                        </div>



                        <div class="waka-today-item">

                            <p class="waka-today-label">
                                Jurnal Hari Ini
                            </p>

                            <div class="waka-today-value">
                                {{ number_format($jumlahJurnalHariIni) }}
                            </div>

                        </div>



                        <div class="waka-today-item">

                            <p class="waka-today-label">
                                Hadir
                            </p>

                            <div class="waka-today-value">
                                {{ number_format($jumlahHadirHariIni) }}
                            </div>

                        </div>



                        <div class="waka-today-item">

                            <p class="waka-today-label">
                                Sakit
                            </p>

                            <div class="waka-today-value">
                                {{ number_format($jumlahSakitHariIni) }}
                            </div>

                        </div>



                        <div class="waka-today-item">

                            <p class="waka-today-label">
                                Izin
                            </p>

                            <div class="waka-today-value">
                                {{ number_format($jumlahIzinHariIni) }}
                            </div>

                        </div>



                        <div class="waka-today-item">

                            <p class="waka-today-label">
                                Alpa
                            </p>

                            <div class="waka-today-value">
                                {{ number_format($jumlahAlpaHariIni) }}
                            </div>

                        </div>


                    </div>

                </div>

            </div>


        </div>



        {{-- =================================================
             KOLOM KANAN
        ================================================== --}}

        <div class="waka-column">


            {{-- =============================================
                 RINGKASAN SISTEM
            ============================================== --}}

            <div class="waka-card">

                <div class="waka-card-header">

                    <h2>
                        Ringkasan Sistem
                    </h2>

                    <p>
                        Data utama yang tersedia di SCP.
                    </p>

                </div>


                <div class="waka-card-body">

                    <div class="waka-summary">


                        <div class="waka-summary-row">

                            <div class="waka-summary-left">

                                <span class="waka-summary-dot"></span>

                                <span class="waka-summary-label">
                                    Siswa
                                </span>

                            </div>


                            <strong class="waka-summary-value">
                                {{ number_format($siswaCount) }}
                            </strong>

                        </div>



                        <div class="waka-summary-row">

                            <div class="waka-summary-left">

                                <span class="waka-summary-dot"></span>

                                <span class="waka-summary-label">
                                    Guru
                                </span>

                            </div>


                            <strong class="waka-summary-value">
                                {{ number_format($guruCount) }}
                            </strong>

                        </div>



                        <div class="waka-summary-row">

                            <div class="waka-summary-left">

                                <span class="waka-summary-dot"></span>

                                <span class="waka-summary-label">
                                    Kelas
                                </span>

                            </div>


                            <strong class="waka-summary-value">
                                {{ number_format($kelasCount) }}
                            </strong>

                        </div>



                        <div class="waka-summary-row">

                            <div class="waka-summary-left">

                                <span class="waka-summary-dot"></span>

                                <span class="waka-summary-label">
                                    Jadwal
                                </span>

                            </div>


                            <strong class="waka-summary-value">
                                {{ number_format($jadwalCount) }}
                            </strong>

                        </div>


                    </div>

                </div>

            </div>



            {{-- =============================================
                 PRESENSI HARI INI
            ============================================== --}}

            <div class="waka-card">

                <div class="waka-card-header">

                    <h2>
                        Presensi Hari Ini
                    </h2>

                    <p>
                        Rekap status presensi yang tercatat.
                    </p>

                </div>


                <div class="waka-card-body">

                    <div class="waka-summary">


                        <div class="waka-summary-row">

                            <div class="waka-summary-left">

                                <span class="waka-summary-dot"></span>

                                <span class="waka-summary-label">
                                    Hadir
                                </span>

                            </div>


                            <strong class="waka-summary-value">
                                {{ number_format($jumlahHadirHariIni) }}
                            </strong>

                        </div>



                        <div class="waka-summary-row">

                            <div class="waka-summary-left">

                                <span class="waka-summary-dot"></span>

                                <span class="waka-summary-label">
                                    Sakit
                                </span>

                            </div>


                            <strong class="waka-summary-value">
                                {{ number_format($jumlahSakitHariIni) }}
                            </strong>

                        </div>



                        <div class="waka-summary-row">

                            <div class="waka-summary-left">

                                <span class="waka-summary-dot"></span>

                                <span class="waka-summary-label">
                                    Izin
                                </span>

                            </div>


                            <strong class="waka-summary-value">
                                {{ number_format($jumlahIzinHariIni) }}
                            </strong>

                        </div>



                        <div class="waka-summary-row">

                            <div class="waka-summary-left">

                                <span class="waka-summary-dot"></span>

                                <span class="waka-summary-label">
                                    Alpa
                                </span>

                            </div>


                            <strong class="waka-summary-value">
                                {{ number_format($jumlahAlpaHariIni) }}
                            </strong>

                        </div>


                    </div>

                </div>

            </div>



            {{-- =============================================
                 TENTANG SCP
            ============================================== --}}

            <div class="waka-card">

                <div class="waka-card-header">

                    <h2>
                        Tentang SCP
                    </h2>

                    <p>
                        School Class Progress
                    </p>

                </div>


                <div class="waka-card-body">

                    <div class="waka-info">

                        <div class="waka-info-title">

                            <i class="fa-solid fa-circle-info"></i>

                            Sistem Informasi Akademik

                        </div>


                        <div class="waka-info-text">

                            School Class Progress membantu sekolah
                            mengelola data siswa, guru, kelas,
                            jadwal pembelajaran, presensi, dan
                            jurnal mengajar secara terintegrasi.

                        </div>

                    </div>


                    <div style="margin-top: 14px;">

                        <a
                            href="{{ route('waka.jadwal') }}"
                            class="waka-button"
                        >

                            <i class="fa-solid fa-calendar-days"></i>

                            Lihat Jadwal

                        </a>

                    </div>

                </div>

            </div>


        </div>

    </section>

</div>

@endsection