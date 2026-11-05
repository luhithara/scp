@extends('layouts.app')

@section('title', 'Detail Siswa - BK | SCP')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | DATA DARI CONTROLLER
    |--------------------------------------------------------------------------
    */

    $riwayatPresensi = $presensi ?? collect();
    $riwayatIzin = $izin ?? collect();


    /*
    |--------------------------------------------------------------------------
    | PRESENSI HARI INI
    |--------------------------------------------------------------------------
    */

    $presensiHariIni = $riwayatPresensi->filter(function ($item) {

        if (empty($item->waktu)) {
            return false;
        }

        try {
            return \Carbon\Carbon::parse($item->waktu)->isToday();
        } catch (\Throwable $e) {
            return false;
        }

    });


    $totalTidakHadir = $presensiHariIni
        ->whereIn('status', [
            'sakit',
            'izin',
            'alpa',
            'alpha',
        ])
        ->count();


    /*
    |--------------------------------------------------------------------------
    | REKAP PRESENSI
    |--------------------------------------------------------------------------
    */

    $totalHadir = $riwayatPresensi
        ->where('status', 'hadir')
        ->count();

    $totalSakit = $riwayatPresensi
        ->where('status', 'sakit')
        ->count();

    $totalIzin = $riwayatPresensi
        ->where('status', 'izin')
        ->count();

    $totalAlpa = $riwayatPresensi
        ->filter(function ($item) {
            return in_array(
                $item->status ?? '',
                ['alpa', 'alpha'],
                true
            );
        })
        ->count();

@endphp


<style>
    .bk-detail-page {
        width: 100%;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .bk-detail-header {
        margin-bottom: 20px;
    }

    .bk-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 12px;
        color: #2563eb;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: .2s ease;
    }

    .bk-back:hover {
        color: #1d4ed8;
        transform: translateX(-2px);
    }

    .bk-detail-header h1 {
        margin: 0;
        color: #172033;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -.4px;
    }

    .bk-detail-header p {
        margin: 7px 0 0;
        color: #718096;
        font-size: 14px;
    }


    /* =========================================================
       GRID UTAMA
    ========================================================= */

    .bk-detail-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(320px, .85fr);
        gap: 16px;
        align-items: start;
    }

    .bk-card {
        background: #fff;
        border: 1px solid #e6ebf2;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
    }

    .bk-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf1f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .bk-card-header h3 {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 800;
    }

    .bk-card-header p {
        margin: 5px 0 0;
        color: #8a94a6;
        font-size: 12px;
    }

    .bk-card-body {
        padding: 20px;
    }


    /* =========================================================
       STATUS
    ========================================================= */

    .bk-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .bk-status-safe {
        background: #ecfdf3;
        color: #087443;
    }

    .bk-status-warning {
        background: #fffaeb;
        color: #b54708;
    }

    .bk-status-danger {
        background: #fef3f2;
        color: #b42318;
    }


    /* =========================================================
       PROFIL SISWA
    ========================================================= */

    .bk-student-profile {
        display: flex;
        align-items: center;
        gap: 15px;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid #edf1f5;
    }

    .bk-avatar {
        width: 64px;
        height: 64px;
        flex: 0 0 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 23px;
        font-weight: 800;
    }

    .bk-student-name {
        color: #172033;
        font-size: 19px;
        font-weight: 800;
    }

    .bk-student-nisn {
        margin-top: 5px;
        color: #7a8699;
        font-size: 12px;
    }


    /* =========================================================
       DETAIL DATA
    ========================================================= */

    .bk-data-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 20px;
    }

    .bk-data-item {
        min-width: 0;
    }

    .bk-data-item.full {
        grid-column: 1 / -1;
    }

    .bk-data-label {
        margin-bottom: 5px;
        color: #8a94a6;
        font-size: 11px;
        font-weight: 700;
    }

    .bk-data-value {
        color: #344054;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.5;
        word-break: break-word;
    }

    .bk-data-secondary {
        margin-top: 3px;
        color: #98a2b3;
        font-size: 11px;
    }


    /* =========================================================
       RINGKASAN HARI INI
    ========================================================= */

    .bk-today-box {
        padding: 20px;
        border-radius: 14px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
    }

    .bk-today-box.warning {
        background: #fffbeb;
        border-color: #fde68a;
    }

    .bk-today-label {
        color: #667085;
        font-size: 12px;
        font-weight: 700;
    }

    .bk-today-number {
        margin-top: 5px;
        color: #172033;
        font-size: 38px;
        line-height: 1;
        font-weight: 800;
    }

    .bk-today-description {
        margin-top: 7px;
        color: #8a94a6;
        font-size: 11px;
    }


    /* =========================================================
       REKAP
    ========================================================= */

    .bk-rekap-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .bk-rekap {
        padding: 15px;
        border-radius: 12px;
        border: 1px solid #e8edf3;
        background: #fafbfc;
    }

    .bk-rekap-label {
        color: #667085;
        font-size: 11px;
        font-weight: 700;
    }

    .bk-rekap-number {
        margin-top: 6px;
        color: #172033;
        font-size: 25px;
        font-weight: 800;
    }

    .bk-rekap.hadir {
        background: #f0fdf4;
        border-color: #dcfce7;
    }

    .bk-rekap.sakit {
        background: #fffbeb;
        border-color: #fef3c7;
    }

    .bk-rekap.izin {
        background: #eff6ff;
        border-color: #dbeafe;
    }

    .bk-rekap.alpa {
        background: #fef2f2;
        border-color: #fee2e2;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .bk-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .bk-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .bk-table th {
        padding: 13px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e9ef;
        color: #667085;
        font-size: 11px;
        font-weight: 800;
        text-align: left;
        white-space: nowrap;
    }

    .bk-table td {
        padding: 14px;
        border-bottom: 1px solid #edf0f4;
        color: #344054;
        font-size: 12px;
        vertical-align: middle;
    }

    .bk-table tbody tr:hover {
        background: #fafcff;
    }

    .bk-table tbody tr:last-child td {
        border-bottom: 0;
    }


    /* =========================================================
       BADGE
    ========================================================= */

    .bk-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 8px;
        background: #f3f5f8;
        color: #475467;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .bk-badge-success {
        background: #ecfdf3;
        color: #087443;
    }

    .bk-badge-warning {
        background: #fffaeb;
        color: #b54708;
    }

    .bk-badge-info {
        background: #eff8ff;
        color: #175cd3;
    }

    .bk-badge-danger {
        background: #fef3f2;
        color: #b42318;
    }


    /* =========================================================
       RIWAYAT IZIN
    ========================================================= */

    .bk-izin-list {
        padding: 0;
    }

    .bk-izin-item {
        padding: 16px 20px;
        border-bottom: 1px solid #edf1f5;
    }

    .bk-izin-item:last-child {
        border-bottom: 0;
    }

    .bk-izin-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .bk-izin-title {
        color: #172033;
        font-size: 13px;
        font-weight: 800;
    }

    .bk-izin-date {
        margin-top: 4px;
        color: #98a2b3;
        font-size: 11px;
    }

    .bk-izin-reason {
        margin-top: 9px;
        color: #667085;
        font-size: 12px;
        line-height: 1.6;
    }


    /* =========================================================
       EMPTY
    ========================================================= */

    .bk-empty {
        padding: 45px 20px;
        text-align: center;
        color: #98a2b3;
        font-size: 12px;
    }

    .bk-empty-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 18px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1050px) {
        .bk-detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .bk-data-grid {
            grid-template-columns: 1fr;
        }

        .bk-data-item.full {
            grid-column: auto;
        }

        .bk-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .bk-student-profile {
            align-items: flex-start;
        }
    }

    @media (max-width: 500px) {
        .bk-student-profile {
            flex-direction: column;
        }

        .bk-rekap-grid {
            grid-template-columns: 1fr 1fr;
        }

        .bk-card-body {
            padding: 15px;
        }

        .bk-izin-item {
            padding: 15px;
        }

        .bk-detail-header h1 {
            font-size: 22px;
        }
    }
</style>


