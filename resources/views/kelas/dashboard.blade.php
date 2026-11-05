@extends('layouts.app')

@section('content')
@php
    $tabAktif = request('tab', 'siswa');

    $formatStatus = function ($status) {
        $status = strtolower(trim((string) $status));

        return match ($status) {
            'hadir', 'h', 'present' => [
                'label' => 'Hadir',
                'class' => 'status-hadir'
            ],
            'izin', 'i' => [
                'label' => 'Izin',
                'class' => 'status-izin'
            ],
            'sakit', 's' => [
                'label' => 'Sakit',
                'class' => 'status-sakit'
            ],
            'alpa', 'alpha', 'a', 'tanpa keterangan' => [
                'label' => 'Alpa',
                'class' => 'status-alpa'
            ],
            'disetujui', 'diterima', 'approved' => [
                'label' => 'Disetujui',
                'class' => 'status-hadir'
            ],
            'ditolak', 'reject', 'rejected' => [
                'label' => 'Ditolak',
                'class' => 'status-alpa'
            ],
            'menunggu', 'pending', 'diajukan' => [
                'label' => 'Menunggu',
                'class' => 'status-menunggu'
            ],
            default => [
                'label' => ucfirst($status ?: 'Belum ada status'),
                'class' => 'status-default'
            ]
        };
    };

    $formatTanggal = function ($tanggal) {
        if (!$tanggal) {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y');
        } catch (\Throwable $e) {
            return $tanggal;
        }
    };

    $formatWaktu = function ($waktu) {
        if (!$waktu) {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($waktu)->format('d-m-Y H:i');
        } catch (\Throwable $e) {
            return $waktu;
        }
    };

    $jumlahHadirSiswa = (int) data_get($rekapAbsensiSiswa ?? [], 'hadir', 0);
    $jumlahIzinSiswa = (int) data_get($rekapAbsensiSiswa ?? [], 'izin', 0);
    $jumlahSakitSiswa = (int) data_get($rekapAbsensiSiswa ?? [], 'sakit', 0);
    $jumlahAlpaSiswa = (int) data_get($rekapAbsensiSiswa ?? [], 'alpa', 0);
@endphp

@if(session('success'))
    <div class="kelas-section" style="padding:14px 18px;color:#166534;background:#ecfdf3;border:1px solid #bbf7d0;margin-bottom:16px;">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="kelas-section" style="padding:14px 18px;color:#b42318;background:#fff1f2;border:1px solid #fecdd3;margin-bottom:16px;">{{ $errors->first() }}</div>
@endif

