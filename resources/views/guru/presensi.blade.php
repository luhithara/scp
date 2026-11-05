{{-- =========================================================
     SCP - PRESENSI GURU
     File:
     resources/views/presensi/index.blade.php

     Fungsi:
     - Menampilkan jadwal guru
     - Guru memilih jadwal
     - Membuka daftar siswa untuk presensi
     - Tampilan biru-putih
     - Tetap menggunakan layouts.app
     ========================================================= --}}

@extends('layouts.app')

@section('title', 'Presensi')

@push('styles')
<style>
    .scp-presensi-page {
        width: 100%;
        max-width: 1450px;
        margin: 0 auto;
        padding: 28px 30px 50px;
        color: #172033;
    }

    .scp-presensi-page *,
    .scp-presensi-page *::before,
    .scp-presensi-page *::after {
        box-sizing: border-box;
    }

    /* =====================================================
       HEADER
       ===================================================== */

    .presensi-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 25px;
    }

    .presensi-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 13px;
        color: #8a96a8;
    }

    .presensi-breadcrumb .active {
        color: #2563eb;
        font-weight: 700;
    }

    .presensi-title {
        margin: 0;
        font-size: 28px;
        font-weight: 750;
        line-height: 1.2;
        color: #14213d;
    }

    .presensi-description {
        margin: 8px 0 0;
        color: #6b778c;
        font-size: 14px;
        line-height: 1.6;
    }

    .presensi-date-card {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px 15px;
        border: 1px solid #e1e9f3;
        background: #ffffff;
        border-radius: 13px;
        box-shadow: 0 4px 15px rgba(30, 64, 175, .05);
        white-space: nowrap;
    }

    .presensi-date-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #edf4ff;
        color: #2563eb;
        border-radius: 10px;
    }

    .presensi-date-icon svg {
        width: 20px;
        height: 20px;
    }

    .presensi-date-label {
        font-size: 11px;
        color: #8a96a8;
    }

    .presensi-date-value {
        margin-top: 2px;
        font-size: 13px;
        font-weight: 750;
        color: #344054;
    }

    /* =====================================================
       ALERT
       ===================================================== */

    .presensi-alert {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-bottom: 20px;
        padding: 14px 16px;
        border-radius: 11px;
        font-size: 13px;
        line-height: 1.5;
    }

    .presensi-alert-success {
        color: #166534;
        background: #effaf4;
        border: 1px solid #cceedd;
    }

    .presensi-alert-error {
        color: #b42318;
        background: #fff4f4;
        border: 1px solid #f4cccc;
    }

    .presensi-alert svg {
        width: 18px;
        height: 18px;
        flex: 0 0 18px;
    }

    /* =====================================================
       STATISTIK
       ===================================================== */

    .presensi-stat-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 22px;
    }

    .presensi-stat {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 17px;
        background: #ffffff;
        border: 1px solid #e1e9f3;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(30, 64, 175, .045);
    }

    .presensi-stat-icon {
        width: 43px;
        height: 43px;
        flex: 0 0 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #edf4ff;
        color: #2563eb;
    }

    .presensi-stat-icon svg {
        width: 21px;
        height: 21px;
    }

    .presensi-stat-label {
        font-size: 11px;
        color: #8995a7;
    }

    .presensi-stat-value {
        margin-top: 2px;
        font-size: 20px;
        font-weight: 800;
        color: #172033;
    }

    /* =====================================================
       PANEL
       ===================================================== */

    .presensi-panel {
        background: #ffffff;
        border: 1px solid #e1e9f3;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(30, 64, 175, .05);
    }

    .presensi-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 19px 21px;
        border-bottom: 1px solid #edf1f6;
    }

    .presensi-panel-heading {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .presensi-panel-icon {
        width: 39px;
        height: 39px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #edf4ff;
        color: #2563eb;
        border-radius: 10px;
    }

    .presensi-panel-icon svg {
        width: 20px;
        height: 20px;
    }

    .presensi-panel-title {
        margin: 0;
        font-size: 16px;
        font-weight: 750;
        color: #172033;
    }

    .presensi-panel-subtitle {
        margin: 3px 0 0;
        font-size: 11px;
        color: #8995a7;
    }

    .presensi-panel-body {
        padding: 18px;
    }

    /* =====================================================
       JADWAL
       ===================================================== */

    .presensi-schedule-list {
        display: grid;
        gap: 10px;
    }

    .presensi-schedule {
        display: grid;
        grid-template-columns: 95px minmax(0, 1fr) auto;
        align-items: center;
        gap: 14px;
        padding: 14px;
        border: 1px solid #e2e9f2;
        border-radius: 12px;
        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            transform .15s ease;
    }

    .presensi-schedule:hover {
        border-color: #bfd4f7;
        box-shadow: 0 5px 16px rgba(37, 99, 235, .07);
        transform: translateY(-1px);
    }

    .presensi-time {
        padding: 9px 8px;
        text-align: center;
        background: #f1f6ff;
        border: 1px solid #dfebff;
        border-radius: 9px;
    }

    .presensi-time-main {
        font-size: 11px;
        font-weight: 800;
        color: #2457ad;
    }

    .presensi-time-day {
        margin-top: 3px;
        font-size: 9px;
        color: #8190a5;
    }

    .presensi-mapel {
        margin-bottom: 5px;
        font-size: 14px;
        font-weight: 750;
        color: #172033;
    }

    .presensi-class {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
        font-size: 11px;
        color: #718096;
    }

    .presensi-class strong {
        color: #344054;
    }

    .presensi-meta {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 7px;
        flex-wrap: wrap;
    }

    .presensi-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 8px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .presensi-badge-blue {
        color: #1d4ed8;
        background: #edf4ff;
        border: 1px solid #dbe9ff;
    }

    .presensi-badge-room {
        color: #59687b;
        background: #f5f7fa;
        border: 1px solid #e4e9ef;
    }

    .presensi-btn {
        min-height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 0 12px;
        border: 0;
        border-radius: 8px;
        background: #2563eb;
        color: #ffffff;
        text-decoration: none;
        font-size: 11px;
        font-weight: 700;
        transition: background .2s ease, box-shadow .2s ease;
    }

    .presensi-btn:hover {
        background: #1d4ed8;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, .18);
    }

    .presensi-btn svg {
        width: 15px;
        height: 15px;
    }

    /* =====================================================
       EMPTY
       ===================================================== */

    .presensi-empty {
        padding: 45px 20px;
        text-align: center;
    }

    .presensi-empty-icon {
        width: 58px;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 13px;
        border-radius: 15px;
        background: #f1f6ff;
        color: #4a78c9;
    }

    .presensi-empty-icon svg {
        width: 27px;
        height: 27px;
    }

    .presensi-empty-title {
        margin: 0 0 5px;
        font-size: 14px;
        font-weight: 750;
        color: #344054;
    }

    .presensi-empty-text {
        max-width: 430px;
        margin: 0 auto;
        font-size: 12px;
        color: #8995a7;
        line-height: 1.6;
    }

    /* =====================================================
       RESPONSIVE
       ===================================================== */

    @media (max-width: 1000px) {
        .presensi-schedule {
            grid-template-columns: 85px minmax(0, 1fr);
        }

        .presensi-meta {
            grid-column: 2;
            justify-content: flex-start;
        }
    }

    @media (max-width: 760px) {
        .scp-presensi-page {
            padding: 20px 15px 40px;
        }

        .presensi-header {
            flex-direction: column;
        }

        .presensi-date-card {
            width: 100%;
        }

        .presensi-title {
            font-size: 24px;
        }

        .presensi-stat-grid {
            grid-template-columns: 1fr;
        }

        .presensi-panel-header {
            padding: 17px;
        }

        .presensi-panel-body {
            padding: 13px;
        }
    }

    @media (max-width: 500px) {
        .presensi-schedule {
            grid-template-columns: 1fr;
        }

        .presensi-time {
            text-align: left;
        }

        .presensi-meta {
            grid-column: auto;
            justify-content: flex-start;
        }

        .presensi-btn {
            flex: 1;
        }
    }
</style>
@endpush


@section('content')

<div class="scp-presensi-page">

    {{-- =====================================================
         HEADER
         ===================================================== --}}

    <div class="presensi-header">

        <div>

            <div class="presensi-breadcrumb">
                <span>Guru</span>
                <span>/</span>
                <span class="active">Presensi</span>
            </div>

            <h1 class="presensi-title">
                Presensi Siswa
            </h1>

            <p class="presensi-description">
                Pilih jadwal mengajar untuk mengisi presensi
                siswa pada kelas tersebut.
            </p>

        </div>


        <div class="presensi-date-card">

            <div class="presensi-date-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <rect
                        x="3.5"
                        y="5"
                        width="17"
                        height="15"
                        rx="2"
                    />

                    <path d="M7 3.5v3" />
                    <path d="M17 3.5v3" />
                    <path d="M3.5 9h17" />
                </svg>

            </div>

            <div>

                <div class="presensi-date-label">
                    Hari ini
                </div>

                <div class="presensi-date-value">
                    {{ now()->translatedFormat('d F Y') }}
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         ALERT
         ===================================================== --}}

    @if(session('success'))

        <div class="presensi-alert presensi-alert-success">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="M5 12.5l4 4L19 6.5" />
            </svg>

            <div>
                {{ session('success') }}
            </div>

        </div>

    @endif


    @if(session('error'))

        <div class="presensi-alert presensi-alert-error">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <circle
                    cx="12"
                    cy="12"
                    r="9"
                />

                <path d="M12 7v5" />
                <path d="M12 16h.01" />
            </svg>

            <div>
                {{ session('error') }}
            </div>

        </div>

    @endif


    {{-- =====================================================
         STATISTIK
         ===================================================== --}}

    <div class="presensi-stat-grid">

        <div class="presensi-stat">

            <div class="presensi-stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <rect
                        x="3.5"
                        y="5"
                        width="17"
                        height="15"
                        rx="2"
                    />

                    <path d="M7 3.5v3" />
                    <path d="M17 3.5v3" />
                    <path d="M3.5 9h17" />
                </svg>

            </div>

            <div>

                <div class="presensi-stat-label">
                    Jadwal Mengajar
                </div>

                <div class="presensi-stat-value">
                    {{ $jadwal->count() }}
                </div>

            </div>

        </div>


        <div class="presensi-stat">

            <div class="presensi-stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <circle
                        cx="12"
                        cy="8"
                        r="3.5"
                    />

                    <path d="M5 20c.7-3.5 3.1-5.5 7-5.5s6.3 2 7 5.5" />
                </svg>

            </div>

            <div>

                <div class="presensi-stat-label">
                    Kelas
                </div>

                <div class="presensi-stat-value">
                    {{ $jadwal->pluck('kelasid')->unique()->count() }}
                </div>

            </div>

        </div>


        <div class="presensi-stat">

            <div class="presensi-stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M5 12.5l4 4L19 6.5" />
                </svg>

            </div>

            <div>

                <div class="presensi-stat-label">
                    Status
                </div>

                <div class="presensi-stat-value">
                    Aktif
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         PANEL JADWAL
         ===================================================== --}}

    <section class="presensi-panel">

        <div class="presensi-panel-header">

            <div class="presensi-panel-heading">

                <div class="presensi-panel-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M4 6h16" />
                        <path d="M4 12h16" />
                        <path d="M4 18h16" />
                    </svg>

                </div>

                <div>

                    <h2 class="presensi-panel-title">
                        Pilih Jadwal
                    </h2>

                    <p class="presensi-panel-subtitle">
                        Pilih kelas yang ingin dipresensi.
                    </p>

                </div>

            </div>

        </div>


        <div class="presensi-panel-body">

            @if(isset($jadwal) && $jadwal->count())

                @php

                    $urutanHari = [
                        'Senin' => 1,
                        'Selasa' => 2,
                        'Rabu' => 3,
                        'Kamis' => 4,
                        'Jumat' => 5,
                        'Sabtu' => 6,
                    ];

                    $jadwalTerurut = $jadwal
                        ->sortBy(function ($item) use ($urutanHari) {

                            return sprintf(
                                '%02d-%03d',
                                $urutanHari[$item->hari] ?? 99,
                                (int) ($item->jamMulai ?? 999)
                            );

                        });

                @endphp


                <div class="presensi-schedule-list">

                    @foreach($jadwalTerurut as $item)

                        <div class="presensi-schedule">

                            {{-- =====================================
                                 WAKTU
                                 ===================================== --}}

                            <div class="presensi-time">

                                <div class="presensi-time-main">

                                    Jam
                                    {{ $item->jamMulai ?? '-' }}

                                    –

                                    {{ $item->jamSelesai ?? '-' }}

                                </div>

                                <div class="presensi-time-day">
                                    {{ $item->hari ?? '-' }}
                                </div>

                            </div>


                            {{-- =====================================
                                 DETAIL
                                 ===================================== --}}

                            <div>

                                <div class="presensi-mapel">

                                    {{ $item->namamapel
                                        ?? $item->namamapelmaster
                                        ?? 'Mata Pelajaran' }}

                                </div>


                                <div class="presensi-class">

                                    <strong>

                                        {{ $item->namakelas
                                            ?? optional($item->kelas)->namakelas
                                            ?? 'Kelas belum tersedia' }}

                                    </strong>


                                    @if(isset($item->namajurusan))

                                        <span>
                                            •
                                        </span>

                                        <span>
                                            {{ $item->namajurusan }}
                                        </span>

                                    @elseif(
                                        isset($item->kelas) &&
                                        isset($item->kelas->jurusan)
                                    )

                                        <span>
                                            •
                                        </span>

                                        <span>
                                            {{ $item->kelas->jurusan->namajurusan }}
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- =====================================
                                 AKSI
                                 ===================================== --}}

                            <div class="presensi-meta">

                                @if(isset($item->putaranblok))

                                    <span class="presensi-badge presensi-badge-blue">

                                        Putaran {{ $item->putaranblok }}

                                    </span>

                                @elseif(isset($item->blok))

                                    <span class="presensi-badge presensi-badge-blue">

                                        Putaran {{ $item->blok }}

                                    </span>

                                @endif


                                @if(isset($item->namaruangan) && $item->namaruangan)

                                    <span class="presensi-badge presensi-badge-room">

                                        {{ $item->namaruangan }}

                                    </span>

                                @elseif(
                                    isset($item->ruangan) &&
                                    $item->ruangan
                                )

                                    <span class="presensi-badge presensi-badge-room">

                                        {{ $item->ruangan->namaruangan }}

                                    </span>

                                @endif


                                <a
                                    href="{{ route('presensi.form', $item->id) }}"
                                    class="presensi-btn"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path d="M5 12h14" />
                                        <path d="M13 6l6 6-6 6" />
                                    </svg>

                                    Isi Presensi

                                </a>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="presensi-empty">

                    <div class="presensi-empty-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <rect
                                x="3.5"
                                y="5"
                                width="17"
                                height="15"
                                rx="2"
                            />

                            <path d="M7 3.5v3" />
                            <path d="M17 3.5v3" />
                            <path d="M3.5 9h17" />
                        </svg>

                    </div>

                    <h3 class="presensi-empty-title">
                        Belum Ada Jadwal
                    </h3>

                    <p class="presensi-empty-text">
                        Belum ada jadwal mengajar yang tersedia
                        untuk akun guru ini.
                    </p>

                </div>

            @endif

        </div>

    </section>

</div>

@endsection