<div class="bk-detail-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="bk-detail-header">

        <a
            href="{{ route('bk.siswa') }}"
            class="bk-back"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke Data Siswa
        </a>

        <h1>Detail Siswa</h1>

        <p>
            Informasi lengkap siswa dan riwayat kehadirannya.
        </p>

    </div>


    {{-- =========================================================
         DATA UTAMA + RINGKASAN
    ========================================================== --}}

    <div class="bk-detail-grid">

        {{-- =====================================================
             DATA DIRI
        ====================================================== --}}

        <div class="bk-card">

            <div class="bk-card-header">

                <div>
                    <h3>Data Diri</h3>
                </div>


                @if($totalTidakHadir > 2)

                    <span class="bk-status bk-status-danger">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Butuh Penanganan
                    </span>

                @elseif($totalTidakHadir > 0)

                    <span class="bk-status bk-status-warning">
                        <i class="fa-solid fa-eye"></i>
                        Perlu Dipantau
                    </span>

                @else

                    <span class="bk-status bk-status-safe">
                        <i class="fa-solid fa-circle-check"></i>
                        Aman
                    </span>

                @endif

            </div>


            <div class="bk-card-body">

                <div class="bk-student-profile">

                    <div class="bk-avatar">
                        {{ strtoupper(substr($siswa->nama ?? '-', 0, 1)) }}
                    </div>

                    <div>

                        <div class="bk-student-name">
                            {{ $siswa->nama ?? '-' }}
                        </div>

                        <div class="bk-student-nisn">
                            NISN: {{ $siswa->nisn ?? '-' }}
                        </div>

                    </div>

                </div>


                <div class="bk-data-grid">

                    {{-- KELAS --}}
                    <div class="bk-data-item">

                        <div class="bk-data-label">
                            Kelas
                        </div>

                        <div class="bk-data-value">
                            {{ $siswa->namakelas ?? '-' }}
                        </div>

                    </div>


                    {{-- TINGKAT --}}
                    <div class="bk-data-item">

                        <div class="bk-data-label">
                            Tingkat
                        </div>

                        <div class="bk-data-value">
                            {{ $siswa->tingkat ?? '-' }}
                        </div>

                    </div>


                    {{-- JURUSAN --}}
                    <div class="bk-data-item">

                        <div class="bk-data-label">
                            Jurusan
                        </div>

                        <div class="bk-data-value">
                            {{ $siswa->kodejurusan ?? '-' }}
                        </div>

                        @if(!empty($siswa->namajurusan))

                            <div class="bk-data-secondary">
                                {{ $siswa->namajurusan }}
                            </div>

                        @endif

                    </div>


                    {{-- JENIS KELAMIN --}}
                    <div class="bk-data-item">

                        <div class="bk-data-label">
                            Jenis Kelamin
                        </div>

                        <div class="bk-data-value">
                            {{ $siswa->jeniskelamin ?? '-' }}
                        </div>

                    </div>


                    {{-- TEMPAT LAHIR --}}
                    <div class="bk-data-item">

                        <div class="bk-data-label">
                            Tempat Lahir
                        </div>

                        <div class="bk-data-value">
                            {{ $siswa->tempatlahir ?? '-' }}
                        </div>

                    </div>


                    {{-- TANGGAL LAHIR --}}
                    <div class="bk-data-item">

                        <div class="bk-data-label">
                            Tanggal Lahir
                        </div>

                        <div class="bk-data-value">

                            @if(!empty($siswa->tanggallahir))

                                {{ \Carbon\Carbon::parse($siswa->tanggallahir)->format('d-m-Y') }}

                            @else

                                -

                            @endif

                        </div>

                    </div>


                    {{-- NOMOR TELEPON --}}
                    <div class="bk-data-item">

                        <div class="bk-data-label">
                            Nomor Telepon
                        </div>

                        <div class="bk-data-value">
                            {{ $siswa->nomortelepon ?? '-' }}
                        </div>

                    </div>


                    {{-- ALAMAT --}}
                    <div class="bk-data-item full">

                        <div class="bk-data-label">
                            Alamat
                        </div>

                        <div class="bk-data-value">
                            {{ $siswa->alamat ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             RINGKASAN
        ====================================================== --}}

        <div>

            {{-- PRESENSI HARI INI --}}

            <div class="bk-card">

                <div class="bk-card-header">

                    <div>

                        <h3>
                            Presensi Hari Ini
                        </h3>

                        <p>
                            Ringkasan ketidakhadiran siswa.
                        </p>

                    </div>

                </div>


                <div class="bk-card-body">

                    <div class="bk-today-box {{ $totalTidakHadir > 2 ? 'warning' : '' }}">

                        <div class="bk-today-label">
                            Tidak Hadir
                        </div>

                        <div class="bk-today-number">
                            {{ $totalTidakHadir }}
                        </div>

                        <div class="bk-today-description">
                            mata pelajaran hari ini
                        </div>

                    </div>

                </div>

            </div>


            {{-- REKAP PRESENSI --}}

            <div
                class="bk-card"
                style="margin-top:16px;"
            >

                <div class="bk-card-header">

                    <div>

                        <h3>
                            Rekap Presensi
                        </h3>

                        <p>
                            Total seluruh riwayat presensi.
                        </p>

                    </div>

                </div>


                <div class="bk-card-body">

                    <div class="bk-rekap-grid">

                        <div class="bk-rekap hadir">

                            <div class="bk-rekap-label">
                                Hadir
                            </div>

                            <div class="bk-rekap-number">
                                {{ $totalHadir }}
                            </div>

                        </div>


                        <div class="bk-rekap sakit">

                            <div class="bk-rekap-label">
                                Sakit
                            </div>

                            <div class="bk-rekap-number">
                                {{ $totalSakit }}
                            </div>

                        </div>


                        <div class="bk-rekap izin">

                            <div class="bk-rekap-label">
                                Izin
                            </div>

                            <div class="bk-rekap-number">
                                {{ $totalIzin }}
                            </div>

                        </div>


                        <div class="bk-rekap alpa">

                            <div class="bk-rekap-label">
                                Alpa
                            </div>

                            <div class="bk-rekap-number">
                                {{ $totalAlpa }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         RIWAYAT PRESENSI
    ========================================================== --}}

    <div
        class="bk-card"
        style="margin-top:16px;"
    >

        <div class="bk-card-header">

            <div>

                <h3>
                    Riwayat Presensi
                </h3>

                <p>
                    Riwayat kehadiran berdasarkan mata pelajaran.
                </p>

            </div>

        </div>


        <div class="bk-table-wrapper">

            <table class="bk-table">

                <thead>

                    <tr>

                        <th>
                            Waktu
                        </th>

                        <th>
                            Mata Pelajaran
                        </th>

                        <th>
                            Guru
                        </th>

                        <th>
                            Jam
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Keterangan
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($riwayatPresensi as $item)

                        <tr>

                            {{-- WAKTU --}}

                            <td>

                                @if(!empty($item->waktu))

                                    {{ \Carbon\Carbon::parse($item->waktu)->format('d-m-Y H:i') }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- MAPEL --}}

                            <td>

                                <strong>

                                    {{
                                        $item->namamapel
                                        ?? $item->mapel_nama
                                        ?? $item->nama_mapel
                                        ?? '-'
                                    }}

                                </strong>

                            </td>


                            {{-- GURU --}}

                            <td>
                                {{ $item->nama_guru ?? '-' }}
                            </td>


                            {{-- JAM --}}

                            <td>

                                @if(!empty($item->jamMulai))

                                    {{ substr($item->jamMulai, 0, 5) }}

                                    @if(!empty($item->jamSelesai))

                                        - {{ substr($item->jamSelesai, 0, 5) }}

                                    @endif

                                @else

                                    -

                                @endif

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if(($item->status ?? '') === 'hadir')

                                    <span class="bk-badge bk-badge-success">
                                        <i class="fa-solid fa-check"></i>
                                        Hadir
                                    </span>

                                @elseif(($item->status ?? '') === 'sakit')

                                    <span class="bk-badge bk-badge-warning">
                                        <i class="fa-solid fa-notes-medical"></i>
                                        Sakit
                                    </span>

                                @elseif(($item->status ?? '') === 'izin')

                                    <span class="bk-badge bk-badge-info">
                                        <i class="fa-solid fa-file-circle-check"></i>
                                        Izin
                                    </span>

                                @elseif(in_array(($item->status ?? ''), ['alpa', 'alpha'], true))

                                    <span class="bk-badge bk-badge-danger">
                                        <i class="fa-solid fa-user-xmark"></i>
                                        Alpa
                                    </span>

                                @else

                                    <span class="bk-badge">
                                        {{ ucfirst($item->status ?? '-') }}
                                    </span>

                                @endif

                            </td>


                            {{-- KETERANGAN --}}

                            <td>
                                {{ $item->keterangan ?? '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="bk-empty"
                            >

                                <div class="bk-empty-icon">
                                    <i class="fa-regular fa-clipboard"></i>
                                </div>

                                Belum ada data presensi.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
         RIWAYAT IZIN
    ========================================================== --}}

    <div
        class="bk-card"
        style="margin-top:16px;"
    >

        <div class="bk-card-header">

            <div>

                <h3>
                    Riwayat Perizinan
                </h3>

                <p>
                    Riwayat pengajuan izin atau sakit siswa.
                </p>

            </div>

        </div>


        <div class="bk-izin-list">

            @forelse($riwayatIzin as $item)

                <div class="bk-izin-item">

                    <div class="bk-izin-top">

                        <div>

                            <div class="bk-izin-title">
                                {{ ucfirst($item->jenis ?? '-') }}
                            </div>

                            <div class="bk-izin-date">

                                @if(!empty($item->tanggal))

                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}

                                @elseif(!empty($item->waktu))

                                    {{ \Carbon\Carbon::parse($item->waktu)->format('d-m-Y H:i') }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        @php
                            $statusIzin = strtolower($item->status ?? 'menunggu');
                        @endphp


                        @if($statusIzin === 'disetujui')

                            <span class="bk-badge bk-badge-success">
                                <i class="fa-solid fa-check"></i>
                                Disetujui
                            </span>

                        @elseif($statusIzin === 'ditolak')

                            <span class="bk-badge bk-badge-danger">
                                <i class="fa-solid fa-xmark"></i>
                                Ditolak
                            </span>

                        @else

                            <span class="bk-badge bk-badge-warning">
                                <i class="fa-solid fa-clock"></i>
                                Menunggu
                            </span>

                        @endif

                    </div>


                    <div class="bk-izin-reason">

                        <strong>
                            Alasan:
                        </strong>

                        {{ $item->alasan ?? '-' }}

                    </div>

                </div>

            @empty

                <div class="bk-empty">

                    <div class="bk-empty-icon">
                        <i class="fa-regular fa-file-lines"></i>
                    </div>

                    Belum ada riwayat perizinan.

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection