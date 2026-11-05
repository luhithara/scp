@extends('layouts.app')

@section('title', 'Data Sekolah')

@section('content')

<div class="data-page">

    {{-- =========================================================
         HEADER HALAMAN
         ========================================================= --}}
    <div class="data-page-header">

        <div class="header-left">

            <div class="header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M3 21h18"/>
                    <path d="M5 21V5l7-3 7 3v16"/>
                    <path d="M9 21v-4h6v4"/>
                    <path d="M9 8h.01"/>
                    <path d="M15 8h.01"/>
                    <path d="M9 12h.01"/>
                    <path d="M15 12h.01"/>
                </svg>
            </div>

            <div>
                <div class="breadcrumb">
                    <span>Waka</span>
                    <span class="breadcrumb-separator">/</span>
                    <span>Data Sekolah</span>
                </div>

                <h1>Data Sekolah</h1>

                <p>
                    Kelola data utama sekolah melalui menu yang tersedia.
                </p>
            </div>

        </div>

        <a href="{{ route('waka.dashboard') }}" class="back-button">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M19 12H5"/>
                <path d="M12 19l-7-7 7-7"/>
            </svg>
            <span>Kembali</span>
        </a>

    </div>


    {{-- =========================================================
         INFO CARD
         ========================================================= --}}
    <div class="data-info-card">

        <div class="info-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 10v6"/>
                <path d="M12 7h.01"/>
            </svg>
        </div>

        <div class="info-content">
            <h3>Kelola Data Sekolah</h3>
            <p>
                Pilih salah satu menu di bawah untuk menambahkan,
                melihat, dan mengelola data sekolah.
            </p>
        </div>

    </div>


    {{-- =========================================================
         MENU DATA
         ========================================================= --}}
    <div class="section-heading">
        <div>
            <h2>Master Data</h2>
            <p>Pilih data yang ingin kamu kelola.</p>
        </div>
    </div>


    <div class="data-menu-grid">

        {{-- =====================================================
             DATA SISWA
             ====================================================== --}}
        <a href="{{ route('waka.siswa') }}" class="data-menu-card">

            <div class="data-card-top">
                <div class="data-menu-icon siswa-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>

                <div class="data-menu-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M5 12h14"/>
                        <path d="M13 6l6 6-6 6"/>
                    </svg>
                </div>
            </div>

            <div class="data-card-content">
                <span class="data-card-label">MASTER DATA</span>

                <h3>Data Siswa</h3>

                <p>
                    Tambah dan kelola informasi siswa,
                    termasuk kelas dan data pribadi.
                </p>
            </div>

            <div class="data-card-footer">
                <span>Kelola data</span>

                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M5 12h14"/>
                    <path d="M13 6l6 6-6 6"/>
                </svg>
            </div>

        </a>


        {{-- =====================================================
             DATA GURU
             ====================================================== --}}
        <a href="{{ route('waka.guru') }}" class="data-menu-card">

            <div class="data-card-top">
                <div class="data-menu-icon guru-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>

                <div class="data-menu-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M5 12h14"/>
                        <path d="M13 6l6 6-6 6"/>
                    </svg>
                </div>
            </div>

            <div class="data-card-content">
                <span class="data-card-label">MASTER DATA</span>

                <h3>Data Guru</h3>

                <p>
                    Tambah dan kelola data guru,
                    termasuk informasi kepegawaian.
                </p>
            </div>

            <div class="data-card-footer">
                <span>Kelola data</span>

                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M5 12h14"/>
                    <path d="M13 6l6 6-6 6"/>
                </svg>
            </div>

        </a>


        {{-- =====================================================
             DATA KELAS
             ====================================================== --}}
        <a href="{{ route('waka.kelas') }}" class="data-menu-card">

            <div class="data-card-top">
                <div class="data-menu-icon kelas-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 4h18v16H3z"/>
                        <path d="M3 9h18"/>
                        <path d="M8 4v5"/>
                        <path d="M16 4v5"/>
                    </svg>
                </div>

                <div class="data-menu-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M5 12h14"/>
                        <path d="M13 6l6 6-6 6"/>
                    </svg>
                </div>
            </div>

            <div class="data-card-content">
                <span class="data-card-label">MASTER DATA</span>

                <h3>Data Kelas</h3>

                <p>
                    Kelola kelas, tingkat, dan
                    jurusan yang digunakan sekolah.
                </p>
            </div>

            <div class="data-card-footer">
                <span>Kelola data</span>

                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M5 12h14"/>
                    <path d="M13 6l6 6-6 6"/>
                </svg>
            </div>

        </a>


        {{-- =====================================================
             DATA ORANG TUA
             ====================================================== --}}
        <a href="{{ route('waka.orangtua') }}" class="data-menu-card">

            <div class="data-card-top">
                <div class="data-menu-icon orangtua-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/>
                        <circle cx="10" cy="7" r="4"/>
                        <path d="M21 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M17 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>

                <div class="data-menu-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M5 12h14"/>
                        <path d="M13 6l6 6-6 6"/>
                    </svg>
                </div>
            </div>

            <div class="data-card-content">
                <span class="data-card-label">MASTER DATA</span>

                <h3>Data Orang Tua</h3>

                <p>
                    Kelola data orang tua atau wali
                    yang terhubung dengan siswa.
                </p>
            </div>

            <div class="data-card-footer">
                <span>Kelola data</span>

                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M5 12h14"/>
                    <path d="M13 6l6 6-6 6"/>
                </svg>
            </div>

        </a>

    </div>