<style>
    .kelas-dashboard {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        padding: 24px;
        color: #1e293b;
    }

    .kelas-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 24px;
    }

    .kelas-header h1 {
        margin: 0 0 7px;
        color: #0f172a;
        font-size: 27px;
        font-weight: 750;
        letter-spacing: -0.5px;
    }

    .kelas-header p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }

    .kelas-label {
        display: inline-flex;
        align-items: center;
        padding: 8px 13px;
        border: 1px solid #dbeafe;
        border-radius: 9px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 13px;
        font-weight: 700;
    }

    .kelas-info {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .kelas-card {
        min-width: 0;
        padding: 20px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.025);
    }

    .kelas-card-label {
        margin-bottom: 10px;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    .kelas-card-value {
        color: #0f172a;
        font-size: 25px;
        font-weight: 750;
        overflow-wrap: anywhere;
    }

    .kelas-card-caption {
        margin-top: 7px;
        color: #94a3b8;
        font-size: 12px;
    }

    .kelas-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 7px;
        margin-bottom: 22px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
    }

    .kelas-nav a {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        padding: 11px 15px;
        border-radius: 8px;
        color: #64748b;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        transition: background .2s, color .2s;
    }

    .kelas-nav a:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .kelas-nav a.active {
        background: #2563eb;
        color: #fff;
    }

    .kelas-section {
        min-width: 0;
        margin-bottom: 22px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #fff;
    }

    .kelas-section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 12px;
        padding: 20px 22px;
        border-bottom: 1px solid #e2e8f0;
    }

    .kelas-section-header h2 {
        margin: 0 0 6px;
        color: #0f172a;
        font-size: 17px;
        font-weight: 750;
    }

    .kelas-section-header p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .kelas-section-body {
        padding: 22px;
    }

    .kelas-filter {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .kelas-filter-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 180px;
        flex: 1;
    }

    .kelas-filter-group label {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
    }

    .kelas-filter input,
    .kelas-filter select {
        width: 100%;
        min-height: 41px;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: #334155;
        font-size: 13px;
    }

    .kelas-filter input:focus,
    .kelas-filter select:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
    }

    .btn-kelas,
    .btn-reset {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        min-height: 41px;
        padding: 10px 15px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: .2s;
    }

    .btn-kelas {
        background: #2563eb;
        color: #fff;
    }

    .btn-kelas:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .btn-reset {
        border-color: #cbd5e1;
        background: #fff;
        color: #475569;
    }

    .btn-reset:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .kelas-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .kelas-table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
    }

    .kelas-table thead th {
        padding: 13px 15px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 750;
        letter-spacing: .45px;
        text-align: left;
        text-transform: uppercase;
    }

    .kelas-table tbody td {
        padding: 14px 15px;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
        font-size: 13px;
        vertical-align: middle;
    }

    .kelas-table tbody tr:last-child td {
        border-bottom: none;
    }

    .kelas-table tbody tr:hover {
        background: #f8fafc;
    }

    .nama-siswa {
        color: #1d4ed8;
        font-weight: 700;
        text-decoration: none;
    }

    .nama-siswa:hover {
        color: #1e40af;
        text-decoration: underline;
    }

    .teks-kecil {
        margin-top: 4px;
        color: #94a3b8;
        font-size: 11px;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 9px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 750;
    }

    .status-hadir {
        background: #dcfce7;
        color: #166534;
    }

    .status-izin {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-sakit {
        background: #fef3c7;
        color: #92400e;
    }

    .status-alpa {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-menunggu {
        background: #f1f5f9;
        color: #475569;
    }

    .status-default {
        background: #f1f5f9;
        color: #475569;
    }

    .kelas-rekap {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .kelas-rekap-item {
        padding: 18px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
    }

    .kelas-rekap-label {
        margin-bottom: 9px;
        color: #64748b;
        font-size: 12px;
        font-weight: 650;
    }

    .kelas-rekap-value {
        color: #0f172a;
        font-size: 27px;
        font-weight: 800;
    }

    .rekap-hadir {
        border-top: 3px solid #22c55e;
    }

    .rekap-izin {
        border-top: 3px solid #3b82f6;
    }

    .rekap-sakit {
        border-top: 3px solid #f59e0b;
    }

    .rekap-alpa {
        border-top: 3px solid #ef4444;
    }

    .kelas-detail-identitas {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px 24px;
        padding: 16px 18px;
        margin-bottom: 22px;
        border: 1px solid #dbeafe;
        border-radius: 11px;
        background: #eff6ff;
    }

    .kelas-detail-identitas-item {
        min-width: 120px;
    }

    .kelas-detail-identitas-label {
        margin-bottom: 5px;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .kelas-detail-identitas-value {
        color: #1e3a8a;
        font-size: 14px;
        font-weight: 750;
    }

    .kelas-subsection {
        margin-top: 25px;
    }

    .kelas-subsection:first-child {
        margin-top: 0;
    }

    .kelas-subsection-title {
        margin: 0 0 14px;
        color: #0f172a;
        font-size: 15px;
        font-weight: 750;
    }

    .kosong {
        padding: 38px 20px;
        color: #94a3b8;
        font-size: 13px;
        text-align: center;
    }

    .kelas-section-footer {
        padding: 13px 22px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
    }

    .kelas-jadwal-hari {
        color: #0f172a;
        font-weight: 700;
    }

    .kelas-jadwal-jam {
        color: #64748b;
        font-size: 12px;
    }

    @media (max-width: 900px) {
        .kelas-dashboard {
            padding: 18px;
        }

        .kelas-info {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .kelas-rekap {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .kelas-dashboard {
            padding: 12px;
        }

        .kelas-header h1 {
            font-size: 23px;
        }

        .kelas-info,
        .kelas-rekap {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .kelas-card,
        .kelas-rekap-item {
            padding: 14px;
        }

        .kelas-card-value,
        .kelas-rekap-value {
            font-size: 22px;
        }

        .kelas-nav {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .kelas-nav a {
            padding: 10px 8px;
            font-size: 12px;
        }

        .kelas-section-header,
        .kelas-section-body {
            padding: 16px;
        }

        .kelas-filter {
            align-items: stretch;
        }

        .kelas-filter-group {
            min-width: 100%;
        }

        .kelas-filter .btn-kelas,
        .kelas-filter .btn-reset {
            flex: 1;
        }
    }
</style>

<div class="kelas-dashboard">

    {{-- HEADER DASHBOARD --}}
    <div class="kelas-header">
        <div>
            <h1>Dashboard {{ $kelas->namakelas }}</h1>
            <p>
                Pantau data siswa, kehadiran, perizinan, dan jadwal pembelajaran kelas.
            </p>
        </div>

        <span class="kelas-label">
            Akun Kelas
        </span>
    </div>

    {{-- INFORMASI KELAS --}}
    <div class="kelas-info">
        <div class="kelas-card">
            <div class="kelas-card-label">Nama Kelas</div>
            <div class="kelas-card-value">{{ $kelas->namakelas }}</div>
            <div class="kelas-card-caption">Kelas yang sedang dipantau</div>
        </div>

        <div class="kelas-card">
            <div class="kelas-card-label">Jumlah Siswa</div>
            <div class="kelas-card-value">{{ $jumlahSiswa ?? 0 }}</div>
            <div class="kelas-card-caption">Total siswa terdaftar di kelas ini</div>
        </div>

        <div class="kelas-card">
            <div class="kelas-card-label">Data yang Ditampilkan</div>
            <div class="kelas-card-value" style="font-size: 20px;">
                @switch($tabAktif)
                    @case('presensi')
                        Presensi
                        @break
                    @case('perizinan')
                        Perizinan
                        @break
                    @case('jadwal')
                        Jadwal
                        @break
                    @default
                        Data Siswa
                @endswitch
            </div>
            <div class="kelas-card-caption">Informasi berdasarkan kelas yang dipilih</div>
        </div>
    </div>

    {{-- NAVIGASI --}}
    <nav class="kelas-nav">
        <a href="{{ route('kelas.dashboard', ['tab' => 'siswa']) }}"
           class="{{ $tabAktif === 'siswa' ? 'active' : '' }}">
            Data Siswa
        </a>

        <a href="{{ route('kelas.dashboard', ['tab' => 'presensi']) }}"
           class="{{ $tabAktif === 'presensi' ? 'active' : '' }}">
            Data Presensi
        </a>

        <a href="{{ route('kelas.dashboard', ['tab' => 'perizinan']) }}"
           class="{{ $tabAktif === 'perizinan' ? 'active' : '' }}">
            Perizinan
        </a>

        <a href="{{ route('kelas.dashboard', ['tab' => 'jadwal']) }}"
           class="{{ $tabAktif === 'jadwal' ? 'active' : '' }}">
            Jadwal
        </a>
    </nav>

    {{-- TAB DATA SISWA --}}
    @if ($tabAktif === 'siswa')

        @if (isset($siswaDipilih) && $siswaDipilih)

            {{-- DETAIL ABSENSI SISWA --}}
            <section class="kelas-section">
                <div class="kelas-section-header">
                    <div>
                        <h2>Absensi Siswa</h2>
                        <p>Data kehadiran berdasarkan catatan presensi siswa yang dipilih.</p>
                    </div>

                    <a href="{{ route('kelas.dashboard', ['tab' => 'siswa']) }}"
                       class="btn-reset">
                        Kembali ke Data Siswa
                    </a>
                </div>

                <div class="kelas-section-body">

                    <div class="kelas-detail-identitas">
                        <div class="kelas-detail-identitas-item">
                            <div class="kelas-detail-identitas-label">Nama Siswa</div>
                            <div class="kelas-detail-identitas-value">
                                {{ $siswaDipilih->nama }}
                            </div>
                        </div>

                        <div class="kelas-detail-identitas-item">
                            <div class="kelas-detail-identitas-label">NISN</div>
                            <div class="kelas-detail-identitas-value">
                                {{ $siswaDipilih->nisn ?? '-' }}
                            </div>
                        </div>

                        <div class="kelas-detail-identitas-item">
                            <div class="kelas-detail-identitas-label">Kelas</div>
                            <div class="kelas-detail-identitas-value">
                                {{ $kelas->namakelas }}
                            </div>
                        </div>
                    </div>

                    {{-- REKAP ABSENSI --}}
                    <div class="kelas-rekap">
                        <div class="kelas-rekap-item rekap-hadir">
                            <div class="kelas-rekap-label">Hadir</div>
                            <div class="kelas-rekap-value">{{ $jumlahHadirSiswa }}</div>
                        </div>

                        <div class="kelas-rekap-item rekap-izin">
                            <div class="kelas-rekap-label">Izin</div>
                            <div class="kelas-rekap-value">{{ $jumlahIzinSiswa }}</div>
                        </div>

                        <div class="kelas-rekap-item rekap-sakit">
                            <div class="kelas-rekap-label">Sakit</div>
                            <div class="kelas-rekap-value">{{ $jumlahSakitSiswa }}</div>
                        </div>

                        <div class="kelas-rekap-item rekap-alpa">
                            <div class="kelas-rekap-label">Alpa</div>
                            <div class="kelas-rekap-value">{{ $jumlahAlpaSiswa }}</div>
                        </div>
                    </div>

                    {{-- FILTER ABSENSI SISWA --}}
                    <form method="GET" action="{{ route('kelas.dashboard') }}" class="kelas-filter">
                        <input type="hidden" name="tab" value="siswa">
                        <input type="hidden" name="siswa_id" value="{{ $siswaDipilih->id }}">

                        <div class="kelas-filter-group">
                            <label for="tanggal-absensi-siswa">Tanggal Absensi</label>
                            <input
                                type="date"
                                id="tanggal-absensi-siswa"
                                name="tanggal"
                                value="{{ request('tanggal') }}"
                            >
                        </div>

                        <button type="submit" class="btn-kelas">
                            Terapkan Filter
                        </button>

                        <a href="{{ route('kelas.dashboard', [
                            'tab' => 'siswa',
                            'siswa_id' => $siswaDipilih->id
                        ]) }}" class="btn-reset">
                            Reset
                        </a>
                    </form>

                    {{-- TABEL ABSENSI SISWA --}}
                    <div class="kelas-subsection">
                        <h3 class="kelas-subsection-title">Rincian Absensi per Mata Pelajaran</h3>

                        <div class="kelas-table-wrapper">
                            <table class="kelas-table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Tanggal dan Waktu</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Hari</th>
                                        <th>Jam Ke</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse (($absensiSiswa ?? collect()) as $index => $absensi)
                                        @php
                                            $statusAbsensi = $formatStatus($absensi->status ?? '');
                                        @endphp

                                        <tr>
                                            <td>{{ $index + 1 }}</td>

                                            <td>
                                                {{ $formatWaktu($absensi->waktu ?? null) }}
                                            </td>

                                            <td>
                                                {{ $absensi->namamapel ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $absensi->hari ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $absensi->jamke ?? '-' }}
                                            </td>

                                            <td>
                                                <span class="badge-status {{ $statusAbsensi['class'] }}">
                                                    {{ $statusAbsensi['label'] }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ $absensi->keterangan ?: '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="kosong">
                                                Belum ada data absensi untuk siswa ini pada tanggal yang dipilih.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TABEL IZIN SISWA --}}
                    <div class="kelas-subsection">
                        <h3 class="kelas-subsection-title">Data Perizinan Siswa</h3>

                        <div class="kelas-table-wrapper">
                            <table class="kelas-table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Tanggal</th>
                                        <th>Jenis Izin</th>
                                        <th>Alasan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse (($izinSiswa ?? collect()) as $index => $izin)
                                        @php
                                            $statusIzinSiswa = $formatStatus($izin->status ?? '');
                                        @endphp

                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $formatTanggal($izin->tanggal ?? $izin->waktu ?? null) }}</td>
                                            <td>{{ ucfirst($izin->jenis ?? '-') }}</td>
                                            <td>{{ $izin->alasan ?? '-' }}</td>
                                            <td>
                                                <span class="badge-status {{ $statusIzinSiswa['class'] }}">
                                                    {{ $statusIzinSiswa['label'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="kosong">
                                                Belum ada data perizinan untuk siswa ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </section>

        @else

            {{-- DAFTAR SELURUH SISWA --}}
            <section class="kelas-section">
                <div class="kelas-section-header">
                    <div>
                        <h2>Data Siswa</h2>
                        <p>Pilih nama siswa untuk melihat rincian absensinya.</p>
                    </div>
                </div>

                <div class="kelas-section-body">

                    <form method="GET" action="{{ route('kelas.dashboard') }}" class="kelas-filter">
                        <input type="hidden" name="tab" value="siswa">

                        <div class="kelas-filter-group">
                            <label for="cari-siswa">Cari Siswa</label>
                            <input
                                type="text"
                                id="cari-siswa"
                                name="cari"
                                value="{{ request('cari') }}"
                                placeholder="Masukkan nama atau NISN siswa"
                            >
                        </div>

                        <button type="submit" class="btn-kelas">
                            Cari Siswa
                        </button>

                        <a href="{{ route('kelas.dashboard', ['tab' => 'siswa']) }}"
                           class="btn-reset">
                            Reset
                        </a>
                    </form>

                    <div class="kelas-table-wrapper">
                        <table class="kelas-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Siswa</th>
                                    <th>NISN</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Absensi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse (($daftarSiswa ?? collect()) as $index => $siswa)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            <a
                                                href="{{ route('kelas.dashboard', [
                                                    'tab' => 'siswa',
                                                    'siswa_id' => $siswa->id
                                                ]) }}"
                                                class="nama-siswa"
                                            >
                                                {{ $siswa->nama }}
                                            </a>
                                            <div class="teks-kecil">
                                                Klik nama untuk melihat absensi
                                            </div>
                                        </td>

                                        <td>{{ $siswa->nisn ?? '-' }}</td>
                                        <td>{{ $siswa->jeniskelamin ?? '-' }}</td>

                                        <td>
                                            <a
                                                href="{{ route('kelas.dashboard', [
                                                    'tab' => 'siswa',
                                                    'siswa_id' => $siswa->id
                                                ]) }}"
                                                class="btn-kelas"
                                            >
                                                Lihat Absensi
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="kosong">
                                            Data siswa tidak ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="kelas-section-footer">
                    Total {{ ($daftarSiswa ?? collect())->count() }} siswa ditampilkan.
                </div>
            </section>

        @endif

    {{-- TAB DATA PRESENSI SELURUH KELAS --}}
    @elseif ($tabAktif === 'presensi')

        <section class="kelas-section">
            <div class="kelas-section-header">
                <div>
                    <h2>Data Presensi Kelas</h2>
                    <p>Daftar kehadiran siswa berdasarkan catatan presensi yang tersimpan.</p>
                </div>
            </div>

            <div class="kelas-section-body">

                <div class="kelas-rekap">
                    <div class="kelas-rekap-item rekap-hadir">
                        <div class="kelas-rekap-label">Hadir</div>
                        <div class="kelas-rekap-value">{{ data_get($rekap ?? [], 'hadir', 0) }}</div>
                    </div>

                    <div class="kelas-rekap-item rekap-izin">
                        <div class="kelas-rekap-label">Izin</div>
                        <div class="kelas-rekap-value">{{ data_get($rekap ?? [], 'izin', 0) }}</div>
                    </div>

                    <div class="kelas-rekap-item rekap-sakit">
                        <div class="kelas-rekap-label">Sakit</div>
                        <div class="kelas-rekap-value">{{ data_get($rekap ?? [], 'sakit', 0) }}</div>
                    </div>

                    <div class="kelas-rekap-item rekap-alpa">
                        <div class="kelas-rekap-label">Alpa</div>
                        <div class="kelas-rekap-value">{{ data_get($rekap ?? [], 'alpa', 0) }}</div>
                    </div>
                </div>

                <form method="GET" action="{{ route('kelas.dashboard') }}" class="kelas-filter">
                    <input type="hidden" name="tab" value="presensi">

                    <div class="kelas-filter-group">
                        <label for="tanggal-presensi">Tanggal Presensi</label>
                        <input
                            type="date"
                            id="tanggal-presensi"
                            name="tanggal"
                            value="{{ request('tanggal') }}"
                        >
                    </div>

                    <div class="kelas-filter-group">
                        <label for="cari-presensi">Cari Siswa</label>
                        <input
                            type="text"
                            id="cari-presensi"
                            name="cari"
                            value="{{ request('cari') }}"
                            placeholder="Nama atau NISN"
                        >
                    </div>

                    <button type="submit" class="btn-kelas">
                        Filter
                    </button>

                    <a href="{{ route('kelas.dashboard', ['tab' => 'presensi']) }}"
                       class="btn-reset">
                        Reset
                    </a>
                </form>

                <div class="kelas-table-wrapper">
                    <table class="kelas-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Tanggal dan Waktu</th>
                                <th>Mata Pelajaran</th>
                                <th>Hari</th>
                                <th>Jam Ke</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse (($daftarPresensi ?? collect()) as $index => $presensi)
                                @php
                                    $statusPresensi = $formatStatus($presensi->status ?? '');
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <a
                                            href="{{ route('kelas.dashboard', [
                                                'tab' => 'siswa',
                                                'siswa_id' => $presensi->siswaid
                                            ]) }}"
                                            class="nama-siswa"
                                        >
                                            {{ $presensi->namasiswa ?? '-' }}
                                        </a>
                                    </td>

                                    <td>{{ $presensi->nisn ?? '-' }}</td>
                                    <td>{{ $formatWaktu($presensi->waktu ?? null) }}</td>
                                    <td>{{ $presensi->namamapel ?? '-' }}</td>
                                    <td>{{ $presensi->hari ?? '-' }}</td>
                                    <td>{{ $presensi->jamke ?? '-' }}</td>

                                    <td>
                                        <span class="badge-status {{ $statusPresensi['class'] }}">
                                            {{ $statusPresensi['label'] }}
                                        </span>
                                    </td>

                                    <td>{{ $presensi->keterangan ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="kosong">
                                        Belum ada data presensi yang sesuai dengan filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="kelas-section-footer">
                Total {{ ($daftarPresensi ?? collect())->count() }} catatan presensi ditampilkan.
            </div>
        </section>

    {{-- TAB PERIZINAN --}}
    @elseif ($tabAktif === 'perizinan')

        <section class="kelas-section">
            <div class="kelas-section-header">
                <div>
                    <h2>Data Perizinan Siswa</h2>
                    <p>Pantau pengajuan izin siswa di kelas {{ $kelas->namakelas }}.</p>
                </div>
            </div>

            <div class="kelas-section-body">

                <form method="GET" action="{{ route('kelas.dashboard') }}" class="kelas-filter">
                    <input type="hidden" name="tab" value="perizinan">

                    <div class="kelas-filter-group">
                        <label for="tanggal-izin">Tanggal</label>
                        <input
                            type="date"
                            id="tanggal-izin"
                            name="tanggal"
                            value="{{ request('tanggal') }}"
                        >
                    </div>

                    <div class="kelas-filter-group">
                        <label for="cari-izin">Cari Siswa</label>
                        <input
                            type="text"
                            id="cari-izin"
                            name="cari"
                            value="{{ request('cari') }}"
                            placeholder="Nama atau NISN"
                        >
                    </div>

                    <div class="kelas-filter-group">
                        <label for="status-izin">Status Izin</label>
                        <select id="status-izin" name="status_izin">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status_izin') === 'menunggu' ? 'selected' : '' }}>
                                Menunggu
                            </option>
                            <option value="disetujui" {{ request('status_izin') === 'disetujui' ? 'selected' : '' }}>
                                Disetujui
                            </option>
                            <option value="ditolak" {{ request('status_izin') === 'ditolak' ? 'selected' : '' }}>
                                Ditolak
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn-kelas">
                        Filter
                    </button>

                    <a href="{{ route('kelas.dashboard', ['tab' => 'perizinan']) }}"
                       class="btn-reset">
                        Reset
                    </a>
                </form>

                <div class="kelas-table-wrapper">
                    <table class="kelas-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Bukti</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse (($daftarIzin ?? collect()) as $index => $izin)
                                @php
                                    $statusIzin = $formatStatus($izin->status ?? '');
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <a
                                            href="{{ route('kelas.dashboard', [
                                                'tab' => 'siswa',
                                                'siswa_id' => $izin->siswaid
                                            ]) }}"
                                            class="nama-siswa"
                                        >
                                            {{ $izin->namasiswa ?? '-' }}
                                        </a>
                                    </td>

                                    <td>{{ $izin->nisn ?? '-' }}</td>
                                    <td>{{ $formatTanggal($izin->tanggal ?? $izin->waktu ?? null) }}</td>
                                    <td>{{ ucfirst($izin->jenis ?? '-') }}</td>
                                    <td>{{ $izin->alasan ?? '-' }}</td>

                                    <td>
                                        <span class="badge-status {{ $statusIzin['class'] }}">
                                            {{ $statusIzin['label'] }}
                                        </span>
                                    </td>

                                    <td>
                                        @if(!empty($izin->bukti))
                                            <a href="{{ asset('storage/' . $izin->bukti) }}" target="_blank" rel="noopener" class="nama-siswa">Lihat Bukti</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $izin->catatanbk ?? '-' }}</td>
                                    <td>
                                        @if(strtolower((string) $izin->status) === 'menunggu')
                                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                                <form method="POST" action="{{ route('kelas.izin.setujui', $izin->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn-kelas" onclick="return confirm('Setujui pengajuan izin siswa ini?')">Setujui</button>
                                                </form>
                                                <form method="POST" action="{{ route('kelas.izin.tolak', $izin->id) }}" style="display:flex;gap:5px;flex-wrap:wrap;">
                                                    @csrf
                                                    <input type="text" name="catatanbk" placeholder="Catatan (opsional)" maxlength="1000" style="max-width:150px;min-width:100px;">
                                                    <button type="submit" class="btn-reset" onclick="return confirm('Tolak pengajuan izin siswa ini?')">Tolak</button>
                                                </form>
                                            </div>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="kosong">
                                        Belum ada data perizinan yang sesuai dengan filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="kelas-section-footer">
                Total {{ $jumlahIzin ?? ($daftarIzin ?? collect())->count() }} pengajuan izin.
            </div>
        </section>

    {{-- TAB JADWAL --}}
    @elseif ($tabAktif === 'jadwal')

        <section class="kelas-section">
            <div class="kelas-section-header">
                <div>
                    <h2>Jadwal Pelajaran</h2>
                    <p>Jadwal pembelajaran kelas {{ $kelas->namakelas }}.</p>
                </div>
            </div>

            <div class="kelas-section-body">
                <div class="kelas-table-wrapper">
                    <table class="kelas-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Hari</th>
                                <th>Jam Ke</th>
                                <th>Waktu</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Ruangan</th>
                                <th>Putaran Blok</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse (($daftarJadwal ?? collect()) as $index => $jadwal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        <span class="kelas-jadwal-hari">
                                            {{ $jadwal->hari ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $jadwal->jamke ? 'Jam ke-' . $jadwal->jamke : '-' }}
                                    </td>

                                    <td>
                                        {{ $jadwal->jamMulai ?? '-' }}
                                        -
                                        {{ $jadwal->jamSelesai ?? '-' }}
                                    </td>

                                    <td>{{ $jadwal->namamapel ?? '-' }}</td>
                                    <td>{{ $jadwal->namaguru ?? '-' }}</td>
                                    <td>{{ $jadwal->namaruangan ?? '-' }}</td>

                                    <td>
                                        {{ $jadwal->putaranblok ?? $jadwal->blok ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="kosong">
                                        Belum ada jadwal pelajaran untuk kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="kelas-section-footer">
                Total {{ ($daftarJadwal ?? collect())->count() }} jadwal ditampilkan.
            </div>
        </section>

    @endif

</div>
@endsection