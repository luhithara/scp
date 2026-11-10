@extends('layouts.app')

@section('title', 'Dashboard Waka - SCP')
@section('page-title', 'Dashboard Waka')
@section('page-subtitle', 'Menu utama Wakil Kepala Sekolah')

@push('styles')
<style>
    .waka-dashboard {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    /* WELCOME */
    .waka-welcome {
        position: relative;
        overflow: hidden;
        padding: 30px;
        border-radius: 18px;
        background: linear-gradient(135deg, #0f4c81 0%, #1d5fa7 52%, #2563eb 100%);
        color: #fff;
        box-shadow: 0 12px 30px rgba(15, 76, 129, .15);
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
        color: rgba(255,255,255,.78);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .4px;
        text-transform: uppercase;
    }

    .waka-welcome h1 {
        margin: 0;
        color: #fff;
        font-size: 27px;
        line-height: 1.3;
        font-weight: 750;
    }

    .waka-welcome p {
        max-width: 680px;
        margin: 8px 0 0;
        color: rgba(255,255,255,.84);
        font-size: 13px;
        line-height: 1.6;
    }

    /* AKSES CEPAT */
    .waka-card {
        width: 100%;
        overflow: hidden;
        border: 1px solid #e5eaf1;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(15, 23, 42, .035);
    }

    .waka-card-header {
        padding: 19px 21px;
        border-bottom: 1px solid #edf1f6;
    }

    .waka-card-header h2 {
        margin: 0;
        color: #0f172a;
        font-size: 16px;
        font-weight: 750;
    }

    .waka-card-header p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 11px;
        line-height: 1.5;
    }

    .waka-card-body {
        padding: 21px;
    }

    .waka-quick-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 13px;
    }

    .waka-quick {
        min-width: 0;
        display: flex;
        align-items: flex-start;
        gap: 13px;
        padding: 17px;
        border: 1px solid #e5eaf1;
        border-radius: 12px;
        background: #fff;
        text-decoration: none;
        transition: .2s ease;
    }

    .waka-quick:hover {
        transform: translateY(-2px);
        border-color: #93c5fd;
        background: #f8fbff;
        box-shadow: 0 7px 18px rgba(37, 99, 235, .06);
    }

    .waka-quick-icon {
        width: 43px;
        height: 43px;
        flex: 0 0 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 16px;
    }

    .waka-quick-content {
        min-width: 0;
        flex: 1;
    }

    .waka-quick-title {
        display: block;
        margin: 2px 0 5px;
        color: #1e293b;
        font-size: 12px;
        font-weight: 750;
    }

    .waka-quick-description {
        display: block;
        color: #64748b;
        font-size: 10.5px;
        line-height: 1.6;
    }

    .waka-quick-arrow {
        margin-left: auto;
        padding-top: 3px;
        color: #94a3b8;
        font-size: 11px;
    }

    @media (max-width: 720px) {
        .waka-welcome {
            padding: 23px;
        }

        .waka-welcome h1 {
            font-size: 23px;
        }

        .waka-quick-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 520px) {
        .waka-dashboard {
            gap: 15px;
        }

        .waka-welcome {
            padding: 20px;
        }

        .waka-card-header {
            padding: 16px;
        }

        .waka-card-body {
            padding: 15px;
        }

        .waka-quick {
            padding: 14px;
        }
    }
</style>
@endpush

@section('content')

<div class="waka-dashboard">

    {{-- WELCOME --}}
    <section class="waka-welcome">
        <div class="waka-welcome-content">

            <div class="waka-welcome-label">
                <i class="fa-solid fa-chart-line"></i>
                School Class Progress
            </div>

            <h1>Dashboard Waka</h1>

            <p>
                Selamat datang di School Class Progress.
                Gunakan menu berikut untuk mengakses dan mengelola
                data akademik sekolah secara terintegrasi.
            </p>

        </div>
    </section>

    {{-- AKSES CEPAT --}}
    <section class="waka-card">

        <div class="waka-card-header">
            <h2>Akses Cepat</h2>
            <p>
                Pilih menu untuk melihat dan mengelola informasi sekolah.
            </p>
        </div>

        <div class="waka-card-body">

            <div class="waka-quick-grid">

                {{-- DATA GURU --}}
                <a href="{{ route('waka.guru') }}" class="waka-quick">

                    <div class="waka-quick-icon">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>

                    <div class="waka-quick-content">
                        <span class="waka-quick-title">Data Guru</span>
                        <span class="waka-quick-description">
                            Melihat dan mengelola data guru serta informasi pengajar.
                        </span>
                    </div>

                    <i class="fa-solid fa-chevron-right waka-quick-arrow"></i>
                </a>

                {{-- DATA KELAS --}}
                <a href="{{ route('waka.kelas') }}" class="waka-quick">

                    <div class="waka-quick-icon">
                        <i class="fa-solid fa-school"></i>
                    </div>

                    <div class="waka-quick-content">
                        <span class="waka-quick-title">Data Kelas</span>
                        <span class="waka-quick-description">
                            Melihat informasi kelas dan data siswa berdasarkan kelas.
                        </span>
                    </div>

                    <i class="fa-solid fa-chevron-right waka-quick-arrow"></i>
                </a>

                {{-- JADWAL --}}
                <a href="{{ route('waka.jadwal') }}" class="waka-quick">

                    <div class="waka-quick-icon">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>

                    <div class="waka-quick-content">
                        <span class="waka-quick-title">Jadwal Pembelajaran</span>
                        <span class="waka-quick-description">
                            Melihat dan memantau jadwal pembelajaran seluruh sekolah.
                        </span>
                    </div>

                    <i class="fa-solid fa-chevron-right waka-quick-arrow"></i>
                </a>

                {{-- JURNAL --}}
                <a href="{{ route('waka.jurnal') }}" class="waka-quick">

                    <div class="waka-quick-icon">
                        <i class="fa-solid fa-book-open"></i>
                    </div>

                    <div class="waka-quick-content">
                        <span class="waka-quick-title">Jurnal Mengajar</span>
                        <span class="waka-quick-description">
                            Melihat dan memantau jurnal mengajar yang diisi oleh guru.
                        </span>
                    </div>

                    <i class="fa-solid fa-chevron-right waka-quick-arrow"></i>
                </a>

            </div>

        </div>
    </section>

</div>

@endsection