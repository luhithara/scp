@extends('layouts.app')

@section('title', 'Presensi Siswa - BK | SCP')

@section('content')

<style>
    .bk-page {
        width: 100%;
    }

    .bk-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .bk-page-header h1 {
        margin: 0;
        font-size: 26px;
        font-weight: 800;
        color: #172033;
        letter-spacing: -0.4px;
    }

    .bk-page-header p {
        margin: 7px 0 0;
        color: #718096;
        font-size: 14px;
        line-height: 1.6;
    }

    .bk-card {
        background: #ffffff;
        border: 1px solid #e6ebf2;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
    }

    .bk-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf1f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .bk-card-header-left h3 {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 800;
    }

    .bk-card-header-left p {
        margin: 5px 0 0;
        color: #7a8699;
        font-size: 12px;
        line-height: 1.5;
    }

    .bk-card-body {
        padding: 20px;
    }

    /* =========================================
       STATISTIK
    ========================================= */

    .bk-stat-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .bk-stat-card {
        position: relative;
        overflow: hidden;
        padding: 20px;
        min-height: 135px;
    }

    .bk-stat-card::after {
        content: "";
        position: absolute;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        right: -35px;
        bottom: -40px;
        background: rgba(37, 99, 235, 0.06);
    }

    .bk-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .bk-stat-label {
        color: #667085;
        font-size: 13px;
        font-weight: 700;
    }

    .bk-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 16px;
        flex-shrink: 0;
    }

    .bk-stat-number {
        position: relative;
        z-index: 2;
        color: #172033;
        font-size: 30px;
        line-height: 1;
        font-weight: 800;
    }

    .bk-stat-description {
        position: relative;
        z-index: 2;
        margin: 9px 0 0;
        color: #8994a6;
        font-size: 12px;
    }

    /* =========================================
       FILTER
    ========================================= */

    .bk-filter-grid {
        display: grid;
        grid-template-columns:
            minmax(150px, 1fr)
            minmax(170px, 1.2fr)
            minmax(160px, 1fr)
            minmax(140px, 0.8fr)
            auto;
        gap: 12px;
        align-items: end;
    }

    .bk-field {
        min-width: 0;
    }

    .bk-field label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
    }

    .bk-input,
    .bk-select {
        width: 100%;
        min-height: 42px;
        padding: 10px 12px;
        border: 1px solid #d9e0e9;
        border-radius: 10px;
        background: #ffffff;
        color: #273142;
        font-size: 13px;
        outline: none;
        transition: 0.2s ease;
        box-sizing: border-box;
    }

    .bk-input:focus,
    .bk-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.10);
    }

    .bk-filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bk-btn {
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 15px;
        border: 0;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: 0.2s ease;
        white-space: nowrap;
    }

    .bk-btn-primary {
        background: #2563eb;
        color: #ffffff;
    }

    .bk-btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .bk-btn-secondary {
        background: #f5f7fa;
        color: #475467;
        border: 1px solid #e2e7ee;
    }

    .bk-btn-secondary:hover {
        background: #eef2f7;
    }

    /* =========================================
       BADGE
    ========================================= */

    .bk-badges {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        gap: 7px;
    }

    .bk-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-height: 28px;
        padding: 5px 9px;
        border-radius: 8px;
        background: #f3f5f8;
        color: #475467;
        font-size: 11px;
        font-weight: 700;
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

    .bk-badge-danger {
        background: #fef3f2;
        color: #b42318;
    }

    .bk-badge-info {
        background: #eff8ff;
        color: #175cd3;
    }

    /* =========================================
       TABLE
    ========================================= */

    .bk-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .bk-table {
        width: 100%;
        min-width: 1050px;
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

    .bk-table tbody tr {
        transition: background 0.15s ease;
    }

    .bk-table tbody tr:hover {
        background: #fafcff;
    }

    .bk-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .bk-number {
        color: #98a2b3;
        font-weight: 700;
    }

    .bk-student-name {
        color: #172033;
        font-weight: 800;
    }

    .bk-student-meta {
        margin-top: 4px;
        color: #98a2b3;
        font-size: 10px;
    }

    .bk-class-name {
        color: #172033;
        font-weight: 800;
    }

    .bk-class-meta {
        margin-top: 4px;
        color: #98a2b3;
        font-size: 10px;
    }

    .bk-muted {
        color: #98a2b3;
    }

    /* =========================================
       EMPTY STATE
    ========================================= */

    .bk-empty {
        padding: 55px 20px !important;
        text-align: center;
    }

    .bk-empty-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 14px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 20px;
    }

    .bk-empty-title {
        margin: 0;
        color: #344054;
        font-size: 14px;
        font-weight: 800;
    }

    .bk-empty-description {
        margin: 6px 0 0;
        color: #98a2b3;
        font-size: 12px;
    }

    /* =========================================
       RESPONSIVE
    ========================================= */

    @media (max-width: 1100px) {
        .bk-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .bk-filter-actions {
            align-items: stretch;
        }

        .bk-filter-actions .bk-btn {
            flex: 1;
        }
    }

    @media (max-width: 800px) {
        .bk-stat-grid {
            grid-template-columns: 1fr;
        }

        .bk-page-header {
            flex-direction: column;
        }

        .bk-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .bk-badges {
            justify-content: flex-start;
        }
    }

    @media (max-width: 600px) {
        .bk-filter-grid {
            grid-template-columns: 1fr;
        }

        .bk-card-body {
            padding: 15px;
        }

        .bk-card-header {
            padding: 15px;
        }

        .bk-page-header h1 {
            font-size: 22px;
        }

        .bk-filter-actions {
            width: 100%;
        }

        .bk-filter-actions .bk-btn {
            width: 100%;
        }
    }
