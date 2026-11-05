@extends('layouts.app')

@section('title', 'Dashboard BK - SCP')

@section('content')

<style>
    .bk-dashboard {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .bk-welcome {
        background: linear-gradient(135deg, #0f4c81, #2563eb);
        color: #fff;
        border-radius: 18px;
        padding: 28px 30px;
        box-shadow: 0 10px 30px rgba(15, 76, 129, .12);
    }

    .bk-welcome h1 {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 750;
    }

    .bk-welcome p {
        margin: 0;
        color: rgba(255,255,255,.88);
        font-size: 14px;
        line-height: 1.6;
    }

    .bk-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .bk-stat {
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 6px 20px rgba(15, 23, 42, .05);
    }

    .bk-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .bk-stat-title {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        font-weight: 650;
    }

    .bk-stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 17px;
        flex-shrink: 0;
    }

    .bk-stat-number {
        margin-top: 15px;
        color: #0f172a;
        font-size: 30px;
        line-height: 1;
        font-weight: 750;
    }

    .bk-stat-desc {
        margin: 10px 0 0;
        color: #94a3b8;
        font-size: 12px;
    }

    .bk-section {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .bk-section-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
    }

    .bk-section-head h2 {
        margin: 0 0 5px;
        color: #0f172a;
        font-size: 20px;
        font-weight: 750;
    }

    .bk-section-head p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }

    .bk-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #2563eb;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .bk-link:hover {
        text-decoration: underline;
    }

    .bk-attendance {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .bk-attendance-card {
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 6px 20px rgba(15, 23, 42, .04);
    }

    .bk-attendance-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .bk-attendance-card h3 {
        margin: 0;
        color: #334155;
        font-size: 14px;
    }

    .bk-attendance-number {
        margin-top: 15px;
        color: #0f172a;
        font-size: 28px;
        font-weight: 750;
    }

    .bk-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 999px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 11px;
        font-weight: 700;
    }

    .bk-badge.success {
        background: #ecfdf5;
        color: #047857;
    }

    .bk-badge.warning {
        background: #fffbeb;
        color: #b45309;
    }

    .bk-panel {
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(15, 23, 42, .04);
    }

    .bk-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .bk-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    .bk-table th {
        padding: 14px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e5eaf1;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .bk-table td {
        padding: 15px 18px;
        border-bottom: 1px solid #eef2f7;
        color: #475569;
        font-size: 13px;
    }

    .bk-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .bk-table tbody tr:hover {
        background: #f8fbff;
    }

    .bk-table strong {
        color: #0f172a;
        font-weight: 700;
    }

    .bk-empty {
        padding: 42px 20px !important;
        text-align: center;
        color: #94a3b8 !important;
    }

    .bk-empty-icon {
        width: 46px;
        height: 46px;
        margin: 0 auto 12px;
        border-radius: 13px;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .bk-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 38px;
        padding: 9px 14px;
        border-radius: 10px;
        background: #2563eb;
        color: #fff;
        text-decoration: none;
        border: 1px solid #2563eb;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .bk-button:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #fff;
        transform: translateY(-1px);
    }

    .bk-panel-head {
        padding: 18px 20px;
        border-bottom: 1px solid #e5eaf1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .bk-panel-head h3 {
        margin: 0;
        color: #0f172a;
        font-size: 15px;
        font-weight: 750;
    }

    @media (max-width: 900px) {
        .bk-stats,
        .bk-attendance {
            grid-template-columns: 1fr;
        }

        .bk-section-head {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 600px) {
        .bk-welcome {
            padding: 22px;
            border-radius: 15px;
        }

        .bk-welcome h1 {
            font-size: 23px;
        }

        .bk-stat,
        .bk-attendance-card {
            padding: 17px;
        }

        .bk-section-head h2 {
            font-size: 18px;
        }
    }
</style>

<div class="bk-dashboard">

    {{-- HEADER --}}
    <div class="bk-welcome">
        <h1>Dashboard BK</h1>

        <p>
            Selamat datang, {{ $bk->nama }}.
            Pantau perkembangan siswa yang menjadi tanggung jawab BK.
        </p>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    {{-- STATISTIK UTAMA --}}
    <div class="bk-stats">

        <div class="bk-stat">
            <div class="bk-stat-top">
                <p class="bk-stat-title">Total Siswa</p>

                <div class="bk-stat-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>

            <div class="bk-stat-number">
                {{ $jumlahSiswa }}
            </div>

            <p class="bk-stat-desc">
                Siswa dari kelas yang ditangani
            </p>
        </div>


        <div class="bk-stat">
            <div class="bk-stat-top">
                <p class="bk-stat-title">Presensi Hari Ini</p>

                <div class="bk-stat-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>

            <div class="bk-stat-number">
                {{ $jumlahPresensi }}
            </div>

            <p class="bk-stat-desc">
                Data presensi siswa
            </p>
        </div>


        <div class="bk-stat">
            <div class="bk-stat-top">
                <p class="bk-stat-title">Jurnal Hari Ini</p>

                <div class="bk-stat-icon">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </div>

            <div class="bk-stat-number">
                {{ $jumlahJurnal }}
            </div>

            <p class="bk-stat-desc">
                Jurnal guru untuk kelas BK
            </p>
        </div>

    </div>


    {{-- REKAP PRESENSI --}}
    <section class="bk-section">

        <div class="bk-section-head">
            <div>
                <h2>Rekap Presensi</h2>

                <p>
                    Rekap status kehadiran siswa hari ini.
                </p>
            </div>
        </div>


        <div class="bk-attendance">

            <div class="bk-attendance-card">
                <div class="bk-attendance-card-head">
                    <h3>Hadir</h3>

                    <span class="bk-badge success">
                        Hadir
                    </span>
                </div>

                <div class="bk-attendance-number">
                    {{ $jumlahHadir }}
                </div>
            </div>


            <div class="bk-attendance-card">
                <div class="bk-attendance-card-head">
                    <h3>Sakit</h3>

                    <span class="bk-badge warning">
                        Sakit
                    </span>
                </div>

                <div class="bk-attendance-number">
                    {{ $jumlahSakit }}
                </div>
            </div>


            <div class="bk-attendance-card">
                <div class="bk-attendance-card-head">
                    <h3>Izin</h3>

                    <span class="bk-badge">
                        Izin
                    </span>
                </div>

                <div class="bk-attendance-number">
                    {{ $jumlahIzin }}
                </div>
            </div>

        </div>

    </section>


    {{-- KELAS YANG DITANGANI --}}
    <section class="bk-section">

        <div class="bk-section-head">

            <div>
                <h2>Kelas yang Ditangani</h2>

                <p>
                    Daftar kelas yang menjadi tanggung jawab BK.
                </p>
            </div>

            <a
                href="{{ route('bk.kelas') }}"
                class="bk-link"
            >
                Kelola Kelas
                <i class="fa-solid fa-arrow-right"></i>
            </a>

        </div>


        <div class="bk-panel">

            <div class="bk-table-wrap">

                <table class="bk-table">

                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Tingkat</th>
                            <th>Jurusan</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($kelas as $item)

                            <tr>
                                <td>
                                    <strong>
                                        {{ $item->namakelas }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $item->tingkat }}
                                </td>

                                <td>
                                    {{ $item->namajurusan ?? '-' }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="bk-empty">

                                    <div class="bk-empty-icon">
                                        <i class="fa-solid fa-school"></i>
                                    </div>

                                    <div>
                                        Belum ada kelas yang ditugaskan.
                                    </div>

                                    <div style="margin-top:14px;">
                                        <a
                                            href="{{ route('bk.kelas') }}"
                                            class="bk-button"
                                        >
                                            <i class="fa-solid fa-plus"></i>
                                            Pilih Kelas
                                        </a>
                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>


    {{-- DATA SISWA --}}
    <section class="bk-section">

        <div class="bk-section-head">

            <div>
                <h2>Data Siswa</h2>

                <p>
                    Siswa dari seluruh kelas yang menjadi tanggung jawab BK.
                </p>
            </div>

            <a
                href="{{ route('bk.siswa') }}"
                class="bk-link"
            >
                Lihat Semua
                <i class="fa-solid fa-arrow-right"></i>
            </a>

        </div>


        <div class="bk-panel">

            <div class="bk-panel-head">

                <h3>Siswa Terbaru</h3>

                <a
                    href="{{ route('bk.siswa') }}"
                    class="bk-button"
                >
                    <i class="fa-solid fa-users"></i>
                    Lihat Semua
                </a>

            </div>


            <div class="bk-table-wrap">

                <table class="bk-table">

                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>L/P</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($dataSiswa as $siswa)

                            <tr>

                                <td>
                                    {{ $siswa->nisn }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $siswa->nama }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $siswa->namakelas }}
                                </td>

                                <td>
                                    {{ $siswa->jeniskelamin }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="bk-empty"
                                >

                                    <div class="bk-empty-icon">
                                        <i class="fa-solid fa-user-group"></i>
                                    </div>

                                    Belum ada data siswa.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>

</div>

@endsection