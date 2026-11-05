@extends('layouts.app')

@section('title', 'Laporan - SCP')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Ringkasan data akademik School Class Progress')

@php
    $nilaiSiswa = (int) ($jumlahSiswa ?? 0);
    $nilaiGuru = (int) ($jumlahGuru ?? 0);
    $nilaiKelas = (int) ($jumlahKelas ?? 0);
    $nilaiJadwal = (int) ($jumlahJadwal ?? 0);
    $nilaiPresensi = (int) ($jumlahPresensi ?? 0);
    $nilaiJurnal = (int) ($jumlahJurnal ?? 0);
    $nilaiIzin = (int) ($jumlahIzin ?? 0);

    $maksimal = max(
        $nilaiSiswa,
        $nilaiGuru,
        $nilaiKelas,
        $nilaiJadwal,
        $nilaiPresensi,
        $nilaiJurnal,
        $nilaiIzin,
        1
    );
@endphp

@push('styles')
<style>
    .report-page {
        max-width: 1500px;
        margin: auto;
    }

    .intro {
        margin-bottom: 22px;
    }

    .intro h2 {
        margin: 0;
        font-size: 22px;
        color: #172033;
    }

    .intro p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 13px;
    }

    .metric-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .metric {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 12px;
        padding: 18px;
    }

    .metric-label {
        color: #718096;
        font-size: 11px;
        margin-bottom: 8px;
    }

    .metric-value {
        color: #172033;
        font-size: 25px;
        font-weight: 700;
    }

    .report-card {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 14px;
        margin-bottom: 18px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(15,23,42,.035);
    }

    .report-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf1f6;
    }

    .report-header h3 {
        margin: 0;
        color: #172033;
        font-size: 15px;
    }

    .report-header p {
        margin: 4px 0 0;
        color: #7b8798;
        font-size: 12px;
    }

    .report-body {
        padding: 20px;
    }

    .progress-item {
        margin-bottom: 18px;
    }

    .progress-item:last-child {
        margin-bottom: 0;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 7px;
        font-size: 12px;
    }

    .progress-label {
        color: #475569;
        font-weight: 600;
    }

    .progress-value {
        color: #172033;
        font-weight: 700;
    }

    .progress {
        height: 7px;
        background: #edf2f7;
        border-radius: 999px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: #2563eb;
        border-radius: inherit;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    th {
        padding: 11px 15px;
        background: #f8fafc;
        color: #64748b;
        text-align: left;
        font-size: 11px;
        border-bottom: 1px solid #e8edf3;
    }

    td {
        padding: 13px 15px;
        border-bottom: 1px solid #eef2f6;
        color: #374151;
        font-size: 12px;
    }

    .strong {
        color: #1e293b;
        font-weight: 600;
    }

    @media(max-width:900px) {
        .metric-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width:550px) {
        .metric-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<div class="report-page">

    <div class="intro">
        <h2>Ringkasan Sistem</h2>
        <p>Informasi jumlah data dan aktivitas yang tercatat dalam SCP.</p>
    </div>

    <div class="metric-grid">

        <div class="metric">
            <div class="metric-label">Siswa</div>
            <div class="metric-value">{{ number_format($nilaiSiswa, 0, ',', '.') }}</div>
        </div>

        <div class="metric">
            <div class="metric-label">Guru</div>
            <div class="metric-value">{{ number_format($nilaiGuru, 0, ',', '.') }}</div>
        </div>

        <div class="metric">
            <div class="metric-label">Kelas</div>
            <div class="metric-value">{{ number_format($nilaiKelas, 0, ',', '.') }}</div>
        </div>

        <div class="metric">
            <div class="metric-label">Jadwal</div>
            <div class="metric-value">{{ number_format($nilaiJadwal, 0, ',', '.') }}</div>
        </div>

    </div>

    <div class="report-card">

        <div class="report-header">
            <h3>Aktivitas Sistem</h3>
            <p>Perbandingan jumlah data aktivitas yang tercatat.</p>
        </div>

        <div class="report-body">

            <div class="progress-item">
                <div class="progress-info">
                    <span class="progress-label">Presensi</span>
                    <span class="progress-value">{{ number_format($nilaiPresensi) }}</span>
                </div>

                <div class="progress">
                    <div
                        class="progress-bar"
                        style="width: {{ min(($nilaiPresensi / $maksimal) * 100, 100) }}%;"
                    ></div>
                </div>
            </div>

            <div class="progress-item">
                <div class="progress-info">
                    <span class="progress-label">Jurnal Mengajar</span>
                    <span class="progress-value">{{ number_format($nilaiJurnal) }}</span>
                </div>

                <div class="progress">
                    <div
                        class="progress-bar"
                        style="width: {{ min(($nilaiJurnal / $maksimal) * 100, 100) }}%;"
                    ></div>
                </div>
            </div>

            <div class="progress-item">
                <div class="progress-info">
                    <span class="progress-label">Pengajuan Izin</span>
                    <span class="progress-value">{{ number_format($nilaiIzin) }}</span>
                </div>

                <div class="progress">
                    <div
                        class="progress-bar"
                        style="width: {{ min(($nilaiIzin / $maksimal) * 100, 100) }}%;"
                    ></div>
                </div>
            </div>

        </div>

    </div>

    <div class="report-card">

        <div class="report-header">
            <h3>Ringkasan Data</h3>
            <p>Jumlah data utama yang tersedia di sistem.</p>
        </div>

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td class="strong">Siswa</td>
                        <td>{{ number_format($nilaiSiswa, 0, ',', '.') }}</td>
                        <td>Data peserta didik</td>
                    </tr>

                    <tr>
                        <td class="strong">Guru</td>
                        <td>{{ number_format($nilaiGuru, 0, ',', '.') }}</td>
                        <td>Data tenaga pengajar</td>
                    </tr>

                    <tr>
                        <td class="strong">Kelas</td>
                        <td>{{ number_format($nilaiKelas, 0, ',', '.') }}</td>
                        <td>Data rombongan belajar</td>
                    </tr>

                    <tr>
                        <td class="strong">Jadwal</td>
                        <td>{{ number_format($nilaiJadwal, 0, ',', '.') }}</td>
                        <td>Jadwal pembelajaran</td>
                    </tr>

                    <tr>
                        <td class="strong">Presensi</td>
                        <td>{{ number_format($nilaiPresensi, 0, ',', '.') }}</td>
                        <td>Data kehadiran siswa</td>
                    </tr>

                    <tr>
                        <td class="strong">Jurnal</td>
                        <td>{{ number_format($nilaiJurnal, 0, ',', '.') }}</td>
                        <td>Jurnal mengajar guru</td>
                    </tr>

                    <tr>
                        <td class="strong">Izin</td>
                        <td>{{ number_format($nilaiIzin, 0, ',', '.') }}</td>
                        <td>Pengajuan izin siswa</td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection