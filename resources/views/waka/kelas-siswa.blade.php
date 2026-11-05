{{-- =========================================================
     DATA KELAS - DAFTAR SISWA DALAM KELAS
     File:
     resources/views/waka/kelas-siswa.blade.php
     ========================================================= --}}

@extends('layouts.app')

@section('title', 'Siswa Kelas - SCP')

@section('content')

{{-- =========================================================
     HEADER HALAMAN
     ========================================================= --}}

<div class="page-header">

    <div>

        <div class="back-wrapper">

            <a
                href="{{ route('waka.kelas.jurusan', [
                    'tingkat' => $kelas->tingkat,
                    'jurusanId' => $kelas->jurusanid
                ]) }}"
                class="back-link"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Daftar Kelas
            </a>

        </div>

        <h1>
            Siswa Kelas {{ $kelas->namakelas }}
        </h1>

        <p>
            Daftar siswa yang terdaftar pada kelas
            {{ $kelas->namakelas }}.
        </p>

    </div>

</div>


{{-- =========================================================
     INFORMASI KELAS
     ========================================================= --}}

<div class="class-summary">

    <div class="class-summary-icon">
        <i class="fa-solid fa-school"></i>
    </div>

    <div class="class-summary-content">

        <span class="summary-label">
            Kelas
        </span>

        <h2>
            {{ $kelas->namakelas }}
        </h2>

        <div class="summary-meta">

            <span>
                <i class="fa-solid fa-graduation-cap"></i>

                {{ $kelas->kodejurusan ?? '-' }}

                @if(!empty($kelas->namajurusan))
                    - {{ $kelas->namajurusan }}
                @endif
            </span>

            <span>
                <i class="fa-solid fa-layer-group"></i>
                Angkatan {{ $kelas->tingkat }}
            </span>

        </div>

    </div>

</div>


{{-- =========================================================
     STATISTIK
     ========================================================= --}}

<div class="stats-grid">

    <div class="stat-card">

        <div class="stat-icon blue">
            <i class="fa-solid fa-users"></i>
        </div>

        <div class="stat-content">

            <span>
                Jumlah Siswa
            </span>

            <strong>
                {{ $jumlahSiswa }}
            </strong>

            <small>
                Siswa terdaftar
            </small>

        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon green">
            <i class="fa-solid fa-school"></i>
        </div>

        <div class="stat-content">

            <span>
                Kelas
            </span>

            <strong>
                {{ $kelas->namakelas }}
            </strong>

            <small>
                Data kelas aktif
            </small>

        </div>

    </div>

</div>


{{-- =========================================================
     DATA SISWA
     ========================================================= --}}