</style>


<div class="bk-page">

    {{-- =========================================
         HEADER
    ========================================== --}}

    <div class="bk-page-header">

        <div>
            <h1>Presensi Siswa</h1>

            <p>
                Pantau data kehadiran siswa dari kelas yang menjadi
                tanggung jawab BK.
            </p>
        </div>

    </div>


    {{-- =========================================
         STATISTIK
    ========================================== --}}

    <div class="bk-stat-grid">

        {{-- Total --}}
        <div class="bk-card bk-stat-card">

            <div class="bk-stat-top">

                <span class="bk-stat-label">
                    Total Presensi
                </span>

                <span class="bk-stat-icon">
                    <i class="fa-solid fa-clipboard-list"></i>
                </span>

            </div>

            <div class="bk-stat-number">
                {{ $total }}
            </div>

            <p class="bk-stat-description">
                Data pada tanggal terpilih
            </p>

        </div>


        {{-- Hadir --}}
        <div class="bk-card bk-stat-card">

            <div class="bk-stat-top">

                <span class="bk-stat-label">
                    Hadir
                </span>

                <span class="bk-stat-icon">
                    <i class="fa-solid fa-user-check"></i>
                </span>

            </div>

            <div class="bk-stat-number">
                {{ $hadir }}
            </div>

            <p class="bk-stat-description">
                Siswa hadir
            </p>

        </div>


        {{-- Tidak hadir --}}
        <div class="bk-card bk-stat-card">

            <div class="bk-stat-top">

                <span class="bk-stat-label">
                    Tidak Hadir
                </span>

                <span class="bk-stat-icon">
                    <i class="fa-solid fa-user-xmark"></i>
                </span>

            </div>

            <div class="bk-stat-number">
                {{ $sakit + $izin + $alpa }}
            </div>

            <p class="bk-stat-description">
                Sakit, izin, dan alpa
            </p>

        </div>

    </div>


    {{-- =========================================
         FILTER
    ========================================== --}}

    <div class="bk-card">

        <div class="bk-card-header">

            <div class="bk-card-header-left">

                <h3>
                    Filter Presensi
                </h3>

                <p>
                    Gunakan filter untuk mencari data presensi siswa.
                </p>

            </div>

        </div>


        <div class="bk-card-body">

            <form
                method="GET"
                action="{{ route('bk.presensi') }}"
            >

                <div class="bk-filter-grid">

                    {{-- Tanggal --}}
                    <div class="bk-field">

                        <label for="tanggal">
                            Tanggal
                        </label>

                        <input
                            id="tanggal"
                            type="date"
                            name="tanggal"
                            value="{{ $tanggal }}"
                            class="bk-input"
                        >

                    </div>


                    {{-- Cari --}}
                    <div class="bk-field">

                        <label for="cari">
                            Cari Siswa
                        </label>

                        <input
                            id="cari"
                            type="text"
                            name="cari"
                            value="{{ request('cari') }}"
                            class="bk-input"
                            placeholder="Nama atau NISN"
                        >

                    </div>


                    {{-- Jurusan --}}
                    <div class="bk-field">

                        <label for="jurusan">
                            Jurusan
                        </label>

                        <select
                            id="jurusan"
                            name="jurusan"
                            class="bk-select"
                        >

                            <option value="">
                                Semua Jurusan
                            </option>

                            @foreach($jurusan as $item)

                                <option
                                    value="{{ $item->id }}"
                                    {{ request('jurusan') == $item->id ? 'selected' : '' }}
                                >
                                    {{ $item->kodejurusan }}
                                    - {{ $item->namajurusan }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Tingkat --}}
                    <div class="bk-field">

                        <label for="tingkat">
                            Tingkat
                        </label>

                        <select
                            id="tingkat"
                            name="tingkat"
                            class="bk-select"
                        >

                            <option value="">
                                Semua Tingkat
                            </option>

                            <option
                                value="10"
                                {{ request('tingkat') == '10' ? 'selected' : '' }}
                            >
                                Kelas 10
                            </option>

                            <option
                                value="11"
                                {{ request('tingkat') == '11' ? 'selected' : '' }}
                            >
                                Kelas 11
                            </option>

                            <option
                                value="12"
                                {{ request('tingkat') == '12' ? 'selected' : '' }}
                            >
                                Kelas 12
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="bk-field">

                        <label for="status">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="bk-select"
                        >

                            <option value="">
                                Semua Status
                            </option>

                            <option
                                value="hadir"
                                {{ request('status') == 'hadir' ? 'selected' : '' }}
                            >
                                Hadir
                            </option>

                            <option
                                value="sakit"
                                {{ request('status') == 'sakit' ? 'selected' : '' }}
                            >
                                Sakit
                            </option>

                            <option
                                value="izin"
                                {{ request('status') == 'izin' ? 'selected' : '' }}
                            >
                                Izin
                            </option>

                            <option
                                value="alpa"
                                {{ request('status') == 'alpa' ? 'selected' : '' }}
                            >
                                Alpa
                            </option>

                        </select>

                    </div>


                    {{-- Tombol --}}
                    <div class="bk-filter-actions">

                        <button
                            type="submit"
                            class="bk-btn bk-btn-primary"
                        >
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Cari
                        </button>

                        <a
                            href="{{ route('bk.presensi') }}"
                            class="bk-btn bk-btn-secondary"
                        >
                            <i class="fa-solid fa-rotate-left"></i>
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================
         DATA PRESENSI
    ========================================== --}}

    <div
        class="bk-card"
        style="margin-top:16px;"
    >

        <div class="bk-card-header">

            <div class="bk-card-header-left">

                <h3>
                    Data Presensi
                </h3>

                <p>

                    Tanggal:
                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}

                </p>

            </div>


            <div class="bk-badges">

                <span class="bk-badge bk-badge-success">
                    <i class="fa-solid fa-check"></i>
                    Hadir {{ $hadir }}
                </span>

                <span class="bk-badge bk-badge-warning">
                    <i class="fa-solid fa-notes-medical"></i>
                    Sakit {{ $sakit }}
                </span>

                <span class="bk-badge bk-badge-info">
                    <i class="fa-solid fa-file-circle-check"></i>
                    Izin {{ $izin }}
                </span>

                <span class="bk-badge bk-badge-danger">
                    <i class="fa-solid fa-user-xmark"></i>
                    Alpa {{ $alpa }}
                </span>

            </div>

        </div>


        <div class="bk-table-wrapper">

            <table class="bk-table">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>NISN</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Guru</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($dataPresensi as $index => $item)

                        <tr>

                            <td>
                                <span class="bk-number">
                                    {{ $index + 1 }}
                                </span>
                            </td>


                            <td>

                                <div class="bk-student-name">
                                    {{ $item->nama_siswa }}
                                </div>

                                <div class="bk-student-meta">
                                    {{ $item->jeniskelamin }}
                                </div>

                            </td>


                            <td>
                                {{ $item->nisn }}
                            </td>


                            <td>

                                <div class="bk-class-name">
                                    {{ $item->namakelas }}
                                </div>

                                <div class="bk-class-meta">
                                    {{ $item->kodejurusan }}
                                </div>

                            </td>


                            <td>

                                {{
                                    $item->namamapel
                                    ?? $item->mapel_nama
                                    ?? '-'
                                }}

                            </td>


                            <td>
                                {{ $item->nama_guru ?? '-' }}
                            </td>


                            <td>

                                @if($item->jamMulai)

                                    {{ $item->jamMulai }}

                                    @if($item->jamSelesai)
                                        - {{ $item->jamSelesai }}
                                    @endif

                                @else
                                    -
                                @endif

                            </td>


                            <td>

                                @if($item->status === 'hadir')

                                    <span class="bk-badge bk-badge-success">
                                        <i class="fa-solid fa-check"></i>
                                        Hadir
                                    </span>

                                @elseif($item->status === 'sakit')

                                    <span class="bk-badge bk-badge-warning">
                                        <i class="fa-solid fa-notes-medical"></i>
                                        Sakit
                                    </span>

                                @elseif($item->status === 'izin')

                                    <span class="bk-badge bk-badge-info">
                                        <i class="fa-solid fa-file-circle-check"></i>
                                        Izin
                                    </span>

                                @elseif($item->status === 'alpa')

                                    <span class="bk-badge bk-badge-danger">
                                        <i class="fa-solid fa-user-xmark"></i>
                                        Alpa
                                    </span>

                                @else

                                    <span class="bk-badge">
                                        {{ ucfirst($item->status) }}
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($item->waktu)

                                    {{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}

                                @else

                                    <span class="bk-muted">-</span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="bk-empty"
                            >

                                <div class="bk-empty-icon">
                                    <i class="fa-regular fa-clipboard"></i>
                                </div>

                                <p class="bk-empty-title">
                                    Tidak ada data presensi
                                </p>

                                <p class="bk-empty-description">
                                    Tidak ada data presensi pada filter yang dipilih.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection