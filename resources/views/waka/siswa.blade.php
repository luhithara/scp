{{-- =========================================================
     SCP - DATA SISWA WAKA
     File:
     resources/views/waka/siswa.blade.php
========================================================= --}}

@extends('layouts.app')

@section('title', 'Data Siswa - SCP')

@section('page-title', 'Data Siswa')

@section('content')

<style>
    .siswa-page {
        width: 100%;
    }

    .page-header {
        margin-bottom: 24px;
    }

    .page-header h1 {
        margin: 0 0 6px;
        font-size: 28px;
        font-weight: 700;
        color: #172033;
    }

    .page-header p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    /* =====================================================
       RINGKASAN
    ===================================================== */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e5eaf2;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    }

    .stat-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .stat-label {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 6px;
    }

    .stat-number {
        color: #172033;
        font-size: 28px;
        font-weight: 700;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 18px;
        flex-shrink: 0;
    }

    /* =====================================================
       CARD UTAMA
    ===================================================== */

    .selection-card {
        background: #ffffff;
        border: 1px solid #e5eaf2;
        border-radius: 18px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .selection-header {
        padding: 22px 24px;
        border-bottom: 1px solid #edf0f5;
    }

    .selection-header h3 {
        margin: 0 0 5px;
        color: #172033;
        font-size: 18px;
        font-weight: 700;
    }

    .selection-header p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .selection-body {
        padding: 24px;
    }

    /* =====================================================
       KARTU ANGKATAN
    ===================================================== */

    .angkatan-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .angkatan-card {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 190px;
        padding: 22px;
        border: 1px solid #e3eaf5;
        border-radius: 16px;
        background: #ffffff;
        text-decoration: none;
        transition:
            transform 0.18s ease,
            border-color 0.18s ease,
            box-shadow 0.18s ease;
        overflow: hidden;
    }

    .angkatan-card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 4px;
        background: #2563eb;
    }

    .angkatan-card:hover {
        transform: translateY(-3px);
        border-color: #bfdbfe;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.10);
    }

    .angkatan-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 15px;
    }

    .angkatan-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 19px;
    }

    .angkatan-arrow {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        color: #64748b;
        transition: 0.18s ease;
    }

    .angkatan-card:hover .angkatan-arrow {
        background: #2563eb;
        color: #ffffff;
    }

    .angkatan-content {
        margin-top: 22px;
    }

    .angkatan-label {
        display: block;
        color: #64748b;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .angkatan-title {
        margin: 0;
        color: #172033;
        font-size: 21px;
        font-weight: 700;
    }

    .angkatan-description {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
    }

    .angkatan-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px solid #edf0f5;
    }

    .angkatan-footer span {
        color: #2563eb;
        font-size: 13px;
        font-weight: 600;
    }

    /* =====================================================
       INFORMASI ALUR
    ===================================================== */

    .info-card {
        margin-top: 18px;
        padding: 18px 20px;
        border: 1px solid #dbeafe;
        border-radius: 14px;
        background: #f8fbff;
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #dbeafe;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-content strong {
        display: block;
        color: #1e3a8a;
        font-size: 13px;
        margin-bottom: 4px;
    }

    .info-content span {
        display: block;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 900px) {

        .angkatan-grid {
            grid-template-columns: 1fr;
        }

    }

    @media (max-width: 700px) {

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .selection-header {
            padding: 18px;
        }

        .selection-body {
            padding: 18px;
        }

        .page-header h1 {
            font-size: 24px;
        }

    }
</style>


<div class="siswa-page">

    {{-- =====================================================
         HEADER
    ===================================================== --}}

    <div class="page-header">

        <h1>
            Data Siswa
        </h1>

        <p>
            Kelola dan lihat data siswa berdasarkan angkatan, jurusan, dan kelas.
        </p>

    </div>


    {{-- =====================================================
         RINGKASAN
    ===================================================== --}}

    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-card-top">

                <div>

                    <div class="stat-label">
                        Jumlah Seluruh Siswa
                    </div>

                    <div class="stat-number">
                        {{ $jumlahSiswa ?? 0 }}
                    </div>

                </div>

                <div class="stat-icon">
                    <i class="fa-solid fa-users"></i>
                </div>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-card-top">

                <div>

                    <div class="stat-label">
                        Pilihan Angkatan
                    </div>

                    <div class="stat-number">
                        3
                    </div>

                </div>

                <div class="stat-icon">
                    <i class="fa-solid fa-layer-group"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         PILIH ANGKATAN
    ===================================================== --}}

    <div class="selection-card">

        <div class="selection-header">

            <h3>
                Pilih Angkatan
            </h3>

            <p>
                Pilih tingkat kelas untuk melihat data siswa berdasarkan
                jurusan dan kelas.
            </p>

        </div>


        <div class="selection-body">

            <div class="angkatan-grid">


                {{-- =================================================
                     KELAS 10
                ================================================= --}}

                <a
                    href="{{ route('waka.kelas.angkatan', 10) }}"
                    class="angkatan-card"
                >

                    <div class="angkatan-top">

                        <div class="angkatan-icon">
                            <i class="fa-solid fa-1"></i>
                        </div>

                        <div class="angkatan-arrow">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>

                    </div>


                    <div class="angkatan-content">

                        <span class="angkatan-label">
                            Tingkat
                        </span>

                        <h2 class="angkatan-title">
                            Kelas 10
                        </h2>

                        <p class="angkatan-description">
                            Lihat jurusan dan kelas yang berada di tingkat 10.
                        </p>

                    </div>


                    <div class="angkatan-footer">

                        <span>
                            Lihat data
                        </span>

                        <i class="fa-solid fa-chevron-right"></i>

                    </div>

                </a>


                {{-- =================================================
                     KELAS 11
                ================================================= --}}

                <a
                    href="{{ route('waka.kelas.angkatan', 11) }}"
                    class="angkatan-card"
                >

                    <div class="angkatan-top">

                        <div class="angkatan-icon">
                            <i class="fa-solid fa-2"></i>
                        </div>

                        <div class="angkatan-arrow">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>

                    </div>


                    <div class="angkatan-content">

                        <span class="angkatan-label">
                            Tingkat
                        </span>

                        <h2 class="angkatan-title">
                            Kelas 11
                        </h2>

                        <p class="angkatan-description">
                            Lihat jurusan dan kelas yang berada di tingkat 11.
                        </p>

                    </div>


                    <div class="angkatan-footer">

                        <span>
                            Lihat data
                        </span>

                        <i class="fa-solid fa-chevron-right"></i>

                    </div>

                </a>


                {{-- =================================================
                     KELAS 12
                ================================================= --}}

                <a
                    href="{{ route('waka.kelas.angkatan', 12) }}"
                    class="angkatan-card"
                >

                    <div class="angkatan-top">

                        <div class="angkatan-icon">
                            <i class="fa-solid fa-3"></i>
                        </div>

                        <div class="angkatan-arrow">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>

                    </div>


                    <div class="angkatan-content">

                        <span class="angkatan-label">
                            Tingkat
                        </span>

                        <h2 class="angkatan-title">
                            Kelas 12
                        </h2>

                        <p class="angkatan-description">
                            Lihat jurusan dan kelas yang berada di tingkat 12.
                        </p>

                    </div>


                    <div class="angkatan-footer">

                        <span>
                            Lihat data
                        </span>

                        <i class="fa-solid fa-chevron-right"></i>

                    </div>

                </a>


            </div>


            {{-- =====================================================
                 INFORMASI ALUR
            ===================================================== --}}

            <div class="info-card">

                <div class="info-icon">
                    <i class="fa-solid fa-circle-info"></i>
                </div>

                <div class="info-content">

                    <strong>
                        Cara melihat data siswa
                    </strong>

                    <span>
                        Pilih angkatan terlebih dahulu, kemudian pilih jurusan,
                        pilih kelas, dan setelah itu daftar siswa dalam kelas
                        tersebut akan ditampilkan.
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection