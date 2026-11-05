@extends('layouts.app')

@section('title', 'Butuh Penanganan - BK | SCP')

@section('content')

<div class="bk-penanganan-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="bk-page-header">

        <div>

            <div class="bk-eyebrow">
                Bimbingan Konseling
            </div>

            <h1>
                Butuh Penanganan
            </h1>

            <p>
                Daftar siswa yang tidak hadir lebih dari 2 mata pelajaran
                dalam satu hari dan perlu mendapat perhatian BK.
            </p>

        </div>

        <div class="bk-header-status">

            <div class="bk-header-status-icon">
                <i class="fa-solid fa-user-shield"></i>
            </div>

            <div>

                <span>
                    Data Hari Ini
                </span>

                <strong>
                    {{ $data->count() }} Siswa
                </strong>

            </div>

        </div>

    </div>


    {{-- =====================================================
         FILTER
    ====================================================== --}}
    <div class="bk-card filter-card">

        <div class="bk-card-header">

            <div class="bk-card-heading">

                <div class="bk-card-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>

                <div>

                    <h3>
                        Filter Data
                    </h3>

                    <p>
                        Pilih tanggal untuk melihat siswa yang perlu
                        mendapat perhatian BK.
                    </p>

                </div>

            </div>

        </div>


        <div class="bk-card-body">

            <form
                method="GET"
                action="{{ route('bk.butuh-penanganan') }}"
                class="penanganan-filter-form"
            >

                <div class="form-field">

                    <label for="tanggal">
                        Tanggal
                    </label>

                    <div class="input-wrap">

                        <i class="fa-regular fa-calendar"></i>

                        <input
                            type="date"
                            id="tanggal"
                            name="tanggal"
                            value="{{ $tanggal }}"
                        >

                    </div>

                </div>


                <button
                    type="submit"
                    class="bk-primary-button"
                >
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Tampilkan
                </button>


                <a
                    href="{{ route('bk.butuh-penanganan') }}"
                    class="bk-secondary-button"
                >
                    <i class="fa-solid fa-calendar-day"></i>
                    Hari Ini
                </a>

            </form>

        </div>

    </div>


    {{-- =====================================================
         INFORMASI
    ====================================================== --}}
    <div class="attention-card">

        <div class="attention-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <div class="attention-content">

            <strong>
                Perhatian BK
            </strong>

            <p>
                Siswa yang tercatat tidak hadir pada
                <strong>lebih dari 2 mata pelajaran</strong>
                di tanggal yang dipilih akan otomatis muncul
                pada halaman ini.
            </p>

        </div>

    </div>


    {{-- =====================================================
         HEADER HASIL
    ====================================================== --}}
    <div class="result-heading">

        <div>

            <h2>
                Siswa yang Perlu Penanganan
            </h2>

            <p>
                Tanggal:
                <strong>
                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                </strong>
            </p>

        </div>


        <div class="result-count">

            <i class="fa-solid fa-user-group"></i>

            {{ $data->count() }} siswa

        </div>

    </div>


    {{-- =====================================================
         DATA SISWA
    ====================================================== --}}
    <div class="student-list">

        @forelse($data as $item)

            <div class="student-card">

                {{-- =========================================
                     HEADER SISWA
                ========================================== --}}
                <div class="student-card-header">

                    <div class="student-main">

                        <div class="student-avatar">

                            {{ strtoupper(substr($item->nama, 0, 1)) }}

                        </div>

                        <div>

                            <h3>
                                {{ $item->nama }}
                            </h3>

                            <div class="student-meta">

                                <span>
                                    <i class="fa-regular fa-id-card"></i>
                                    NISN: {{ $item->nisn ?? '-' }}
                                </span>

                                <span>
                                    <i class="fa-solid fa-door-open"></i>
                                    {{ $item->namakelas }}
                                </span>

                                <span>
                                    <i class="fa-solid fa-layer-group"></i>
                                    {{ $item->kodejurusan ?? '-' }}
                                </span>

                            </div>

                        </div>

                    </div>


                    <div class="absence-summary">

                        <div class="absence-number">

                            {{ $item->jumlah_tidak_hadir }}

                        </div>

                        <div class="absence-label">
                            Mata Pelajaran
                        </div>

                        <span class="danger-status">

                            <i class="fa-solid fa-circle-exclamation"></i>

                            Tidak Hadir

                        </span>

                    </div>

                </div>


                {{-- =========================================
                     DETAIL KETIDAKHADIRAN
                ========================================== --}}
                <div class="detail-section">

                    <div class="detail-title">

                        <div class="detail-title-icon">
                            <i class="fa-solid fa-list-check"></i>
                        </div>

                        <span>
                            Detail Ketidakhadiran
                        </span>

                    </div>


                    <div class="subject-grid">

                        @foreach($item->mataPelajaran as $mapel)

                            <div class="subject-card">

                                <div class="subject-top">

                                    <div class="subject-icon">
                                        <i class="fa-solid fa-book-open"></i>
                                    </div>

                                    <div class="subject-name">

                                        {{
                                            $mapel->namamapel
                                            ?? $mapel->mapel_nama
                                            ?? '-'
                                        }}

                                    </div>

                                </div>


                                <div class="subject-info">

                                    <div class="subject-info-row">

                                        <i class="fa-regular fa-clock"></i>

                                        <span>

                                            @if($mapel->jamMulai)

                                                {{ $mapel->jamMulai }}

                                                @if($mapel->jamSelesai)
                                                    - {{ $mapel->jamSelesai }}
                                                @endif

                                            @else

                                                Jam tidak tersedia

                                            @endif

                                        </span>

                                    </div>


                                    <div class="subject-info-row">

                                        <i class="fa-solid fa-chalkboard-user"></i>

                                        <span>
                                            Guru:
                                            {{ $mapel->nama_guru ?? '-' }}
                                        </span>

                                    </div>

                                </div>


                                <div class="subject-status">

                                    @if($mapel->status === 'alpa')

                                        <span class="status-badge alpa">
                                            <i class="fa-solid fa-xmark"></i>
                                            Alpa
                                        </span>

                                    @elseif($mapel->status === 'sakit')

                                        <span class="status-badge sakit">
                                            <i class="fa-solid fa-notes-medical"></i>
                                            Sakit
                                        </span>

                                    @elseif($mapel->status === 'izin')

                                        <span class="status-badge izin">
                                            <i class="fa-solid fa-file-circle-check"></i>
                                            Izin
                                        </span>

                                    @else

                                        <span class="status-badge default">
                                            {{ ucfirst($mapel->status) }}
                                        </span>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>


                {{-- =========================================
                     ACTION
                ========================================== --}}
                <div class="student-card-footer">

                    <a
                        href="{{ route('bk.siswa.detail', $item->id) }}"
                        class="detail-button"
                    >

                        <i class="fa-regular fa-eye"></i>

                        Lihat Detail Siswa

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>

            </div>

        @empty

            {{-- =============================================
                 EMPTY STATE
            ============================================== --}}
            <div class="bk-card empty-card">

                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>

                    <h3>
                        Tidak Ada Siswa yang Membutuhkan Penanganan
                    </h3>

                    <p>
                        Tidak ditemukan siswa dengan lebih dari
                        2 ketidakhadiran pada tanggal tersebut.
                    </p>

                    <a
                        href="{{ route('bk.butuh-penanganan') }}"
                        class="bk-secondary-button"
                    >
                        <i class="fa-solid fa-calendar-day"></i>
                        Lihat Hari Ini
                    </a>

                </div>

            </div>

        @endforelse

    </div>

</div>


{{-- =========================================================
     STYLE
========================================================= --}}
@push('styles')

<style>

    /* =====================================================
       PAGE
    ====================================================== */

    .bk-penanganan-page {
        width: 100%;
        max-width: 1320px;
        margin: 0 auto;
        padding-bottom: 30px;
    }


    /* =====================================================
       HEADER
    ====================================================== */

    .bk-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .bk-eyebrow {
        margin-bottom: 5px;
        color: #748196;
        font-size: 9px;
        font-weight: 700;
    }

    .bk-page-header h1 {
        margin: 0;
        color: #17233a;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 24px;
        font-weight: 800;
        line-height: 1.2;
    }

    .bk-page-header p {
        max-width: 730px;
        margin: 7px 0 0;
        color: #718096;
        font-size: 11px;
        line-height: 1.55;
    }

    .bk-header-status {
        min-width: 145px;
        padding: 10px 13px;
        border: 1px solid #e0e7f0;
        border-radius: 11px;
        background: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 2px 8px rgba(30, 64, 175, .04);
    }

    .bk-header-status-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 9px;
        background: #fff0f1;
        color: #c23948;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .bk-header-status span {
        display: block;
        color: #8b96a7;
        font-size: 8px;
        font-weight: 600;
    }

    .bk-header-status strong {
        display: block;
        margin-top: 2px;
        color: #26354d;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 800;
    }


    /* =====================================================
       CARD
    ====================================================== */

    .bk-card {
        width: 100%;
        border: 1px solid #e1e7ef;
        border-radius: 13px;
        background: #fff;
        box-shadow: 0 2px 9px rgba(20, 48, 90, .035);
        overflow: hidden;
    }

    .filter-card {
        margin-bottom: 16px;
    }

    .bk-card-header {
        min-height: 74px;
        padding: 14px 17px;
        border-bottom: 1px solid #edf0f4;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .bk-card-heading {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .bk-card-icon {
        width: 35px;
        height: 35px;
        min-width: 35px;
        border-radius: 9px;
        background: #edf4ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .bk-card-heading h3 {
        margin: 0;
        color: #24334b;
        font-size: 12px;
        font-weight: 800;
    }

    .bk-card-heading p {
        margin: 4px 0 0;
        color: #8a95a6;
        font-size: 9px;
        line-height: 1.4;
    }

    .bk-card-body {
        padding: 17px;
    }


    /* =====================================================
       FILTER
    ====================================================== */

    .penanganan-filter-form {
        max-width: 650px;
        display: flex;
        align-items: flex-end;
        gap: 10px;
    }

    .form-field {
        flex: 1;
    }

    .form-field label {
        display: block;
        margin-bottom: 6px;
        color: #34425a;
        font-size: 10px;
        font-weight: 700;
    }

    .input-wrap {
        position: relative;
    }

    .input-wrap i {
        position: absolute;
        top: 50%;
        left: 11px;
        transform: translateY(-50%);
        color: #8995a7;
        font-size: 11px;
        pointer-events: none;
    }

    .input-wrap input {
        width: 100%;
        height: 39px;
        padding: 0 10px 0 32px;
        border: 1px solid #dce2eb;
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: #334158;
        font-family: inherit;
        font-size: 10px;
        transition: .2s ease;
    }

    .input-wrap input:focus {
        border-color: #1769d1;
        box-shadow: 0 0 0 3px rgba(23, 105, 209, .08);
    }


    /* =====================================================
       BUTTON
    ====================================================== */

    .bk-primary-button,
    .bk-secondary-button {
        min-height: 39px;
        padding: 8px 12px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        font-family: inherit;
        font-size: 9px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        transition: .2s ease;
    }

    .bk-primary-button {
        border: 1px solid #1769d1;
        background: #1769d1;
        color: #fff;
    }

    .bk-primary-button:hover {
        background: #1259b4;
        color: #fff;
    }

    .bk-secondary-button {
        border: 1px solid #dce2eb;
        background: #f8fafc;
        color: #68758a;
    }

    .bk-secondary-button:hover {
        background: #f1f4f8;
        color: #35445d;
    }


    /* =====================================================
       ATTENTION
    ====================================================== */

    .attention-card {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        margin-bottom: 22px;
        border: 1px solid #f5dfaa;
        border-radius: 11px;
        background: #fffaf0;
    }

    .attention-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        border-radius: 10px;
        background: #fff0c7;
        color: #b87505;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .attention-content strong {
        display: block;
        color: #7a5a13;
        font-size: 11px;
        font-weight: 800;
    }

    .attention-content p {
        max-width: 850px;
        margin: 5px 0 0;
        color: #7d6840;
        font-size: 9px;
        line-height: 1.55;
    }

    .attention-content p strong {
        display: inline;
        color: #74520b;
        font-size: inherit;
    }


    /* =====================================================
       RESULT HEADING
    ====================================================== */

    .result-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 12px;
    }

    .result-heading h2 {
        margin: 0;
        color: #17233a;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 15px;
        font-weight: 800;
    }

    .result-heading p {
        margin: 4px 0 0;
        color: #8a95a6;
        font-size: 10px;
    }

    .result-heading p strong {
        color: #596579;
        font-weight: 800;
    }

    .result-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 7px;
        background: #fff0f1;
        color: #c23948;
        font-size: 8px;
        font-weight: 800;
        white-space: nowrap;
    }


    /* =====================================================
       STUDENT CARD
    ====================================================== */

    .student-list {
        display: flex;
        flex-direction: column;
        gap: 13px;
    }

    .student-card {
        border: 1px solid #e1e7ef;
        border-radius: 13px;
        background: #fff;
        box-shadow: 0 2px 9px rgba(20, 48, 90, .035);
        overflow: hidden;
        transition: .2s ease;
    }

    .student-card:hover {
        border-color: #d1dceb;
        box-shadow: 0 5px 16px rgba(20, 48, 90, .06);
    }


    /* =====================================================
       STUDENT HEADER
    ====================================================== */

    .student-card-header {
        padding: 17px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        border-bottom: 1px solid #edf0f4;
    }

    .student-main {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
    }

    .student-avatar {
        width: 43px;
        height: 43px;
        min-width: 43px;
        border-radius: 11px;
        background: #edf4ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
    }

    .student-main h3 {
        margin: 0;
        color: #29374e;
        font-size: 13px;
        font-weight: 800;
    }

    .student-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin-top: 6px;
    }

    .student-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: #8a95a6;
        font-size: 8px;
    }

    .student-meta i {
        color: #1769d1;
        font-size: 8px;
    }


    /* =====================================================
       ABSENCE SUMMARY
    ====================================================== */

    .absence-summary {
        min-width: 115px;
        text-align: right;
        flex-shrink: 0;
    }

    .absence-number {
        color: #c23948;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 21px;
        font-weight: 800;
        line-height: 1;
    }

    .absence-label {
        margin-top: 4px;
        color: #8a95a6;
        font-size: 7px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .2px;
    }

    .danger-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 7px;
        padding: 4px 7px;
        border-radius: 6px;
        background: #fff0f1;
        color: #c23948;
        font-size: 7px;
        font-weight: 800;
    }


    /* =====================================================
       DETAIL
    ====================================================== */

    .detail-section {
        padding: 16px 17px;
    }

    .detail-title {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 11px;
        color: #35445d;
        font-size: 10px;
        font-weight: 800;
    }

    .detail-title-icon {
        color: #1769d1;
        font-size: 10px;
    }

    .subject-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 9px;
    }

    .subject-card {
        padding: 12px;
        border: 1px solid #e4e9f0;
        border-radius: 9px;
        background: #f9fbfd;
        transition: .2s ease;
    }

    .subject-card:hover {
        border-color: #cedbeb;
        background: #f7faff;
    }

    .subject-top {
        display: flex;
        align-items: flex-start;
        gap: 7px;
    }

    .subject-icon {
        width: 26px;
        height: 26px;
        min-width: 26px;
        border-radius: 7px;
        background: #edf4ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
    }

    .subject-name {
        color: #35445d;
        font-size: 9px;
        font-weight: 800;
        line-height: 1.45;
    }

    .subject-info {
        margin-top: 10px;
        padding-top: 8px;
        border-top: 1px solid #e9edf2;
    }

    .subject-info-row {
        display: flex;
        align-items: flex-start;
        gap: 6px;
        margin-top: 5px;
        color: #8994a5;
        font-size: 8px;
        line-height: 1.4;
    }

    .subject-info-row:first-child {
        margin-top: 0;
    }

    .subject-info-row i {
        width: 11px;
        color: #1769d1;
        margin-top: 1px;
        text-align: center;
    }

    .subject-status {
        margin-top: 9px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 7px;
        border-radius: 6px;
        font-size: 7px;
        font-weight: 800;
    }

    .status-badge.alpa {
        background: #fff0f1;
        color: #c23948;
    }

    .status-badge.sakit {
        background: #fff5e6;
        color: #a96800;
    }

    .status-badge.izin {
        background: #edf4ff;
        color: #1769d1;
    }

    .status-badge.default {
        background: #f1f4f8;
        color: #68758a;
    }


    /* =====================================================
       FOOTER
    ====================================================== */

    .student-card-footer {
        padding: 12px 17px;
        border-top: 1px solid #edf0f4;
        background: #fbfcfe;
        display: flex;
        justify-content: flex-end;
    }

    .detail-button {
        min-height: 32px;
        padding: 6px 10px;
        border: 1px solid #d8e3f3;
        border-radius: 7px;
        background: #f7faff;
        color: #1769d1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        font-size: 8px;
        font-weight: 800;
        transition: .2s ease;
    }

    .detail-button:hover {
        background: #edf4ff;
        border-color: #c4d7f2;
        color: #1259b4;
    }


    /* =====================================================
       EMPTY
    ====================================================== */

    .empty-card {
        min-height: 300px;
    }

    .empty-state {
        min-height: 300px;
        padding: 40px 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        text-align: center;
    }

    .empty-icon {
        width: 57px;
        height: 57px;
        margin-bottom: 13px;
        border-radius: 15px;
        background: #ebf9f1;
        color: #16804b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .empty-state h3 {
        margin: 0;
        color: #26354d;
        font-size: 13px;
        font-weight: 800;
    }

    .empty-state p {
        max-width: 500px;
        margin: 6px 0 16px;
        color: #8a95a6;
        font-size: 10px;
        line-height: 1.5;
    }


    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 950px) {

        .subject-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }


    @media (max-width: 750px) {

        .bk-page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .bk-header-status {
            width: 100%;
        }

        .penanganan-filter-form {
            max-width: none;
        }

        .student-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .absence-summary {
            width: 100%;
            text-align: left;
        }

        .result-heading {
            align-items: flex-start;
            flex-direction: column;
        }

    }


    @media (max-width: 600px) {

        .penanganan-filter-form {
            align-items: stretch;
            flex-direction: column;
        }

        .penanganan-filter-form .bk-primary-button,
        .penanganan-filter-form .bk-secondary-button {
            width: 100%;
        }

        .subject-grid {
            grid-template-columns: 1fr;
        }

        .student-meta {
            align-items: flex-start;
            flex-direction: column;
            gap: 5px;
        }

        .student-card-footer {
            justify-content: stretch;
        }

        .detail-button {
            width: 100%;
        }

    }

</style>

@endpush

@endsection