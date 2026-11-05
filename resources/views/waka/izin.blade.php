@extends('layouts.app')

@section('title', 'Perizinan Siswa - SCP')
@section('page-title', 'Perizinan Siswa')
@section('page-subtitle', 'Monitoring pengajuan izin dan sakit siswa')

@php
    $dataPerizinan = $dataIzin ?? $data ?? collect();

    $jumlahIzinTampil = $jumlahIzin ?? $dataPerizinan->where('jenis', 'izin')->count();
    $jumlahSakitTampil = $jumlahSakit ?? $dataPerizinan->where('jenis', 'sakit')->count();
@endphp

@push('styles')
<style>
    .waka-page {
        max-width: 1500px;
        margin: auto;
    }

    .page-intro {
        margin-bottom: 22px;
    }

    .page-intro h2 {
        margin: 0;
        font-size: 22px;
        color: #172033;
        font-weight: 700;
    }

    .page-intro p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 13px;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .stat {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 3px 12px rgba(15,23,42,.035);
    }

    .stat-label {
        font-size: 12px;
        color: #718096;
        margin-bottom: 7px;
    }

    .stat-value {
        font-size: 25px;
        font-weight: 700;
        color: #172033;
    }

    .filter-card,
    .table-card {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(15,23,42,.035);
    }

    .filter-card {
        padding: 17px;
        margin-bottom: 18px;
    }

    .filter-row {
        display: grid;
        grid-template-columns: 1fr 180px 180px auto;
        gap: 10px;
        align-items: end;
    }

    .field label {
        display: block;
        margin-bottom: 6px;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
    }

    .field input,
    .field select {
        width: 100%;
        height: 39px;
        border: 1px solid #dce3ec;
        border-radius: 8px;
        padding: 0 11px;
        font-size: 12px;
        color: #334155;
        background: #fff;
        outline: none;
    }

    .field input:focus,
    .field select:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(37,99,235,.08);
    }

    .btn {
        height: 39px;
        border: 0;
        border-radius: 8px;
        padding: 0 15px;
        background: #2563eb;
        color: white;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn:hover {
        background: #1d4ed8;
    }

    .table-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf1f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table-header h3 {
        margin: 0;
        font-size: 15px;
        color: #172033;
    }

    .table-header p {
        margin: 4px 0 0;
        color: #7b8798;
        font-size: 12px;
    }

    .count {
        background: #f1f5f9;
        color: #475569;
        padding: 5px 9px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    th {
        padding: 11px 15px;
        background: #f8fafc;
        border-bottom: 1px solid #e8edf3;
        text-align: left;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    td {
        padding: 12px 15px;
        border-bottom: 1px solid #eef2f6;
        color: #374151;
        font-size: 12px;
        vertical-align: middle;
    }

    tr:last-child td {
        border-bottom: 0;
    }

    tbody tr:hover {
        background: #fafcff;
    }

    .student {
        font-weight: 600;
        color: #1e293b;
    }

    .sub {
        color: #94a3b8;
        font-size: 11px;
        margin-top: 3px;
    }

    .badge {
        display: inline-flex;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 600;
    }

    .izin {
        background: #eff6ff;
        color: #2563eb;
    }

    .sakit {
        background: #fff7ed;
        color: #c2410c;
    }

    .approved {
        background: #f0fdf4;
        color: #15803d;
    }

    .pending {
        background: #f8fafc;
        color: #64748b;
    }

    .rejected {
        background: #fef2f2;
        color: #b91c1c;
    }

    .empty {
        text-align: center;
        color: #94a3b8;
        padding: 35px !important;
    }

    @media(max-width:800px) {
        .stats {
            grid-template-columns: 1fr;
        }

        .filter-row {
            grid-template-columns: 1fr;
        }

        .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

<div class="waka-page">

    <div class="page-intro">
        <h2>Perizinan Siswa</h2>
        <p>Monitoring pengajuan izin dan sakit siswa secara terpusat.</p>
    </div>

    <div class="stats">

        <div class="stat">
            <div class="stat-label">Total Pengajuan</div>
            <div class="stat-value">{{ $dataPerizinan->count() }}</div>
        </div>

        <div class="stat">
            <div class="stat-label">Izin</div>
            <div class="stat-value">{{ $jumlahIzinTampil }}</div>
        </div>

        <div class="stat">
            <div class="stat-label">Sakit</div>
            <div class="stat-value">{{ $jumlahSakitTampil }}</div>
        </div>

    </div>

    <div class="filter-card">

        <form method="GET" action="{{ route('waka.izin') }}">

            <div class="filter-row">

                <div class="field">
                    <label>Pencarian</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama siswa atau NISN..."
                    >
                </div>

                <div class="field">
                    <label>Jenis</label>
                    <select name="jenis">
                        <option value="">Semua</option>
                        <option value="izin" {{ request('jenis') === 'izin' ? 'selected' : '' }}>
                            Izin
                        </option>
                        <option value="sakit" {{ request('jenis') === 'sakit' ? 'selected' : '' }}>
                            Sakit
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                            Menunggu
                        </option>
                        <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>
                            Disetujui
                        </option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>
                            Ditolak
                        </option>
                    </select>
                </div>

                <button class="btn" type="submit">
                    Terapkan
                </button>

            </div>

        </form>

    </div>

    <div class="table-card">

        <div class="table-header">

            <div>
                <h3>Daftar Pengajuan</h3>
                <p>Data izin dan sakit siswa.</p>
            </div>

            <span class="count">
                {{ $dataPerizinan->count() }} data
            </span>

        </div>

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($dataPerizinan as $index => $izin)

                        @php
                            $jenis = strtolower($izin->jenis ?? $izin->jenisizin ?? '');
                            $status = strtolower($izin->status ?? 'menunggu');

                            $namaSiswa =
                                $izin->nama ??
                                $izin->namasiswa ??
                                $izin->nama_siswa ??
                                '-';

                            $nisn = $izin->nisn ?? '-';

                            $keterangan =
                                $izin->alasan ??
                                $izin->keterangan ??
                                $izin->deskripsi ??
                                '-';

                            $tanggal =
                                $izin->tanggal ??
                                $izin->created_at;

                            $jenisClass = $jenis === 'sakit'
                                ? 'sakit'
                                : 'izin';

                            $statusClass = match($status) {
                                'disetujui', 'diterima' => 'approved',
                                'ditolak' => 'rejected',
                                default => 'pending'
                            };
                        @endphp

                        <tr>

                            <td>{{ $index + 1 }}</td>

                            <td>
                                <div class="student">
                                    {{ $namaSiswa }}
                                </div>

                                <div class="sub">
                                    NISN {{ $nisn }}
                                </div>
                            </td>

                            <td>
                                <span class="badge {{ $jenisClass }}">
                                    {{ ucfirst($jenis ?: 'izin') }}
                                </span>
                            </td>

                            <td>
                                {{ $keterangan }}
                            </td>

                            <td>
                                @if($tanggal)
                                    {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($status ?: 'menunggu') }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="empty">
                                Belum ada pengajuan izin.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection