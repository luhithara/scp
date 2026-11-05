@extends('layouts.app')

@section('title', 'Jurnal Mengajar - SCP')
@section('page-title', 'Jurnal Mengajar')
@section('page-subtitle', 'Monitoring jurnal pembelajaran guru')

@php
    $dataJurnal = $dataJurnal ?? collect();
@endphp

@push('styles')
<style>
    .waka-page {
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

    .filter {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 14px;
        padding: 17px;
        margin-bottom: 18px;
        box-shadow: 0 3px 12px rgba(15,23,42,.035);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1fr 220px auto;
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

    .field input {
        width: 100%;
        height: 39px;
        border: 1px solid #dce3ec;
        border-radius: 8px;
        padding: 0 11px;
        outline: none;
        font-size: 12px;
    }

    .field input:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(37,99,235,.08);
    }

    .btn {
        height: 39px;
        border: 0;
        border-radius: 8px;
        background: #2563eb;
        color: #fff;
        padding: 0 16px;
        font-size: 12px;
        font-weight: 600;
    }

    .table-card {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(15,23,42,.035);
    }

    .table-head {
        padding: 17px 20px;
        border-bottom: 1px solid #edf1f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-head h3 {
        margin: 0;
        font-size: 15px;
        color: #172033;
    }

    .table-head p {
        margin: 4px 0 0;
        font-size: 12px;
        color: #7b8798;
    }

    .count {
        background: #f1f5f9;
        color: #475569;
        padding: 5px 9px;
        border-radius: 6px;
        font-size: 11px;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    th {
        background: #f8fafc;
        padding: 11px 15px;
        text-align: left;
        border-bottom: 1px solid #e8edf3;
        color: #64748b;
        font-size: 11px;
    }

    td {
        padding: 13px 15px;
        border-bottom: 1px solid #eef2f6;
        color: #374151;
        font-size: 12px;
        vertical-align: top;
    }

    tbody tr:hover {
        background: #fafcff;
    }

    .guru {
        color: #1e293b;
        font-weight: 600;
    }

    .sub {
        color: #94a3b8;
        font-size: 11px;
        margin-top: 3px;
    }

    .materi {
        max-width: 420px;
        line-height: 1.55;
    }

    .badge {
        display: inline-flex;
        padding: 4px 8px;
        border-radius: 6px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 10px;
        font-weight: 600;
    }

    .empty {
        text-align: center;
        color: #94a3b8;
        padding: 35px !important;
    }

    @media(max-width:700px) {
        .filter-grid {
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

    <div class="intro">
        <h2>Jurnal Mengajar</h2>
        <p>Monitoring aktivitas pembelajaran yang dicatat oleh guru.</p>
    </div>

    <div class="filter">

        <form method="GET" action="{{ route('waka.jurnal') }}">

            <div class="filter-grid">

                <div class="field">
                    <label>Pencarian</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama guru atau isi jurnal..."
                    >
                </div>

                <div class="field">
                    <label>Tanggal</label>
                    <input
                        type="date"
                        name="tanggal"
                        value="{{ request('tanggal') }}"
                    >
                </div>

                <button class="btn" type="submit">
                    Terapkan
                </button>

            </div>

        </form>

    </div>

    <div class="table-card">

        <div class="table-head">

            <div>
                <h3>Daftar Jurnal</h3>
                <p>Catatan kegiatan pembelajaran guru.</p>
            </div>

            <span class="count">
                {{ count($dataJurnal) }} jurnal
            </span>

        </div>

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Guru</th>
                        <th>Tanggal</th>
                        <th>Materi / Kegiatan</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($dataJurnal as $index => $jurnal)

                        @php
                            $isiJurnal =
                                $jurnal->materi ??
                                $jurnal->kegiatan ??
                                $jurnal->isi ??
                                $jurnal->uraian ??
                                $jurnal->deskripsi ??
                                '-';

                            $tanggalJurnal =
                                $jurnal->tanggal ??
                                $jurnal->tanggalmengajar ??
                                $jurnal->created_at;
                        @endphp

                        <tr>

                            <td>{{ $index + 1 }}</td>

                            <td>
                                <div class="guru">
                                    {{ $jurnal->namaguru ?? 'Guru tidak ditemukan' }}
                                </div>

                                @if(!empty($jurnal->kodeguru))
                                    <div class="sub">
                                        {{ $jurnal->kodeguru }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                @if($tanggalJurnal)
                                    {{ \Carbon\Carbon::parse($tanggalJurnal)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <div class="materi">
                                    {{ $isiJurnal }}
                                </div>
                            </td>

                            <td>
                                @if(!empty($jurnal->created_at))
                                    <span class="badge">
                                        {{ \Carbon\Carbon::parse($jurnal->created_at)->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="empty">
                                Belum ada jurnal mengajar.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection