{{-- =========================================================
     SCP - PRESENSI ANAK ORANGTUA
     File:
     resources/views/orangtua/presensi.blade.php
========================================================= --}}

@extends('layouts.app')

@section('title', 'Presensi Anak - SCP')

@section('page-title', 'Presensi Anak')


@push('styles')

<style>

    /* =====================================================
       HALAMAN
    ====================================================== */

    .orangtua-presensi-page {
        width: 100%;
    }


    /* =====================================================
       HEADER
    ====================================================== */

    .presensi-page-header {
        display: flex;

        align-items: flex-start;

        justify-content: space-between;

        gap: 20px;

        margin-bottom: 22px;
    }

    .presensi-page-header h1 {
        margin: 0 0 6px;

        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 24px;
        font-weight: 800;
    }

    .presensi-page-header p {
        margin: 0;

        color: #64748b;

        font-size: 12px;
    }


    /* =====================================================
       ALERT
    ====================================================== */

    .presensi-alert {
        display: flex;

        align-items: center;

        gap: 10px;

        margin-bottom: 20px;

        padding: 13px 16px;

        border:
            1px solid #cdebd9;

        border-radius: 10px;

        background: #effaf3;

        color: #167044;

        font-size: 11px;
        font-weight: 600;
    }

    .presensi-alert i {
        font-size: 14px;
    }


    /* =====================================================
       DATA ANAK
    ====================================================== */

    .presensi-anak-card {
        position: relative;

        overflow: hidden;

        margin-bottom: 22px;

        padding: 24px;

        border-radius: 18px;

        background:
            linear-gradient(
                135deg,
                #0f4c81 0%,
                #1769aa 55%,
                #2383c4 100%
            );

        color: #ffffff;

        box-shadow:
            0 12px 30px
            rgba(15, 76, 129, 0.15);
    }

    .presensi-anak-card::after {
        content: '';

        position: absolute;

        width: 200px;
        height: 200px;

        top: -100px;
        right: -75px;

        border-radius: 50%;

        background:
            rgba(255, 255, 255, 0.07);
    }

    .presensi-anak-label {
        position: relative;

        z-index: 1;

        margin-bottom: 7px;

        font-size: 10px;
        font-weight: 700;

        letter-spacing: 1.2px;

        text-transform: uppercase;

        color:
            rgba(255, 255, 255, 0.70);
    }

    .presensi-anak-card h2 {
        position: relative;

        z-index: 1;

        margin: 0 0 15px;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 22px;
        font-weight: 800;
    }

    .presensi-anak-info {
        position: relative;

        z-index: 1;

        display: flex;

        flex-wrap: wrap;

        gap: 8px;
    }

    .presensi-anak-info span {
        padding: 8px 11px;

        border:
            1px solid
            rgba(255, 255, 255, 0.12);

        border-radius: 8px;

        background:
            rgba(255, 255, 255, 0.10);

        font-size: 10px;

        color:
            rgba(255, 255, 255, 0.92);
    }


    /* =====================================================
       STATISTIK
    ====================================================== */

    .presensi-stat-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 15px;

        margin-bottom: 22px;
    }

    .presensi-stat-card {
        padding: 18px;

        border:
            1px solid #e6edf5;

        border-radius: 15px;

        background: #ffffff;

        box-shadow:
            0 4px 18px
            rgba(15, 47, 95, 0.05);
    }

    .presensi-stat-top {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 10px;

        margin-bottom: 13px;
    }

    .presensi-stat-title {
        color: #64748b;

        font-size: 11px;
        font-weight: 600;
    }

    .presensi-stat-icon {
        width: 36px;
        height: 36px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: #edf6ff;

        color: #1769aa;

        font-size: 14px;
    }

    .presensi-stat-number {
        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 25px;
        font-weight: 800;

        line-height: 1;
    }


    /* =====================================================
       CARD RIWAYAT
    ====================================================== */

    .presensi-history-card {
        overflow: hidden;

        border:
            1px solid #e6edf5;

        border-radius: 16px;

        background: #ffffff;

        box-shadow:
            0 4px 18px
            rgba(15, 47, 95, 0.05);
    }

    .presensi-history-header {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        padding: 19px 21px;

        border-bottom:
            1px solid #edf2f7;
    }

    .presensi-history-header h3 {
        margin: 0 0 4px;

        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 15px;
        font-weight: 800;
    }

    .presensi-history-header p {
        margin: 0;

        color: #64748b;

        font-size: 10px;
    }


    /* =====================================================
       TABLE
    ====================================================== */

    .presensi-table-wrapper {
        width: 100%;

        overflow-x: auto;
    }

    .presensi-table {
        width: 100%;

        border-collapse: collapse;

        min-width: 680px;
    }

    .presensi-table thead th {
        padding: 12px 16px;

        background: #f7fafc;

        border-bottom:
            1px solid #e8eef5;

        color: #64748b;

        font-size: 9px;
        font-weight: 800;

        text-align: left;

        text-transform: uppercase;

        letter-spacing: 0.6px;

        white-space: nowrap;
    }

    .presensi-table tbody td {
        padding: 14px 16px;

        border-bottom:
            1px solid #edf2f7;

        color: #475569;

        font-size: 11px;

        vertical-align: middle;
    }

    .presensi-table tbody tr:last-child td {
        border-bottom: none;
    }

    .presensi-table tbody tr:hover {
        background: #fbfdff;
    }


    /* =====================================================
       MAPEL
    ====================================================== */

    .nama-mapel {
        color: #1e293b;

        font-size: 11px;
        font-weight: 800;
    }

    .nama-guru {
        color: #475569;

        font-size: 11px;
        font-weight: 600;
    }


    /* =====================================================
       TANGGAL
    ====================================================== */

    .tanggal {
        color: #475569;

        font-size: 10px;
        font-weight: 600;

        white-space: nowrap;
    }

    .waktu {
        color: #64748b;

        font-size: 10px;
        font-weight: 600;

        white-space: nowrap;
    }


    /* =====================================================
       BADGE
    ====================================================== */

    .presensi-badge {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-width: 58px;

        padding: 6px 10px;

        border-radius: 20px;

        font-size: 9px;
        font-weight: 800;
    }

    .presensi-badge-hadir {
        background: #e8f7ee;

        color: #16834b;
    }

    .presensi-badge-izin {
        background: #fff5d9;

        color: #a46b00;
    }

    .presensi-badge-sakit {
        background: #ffeaea;

        color: #c0392b;
    }

    .presensi-badge-alpa {
        background: #f1f3f5;

        color: #555f6d;
    }


    /* =====================================================
       KOSONG
    ====================================================== */

    .presensi-empty {
        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

        min-height: 260px;

        padding: 35px 25px;

        text-align: center;
    }

    .presensi-empty-icon {
        width: 50px;
        height: 50px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin-bottom: 13px;

        border-radius: 14px;

        background: #edf6ff;

        color: #1769aa;

        font-size: 18px;
    }

    .presensi-empty h3 {
        margin: 0 0 6px;

        color: #334155;

        font-size: 14px;
        font-weight: 800;
    }

    .presensi-empty p {
        max-width: 380px;

        margin: 0;

        color: #94a3b8;

        font-size: 11px;

        line-height: 1.6;
    }


    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 1000px) {

        .presensi-stat-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }


    @media (max-width: 650px) {

        .presensi-page-header h1 {
            font-size: 21px;
        }

        .presensi-anak-card {
            padding: 20px;
        }

        .presensi-anak-card h2 {
            font-size: 19px;
        }

        .presensi-history-header {
            padding: 17px;
        }

        .presensi-stat-card {
            padding: 15px;
        }

        .presensi-stat-number {
            font-size: 21px;
        }

    }


    @media (max-width: 430px) {

        .presensi-stat-grid {
            gap: 10px;
        }

        .presensi-anak-info {
            flex-direction: column;

            align-items: flex-start;
        }

        .presensi-anak-info span {
            width: 100%;
        }

        .presensi-stat-title {
            font-size: 10px;
        }

    }

</style>

@endpush


@section('content')

<div class="orangtua-presensi-page">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="presensi-page-header">

        <div>

            <h1>
                Presensi Anak
            </h1>

            <p>
                Pantau riwayat kehadiran anak di sekolah.
            </p>

        </div>

    </div>


    {{-- =====================================================
         PESAN BERHASIL
    ====================================================== --}}

    @if(session('success'))

        <div class="presensi-alert">

            <i class="fa-solid fa-circle-check"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         DATA ANAK
    ====================================================== --}}

    @php

        $namaAnak =
            data_get(
                $anak,
                'nama',
                'Data anak tidak ditemukan'
            );

        $nisnAnak =
            data_get(
                $anak,
                'nisn',
                '-'
            );

        $kelasAnak =
            data_get(
                $anak,
                'namakelas',
                '-'
            );

        $jurusanAnak =
            data_get(
                $anak,
                'namajurusan',
                '-'
            );

    @endphp


    @if($anak)

        <section class="presensi-anak-card">

            <div class="presensi-anak-label">
                Data Anak
            </div>

            <h2>
                {{ $namaAnak }}
            </h2>

            <div class="presensi-anak-info">

                <span>
                    NISN:
                    {{ $nisnAnak }}
                </span>

                <span>
                    Kelas:
                    {{ $kelasAnak }}
                </span>

                <span>
                    Jurusan:
                    {{ $jurusanAnak }}
                </span>

            </div>

        </section>

    @else

        <section class="presensi-history-card">

            <div class="presensi-empty">

                <div class="presensi-empty-icon">

                    <i class="fa-solid fa-user-slash"></i>

                </div>

                <h3>
                    Data Anak Tidak Ditemukan
                </h3>

                <p>
                    Akun Orangtua belum terhubung
                    dengan data siswa.
                </p>

            </div>

        </section>

    @endif


    {{-- =====================================================
         STATISTIK
    ====================================================== --}}

    <div class="presensi-stat-grid">


        {{-- HADIR --}}

        <div class="presensi-stat-card">

            <div class="presensi-stat-top">

                <div class="presensi-stat-title">
                    Hadir
                </div>

                <div class="presensi-stat-icon">

                    <i class="fa-solid fa-circle-check"></i>

                </div>

            </div>

            <div class="presensi-stat-number">

                {{ $jumlahHadir ?? 0 }}

            </div>

        </div>


        {{-- IZIN --}}

        <div class="presensi-stat-card">

            <div class="presensi-stat-top">

                <div class="presensi-stat-title">
                    Izin
                </div>

                <div class="presensi-stat-icon">

                    <i class="fa-solid fa-file-circle-check"></i>

                </div>

            </div>

            <div class="presensi-stat-number">

                {{ $jumlahIzin ?? 0 }}

            </div>

        </div>


        {{-- SAKIT --}}

        <div class="presensi-stat-card">

            <div class="presensi-stat-top">

                <div class="presensi-stat-title">
                    Sakit
                </div>

                <div class="presensi-stat-icon">

                    <i class="fa-solid fa-notes-medical"></i>

                </div>

            </div>

            <div class="presensi-stat-number">

                {{ $jumlahSakit ?? 0 }}

            </div>

        </div>


        {{-- ALPA --}}

        <div class="presensi-stat-card">

            <div class="presensi-stat-top">

                <div class="presensi-stat-title">
                    Alpa
                </div>

                <div class="presensi-stat-icon">

                    <i class="fa-solid fa-circle-xmark"></i>

                </div>

            </div>

            <div class="presensi-stat-number">

                {{ $jumlahAlpa ?? 0 }}

            </div>

        </div>


    </div>


    {{-- =====================================================
         RIWAYAT PRESENSI
    ====================================================== --}}

    <section class="presensi-history-card">


        {{-- HEADER --}}

        <div class="presensi-history-header">

            <div>

                <h3>
                    Riwayat Presensi
                </h3>

                <p>
                    Data kehadiran anak berdasarkan
                    presensi yang tercatat.
                </p>

            </div>

        </div>


        {{-- =================================================
             DATA PRESENSI
        ================================================== --}}

        @if(
            isset($presensi)
            &&
            $presensi->count() > 0
        )


            <div class="presensi-table-wrapper">

                <table class="presensi-table">

                    <thead>

                        <tr>

                            <th>
                                Mata Pelajaran
                            </th>

                            <th>
                                Guru
                            </th>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Waktu
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        @foreach($presensi as $data)

                            @php

                                /*
                                |--------------------------------------------------------------------------
                                | AMBIL DATA DENGAN AMAN
                                |--------------------------------------------------------------------------
                                */

                                $namaMapel =
                                    data_get(
                                        $data,
                                        'namamapelmaster'
                                    )
                                    ?? data_get(
                                        $data,
                                        'namamapel'
                                    )
                                    ?? 'Mata Pelajaran';

                                $namaGuru =
                                    data_get(
                                        $data,
                                        'namaguru',
                                        '-'
                                    );

                                $createdAt =
                                    data_get(
                                        $data,
                                        'created_at'
                                    );

                                $waktu =
                                    data_get(
                                        $data,
                                        'waktu'
                                    );

                                $status =
                                    strtolower(
                                        trim(
                                            (string) data_get(
                                                $data,
                                                'status',
                                                ''
                                            )
                                        )
                                    );

                            @endphp


                            <tr>


                                {{-- MAPEL --}}

                                <td>

                                    <div class="nama-mapel">

                                        {{ $namaMapel }}

                                    </div>

                                </td>


                                {{-- GURU --}}

                                <td>

                                    <div class="nama-guru">

                                        {{ $namaGuru }}

                                    </div>

                                </td>


                                {{-- TANGGAL --}}

                                <td>

                                    <div class="tanggal">

                                        @if($createdAt)

                                            {{
                                                \Carbon\Carbon::parse(
                                                    $createdAt
                                                )
                                                ->locale('id')
                                                ->translatedFormat(
                                                    'd F Y'
                                                )
                                            }}

                                        @else

                                            -

                                        @endif

                                    </div>

                                </td>


                                {{-- WAKTU --}}

                                <td>

                                    <div class="waktu">

                                        @if($waktu)

                                            {{
                                                substr(
                                                    $waktu,
                                                    0,
                                                    5
                                                )
                                            }}

                                        @elseif($createdAt)

                                            {{
                                                \Carbon\Carbon::parse(
                                                    $createdAt
                                                )
                                                ->format('H:i')
                                            }}

                                        @else

                                            -

                                        @endif

                                    </div>

                                </td>


                                {{-- STATUS --}}

                                <td>


                                    @if($status === 'hadir')

                                        <span
                                            class="
                                                presensi-badge
                                                presensi-badge-hadir
                                            "
                                        >
                                            HADIR
                                        </span>


                                    @elseif($status === 'izin')

                                        <span
                                            class="
                                                presensi-badge
                                                presensi-badge-izin
                                            "
                                        >
                                            IZIN
                                        </span>


                                    @elseif($status === 'sakit')

                                        <span
                                            class="
                                                presensi-badge
                                                presensi-badge-sakit
                                            "
                                        >
                                            SAKIT
                                        </span>


                                    @else

                                        <span
                                            class="
                                                presensi-badge
                                                presensi-badge-alpa
                                            "
                                        >

                                            {{
                                                strtoupper(
                                                    $status ?: 'alpa'
                                                )
                                            }}

                                        </span>

                                    @endif


                                </td>


                            </tr>

                        @endforeach


                    </tbody>

                </table>

            </div>


        @else


            {{-- =================================================
                 KOSONG
            ================================================== --}}

            <div class="presensi-empty">

                <div class="presensi-empty-icon">

                    <i class="fa-regular fa-clipboard"></i>

                </div>

                <h3>
                    Belum Ada Riwayat Presensi
                </h3>

                <p>
                    Belum ada data presensi yang
                    tercatat untuk anak.
                </p>

            </div>


        @endif


    </section>


</div>

@endsection