{{-- =========================================================
     SCP - PERIZINAN ANAK ORANGTUA
     File:
     resources/views/orangtua/izin.blade.php
========================================================= --}}

@extends('layouts.app')

@section('title', 'Perizinan Anak - SCP')

@section('page-title', 'Perizinan Anak')


@push('styles')

<style>

    /* =====================================================
       HALAMAN
    ====================================================== */

    .orangtua-izin-page {
        width: 100%;
    }


    /* =====================================================
       HEADER
    ====================================================== */

    .izin-page-header {
        margin-bottom: 22px;
    }

    .izin-page-header h1 {
        margin: 0 0 6px;

        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 24px;
        font-weight: 800;
    }

    .izin-page-header p {
        margin: 0;

        color: #64748b;

        font-size: 12px;
    }


    /* =====================================================
       DATA ANAK
    ====================================================== */

    .izin-anak-card {
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

    .izin-anak-card::after {
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

    .izin-anak-label {
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

    .izin-anak-card h2 {
        position: relative;

        z-index: 1;

        margin: 0 0 15px;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 22px;
        font-weight: 800;
    }

    .izin-anak-info {
        position: relative;

        z-index: 1;

        display: flex;

        flex-wrap: wrap;

        gap: 8px;
    }

    .izin-anak-info span {
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
       ALERT
    ====================================================== */

    .izin-alert {
        display: flex;

        align-items: flex-start;

        gap: 10px;

        margin-bottom: 18px;

        padding: 13px 16px;

        border-radius: 10px;

        font-size: 11px;

        line-height: 1.6;
    }

    .izin-alert i {
        margin-top: 2px;

        font-size: 13px;
    }

    .izin-alert-success {
        border:
            1px solid #cdebd9;

        background: #effaf3;

        color: #167044;
    }

    .izin-alert-error {
        border:
            1px solid #f3caca;

        background: #fff3f3;

        color: #a62d2d;
    }

    .izin-alert-error ul {
        margin: 5px 0 0 18px;

        padding: 0;
    }


    /* =====================================================
       CONTENT GRID
    ====================================================== */

    .izin-content-grid {
        display: grid;

        grid-template-columns:
            minmax(0, 1.2fr)
            minmax(0, 0.8fr);

        gap: 20px;

        margin-bottom: 20px;
    }


    /* =====================================================
       CARD
    ====================================================== */

    .izin-card {
        overflow: hidden;

        border:
            1px solid #e6edf5;

        border-radius: 16px;

        background: #ffffff;

        box-shadow:
            0 4px 18px
            rgba(15, 47, 95, 0.05);
    }

    .izin-card-header {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        padding: 18px 20px;

        border-bottom:
            1px solid #edf2f7;
    }

    .izin-card-header h3 {
        margin: 0;

        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 15px;
        font-weight: 800;
    }

    .izin-card-header span {
        color: #64748b;

        font-size: 10px;
    }

    .izin-card-body {
        padding: 20px;
    }


    /* =====================================================
       FORM
    ====================================================== */

    .izin-form-group {
        margin-bottom: 17px;
    }

    .izin-form-group:last-child {
        margin-bottom: 0;
    }

    .izin-form-label {
        display: block;

        margin-bottom: 7px;

        color: #334155;

        font-size: 11px;
        font-weight: 700;
    }

    .izin-form-control {
        width: 100%;

        padding: 11px 12px;

        border:
            1px solid #dbe5ee;

        border-radius: 9px;

        outline: none;

        background: #ffffff;

        color: #334155;

        font-family: inherit;

        font-size: 11px;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .izin-form-control:focus {
        border-color: #4a90c2;

        box-shadow:
            0 0 0 3px
            rgba(74, 144, 194, 0.10);
    }

    textarea.izin-form-control {
        min-height: 110px;

        resize: vertical;

        line-height: 1.6;
    }

    .izin-form-help {
        margin-top: 6px;

        color: #94a3b8;

        font-size: 9px;

        line-height: 1.5;
    }

    .izin-submit {
        width: 100%;

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        padding: 11px 16px;

        border: none;

        border-radius: 9px;

        background: #1769aa;

        color: #ffffff;

        cursor: pointer;

        font-family: inherit;

        font-size: 11px;
        font-weight: 700;

        transition:
            background 0.2s ease,
            transform 0.2s ease;
    }

    .izin-submit:hover {
        background: #12598f;

        transform: translateY(-1px);
    }


    /* =====================================================
       INFO
    ====================================================== */

    .izin-info-box {
        padding: 16px;

        border:
            1px solid #dcecf8;

        border-radius: 11px;

        background: #f4f9fd;
    }

    .izin-info-title {
        margin-bottom: 10px;

        color: #1769aa;

        font-size: 11px;
        font-weight: 800;
    }

    .izin-info-box ul {
        margin: 0;

        padding-left: 17px;

        color: #64748b;

        font-size: 10px;

        line-height: 1.8;
    }

    .izin-info-box li {
        margin-bottom: 3px;
    }


    /* =====================================================
       TANGGAL
    ====================================================== */

    .izin-date-box {
        margin-top: 15px;

        padding: 15px;

        border:
            1px solid #edf2f7;

        border-radius: 11px;

        background: #ffffff;
    }

    .izin-date-label {
        margin-bottom: 5px;

        color: #94a3b8;

        font-size: 9px;
        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 0.7px;
    }

    .izin-date-value {
        color: #123f68;

        font-size: 13px;
        font-weight: 800;
    }


    /* =====================================================
       HISTORY
    ====================================================== */

    .izin-history-card {
        overflow: hidden;

        border:
            1px solid #e6edf5;

        border-radius: 16px;

        background: #ffffff;

        box-shadow:
            0 4px 18px
            rgba(15, 47, 95, 0.05);
    }

    .izin-history-header {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        padding: 18px 20px;

        border-bottom:
            1px solid #edf2f7;
    }

    .izin-history-header h3 {
        margin: 0 0 4px;

        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 15px;
        font-weight: 800;
    }

    .izin-history-header p {
        margin: 0;

        color: #64748b;

        font-size: 10px;
    }


    /* =====================================================
       TABLE
    ====================================================== */

    .izin-table-wrapper {
        width: 100%;

        overflow-x: auto;
    }

    .izin-table {
        width: 100%;

        min-width: 750px;

        border-collapse: collapse;
    }

    .izin-table th {
        padding: 12px 15px;

        border-bottom:
            1px solid #e8eef5;

        background: #f7fafc;

        color: #64748b;

        font-size: 9px;
        font-weight: 800;

        text-align: left;

        text-transform: uppercase;

        letter-spacing: 0.5px;

        white-space: nowrap;
    }

    .izin-table td {
        padding: 14px 15px;

        border-bottom:
            1px solid #edf2f7;

        color: #475569;

        font-size: 10px;

        vertical-align: middle;
    }

    .izin-table tbody tr:last-child td {
        border-bottom: none;
    }

    .izin-table tbody tr:hover {
        background: #fbfdff;
    }


    /* =====================================================
       TEXT TABLE
    ====================================================== */

    .izin-tanggal {
        color: #475569;

        font-size: 10px;
        font-weight: 700;

        white-space: nowrap;
    }

    .izin-jenis {
        color: #1e293b;

        font-size: 10px;
        font-weight: 800;
    }

    .izin-alasan {
        max-width: 300px;

        color: #64748b;

        font-size: 10px;

        line-height: 1.5;
    }


    /* =====================================================
       BUKTI
    ====================================================== */

    .izin-bukti-link {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 6px 9px;

        border-radius: 7px;

        background: #edf6ff;

        color: #1769aa;

        text-decoration: none;

        font-size: 9px;
        font-weight: 700;
    }

    .izin-bukti-link:hover {
        background: #dfeef9;
    }


    /* =====================================================
       STATUS
    ====================================================== */

    .izin-status {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-width: 72px;

        padding: 6px 9px;

        border-radius: 20px;

        font-size: 8px;
        font-weight: 800;

        white-space: nowrap;
    }

    .izin-status-menunggu {
        background: #fff5d9;

        color: #a46b00;
    }

    .izin-status-disetujui {
        background: #e8f7ee;

        color: #16834b;
    }

    .izin-status-ditolak {
        background: #ffeaea;

        color: #c0392b;
    }

    .izin-status-default {
        background: #f1f3f5;

        color: #64748b;
    }


    /* =====================================================
       EMPTY
    ====================================================== */

    .izin-empty {
        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

        min-height: 220px;

        padding: 30px;

        text-align: center;
    }

    .izin-empty-icon {
        width: 48px;
        height: 48px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin-bottom: 12px;

        border-radius: 13px;

        background: #edf6ff;

        color: #1769aa;

        font-size: 18px;
    }

    .izin-empty h3 {
        margin: 0 0 6px;

        color: #334155;

        font-size: 14px;
        font-weight: 800;
    }

    .izin-empty p {
        margin: 0;

        color: #94a3b8;

        font-size: 10px;
    }


    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 950px) {

        .izin-content-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 650px) {

        .izin-page-header h1 {
            font-size: 21px;
        }

        .izin-anak-card {
            padding: 20px;
        }

        .izin-anak-card h2 {
            font-size: 19px;
        }

        .izin-card-header,
        .izin-history-header {
            padding: 16px;
        }

        .izin-card-body {
            padding: 16px;
        }

    }


    @media (max-width: 430px) {

        .izin-anak-info {
            flex-direction: column;

            align-items: flex-start;
        }

        .izin-anak-info span {
            width: 100%;
        }

    }

</style>

@endpush


@section('content')

<div class="orangtua-izin-page">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="izin-page-header">

        <h1>
            Perizinan Anak
        </h1>

        <p>
            Ajukan izin atau sakit untuk anak dan pantau riwayat pengajuannya.
        </p>

    </div>


    {{-- =====================================================
         DATA ANAK
    ====================================================== --}}

    @php

        $namaAnak =
            data_get(
                $siswa,
                'nama',
                'Data anak tidak ditemukan'
            );

        $nisnAnak =
            data_get(
                $siswa,
                'nisn',
                '-'
            );

        $kelasAnak =
            data_get(
                $siswa,
                'namakelas',
                '-'
            );

        $jurusanAnak =
            data_get(
                $siswa,
                'namajurusan',
                '-'
            );

    @endphp


    @if($siswa)

        <section class="izin-anak-card">

            <div class="izin-anak-label">
                Data Anak
            </div>

            <h2>
                {{ $namaAnak }}
            </h2>

            <div class="izin-anak-info">

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

    @endif


    {{-- =====================================================
         SUCCESS
    ====================================================== --}}

    @if(session('success'))

        <div class="izin-alert izin-alert-success">

            <i class="fa-solid fa-circle-check"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         ERROR
    ====================================================== --}}

    @if($errors->any())

        <div class="izin-alert izin-alert-error">

            <i class="fa-solid fa-circle-exclamation"></i>

            <div>

                <strong>
                    Pengajuan belum dapat dikirim.
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =====================================================
         FORM + INFORMASI
    ====================================================== --}}

    <div class="izin-content-grid">


        {{-- =================================================
             FORM
        ================================================== --}}

        <section class="izin-card">

            <div class="izin-card-header">

                <h3>
                    Ajukan Izin
                </h3>

                <span>
                    Pengajuan hari ini
                </span>

            </div>


            <div class="izin-card-body">


                @if($siswa)

                    <form
                        action="{{ route('izin.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >

                        @csrf


                        {{-- JENIS --}}

                        <div class="izin-form-group">

                            <label
                                for="jenis"
                                class="izin-form-label"
                            >
                                Jenis Pengajuan
                            </label>

                            <select
                                id="jenis"
                                name="jenis"
                                class="izin-form-control"
                                required
                            >

                                <option value="">
                                    -- Pilih jenis --
                                </option>

                                <option
                                    value="izin"
                                    {{ old('jenis') === 'izin' ? 'selected' : '' }}
                                >
                                    Izin
                                </option>

                                <option
                                    value="sakit"
                                    {{ old('jenis') === 'sakit' ? 'selected' : '' }}
                                >
                                    Sakit
                                </option>

                            </select>

                        </div>


                        {{-- ALASAN --}}

                        <div class="izin-form-group">

                            <label
                                for="alasan"
                                class="izin-form-label"
                            >
                                Keterangan / Alasan
                            </label>

                            <textarea
                                id="alasan"
                                name="alasan"
                                class="izin-form-control"
                                placeholder="Tuliskan alasan izin atau keterangan sakit..."
                                required
                            >{{ old('alasan') }}</textarea>

                        </div>


                        {{-- BUKTI --}}

                        <div class="izin-form-group">

                            <label
                                for="bukti"
                                class="izin-form-label"
                            >
                                Surat / Bukti Izin
                            </label>

                            <input
                                id="bukti"
                                type="file"
                                name="bukti"
                                class="izin-form-control"
                                accept=".jpg,.jpeg,.png,.pdf"
                            >

                            <div class="izin-form-help">
                                JPG, JPEG, PNG atau PDF.
                                Maksimal 5 MB.
                            </div>

                        </div>


                        {{-- TANGGAL --}}

                        <div class="izin-form-group">

                            <label
                                class="izin-form-label"
                            >
                                Tanggal Pengajuan
                            </label>

                            <input
                                type="text"
                                class="izin-form-control"
                                value="{{
                                    \Carbon\Carbon::today()
                                        ->locale('id')
                                        ->translatedFormat('l, d F Y')
                                }} (hari ini)"
                                readonly
                            >

                        </div>


                        {{-- SUBMIT --}}

                        <button
                            type="submit"
                            class="izin-submit"
                        >

                            <i class="fa-solid fa-paper-plane"></i>

                            <span>
                                Kirim Pengajuan
                            </span>

                        </button>


                    </form>


                @else


                    <div class="izin-alert izin-alert-error">

                        <i class="fa-solid fa-circle-exclamation"></i>

                        <span>
                            Data anak tidak ditemukan.
                        </span>

                    </div>


                @endif


            </div>

        </section>


        {{-- =================================================
             INFORMASI
        ================================================== --}}

        <section class="izin-card">

            <div class="izin-card-header">

                <h3>
                    Informasi Perizinan
                </h3>

            </div>


            <div class="izin-card-body">


                <div class="izin-info-box">

                    <div class="izin-info-title">
                        Ketentuan Pengajuan
                    </div>

                    <ul>

                        <li>
                            Pengajuan hanya berlaku untuk hari ini.
                        </li>

                        <li>
                            Pilih jenis pengajuan Izin atau Sakit.
                        </li>

                        <li>
                            Tuliskan alasan secara jelas.
                        </li>

                        <li>
                            Surat atau bukti dapat dilampirkan.
                        </li>

                        <li>
                            Ukuran bukti maksimal 5 MB.
                        </li>

                        <li>
                            Pengajuan akan diteruskan kepada guru
                            yang memiliki jadwal anak.
                        </li>

                    </ul>

                </div>


                <div class="izin-date-box">

                    <div class="izin-date-label">
                        Tanggal Pengajuan
                    </div>

                    <div class="izin-date-value">

                        {{
                            \Carbon\Carbon::today()
                                ->locale('id')
                                ->translatedFormat('d F Y')
                        }}

                    </div>

                </div>


            </div>

        </section>


    </div>


    {{-- =====================================================
         RIWAYAT
    ====================================================== --}}

    <section class="izin-history-card">


        <div class="izin-history-header">

            <div>

                <h3>
                    Riwayat Perizinan Anak
                </h3>

                <p>
                    Daftar pengajuan izin yang pernah dikirim.
                </p>

            </div>

            <span>
                Riwayat
            </span>

        </div>


        @if(
            isset($riwayatIzin)
            &&
            $riwayatIzin->count() > 0
        )


            <div class="izin-table-wrapper">

                <table class="izin-table">

                    <thead>

                        <tr>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Jenis
                            </th>

                            <th>
                                Alasan
                            </th>

                            <th>
                                Bukti
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        @foreach($riwayatIzin as $izin)

                            @php

                                $tanggal =
                                    data_get(
                                        $izin,
                                        'tanggal'
                                    );

                                $jenis =
                                    data_get(
                                        $izin,
                                        'jenis',
                                        '-'
                                    );

                                $alasan =
                                    data_get(
                                        $izin,
                                        'alasan',
                                        '-'
                                    );

                                $bukti =
                                    data_get(
                                        $izin,
                                        'bukti'
                                    );

                                $status =
                                    strtolower(
                                        trim(
                                            (string) data_get(
                                                $izin,
                                                'status',
                                                ''
                                            )
                                        )
                                    );

                            @endphp


                            <tr>


                                {{-- TANGGAL --}}

                                <td>

                                    <div class="izin-tanggal">

                                        @if($tanggal)

                                            {{
                                                \Carbon\Carbon::parse(
                                                    $tanggal
                                                )
                                                ->locale('id')
                                                ->translatedFormat(
                                                    'd M Y'
                                                )
                                            }}

                                        @else

                                            -

                                        @endif

                                    </div>

                                </td>


                                {{-- JENIS --}}

                                <td>

                                    <div class="izin-jenis">

                                        {{
                                            ucfirst(
                                                $jenis
                                            )
                                        }}

                                    </div>

                                </td>


                                {{-- ALASAN --}}

                                <td>

                                    <div class="izin-alasan">

                                        {{
                                            $alasan
                                        }}

                                    </div>

                                </td>


                                {{-- BUKTI --}}

                                <td>

                                    @if($bukti)

                                        <a
                                            href="{{
                                                asset(
                                                    'storage/' . $bukti
                                                )
                                            }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="izin-bukti-link"
                                        >

                                            <i class="fa-regular fa-file-lines"></i>

                                            <span>
                                                Lihat Bukti
                                            </span>

                                        </a>

                                    @else

                                        <span>
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}

                                <td>


                                    @if(
                                        $status === 'menunggu'
                                        ||
                                        $status === 'diajukan'
                                    )

                                        <span
                                            class="
                                                izin-status
                                                izin-status-menunggu
                                            "
                                        >
                                            MENUNGGU
                                        </span>


                                    @elseif(
                                        $status === 'disetujui'
                                        ||
                                        $status === 'approved'
                                    )

                                        <span
                                            class="
                                                izin-status
                                                izin-status-disetujui
                                            "
                                        >
                                            DISETUJUI
                                        </span>


                                    @elseif(
                                        $status === 'ditolak'
                                        ||
                                        $status === 'rejected'
                                    )

                                        <span
                                            class="
                                                izin-status
                                                izin-status-ditolak
                                            "
                                        >
                                            DITOLAK
                                        </span>


                                    @else

                                        <span
                                            class="
                                                izin-status
                                                izin-status-default
                                            "
                                        >

                                            {{
                                                strtoupper(
                                                    $status ?: '-'
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


            <div class="izin-empty">

                <div class="izin-empty-icon">

                    <i class="fa-regular fa-file-lines"></i>

                </div>

                <h3>
                    Belum Ada Pengajuan
                </h3>

                <p>
                    Belum ada riwayat perizinan untuk anak.
                </p>

            </div>


        @endif


    </section>


</div>

@endsection