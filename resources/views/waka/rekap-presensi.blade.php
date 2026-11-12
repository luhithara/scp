@extends('layouts.app')

@section('title', 'Rekap Presensi Semester - SCP')
@section('page-title', 'Rekap Presensi Semester')
@section('page-subtitle', 'Rekapitulasi kehadiran siswa berdasarkan semester')

@section('content')

@php
    $kelasTerpilih = request('kelasid');
    $tampilkanTabel = request('tampilkan') == '1' && !empty($kelasTerpilih);

    $queryFilter = request()->except(['kelasid', 'tampilkan']);

    $querySemuaKelas = array_merge($queryFilter, [
        'tampilkan' => 0,
    ]);

    $namaKelasAktif = $daftarKelas->firstWhere('id', $kelasTerpilih);
@endphp

<style>
    .rekap-page {
        width: 100%;
    }

    .rekap-header {
        margin-bottom: 20px;
    }

    .rekap-header h2 {
        margin: 0 0 5px;
        font-size: 21px;
        color: #172033;
    }

    .rekap-header p {
        margin: 0;
        color: #718096;
        font-size: 13px;
    }

    .filter-card,
    .summary-card,
    .table-card,
    .kelas-card {
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 13px;
        margin-bottom: 18px;
    }

    .filter-card {
        padding: 18px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 12px;
        align-items: end;
    }

    .filter-group label {
        display: block;
        margin-bottom: 7px;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        height: 40px;
        padding: 0 10px;
        border: 1px solid #dce3ed;
        border-radius: 8px;
        background: #fff;
        color: #334155;
        font-size: 12px;
    }

    .btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        min-height: 40px;
        padding: 0 14px;
        border: 0;
        border-radius: 8px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s ease;
        white-space: nowrap;
    }

    .btn-primary {
        background: #2563eb;
        color: #fff;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .btn-success {
        background: #15803d;
        color: #fff;
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #334155;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 13px;
        margin-bottom: 18px;
    }

    .summary-card {
        padding: 17px;
    }

    .summary-label {
        color: #718096;
        font-size: 11px;
        margin-bottom: 9px;
    }

    .summary-value {
        color: #172033;
        font-size: 23px;
        font-weight: 750;
    }

    .summary-note {
        color: #94a3b8;
        font-size: 10px;
        margin-top: 5px;
    }

    /* PILIH KELAS */

    .kelas-card {
        padding: 18px;
    }

    .kelas-heading {
        margin-bottom: 13px;
    }

    .kelas-heading h3 {
        margin: 0 0 5px;
        color: #172033;
        font-size: 14px;
    }

    .kelas-heading p {
        margin: 0;
        color: #94a3b8;
        font-size: 11px;
    }

    .kelas-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    .btn-kelas {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        min-height: 37px;
        padding: 0 15px;
        border: 1px solid #dce3ed;
        border-radius: 8px;
        background: #fff;
        color: #475569;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: .2s ease;
    }

    .btn-kelas:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #eff6ff;
    }

    .btn-kelas.active {
        border-color: #2563eb;
        background: #2563eb;
        color: #fff;
    }

    /* TABEL */

    .table-card {
        overflow: hidden;
    }

    .table-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 17px 19px;
        border-bottom: 1px solid #edf1f5;
    }

    .table-heading h3 {
        margin: 0;
        color: #172033;
        font-size: 15px;
    }

    .table-heading p {
        margin: 4px 0 0;
        color: #94a3b8;
        font-size: 11px;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .rekap-table {
        width: 100%;
        min-width: 950px;
        border-collapse: collapse;
    }

    .rekap-table th {
        padding: 12px 13px;
        background: #f8fafc;
        color: #64748b;
        text-align: left;
        white-space: nowrap;
        font-size: 11px;
        font-weight: 700;
    }

    .rekap-table td {
        padding: 12px 13px;
        border-bottom: 1px solid #eef2f6;
        color: #475569;
        font-size: 12px;
    }

    .rekap-table tbody tr:hover {
        background: #f8fafc;
    }

    .nama-siswa {
        color: #1e293b;
        font-weight: 650;
    }

    .angka {
        text-align: center;
        font-weight: 650;
    }

    .persentase {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 700;
        white-space: nowrap;
    }

    .empty-data {
        padding: 35px 15px !important;
        color: #94a3b8 !important;
        text-align: center;
    }

    .periode-info {
        color: #64748b;
        font-size: 11px;
    }

    @media(max-width: 1100px) {
        .filter-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .summary-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media(max-width: 650px) {
        .filter-grid,
        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .table-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .btn-kelas {
            flex: 1 1 auto;
        }
    }

    @media print {
        body {
            background: #fff !important;
        }

        .filter-card,
        .kelas-card,
        .no-print,
        .sidebar,
        nav {
            display: none !important;
        }

        .summary-card,
        .table-card {
            break-inside: avoid;
        }

        .table-wrap {
            overflow: visible;
        }

        .rekap-table {
            min-width: 100%;
            font-size: 9px;
        }

        .rekap-table th,
        .rekap-table td {
            padding: 6px;
            font-size: 9px;
        }
    }
</style>

<div class="rekap-page">

    {{-- HEADER --}}

    <div class="rekap-header">
        <h2>Rekapitulasi Presensi Siswa</h2>
        <p>Ringkasan kehadiran siswa berdasarkan periode semester.</p>
    </div>

    {{-- FILTER --}}

    <div class="filter-card no-print">

        <form method="GET" action="{{ route('waka.rekap-presensi') }}">

            <div class="filter-grid">

                <div class="filter-group">
                    <label>Tahun Ajaran</label>
                    <select name="tahun">
                        @for($i = now()->year - 3; $i <= now()->year + 1; $i++)
                            <option value="{{ $i }}" @selected((int)$tahun === $i)>
                                {{ $i }}/{{ $i + 1 }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="filter-group">
                    <label>Semester</label>
                    <select name="semester">
                        <option value="1" @selected((int)$semester === 1)>Semester 1</option>
                        <option value="2" @selected((int)$semester === 2)>Semester 2</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Tingkat</label>
                    <select name="tingkat">
                        <option value="">Semua Tingkat</option>
                        @foreach($daftarTingkat as $tingkat)
                            <option value="{{ $tingkat }}" @selected(request('tingkat') == $tingkat)>
                                Kelas {{ $tingkat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label>Jurusan</label>
                    <select name="jurusanid">
                        <option value="">Semua Jurusan</option>
                        @foreach($daftarJurusan as $jurusan)
                            <option value="{{ $jurusan->id }}" @selected(request('jurusanid') == $jurusan->id)>
                                {{ $jurusan->namajurusan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label>Cari Siswa</label>
                    <input
                        type="text"
                        name="cari"
                        value="{{ request('cari') }}"
                        placeholder="Nama atau NISN"
                    >
                </div>

                <div class="filter-group">
                    <button type="submit" class="btn btn-primary" style="width:100%">
                        Terapkan Filter
                    </button>
                </div>

                <div class="filter-group">
                    <a
                        href="{{ route('waka.rekap-presensi') }}"
                        class="btn btn-secondary"
                        style="width:100%"
                    >
                        Reset
                    </a>
                </div>

            </div>

        </form>

    </div>

    {{-- RINGKASAN --}}

    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-label">Jumlah Siswa</div>
            <div class="summary-value">{{ number_format($totalSiswa, 0, ',', '.') }}</div>
            <div class="summary-note">Sesuai filter</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Total Hadir</div>
            <div class="summary-value">{{ number_format($totalHadir, 0, ',', '.') }}</div>
            <div class="summary-note">Presensi hadir</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Total Izin</div>
            <div class="summary-value">{{ number_format($totalIzin, 0, ',', '.') }}</div>
            <div class="summary-note">Presensi izin</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Total Sakit</div>
            <div class="summary-value">{{ number_format($totalSakit, 0, ',', '.') }}</div>
            <div class="summary-note">Presensi sakit</div>
        </div>

        <div class="summary-card">
            <div class="summary-label">Total Alpa</div>
            <div class="summary-value">{{ number_format($totalAlpa, 0, ',', '.') }}</div>
            <div class="summary-note">Presensi alpa</div>
        </div>

    </div>

    {{-- PILIH KELAS --}}

    <div class="kelas-card no-print">

        <div class="kelas-heading">
            <h3>Pilih Kelas</h3>
            <p>Pilih tombol kelas untuk menampilkan tabel rekap presensi siswa.</p>
        </div>

        <div class="kelas-buttons">

            <a
                href="{{ route('waka.rekap-presensi', $querySemuaKelas) }}"
                class="btn-kelas {{ !$tampilkanTabel ? 'active' : '' }}"
            >
                Semua Kelas
            </a>

            @foreach($daftarKelas as $kelas)

                <a
                    href="{{ route('waka.rekap-presensi', array_merge(request()->except('tampilkan'), [
                        'kelasid' => $kelas->id,
                        'tampilkan' => 1
                    ])) }}"
                    class="btn-kelas {{
                        $tampilkanTabel && (string)$kelasTerpilih === (string)$kelas->id
                        ? 'active'
                        : ''
                    }}"
                >
                    {{ $kelas->namakelas }}
                </a>

            @endforeach

        </div>

    </div>

    {{-- TABEL HANYA MUNCUL SETELAH TOMBOL KELAS DIKLIK --}}

    @if($tampilkanTabel)

        <div class="table-card">

            <div class="table-heading">

                <div>
                    <h3>
                        Data Presensi Siswa
                        @if($namaKelasAktif)
                            — {{ $namaKelasAktif->namakelas }}
                        @endif
                    </h3>

                    <p class="periode-info">
                        Periode
                        {{ \Carbon\Carbon::parse($mulai)->translatedFormat('d F Y') }}
                        sampai
                        {{ \Carbon\Carbon::parse($selesai)->translatedFormat('d F Y') }}
                        — Semester {{ $semester }}
                    </p>
                </div>

                <div class="no-print" style="display:flex;gap:8px;flex-wrap:wrap">

                    <a
                        href="{{ route('waka.rekap-presensi.excel', request()->except('tampilkan')) }}"
                        class="btn btn-success"
                    >
                        Export Excel
                    </a>

                    <button
                        type="button"
                        onclick="window.print()"
                        class="btn btn-primary"
                    >
                        Cetak / PDF
                    </button>

                </div>

            </div>

            <div class="table-wrap">

                <table class="rekap-table">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Tingkat</th>
                            <th>Jurusan</th>
                            <th>Kelas</th>
                            <th>Hadir</th>
                            <th>Izin</th>
                            <th>Sakit</th>
                            <th>Alpa</th>
                            <th>Total</th>
                            <th>Persentase Hadir</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($dataRekap as $index => $item)

                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->nisn }}</td>
                                <td class="nama-siswa">{{ $item->nama }}</td>
                                <td>{{ $item->tingkat }}</td>
                                <td>{{ $item->namajurusan ?? '-' }}</td>
                                <td>{{ $item->namakelas }}</td>
                                <td class="angka">{{ $item->jumlah_hadir }}</td>
                                <td class="angka">{{ $item->jumlah_izin }}</td>
                                <td class="angka">{{ $item->jumlah_sakit }}</td>
                                <td class="angka">{{ $item->jumlah_alpa }}</td>
                                <td class="angka">{{ $item->total_presensi }}</td>
                                <td>
                                    <span class="persentase">
                                        {{ number_format($item->persentase, 2, ',', '.') }}%
                                    </span>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="12" class="empty-data">
                                    Belum ada data presensi untuk kelas ini.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    @endif

</div>

@endsection