<div class="card">

    <div class="card-header">

        <div>

            <h3>
                <i class="fa-solid fa-users"></i>
                Daftar Siswa
            </h3>

            <p>
                Siswa yang terdaftar di kelas {{ $kelas->namakelas }}.
            </p>

        </div>

        <div class="total-badge">
            {{ $jumlahSiswa }} Siswa
        </div>

    </div>


    <div class="card-body">


        {{-- =================================================
             PENCARIAN
             ================================================= --}}

        <form
            action="{{ route('waka.kelas.siswa', ['id' => $kelas->id]) }}"
            method="GET"
            class="search-toolbar"
        >

            <div class="search-input-wrapper">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    name="cari"
                    value="{{ $kataKunci }}"
                    placeholder="Cari NISN atau nama siswa..."
                    autocomplete="off"
                >

            </div>


            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="fa-solid fa-magnifying-glass"></i>
                Cari
            </button>


            @if($kataKunci !== '')

                <a
                    href="{{ route('waka.kelas.siswa', ['id' => $kelas->id]) }}"
                    class="btn btn-secondary"
                >
                    <i class="fa-solid fa-rotate-left"></i>
                    Reset
                </a>

            @endif

        </form>


        {{-- =================================================
             TABEL SISWA
             ================================================= --}}

        <div class="table-wrapper">

            <table class="data-table">

                <thead>

                    <tr>

                        <th class="col-no">
                            No
                        </th>

                        <th>
                            NISN
                        </th>

                        <th>
                            Nama Siswa
                        </th>

                        <th>
                            Jenis Kelamin
                        </th>

                        <th>
                            Nomor Telepon
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($dataSiswa as $index => $siswa)

                        <tr>

                            {{-- No --}}
                            <td class="number-cell">
                                {{ $index + 1 }}
                            </td>


                            {{-- NISN --}}
                            <td>

                                <span class="nisn-text">
                                    {{ $siswa->nisn }}
                                </span>

                            </td>


                            {{-- Nama --}}
                            <td>

                                <div class="student-name">

                                    <div class="student-avatar">
                                        <i class="fa-solid fa-user"></i>
                                    </div>

                                    <strong>
                                        {{ $siswa->nama }}
                                    </strong>

                                </div>

                            </td>


                            {{-- Jenis Kelamin --}}
                            <td>

                                @if(strtolower($siswa->jeniskelamin ?? '') === 'l')

                                    <span class="gender-badge male">
                                        Laki-laki
                                    </span>

                                @elseif(strtolower($siswa->jeniskelamin ?? '') === 'p')

                                    <span class="gender-badge female">
                                        Perempuan
                                    </span>

                                @else

                                    <span class="gender-badge">
                                        {{ $siswa->jeniskelamin ?? '-' }}
                                    </span>

                                @endif

                            </td>


                            {{-- Nomor Telepon --}}
                            <td>

                                @if(!empty($siswa->nomortelepon))

                                    <span class="phone-text">
                                        <i class="fa-solid fa-phone"></i>
                                        {{ $siswa->nomortelepon }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="empty-table-cell"
                            >

                                <div class="empty-state">

                                    <div class="empty-state-icon">

                                        <i class="fa-solid fa-user-slash"></i>

                                    </div>

                                    @if($kataKunci !== '')

                                        <h3>
                                            Siswa Tidak Ditemukan
                                        </h3>

                                        <p>
                                            Tidak ada siswa yang sesuai
                                            dengan kata pencarian
                                            "{{ $kataKunci }}".
                                        </p>

                                        <a
                                            href="{{ route('waka.kelas.siswa', ['id' => $kelas->id]) }}"
                                            class="btn btn-secondary"
                                        >
                                            <i class="fa-solid fa-rotate-left"></i>
                                            Tampilkan Semua Siswa
                                        </a>

                                    @else

                                        <h3>
                                            Belum Ada Siswa
                                        </h3>

                                        <p>
                                            Belum ada siswa yang
                                            terdaftar di kelas ini.
                                        </p>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection


{{-- =========================================================
     STYLE KHUSUS
     ========================================================= --}}

@push('styles')

<style>

    /* =========================================================
       TOMBOL KEMBALI
       ========================================================= */

    .back-wrapper {
        margin-bottom: 12px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        padding: 9px 14px;

        border: 1px solid #dbe4f0;
        border-radius: 9px;

        background: #ffffff;

        color: #2563eb;

        text-decoration: none;

        font-size: 13px;
        font-weight: 600;

        transition: all 0.2s ease;
    }

    .back-link:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        transform: translateX(-2px);
    }


    /* =========================================================
       INFORMASI KELAS
       ========================================================= */

    .class-summary {
        display: flex;
        align-items: center;
        gap: 18px;

        padding: 20px 22px;

        margin-bottom: 20px;

        background: #ffffff;

        border: 1px solid #e5e7eb;
        border-radius: 16px;

        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
    }

    .class-summary-icon {
        width: 58px;
        height: 58px;

        min-width: 58px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: #eff6ff;
        color: #2563eb;

        font-size: 23px;
    }

    .class-summary-content {
        min-width: 0;
    }

    .summary-label {
        display: block;

        margin-bottom: 3px;

        color: #64748b;

        font-size: 12px;
        font-weight: 600;
    }

    .class-summary h2 {
        margin: 0;

        color: #0f172a;

        font-size: 21px;
        font-weight: 700;
    }

    .summary-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        gap: 18px;

        margin-top: 8px;
    }

    .summary-meta span {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        color: #64748b;

        font-size: 13px;
    }

    .summary-meta i {
        color: #2563eb;
    }


    /* =========================================================
       STATISTIK
       ========================================================= */

    .stats-grid {
        display: grid;

        grid-template-columns: repeat(2, minmax(0, 1fr));

        gap: 16px;

        margin-bottom: 20px;
    }

    .stat-card {
        display: flex;
        align-items: center;

        gap: 14px;

        padding: 18px;

        background: #ffffff;

        border: 1px solid #e5e7eb;

        border-radius: 14px;

        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
    }

    .stat-icon {
        width: 48px;
        height: 48px;

        min-width: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        font-size: 18px;
    }

    .stat-icon.blue {
        background: #eff6ff;
        color: #2563eb;
    }

    .stat-icon.green {
        background: #ecfdf5;
        color: #059669;
    }

    .stat-content {
        min-width: 0;
    }

    .stat-content span {
        display: block;

        margin-bottom: 3px;

        color: #64748b;

        font-size: 12px;
        font-weight: 600;
    }

    .stat-content strong {
        display: block;

        color: #0f172a;

        font-size: 20px;
        font-weight: 700;
    }

    .stat-content small {
        display: block;

        margin-top: 2px;

        color: #94a3b8;

        font-size: 11px;
    }


    /* =========================================================
       HEADER CARD
       ========================================================= */

    .card-header h3 {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-header h3 i {
        color: #2563eb;

        font-size: 16px;
    }

    .total-badge {
        flex-shrink: 0;

        padding: 7px 12px;

        border-radius: 999px;

        background: #eff6ff;

        color: #2563eb;

        font-size: 12px;
        font-weight: 700;
    }


    /* =========================================================
       SEARCH
       ========================================================= */

    .search-toolbar {
        display: flex;
        align-items: center;

        gap: 9px;

        margin-bottom: 18px;
    }

    .search-input-wrapper {
        position: relative;

        flex: 1;

        max-width: 440px;
    }

    .search-input-wrapper i {
        position: absolute;

        left: 14px;
        top: 50%;

        transform: translateY(-50%);

        color: #94a3b8;

        font-size: 13px;

        pointer-events: none;
    }

    .search-input-wrapper input {
        width: 100%;
        height: 42px;

        padding: 0 14px 0 38px;

        border: 1px solid #dbe4f0;
        border-radius: 9px;

        outline: none;

        background: #ffffff;

        color: #0f172a;

        font-size: 13px;

        box-sizing: border-box;

        transition: all 0.2s ease;
    }

    .search-input-wrapper input::placeholder {
        color: #94a3b8;
    }

    .search-input-wrapper input:focus {
        border-color: #2563eb;

        box-shadow:
            0 0 0 3px rgba(37, 99, 235, 0.08);
    }


    /* =========================================================
       BUTTON
       ========================================================= */

    .btn {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 8px;

        height: 42px;

        padding: 0 15px;

        border-radius: 9px;

        font-size: 13px;
        font-weight: 600;

        text-decoration: none;

        border: 1px solid transparent;

        cursor: pointer;

        transition: all 0.2s ease;
    }

    .btn-primary {
        background: #2563eb;

        border-color: #2563eb;

        color: #ffffff;
    }

    .btn-primary:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-secondary {
        background: #ffffff;

        border-color: #dbe4f0;

        color: #334155;
    }

    .btn-secondary:hover {
        background: #f8fafc;

        border-color: #cbd5e1;
    }


    /* =========================================================
       TABEL
       ========================================================= */

    .table-wrapper {
        width: 100%;

        overflow-x: auto;
    }

    .data-table {
        width: 100%;

        min-width: 760px;

        border-collapse: collapse;
    }

    .data-table thead th {
        padding: 13px 14px;

        background: #f8fafc;

        border-bottom: 1px solid #e2e8f0;

        color: #475569;

        font-size: 12px;
        font-weight: 700;

        text-align: left;

        white-space: nowrap;
    }

    .data-table tbody td {
        padding: 14px;

        border-bottom: 1px solid #eef2f7;

        color: #334155;

        font-size: 13px;

        vertical-align: middle;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr {
        transition: background 0.15s ease;
    }

    .data-table tbody tr:hover {
        background: #f8fbff;
    }

    .col-no {
        width: 60px;
    }

    .number-cell {
        color: #64748b;

        font-weight: 600;
    }


    /* =========================================================
       NISN
       ========================================================= */

    .nisn-text {
        display: inline-flex;

        padding: 5px 8px;

        border-radius: 6px;

        background: #f8fafc;

        color: #334155;

        font-size: 12px;
        font-weight: 600;
    }


    /* =========================================================
       NAMA SISWA
       ========================================================= */

    .student-name {
        display: flex;
        align-items: center;

        gap: 10px;
    }

    .student-avatar {
        width: 34px;
        height: 34px;

        min-width: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #eff6ff;

        color: #2563eb;

        font-size: 13px;
    }

    .student-name strong {
        color: #0f172a;

        font-size: 13px;
        font-weight: 600;
    }


    /* =========================================================
       JENIS KELAMIN
       ========================================================= */

    .gender-badge {
        display: inline-flex;
        align-items: center;

        padding: 5px 9px;

        border-radius: 7px;

        background: #f1f5f9;

        color: #475569;

        font-size: 11px;
        font-weight: 600;
    }

    .gender-badge.male {
        background: #eff6ff;

        color: #2563eb;
    }

    .gender-badge.female {
        background: #fdf2f8;

        color: #be185d;
    }


    /* =========================================================
       TELEPON
       ========================================================= */

    .phone-text {
        display: inline-flex;
        align-items: center;

        gap: 7px;

        color: #475569;

        font-size: 12px;
    }

    .phone-text i {
        color: #64748b;

        font-size: 11px;
    }

    .text-muted {
        color: #94a3b8;
    }


    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .empty-table-cell {
        padding: 0 !important;
    }

    .empty-state {
        padding: 48px 20px;

        text-align: center;
    }

    .empty-state-icon {
        width: 58px;
        height: 58px;

        margin: 0 auto 14px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: #f8fafc;

        color: #94a3b8;

        font-size: 22px;
    }

    .empty-state h3 {
        margin: 0 0 6px;

        color: #334155;

        font-size: 15px;
        font-weight: 700;
    }

    .empty-state p {
        margin: 0 auto 16px;

        max-width: 430px;

        color: #64748b;

        font-size: 13px;
        line-height: 1.6;
    }


    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 800px) {

        .stats-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 650px) {

        .class-summary {
            align-items: flex-start;

            padding: 17px;
        }

        .class-summary-icon {
            width: 48px;
            height: 48px;

            min-width: 48px;

            font-size: 19px;
        }

        .class-summary h2 {
            font-size: 18px;
        }

        .summary-meta {
            flex-direction: column;

            align-items: flex-start;

            gap: 6px;
        }

        .search-toolbar {
            flex-direction: column;

            align-items: stretch;
        }

        .search-input-wrapper {
            max-width: none;
        }

        .search-toolbar .btn {
            width: 100%;
        }

        .total-badge {
            display: none;
        }

    }

</style>

@endpush