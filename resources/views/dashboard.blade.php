@extends('layouts.app')

@section('title', 'Dashboard Siswa - SCP')

@section('content')

<style>
    /* =========================================================
       DASHBOARD SISWA - SCP
    ========================================================= */

    .siswa-dashboard {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* =========================================================
       HERO
    ========================================================= */

    .siswa-hero {
        position: relative;
        overflow: hidden;

        display: flex;
        align-items: center;
        justify-content: space-between;

        min-height: 220px;

        padding: 38px 42px;

        border-radius: 22px;

        background:
            linear-gradient(
                135deg,
                #0f3c88 0%,
                #1554b5 52%,
                #2184d8 100%
            );

        color: #ffffff;

        box-shadow:
            0 12px 30px rgba(15, 60, 136, 0.16);
    }

    .siswa-hero::before {
        content: "";

        position: absolute;

        width: 300px;
        height: 300px;

        top: -170px;
        right: -60px;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.07);
    }

    .siswa-hero::after {
        content: "";

        position: absolute;

        width: 220px;
        height: 220px;

        bottom: -150px;
        left: 40%;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.05);
    }

    .siswa-hero-content {
        position: relative;
        z-index: 2;

        max-width: 700px;
    }

    .siswa-hero-label {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        margin-bottom: 16px;

        padding: 7px 12px;

        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 999px;

        background: rgba(255, 255, 255, 0.10);

        color: rgba(255, 255, 255, 0.92);

        font-size: 12px;
        font-weight: 600;
    }

    .siswa-hero-label i {
        font-size: 11px;
    }

    .siswa-hero h1 {
        margin: 0 0 10px;

        font-size: 32px;
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .siswa-hero p {
        margin: 0;

        max-width: 650px;

        color: rgba(255, 255, 255, 0.82);

        font-size: 14px;
        line-height: 1.7;
    }

    .siswa-hero-icon {
        position: relative;
        z-index: 2;

        width: 110px;
        height: 110px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-left: 30px;

        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 28px;

        background: rgba(255, 255, 255, 0.10);

        backdrop-filter: blur(8px);
    }

    .siswa-hero-icon i {
        font-size: 46px;

        color: rgba(255, 255, 255, 0.95);
    }

    /* =========================================================
       SECTION HEADER
    ========================================================= */

    .siswa-section {
        margin-top: 34px;
    }

    .siswa-section-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;

        margin-bottom: 18px;
    }

    .siswa-section-header h2 {
        margin: 0 0 6px;

        color: #172033;

        font-size: 21px;
        line-height: 1.3;
        font-weight: 750;
    }

    .siswa-section-header p {
        margin: 0;

        color: #718096;

        font-size: 13px;
        line-height: 1.6;
    }

    /* =========================================================
       QUICK ACCESS
    ========================================================= */

    .siswa-actions {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 18px;
    }

    .siswa-action-card {
        position: relative;

        min-height: 205px;

        display: flex;
        flex-direction: column;

        padding: 24px;

        border: 1px solid #e3eaf2;
        border-radius: 18px;

        background: #ffffff;

        text-decoration: none;

        box-shadow:
            0 4px 14px rgba(15, 23, 42, 0.04);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            border-color 0.2s ease;
    }

    .siswa-action-card:hover {
        transform: translateY(-4px);

        border-color: #c7d7ee;

        box-shadow:
            0 14px 30px rgba(15, 23, 42, 0.09);
    }

    /* =========================================================
       ICON CARD
    ========================================================= */

    .siswa-action-icon {
        width: 48px;
        height: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 20px;

        border-radius: 13px;

        background: #edf4ff;

        color: #2563eb;
    }

    .siswa-action-icon i {
        font-size: 20px;
    }

    /* =========================================================
       CARD TEXT
    ========================================================= */

    .siswa-action-card h3 {
        margin: 0 0 8px;

        color: #172033;

        font-size: 16px;
        line-height: 1.35;
        font-weight: 700;
    }

    .siswa-action-card p {
        margin: 0;

        color: #718096;

        font-size: 13px;
        line-height: 1.65;
    }

    /* =========================================================
       ARROW
    ========================================================= */

    .siswa-action-arrow {
        margin-top: auto;
        padding-top: 18px;

        display: flex;
        align-items: center;
        gap: 6px;

        color: #2563eb;

        font-size: 12px;
        font-weight: 650;
    }

    .siswa-action-arrow i {
        font-size: 10px;

        transition: transform 0.2s ease;
    }

    .siswa-action-card:hover .siswa-action-arrow i {
        transform: translateX(4px);
    }

    /* =========================================================
       INFO BAWAH
    ========================================================= */

    .siswa-info {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            minmax(0, 1fr);

        gap: 18px;

        margin-top: 28px;
    }

    .siswa-info-card {
        display: flex;
        align-items: center;
        gap: 16px;

        padding: 22px;

        border: 1px solid #e3eaf2;
        border-radius: 18px;

        background: #ffffff;

        box-shadow:
            0 4px 14px rgba(15, 23, 42, 0.03);
    }

    .siswa-info-icon {
        width: 44px;
        height: 44px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #f1f6fd;

        color: #2563eb;
    }

    .siswa-info-icon i {
        font-size: 18px;
    }

    .siswa-info-text strong {
        display: block;

        margin-bottom: 4px;

        color: #172033;

        font-size: 14px;
        font-weight: 700;
    }

    .siswa-info-text span {
        display: block;

        color: #718096;

        font-size: 12px;
        line-height: 1.5;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .siswa-actions {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }


    @media (max-width: 800px) {

        .siswa-hero {
            min-height: auto;

            padding: 30px;
        }

        .siswa-hero h1 {
            font-size: 28px;
        }

        .siswa-hero-icon {
            width: 85px;
            height: 85px;
        }

        .siswa-hero-icon i {
            font-size: 34px;
        }

        .siswa-info {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 600px) {

        .siswa-hero {
            padding: 26px 22px;
        }

        .siswa-hero-icon {
            display: none;
        }

        .siswa-hero h1 {
            font-size: 25px;
        }

        .siswa-hero p {
            font-size: 13px;
        }

        .siswa-actions {
            grid-template-columns: 1fr;

            gap: 14px;
        }

        .siswa-action-card {
            min-height: 180px;

            padding: 21px;
        }

        .siswa-section {
            margin-top: 26px;
        }

    }
</style>


<div class="siswa-dashboard">


    {{-- =========================================================
         HERO
    ========================================================== --}}

    <section class="siswa-hero">

        <div class="siswa-hero-content">

            <div class="siswa-hero-label">

                <i class="fa-solid fa-circle-check"></i>

                <span>
                    Akun siswa aktif
                </span>

            </div>


            <h1>
                Halo, {{ $user->name ?? 'Siswa' }}
            </h1>


            <p>
                Selamat datang di School Class Progress.
                Pantau jadwal, kehadiran, perizinan,
                dan aktivitas belajar kamu dalam satu tempat.
            </p>

        </div>


        <div class="siswa-hero-icon">

            <i class="fa-solid fa-graduation-cap"></i>

        </div>

    </section>



    {{-- =========================================================
         AKSES CEPAT
    ========================================================== --}}

    <section class="siswa-section">

        <div class="siswa-section-header">

            <div>

                <h2>
                    Akses Cepat
                </h2>

                <p>
                    Pilih fitur yang ingin kamu gunakan.
                </p>

            </div>

        </div>


        <div class="siswa-actions">


            {{-- =================================================
                 JADWAL
            ================================================== --}}

            <a
                href="{{ route('jadwal.siswa') }}"
                class="siswa-action-card"
            >

                <div class="siswa-action-icon">

                    <i class="fa-regular fa-calendar"></i>

                </div>


                <h3>
                    Jadwal Kelas
                </h3>


                <p>
                    Lihat jadwal pembelajaran
                    dan kegiatan kelas kamu.
                </p>


                <div class="siswa-action-arrow">

                    <span>
                        Lihat jadwal
                    </span>

                    <i class="fa-solid fa-arrow-right"></i>

                </div>

            </a>



            {{-- =================================================
                 PRESENSI
            ================================================== --}}

            <a
                href="{{ route('presensi.siswa') }}"
                class="siswa-action-card"
            >

                <div class="siswa-action-icon">

                    <i class="fa-solid fa-clipboard-check"></i>

                </div>


                <h3>
                    Riwayat Presensi
                </h3>


                <p>
                    Pantau kehadiran dan status
                    presensi kamu.
                </p>


                <div class="siswa-action-arrow">

                    <span>
                        Lihat presensi
                    </span>

                    <i class="fa-solid fa-arrow-right"></i>

                </div>

            </a>



            {{-- =================================================
                 PERIZINAN
            ================================================== --}}

            <a
                href="{{ route('izin') }}"
                class="siswa-action-card"
            >

                <div class="siswa-action-icon">

                    <i class="fa-solid fa-file-circle-plus"></i>

                </div>


                <h3>
                    Perizinan
                </h3>


                <p>
                    Ajukan izin atau sakit
                    untuk kegiatan hari ini.
                </p>


                <div class="siswa-action-arrow">

                    <span>
                        Ajukan izin
                    </span>

                    <i class="fa-solid fa-arrow-right"></i>

                </div>

            </a>



            {{-- =================================================
                 PROFIL
            ================================================== --}}

            <a
                href="{{ route('profil') }}"
                class="siswa-action-card"
            >

                <div class="siswa-action-icon">

                    <i class="fa-regular fa-user"></i>

                </div>


                <h3>
                    Profil
                </h3>


                <p>
                    Lihat dan perbarui
                    informasi akun kamu.
                </p>


                <div class="siswa-action-arrow">

                    <span>
                        Lihat profil
                    </span>

                    <i class="fa-solid fa-arrow-right"></i>

                </div>

            </a>


        </div>

    </section>



    {{-- =========================================================
         INFORMASI
    ========================================================== --}}

    <div class="siswa-info">


        <div class="siswa-info-card">

            <div class="siswa-info-icon">

                <i class="fa-solid fa-calendar-days"></i>

            </div>

            <div class="siswa-info-text">

                <strong>
                    Pantau jadwal kamu
                </strong>

                <span>
                    Pastikan selalu melihat jadwal
                    pembelajaran terbaru.
                </span>

            </div>

        </div>



        <div class="siswa-info-card">

            <div class="siswa-info-icon">

                <i class="fa-solid fa-shield-halved"></i>

            </div>

            <div class="siswa-info-text">

                <strong>
                    Data akun tetap aman
                </strong>

                <span>
                    Gunakan akun kamu sendiri
                    dan jangan membagikan password.
                </span>

            </div>

        </div>


    </div>

</div>

@endsection