</div>

@endsection


@push('styles')
<style>

    /* =========================================================
       HALAMAN DATA SEKOLAH
       ========================================================= */

    .data-page {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
    }


    /* =========================================================
       HEADER
       ========================================================= */

    .data-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 0;
    }

    .header-icon {
        width: 54px;
        height: 54px;
        min-width: 54px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: linear-gradient(
            135deg,
            #2563eb,
            #1d4ed8
        );

        color: #ffffff;
        border-radius: 15px;

        box-shadow:
            0 8px 20px rgba(37, 99, 235, .18);
    }

    .header-icon svg {
        width: 27px;
        height: 27px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;

        font-size: 12px;
        color: #8a9bb0;
        font-weight: 500;
    }

    .breadcrumb-separator {
        color: #c4ceda;
    }

    .data-page-header h1 {
        margin: 0;
        color: #102a43;
        font-size: 27px;
        line-height: 1.2;
        font-weight: 750;
        letter-spacing: -.3px;
    }

    .data-page-header p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 13px;
        line-height: 1.5;
    }


    /* =========================================================
       TOMBOL KEMBALI
       ========================================================= */

    .back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        padding: 10px 15px;

        background: #ffffff;
        border: 1px solid #dce6f2;
        border-radius: 10px;

        color: #47617b;
        text-decoration: none;

        font-size: 13px;
        font-weight: 600;

        transition: all .2s ease;
        white-space: nowrap;
    }

    .back-button svg {
        width: 17px;
        height: 17px;
    }

    .back-button:hover {
        color: #2563eb;
        border-color: #bfd3ee;
        background: #f8fbff;
        transform: translateX(-2px);
    }


    /* =========================================================
       INFO CARD
       ========================================================= */

    .data-info-card {
        display: flex;
        align-items: center;
        gap: 14px;

        padding: 16px 18px;
        margin-bottom: 28px;

        background: linear-gradient(
            135deg,
            #f7faff,
            #eef5ff
        );

        border: 1px solid #dce9f9;
        border-radius: 14px;
    }

    .info-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #ffffff;
        color: #2563eb;

        border: 1px solid #dce8f7;
        border-radius: 10px;
    }

    .info-icon svg {
        width: 19px;
        height: 19px;
    }

    .info-content h3 {
        margin: 0 0 3px;

        color: #193b63;
        font-size: 13px;
        font-weight: 700;
    }

    .info-content p {
        margin: 0;

        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
    }


    /* =========================================================
       JUDUL SECTION
       ========================================================= */

    .section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;

        margin-bottom: 14px;
    }

    .section-heading h2 {
        margin: 0;

        color: #173b63;
        font-size: 17px;
        font-weight: 700;
    }

    .section-heading p {
        margin: 3px 0 0;

        color: #8493a7;
        font-size: 12px;
    }


    /* =========================================================
       GRID KARTU
       ========================================================= */

    .data-menu-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }


    /* =========================================================
       KARTU DATA
       ========================================================= */

    .data-menu-card {
        position: relative;

        display: flex;
        flex-direction: column;

        min-height: 205px;
        padding: 20px;

        background: #ffffff;

        border: 1px solid #e2eaf4;
        border-radius: 15px;

        text-decoration: none;

        box-shadow:
            0 4px 14px rgba(15, 50, 90, .045);

        overflow: hidden;

        transition:
            transform .2s ease,
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .data-menu-card::before {
        content: "";

        position: absolute;

        top: 0;
        left: 0;

        width: 100%;
        height: 3px;

        background: #2563eb;

        transform: scaleX(0);
        transform-origin: left;

        transition: transform .25s ease;
    }

    .data-menu-card:hover {
        transform: translateY(-3px);

        border-color: #c9daf0;

        box-shadow:
            0 12px 28px rgba(30, 64, 175, .09);
    }

    .data-menu-card:hover::before {
        transform: scaleX(1);
    }


    /* =========================================================
       BAGIAN ATAS KARTU
       ========================================================= */

    .data-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 20px;
    }


    /* =========================================================
       ICON
       ========================================================= */

    .data-menu-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        transition: transform .2s ease;
    }

    .data-menu-card:hover .data-menu-icon {
        transform: scale(1.04);
    }

    .data-menu-icon svg {
        width: 24px;
        height: 24px;
    }

    .siswa-icon {
        color: #2563eb;
        background: #eef5ff;
    }

    .guru-icon {
        color: #0f766e;
        background: #ecfdf8;
    }

    .kelas-icon {
        color: #7c3aed;
        background: #f5f1ff;
    }

    .orangtua-icon {
        color: #c26a05;
        background: #fff7e8;
    }


    /* =========================================================
       PANAH ATAS
       ========================================================= */

    .data-menu-arrow {
        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #f7faff;

        color: #7c91aa;

        border: 1px solid #e7eef7;
        border-radius: 9px;

        transition: all .2s ease;
    }

    .data-menu-arrow svg {
        width: 17px;
        height: 17px;
    }

    .data-menu-card:hover .data-menu-arrow {
        background: #eef5ff;
        color: #2563eb;
        border-color: #d7e5f8;
    }


    /* =========================================================
       ISI KARTU
       ========================================================= */

    .data-card-content {
        flex: 1;
    }

    .data-card-label {
        display: inline-block;

        margin-bottom: 6px;

        color: #8a9bb0;

        font-size: 9px;
        font-weight: 750;

        letter-spacing: .8px;
    }

    .data-card-content h3 {
        margin: 0 0 6px;

        color: #102a43;

        font-size: 18px;
        line-height: 1.25;

        font-weight: 700;
    }

    .data-card-content p {
        max-width: 400px;

        margin: 0;

        color: #718096;

        font-size: 12px;
        line-height: 1.6;
    }


    /* =========================================================
       FOOTER KARTU
       ========================================================= */

    .data-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-top: 18px;
        padding-top: 13px;

        border-top: 1px solid #edf2f7;

        color: #2563eb;

        font-size: 11px;
        font-weight: 650;
    }

    .data-card-footer svg {
        width: 15px;
        height: 15px;

        transition: transform .2s ease;
    }

    .data-menu-card:hover .data-card-footer svg {
        transform: translateX(3px);
    }


    /* =========================================================
       RESPONSIVE TABLET
       ========================================================= */

    @media (max-width: 850px) {

        .data-page-header {
            align-items: flex-start;
        }

        .data-menu-grid {
            grid-template-columns: 1fr;
        }

    }


    /* =========================================================
       RESPONSIVE HP
       ========================================================= */

    @media (max-width: 600px) {

        .data-page-header {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }

        .header-left {
            align-items: flex-start;
        }

        .header-icon {
            width: 46px;
            height: 46px;
            min-width: 46px;
            border-radius: 12px;
        }

        .header-icon svg {
            width: 23px;
            height: 23px;
        }

        .data-page-header h1 {
            font-size: 22px;
        }

        .data-page-header p {
            font-size: 12px;
        }

        .back-button {
            width: 100%;
        }

        .data-info-card {
            align-items: flex-start;
            padding: 14px;
        }

        .data-menu-card {
            min-height: 190px;
            padding: 18px;
        }

        .data-card-content h3 {
            font-size: 17px;
        }

    }


    /* =========================================================
       RESPONSIVE HP KECIL
       ========================================================= */

    @media (max-width: 400px) {

        .header-left {
            gap: 11px;
        }

        .data-page-header h1 {
            font-size: 20px;
        }

        .data-menu-card {
            min-height: 180px;
        }

    }

</style>
@endpush