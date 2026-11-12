{{-- =========================================================
     SCP - DASHBOARD ORANGTUA
     File:
     resources/views/orangtua/dashboard.blade.php
========================================================= --}}

@extends('layouts.app')

@section('title', 'Dashboard Orangtua - SCP')
@section('page-title', 'Dashboard Orangtua')

@push('styles')
<style>
    .orangtua-dashboard {
        width: 100%;
    }

    /* WELCOME */
    .orangtua-welcome {
        margin-bottom: 22px;
    }

    .orangtua-welcome h1 {
        margin: 0 0 6px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 25px;
        font-weight: 800;
        color: #123f68;
    }

    .orangtua-welcome p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }

    /* DATA ANAK */
    .anak-card {
        position: relative;
        overflow: hidden;
        margin-bottom: 22px;
        padding: 25px;
        border-radius: 18px;
        background: linear-gradient(
            135deg,
            #0f4c81 0%,
            #1769aa 55%,
            #2383c4 100%
        );
        color: #ffffff;
        box-shadow: 0 12px 30px rgba(15, 76, 129, 0.16);
    }

    .anak-card::after {
        content: '';
        position: absolute;
        width: 190px;
        height: 190px;
        top: -90px;
        right: -70px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.07);
    }

    .anak-label {
        position: relative;
        z-index: 1;
        margin-bottom: 8px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.3px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.72);
    }

    .anak-card h2 {
        position: relative;
        z-index: 1;
        margin: 0 0 15px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 23px;
        font-weight: 800;
    }

    .anak-info {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .anak-info span {
        padding: 8px 11px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.11);
        font-size: 11px;
        color: rgba(255, 255, 255, 0.92);
    }

    /* STATISTIK */
    .orangtua-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .orangtua-stat-card {
        padding: 18px;
        border: 1px solid #e6edf5;
        border-radius: 15px;
        background: #ffffff;
        box-shadow: 0 4px 18px rgba(15, 47, 95, 0.05);
    }

    .orangtua-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 13px;
    }

    .orangtua-stat-icon {
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

    .orangtua-stat-label {
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
    }

    .orangtua-stat-number {
        color: #123f68;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 25px;
        font-weight: 800;
        line-height: 1;
    }

    /* PERSENTASE */
    .orangtua-persentase {
        padding: 18px 20px;
        margin-bottom: 22px;
        border: 1px solid #e6edf5;
        border-radius: 15px;
        background: #ffffff;
        box-shadow: 0 4px 18px rgba(15, 47, 95, 0.05);
    }

    .persentase-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .persentase-header strong {
        color: #123f68;
        font-size: 13px;
        font-weight: 800;
    }

    .persentase-header span {
        color: #1769aa;
        font-size: 17px;
        font-weight: 800;
    }

    .persentase-track {
        width: 100%;
        height: 9px;
        overflow: hidden;
        border-radius: 20px;
        background: #edf2f7;
    }

    .persentase-fill {
        height: 100%;
        border-radius: 20px;
        background: linear-gradient(90deg, #1769aa, #35a1e5);
        transition: width .3s ease;
    }

    .persentase-keterangan {
        margin-top: 9px;
        color: #64748b;
        font-size: 10px;
    }

    /* CONTENT */
    .orangtua-content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr);
        gap: 20px;
    }

    /* CARD */
    .orangtua-card {
        overflow: hidden;
        border: 1px solid #e6edf5;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 4px 18px rgba(15, 47, 95, 0.05);
    }

    .orangtua-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 18px 20px;
        border-bottom: 1px solid #edf2f7;
    }

    .orangtua-card-header h3 {
        margin: 0;
        color: #123f68;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 15px;
        font-weight: 800;
    }

    .orangtua-card-header span {
        color: #64748b;
        font-size: 10px;
        font-weight: 500;
    }

    .orangtua-card-body {
        padding: 0 20px;
    }

    /* JADWAL */
    .jadwal-item {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 14px 0;
        border-bottom: 1px solid #edf2f7;
    }

    .jadwal-item:last-child {
        border-bottom: none;
    }

    .jam {
        width: 68px;
        min-width: 68px;
        padding: 8px 5px;
        border-radius: 9px;
        background: #edf6ff;
        color: #1769aa;
        text-align: center;
        font-size: 10px;
        font-weight: 700;
        line-height: 1.5;
    }

    .jadwal-detail {
        min-width: 0;
        flex: 1;
    }

    .jadwal-mapel {
        margin-bottom: 4px;
        color: #1e293b;
        font-size: 12px;
        font-weight: 700;
    }

    .jadwal-guru {
        color: #64748b;
        font-size: 10px;
    }

    .ruangan {
        padding: 6px 8px;
        border-radius: 7px;
        background: #f0f7fd;
        color: #1769aa;
        font-size: 9px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* PRESENSI */
    .presensi-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 14px 0;
        border-bottom: 1px solid #edf2f7;
    }

    .presensi-item:last-child {
        border-bottom: none;
    }

    .presensi-info {
        min-width: 0;
    }

    .presensi-mapel {
        margin-bottom: 4px;
        color: #1e293b;
        font-size: 12px;
        font-weight: 700;
    }

    .presensi-waktu {
        color: #64748b;
        font-size: 10px;
    }

    /* BADGE */
    .orangtua-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 54px;
        padding: 6px 9px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 800;
    }

    .orangtua-badge-hadir {
        background: #e8f7ee;
        color: #16834b;
    }

    .orangtua-badge-izin {
        background: #fff5d9;
        color: #a46b00;
    }

    .orangtua-badge-sakit {
        background: #ffeaea;
        color: #c0392b;
    }

    .orangtua-badge-alpa {
        background: #f1f3f5;
        color: #555f6d;
    }

    /* EMPTY */
    .orangtua-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 150px;
        padding: 25px;
        color: #94a3b8;
        text-align: center;
    }

    .orangtua-empty-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        border-radius: 12px;
        background: #f1f6fb;
        color: #8aa6bf;
        font-size: 16px;
    }

    .orangtua-empty p {
        margin: 0;
        font-size: 11px;
    }

    /* RESPONSIVE */
    @media (max-width: 1100px) {
        .orangtua-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .orangtua-content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .orangtua-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .orangtua-stat-card {
            padding: 14px;
        }

        .orangtua-stat-number {
            font-size: 21px;
        }

        .anak-card {
            padding: 20px;
        }

        .anak-card h2 {
            font-size: 20px;
        }

        .orangtua-card-header {
            padding: 16px;
        }

        .orangtua-card-body {
            padding: 0 16px;
        }

        .jadwal-item {
            gap: 9px;
        }

        .jam {
            width: 58px;
            min-width: 58px;
        }

        .ruangan {
            display: none;
        }
    }
</style>
@endpush

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | DATA DASHBOARD
    |--------------------------------------------------------------------------
    | Semua angka diambil dari Controller.
    | Tidak ada angka presensi yang dibuat secara manual.
    */

    $anak = $anak ?? null;

    $jumlahHadir = (int) ($jumlahHadir ?? 0);
    $jumlahIzin = (int) ($jumlahIzin ?? 0);
    $jumlahSakit = (int) ($jumlahSakit ?? 0);
    $jumlahAlpa = (int) ($jumlahAlpa ?? 0);

    $totalPresensi =
        $jumlahHadir +
        $jumlahIzin +
        $jumlahSakit +
        $jumlahAlpa;

    // Persentase hadir dari seluruh catatan presensi.
    $persentaseHadir = $totalPresensi > 0
        ? round(($jumlahHadir / $totalPresensi) * 100, 1)
        : 0;

    $persentaseHadir = min(100, max(0, $persentaseHadir));

    $jadwalHariIni = $jadwalHariIni ?? [];
    $presensiHariIni = $presensiHariIni ?? [];
@endphp

<div class="orangtua-dashboard">

    {{-- DATA ANAK --}}
    @if($anak)

        @php
            $namaAnak = data_get($anak, 'nama', '-');
            $nisAnak = data_get($anak, 'nis', '-');
            $kelasAnak = data_get($anak, 'namakelas', '-');
            $jurusanAnak = data_get($anak, 'namajurusan', '-');
        @endphp

        <section class="anak-card">
            <div class="anak-label">Data Anak</div>

            <h2>{{ $namaAnak }}</h2>

            <div class="anak-info">
                <span>NIS: {{ $nisAnak ?: '-' }}</span>
                <span>Kelas: {{ $kelasAnak ?: '-' }}</span>
                <span>Jurusan: {{ $jurusanAnak ?: '-' }}</span>
            </div>
        </section>

    @else

        <section class="orangtua-card" style="margin-bottom:22px;">
            <div class="orangtua-empty">
                <div class="orangtua-empty-icon">
                    <i class="fa-solid fa-user-slash"></i>
                </div>

                <p>
                    Data anak belum terhubung dengan akun Orangtua.
                </p>
            </div>
        </section>

    @endif

    {{-- STATISTIK PRESENSI --}}
    <div class="orangtua-stat-grid">

        <div class="orangtua-stat-card">
            <div class="orangtua-stat-top">
                <div class="orangtua-stat-label">Hadir</div>
                <div class="orangtua-stat-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="orangtua-stat-number">{{ $jumlahHadir }}</div>
        </div>

        <div class="orangtua-stat-card">
            <div class="orangtua-stat-top">
                <div class="orangtua-stat-label">Izin</div>
                <div class="orangtua-stat-icon">
                    <i class="fa-solid fa-file-circle-check"></i>
                </div>
            </div>
            <div class="orangtua-stat-number">{{ $jumlahIzin }}</div>
        </div>

        <div class="orangtua-stat-card">
            <div class="orangtua-stat-top">
                <div class="orangtua-stat-label">Sakit</div>
                <div class="orangtua-stat-icon">
                    <i class="fa-solid fa-notes-medical"></i>
                </div>
            </div>
            <div class="orangtua-stat-number">{{ $jumlahSakit }}</div>
        </div>

        <div class="orangtua-stat-card">
            <div class="orangtua-stat-top">
                <div class="orangtua-stat-label">Alpa</div>
                <div class="orangtua-stat-icon">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
            <div class="orangtua-stat-number">{{ $jumlahAlpa }}</div>
        </div>

    </div>

    {{-- INFOGRAFIK PERSENTASE --}}
    <section class="orangtua-persentase">

        <div class="persentase-header">
            <strong>Persentase Kehadiran</strong>
            <span>{{ number_format($persentaseHadir, 1, ',', '.') }}%</span>
        </div>

        <div
            class="persentase-track"
            role="progressbar"
            aria-valuenow="{{ $persentaseHadir }}"
            aria-valuemin="0"
            aria-valuemax="100"
        >
            <div
                class="persentase-fill"
                style="width: {{ $persentaseHadir }}%;"
            ></div>
        </div>

        <div class="persentase-keterangan">
            Total catatan presensi: {{ $totalPresensi }}
            @if($totalPresensi === 0)
                — Belum ada data presensi.
            @else
                — Berdasarkan catatan presensi yang tersedia.
            @endif
        </div>

    </section>

    {{-- JADWAL DAN PRESENSI HARI INI --}}
    <div class="orangtua-content-grid">

        {{-- JADWAL HARI INI --}}
        <section class="orangtua-card">

            <div class="orangtua-card-header">
                <h3>Jadwal Hari Ini</h3>
                <span>{{ ucfirst($hariSekarang ?? '-') }}</span>
            </div>

            <div class="orangtua-card-body">

                @forelse($jadwalHariIni as $jadwal)

                    @php
                        $jamMulai = data_get($jadwal, 'jamMulai');
                        $jamSelesai = data_get($jadwal, 'jamSelesai');

                        $namaMapel =
                            data_get($jadwal, 'namamapelmaster')
                            ?? data_get($jadwal, 'namamapel')
                            ?? 'Mata Pelajaran';

                        $namaGuru = data_get($jadwal, 'namaguru', '-');
                        $namaRuangan = data_get($jadwal, 'namaruangan');
                    @endphp

                    <div class="jadwal-item">

                        <div class="jam">
                            {{ $jamMulai ? substr($jamMulai, 0, 5) : '--:--' }}
                            <br>-<br>
                            {{ $jamSelesai ? substr($jamSelesai, 0, 5) : '--:--' }}
                        </div>

                        <div class="jadwal-detail">
                            <div class="jadwal-mapel">
                                {{ $namaMapel }}
                            </div>

                            <div class="jadwal-guru">
                                Guru: {{ $namaGuru }}
                            </div>
                        </div>

                        @if($namaRuangan)
                            <div class="ruangan">
                                <i class="fa-solid fa-location-dot"></i>
                                {{ $namaRuangan }}
                            </div>
                        @endif

                    </div>

                @empty

                    <div class="orangtua-empty">
                        <div class="orangtua-empty-icon">
                            <i class="fa-regular fa-calendar-xmark"></i>
                        </div>
                        <p>Belum ada jadwal hari ini.</p>
                    </div>

                @endforelse

            </div>
        </section>

        {{-- PRESENSI HARI INI --}}
        <section class="orangtua-card">

            <div class="orangtua-card-header">
                <h3>Presensi Hari Ini</h3>
                <span>Hari ini</span>
            </div>

            <div class="orangtua-card-body">

                @forelse($presensiHariIni as $presensi)

                    @php
                        $namaMapelPresensi =
                            data_get($presensi, 'namamapelmaster')
                            ?? data_get($presensi, 'namamapel')
                            ?? 'Mata Pelajaran';

                        $namaGuruPresensi =
                            data_get($presensi, 'namaguru', '-');

                        $status = strtolower(trim(
                            (string) data_get($presensi, 'status', '')
                        ));
                    @endphp

                    <div class="presensi-item">

                        <div class="presensi-info">
                            <div class="presensi-mapel">
                                {{ $namaMapelPresensi }}
                            </div>

                            <div class="presensi-waktu">
                                Guru: {{ $namaGuruPresensi }}
                            </div>
                        </div>

                        @if($status === 'hadir')

                            <span class="orangtua-badge orangtua-badge-hadir">
                                HADIR
                            </span>

                        @elseif($status === 'izin')

                            <span class="orangtua-badge orangtua-badge-izin">
                                IZIN
                            </span>

                        @elseif($status === 'sakit')

                            <span class="orangtua-badge orangtua-badge-sakit">
                                SAKIT
                            </span>

                        @elseif($status === 'alpa')

                            <span class="orangtua-badge orangtua-badge-alpa">
                                ALPA
                            </span>

                        @else

                            <span class="orangtua-badge orangtua-badge-alpa">
                                {{ strtoupper($status ?: 'BELUM ADA STATUS') }}
                            </span>

                        @endif

                    </div>

                @empty

                    <div class="orangtua-empty">
                        <div class="orangtua-empty-icon">
                            <i class="fa-regular fa-clipboard"></i>
                        </div>
                        <p>Belum ada presensi hari ini.</p>
                    </div>

                @endforelse

            </div>
        </section>

    </div>

</div>

@endsection