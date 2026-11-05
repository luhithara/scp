{{-- =========================================================
     SCP - JADWAL ANAK ORANGTUA
     File:
     resources/views/orangtua/jadwal.blade.php
========================================================= --}}

@extends('layouts.app')

@section('title', 'Jadwal Anak - SCP')

@section('page-title', 'Jadwal Anak')


@push('styles')

<style>

    /* =====================================================
       HALAMAN JADWAL ORANGTUA
    ====================================================== */

    .orangtua-jadwal-page {
        width: 100%;
    }


    /* =====================================================
       HEADER
    ====================================================== */

    .jadwal-page-header {
        display: flex;

        align-items: flex-start;

        justify-content: space-between;

        gap: 20px;

        margin-bottom: 22px;
    }

    .jadwal-page-header h1 {
        margin: 0 0 6px;

        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 24px;
        font-weight: 800;
    }

    .jadwal-page-header p {
        margin: 0;

        color: #64748b;

        font-size: 12px;
    }


    /* =====================================================
       CARD DATA ANAK
    ====================================================== */

    .jadwal-anak-card {
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

    .jadwal-anak-card::after {
        content: '';

        position: absolute;

        width: 200px;
        height: 200px;

        right: -75px;
        top: -100px;

        border-radius: 50%;

        background:
            rgba(255, 255, 255, 0.07);
    }

    .jadwal-anak-label {
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

    .jadwal-anak-card h2 {
        position: relative;

        z-index: 1;

        margin: 0 0 15px;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 22px;
        font-weight: 800;
    }

    .jadwal-anak-info {
        position: relative;

        z-index: 1;

        display: flex;

        flex-wrap: wrap;

        gap: 8px;
    }

    .jadwal-anak-info span {
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
       CARD JADWAL
    ====================================================== */

    .jadwal-list-card {
        overflow: hidden;

        border:
            1px solid #e6edf5;

        border-radius: 16px;

        background: #ffffff;

        box-shadow:
            0 4px 18px
            rgba(15, 47, 95, 0.05);
    }

    .jadwal-list-header {
        padding: 19px 21px;

        border-bottom:
            1px solid #edf2f7;
    }

    .jadwal-list-header h3 {
        margin: 0 0 5px;

        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 15px;
        font-weight: 800;
    }

    .jadwal-list-header p {
        margin: 0;

        color: #64748b;

        font-size: 11px;
    }


    /* =====================================================
       SECTION HARI
    ====================================================== */

    .hari-section {
        border-bottom:
            1px solid #edf2f7;
    }

    .hari-section:last-child {
        border-bottom: none;
    }

    .hari-title {
        padding: 13px 21px;

        background: #f7fafc;

        border-bottom:
            1px solid #edf2f7;

        color: #1769aa;

        font-size: 11px;
        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: 0.7px;
    }


    /* =====================================================
       ITEM JADWAL
    ====================================================== */

    .jadwal-item {
        display: grid;

        grid-template-columns:
            95px
            minmax(0, 1fr)
            auto
            auto;

        align-items: center;

        gap: 15px;

        padding: 16px 21px;

        border-bottom:
            1px solid #f0f3f7;
    }

    .jadwal-item:last-child {
        border-bottom: none;
    }


    /* =====================================================
       JAM
    ====================================================== */

    .jadwal-jam {
        display: flex;

        align-items: center;
        justify-content: center;

        min-height: 48px;

        padding: 8px;

        border-radius: 10px;

        background: #edf6ff;

        color: #1769aa;

        font-size: 10px;
        font-weight: 800;

        line-height: 1.5;

        text-align: center;
    }


    /* =====================================================
       MAPEL
    ====================================================== */

    .jadwal-detail {
        min-width: 0;
    }

    .jadwal-mapel {
        margin-bottom: 5px;

        color: #1e293b;

        font-size: 12px;
        font-weight: 800;
    }

    .jadwal-guru {
        display: flex;

        align-items: center;

        gap: 6px;

        color: #64748b;

        font-size: 10px;
    }

    .jadwal-guru i {
        color: #1769aa;

        font-size: 10px;
    }


    /* =====================================================
       RUANGAN
    ====================================================== */

    .jadwal-ruangan {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 7px 9px;

        border-radius: 8px;

        background: #f0f7fd;

        color: #1769aa;

        font-size: 9px;
        font-weight: 700;

        white-space: nowrap;
    }

    .jadwal-ruangan i {
        font-size: 9px;
    }


    /* =====================================================
       PUTARAN
    ====================================================== */

    .jadwal-putaran {
        display: inline-flex;

        align-items: center;

        padding: 7px 9px;

        border-radius: 8px;

        background: #f4f6f8;

        color: #64748b;

        font-size: 9px;
        font-weight: 700;

        white-space: nowrap;
    }


    /* =====================================================
       KOSONG
    ====================================================== */

    .jadwal-kosong {
        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

        min-height: 250px;

        padding: 35px 25px;

        text-align: center;
    }

    .jadwal-kosong-icon {
        width: 48px;
        height: 48px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin-bottom: 13px;

        border-radius: 13px;

        background: #edf6ff;

        color: #1769aa;

        font-size: 18px;
    }

    .jadwal-kosong h3 {
        margin: 0 0 6px;

        color: #334155;

        font-size: 14px;
        font-weight: 800;
    }

    .jadwal-kosong p {
        max-width: 380px;

        margin: 0;

        color: #94a3b8;

        font-size: 11px;

        line-height: 1.6;
    }


    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 900px) {

        .jadwal-item {
            grid-template-columns:
                80px
                minmax(0, 1fr)
                auto;
        }

        .jadwal-putaran {
            display: none;
        }

    }


    @media (max-width: 650px) {

        .jadwal-page-header h1 {
            font-size: 21px;
        }

        .jadwal-anak-card {
            padding: 20px;
        }

        .jadwal-anak-card h2 {
            font-size: 19px;
        }

        .jadwal-list-header {
            padding: 17px;
        }

        .hari-title {
            padding: 11px 17px;
        }

        .jadwal-item {
            grid-template-columns:
                70px
                minmax(0, 1fr);

            gap: 10px;

            padding: 13px 17px;
        }

        .jadwal-ruangan {
            grid-column: 2;

            justify-self: start;
        }

        .jadwal-jam {
            min-height: 43px;

            font-size: 9px;
        }

    }


    @media (max-width: 430px) {

        .jadwal-anak-info {
            flex-direction: column;

            align-items: flex-start;
        }

        .jadwal-anak-info span {
            width: 100%;
        }

    }

</style>

@endpush


@section('content')

<div class="orangtua-jadwal-page">


    {{-- =====================================================
         HEADER HALAMAN
    ====================================================== --}}

    <div class="jadwal-page-header">

        <div>

            <h1>
                Jadwal Anak
            </h1>

            <p>
                Jadwal pembelajaran anak berdasarkan kelas.
            </p>

        </div>

    </div>


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

        $tingkatAnak =
            data_get(
                $anak,
                'tingkat',
                '-'
            );

    @endphp


    @if($anak)

        <section class="jadwal-anak-card">

            <div class="jadwal-anak-label">
                Jadwal Anak
            </div>

            <h2>
                {{ $namaAnak }}
            </h2>

            <div class="jadwal-anak-info">

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

                <span>
                    Tingkat:
                    {{ $tingkatAnak }}
                </span>

            </div>

        </section>

    @else

        <section class="jadwal-list-card">

            <div class="jadwal-kosong">

                <div class="jadwal-kosong-icon">

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
         DAFTAR JADWAL
    ====================================================== --}}

    <section class="jadwal-list-card">

        <div class="jadwal-list-header">

            <h3>
                Jadwal Pembelajaran
            </h3>

            <p>

                Jadwal yang terdaftar untuk kelas
                <strong>
                    {{ $kelasAnak }}
                </strong>.

            </p>

        </div>


        @php

            $hariSebelumnya = null;

        @endphp


        @forelse(($jadwal ?? []) as $item)

            @php

                /*
                |--------------------------------------------------------------------------
                | AMBIL DATA DENGAN AMAN
                |--------------------------------------------------------------------------
                */

                $hariSekarang =
                    strtolower(
                        trim(
                            (string) data_get(
                                $item,
                                'hari',
                                ''
                            )
                        )
                    );

                $jamMulai =
                    data_get(
                        $item,
                        'jamMulai'
                    );

                $jamSelesai =
                    data_get(
                        $item,
                        'jamSelesai'
                    );

                $namaMapel =
                    data_get(
                        $item,
                        'namamapel'
                    )
                    ?? data_get(
                        $item,
                        'namamapelmaster'
                    )
                    ?? 'Mata Pelajaran';

                $namaGuru =
                    data_get(
                        $item,
                        'namaguru'
                    )
                    ?? 'Guru belum ditentukan';

                $namaRuangan =
                    data_get(
                        $item,
                        'namaruangan'
                    )
                    ?? 'Belum ditentukan';

                $putaran =
                    data_get(
                        $item,
                        'putaranblok'
                    )
                    ?? '-';


                /*
                |--------------------------------------------------------------------------
                | FORMAT JAM
                |--------------------------------------------------------------------------
                */

                if (
                    $jamMulai !== null
                    &&
                    $jamMulai !== ''
                    &&
                    is_numeric($jamMulai)
                    &&
                    (int) $jamMulai <= 24
                ) {

                    $jamMulaiTampil =
                        str_pad(
                            (int) $jamMulai,
                            2,
                            '0',
                            STR_PAD_LEFT
                        ) . ':00';

                } else {

                    $jamMulaiTampil =
                        $jamMulai
                        ? substr(
                            (string) $jamMulai,
                            0,
                            5
                        )
                        : '-';

                }


                if (
                    $jamSelesai !== null
                    &&
                    $jamSelesai !== ''
                    &&
                    is_numeric($jamSelesai)
                    &&
                    (int) $jamSelesai <= 24
                ) {

                    $jamSelesaiTampil =
                        str_pad(
                            (int) $jamSelesai,
                            2,
                            '0',
                            STR_PAD_LEFT
                        ) . ':00';

                } else {

                    $jamSelesaiTampil =
                        $jamSelesai
                        ? substr(
                            (string) $jamSelesai,
                            0,
                            5
                        )
                        : '-';

                }

            @endphp


            {{-- =================================================
                 GROUP HARI
            ================================================== --}}

            @if($hariSebelumnya !== $hariSekarang)

                @if($hariSebelumnya !== null)

                    </div>

                @endif


                @php

                    $hariSebelumnya =
                        $hariSekarang;

                @endphp


                <div class="hari-section">

                    <div class="hari-title">

                        {{
                            ucfirst(
                                $hariSekarang ?: '-'
                            )
                        }}

                    </div>

            @endif


            {{-- =================================================
                 ITEM
            ================================================== --}}

            <div class="jadwal-item">


                {{-- JAM --}}

                <div class="jadwal-jam">

                    {{ $jamMulaiTampil }}

                    <br>

                    -

                    <br>

                    {{ $jamSelesaiTampil }}

                </div>


                {{-- DETAIL --}}

                <div class="jadwal-detail">

                    <div class="jadwal-mapel">

                        {{ $namaMapel }}

                    </div>

                    <div class="jadwal-guru">

                        <i class="fa-solid fa-user-tie"></i>

                        <span>
                            {{ $namaGuru }}
                        </span>

                    </div>

                </div>


                {{-- RUANGAN --}}

                <div class="jadwal-ruangan">

                    <i class="fa-solid fa-location-dot"></i>

                    <span>
                        {{ $namaRuangan }}
                    </span>

                </div>


                {{-- PUTARAN --}}

                <div class="jadwal-putaran">

                    Putaran
                    {{ $putaran }}

                </div>


            </div>


        @empty

            <div class="jadwal-kosong">

                <div class="jadwal-kosong-icon">

                    <i class="fa-regular fa-calendar-xmark"></i>

                </div>

                <h3>
                    Belum Ada Jadwal
                </h3>

                <p>
                    Jadwal pembelajaran anak
                    belum tersedia untuk periode saat ini.
                </p>

            </div>

        @endforelse


        {{-- =====================================================
             TUTUP HARI TERAKHIR
        ====================================================== --}}

        @if($hariSebelumnya !== null)

            </div>

        @endif


    </section>


</div>

@endsection