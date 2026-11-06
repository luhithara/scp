
@extends('layouts.app')

@section('title', 'Perizinan Siswa - BK | SCP')

@section('content')

@php
    $data = $data ?? $izin ?? $perizinan ?? collect();
    $tanggalFilter = request('tanggal', '');
@endphp

<div class="bk-izin-page">

    {{-- HEADER --}}
    <div class="bk-page-header">
        <div>
            <div class="bk-eyebrow">Bimbingan Konseling</div>
            <h1>Perizinan Siswa</h1>
            <p>
                Pantau dan lakukan persetujuan atau penolakan izin dan sakit
                siswa dari kelas yang menjadi tanggung jawab BK.
            </p>
        </div>

        <div class="bk-header-info">
            <div class="bk-header-info-icon">
                <i class="fa-solid fa-file-circle-check"></i>
            </div>
            <div>
                <span>Total Data</span>
                <strong>{{ $data->count() }}</strong>
            </div>
        </div>
    </div>

    {{-- PESAN --}}
    @if(session('success'))
        <div class="bk-alert success">
            <div class="bk-alert-icon">
                <i class="fa-solid fa-check"></i>
            </div>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bk-alert danger">
            <div class="bk-alert-icon">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bk-alert danger">
            <div class="bk-alert-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- FILTER --}}
    <div class="bk-card filter-card">
        <div class="bk-card-header">
            <div class="bk-card-heading">
                <div class="bk-card-icon">
                    <i class="fa-solid fa-filter"></i>
                </div>
                <div>
                    <h3>Filter Perizinan</h3>
                    <p>
                        Cari data izin atau sakit berdasarkan siswa,
                        tanggal, jenis, dan status.
                    </p>
                </div>
            </div>
        </div>

        <div class="bk-card-body">
            <form method="GET"
                  action="{{ route('bk.izin') }}"
                  class="izin-filter-form">

                <div class="form-field">
                    <label for="cari">Cari Siswa</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input
                            type="text"
                            id="cari"
                            name="cari"
                            value="{{ request('cari', '') }}"
                            placeholder="Nama atau NISN..."
                        >
                    </div>
                </div>

                <div class="form-field">
                    <label for="tanggal">Tanggal</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-calendar"></i>
                        <input
                            type="date"
                            id="tanggal"
                            name="tanggal"
                            value="{{ $tanggalFilter }}"
                        >
                    </div>
                </div>

                <div class="form-field">
                    <label for="jenis">Jenis</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-list"></i>
                        <select id="jenis" name="jenis">
                            <option value="">Semua Jenis</option>
                            <option value="izin" {{ request('jenis') === 'izin' ? 'selected' : '' }}>
                                Izin
                            </option>
                            <option value="sakit" {{ request('jenis') === 'sakit' ? 'selected' : '' }}>
                                Sakit
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-field">
                    <label for="status">Status</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-circle-check"></i>
                        <select id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>
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
                </div>

                <div class="filter-actions">
                    <button type="submit" class="bk-primary-button">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Cari
                    </button>

                    <a href="{{ route('bk.izin') }}" class="bk-secondary-button">
                        <i class="fa-solid fa-rotate-left"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- RINGKASAN --}}
    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-top">
                <div>
                    <span class="summary-label">Total Perizinan</span>
                    <strong class="summary-number">{{ $data->count() }}</strong>
                </div>
                <div class="summary-icon blue">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
            </div>
            <p>Data izin dan sakit siswa</p>
        </div>

        <div class="summary-card">
            <div class="summary-top">
                <div>
                    <span class="summary-label">Izin</span>
                    <strong class="summary-number">
                        {{ $data->where('jenis', 'izin')->count() }}
                    </strong>
                </div>
                <div class="summary-icon green">
                    <i class="fa-solid fa-file-circle-check"></i>
                </div>
            </div>
            <p>Siswa dengan jenis izin</p>
        </div>

        <div class="summary-card">
            <div class="summary-top">
                <div>
                    <span class="summary-label">Sakit</span>
                    <strong class="summary-number">
                        {{ $data->where('jenis', 'sakit')->count() }}
                    </strong>
                </div>
                <div class="summary-icon orange">
                    <i class="fa-solid fa-notes-medical"></i>
                </div>
            </div>
            <p>Siswa dengan jenis sakit</p>
        </div>

    </div>

    {{-- JUDUL TABEL --}}
    <div class="result-heading">
        <div>
            <h2>Daftar Perizinan Siswa</h2>
            <p>Data perizinan yang masuk dari siswa atau orangtua.</p>
        </div>

        <div class="result-count">
            <i class="fa-solid fa-database"></i>
            {{ $data->count() }} data
        </div>
    </div>

    {{-- TABEL --}}
    <div class="bk-card table-card">
        <div class="table-container">

            <table class="izin-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $index => $item)
                        <tr>

                            {{-- NO --}}
                            <td class="number-cell">
                                {{ $index + 1 }}
                            </td>

                            {{-- SISWA --}}
                            <td>
                                <div class="student-cell">
                                    <div class="student-avatar">
                                        {{ strtoupper(substr($item->nama ?? '?', 0, 1)) }}
                                    </div>
                                    <div class="student-info">
                                        <strong>{{ $item->nama ?? '-' }}</strong>
                                        <span>NISN: {{ $item->nisn ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- KELAS --}}
                            <td>
                                <span class="class-badge">
                                    <i class="fa-solid fa-door-open"></i>
                                    {{ $item->namakelas ?? '-' }}
                                </span>
                            </td>

                            {{-- JURUSAN --}}
                            <td>
                                <span class="jurusan-text">
                                    {{ $item->kodejurusan ?? '-' }}
                                </span>
                            </td>

                            {{-- TANGGAL --}}
                            <td>
                                @if(!empty($item->tanggal))
                                    <span class="date-text">
                                        <i class="fa-regular fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                                    </span>
                                @elseif(!empty($item->waktu))
                                    <span class="date-text">
                                        <i class="fa-regular fa-calendar"></i>
                                        {{ \Carbon\Carbon::parse($item->waktu)->format('d-m-Y') }}
                                    </span>
                                @else
                                    <span class="muted">-</span>
                                @endif
                            </td>

                            {{-- JENIS --}}
                            <td>
                                @if(($item->jenis ?? '') === 'sakit')
                                    <span class="type-badge sakit">
                                        <i class="fa-solid fa-notes-medical"></i>
                                        Sakit
                                    </span>
                                @elseif(($item->jenis ?? '') === 'izin')
                                    <span class="type-badge izin">
                                        <i class="fa-solid fa-file-circle-check"></i>
                                        Izin
                                    </span>
                                @else
                                    <span class="type-badge default">
                                        {{ $item->jenis ?? '-' }}
                                    </span>
                                @endif
                            </td>

                            {{-- ALASAN --}}
                            <td>
                                <div class="reason-text">
                                    {{ $item->alasan ?? '-' }}
                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if(($item->status ?? '') === 'disetujui')
                                    <span class="status-badge approved">
                                        <i class="fa-solid fa-check"></i>
                                        Disetujui
                                    </span>
                                @elseif(($item->status ?? '') === 'ditolak')
                                    <span class="status-badge rejected">
                                        <i class="fa-solid fa-xmark"></i>
                                        Ditolak
                                    </span>
                                @elseif(($item->status ?? '') === 'menunggu')
                                    <span class="status-badge waiting">
                                        <i class="fa-solid fa-clock"></i>
                                        Menunggu
                                    </span>
                                @else
                                    <span class="status-badge default">
                                        {{ $item->status ?? '-' }}
                                    </span>
                                @endif
                            </td>

                            {{-- BUKTI --}}
                            <td>
                                @php
                                    $buktiUtama = $item->bukti ?? null;
                                @endphp

                                @if($buktiUtama)
                                    <a
                                        href="{{ asset('storage/' . $buktiUtama) }}"
                                        target="_blank"
                                        class="evidence-button"
                                    >
                                        <i class="fa-regular fa-file-image"></i>
                                        Lihat Bukti
                                    </a>
                                @elseif(
                                    isset($item->buktiFiles)
                                    && $item->buktiFiles
                                    && $item->buktiFiles->count() > 0
                                )
                                    <div class="evidence-list">
                                        @foreach($item->buktiFiles as $bukti)
                                            <a
                                                href="{{ asset('storage/' . $bukti->file) }}"
                                                target="_blank"
                                                class="evidence-button"
                                            >
                                                <i class="fa-regular fa-file-image"></i>
                                                Lihat Bukti
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="no-evidence">Tidak ada bukti</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td>
                                @if(($item->status ?? '') === 'menunggu')

                                    <div class="action-buttons">

                                        <form
                                            method="POST"
                                            action="{{ route('bk.izin.setujui', $item->id) }}"
                                            onsubmit="return confirm('Yakin ingin menyetujui perizinan siswa ini?')"
                                        >
                                            @csrf
                                            <button type="submit" class="action-button approve">
                                                <i class="fa-solid fa-check"></i>
                                                Setujui
                                            </button>
                                        </form>

                                        <button
                                            type="button"
                                            class="action-button reject"
                                            onclick="bukaModalTolak({{ $item->id }})"
                                        >
                                            <i class="fa-solid fa-xmark"></i>
                                            Tolak
                                        </button>

                                    </div>

                                @elseif(($item->status ?? '') === 'disetujui')
                                    <span class="action-finished approved-finished">
                                        <i class="fa-solid fa-lock"></i>
                                        Sudah diproses
                                    </span>

                                @elseif(($item->status ?? '') === 'ditolak')
                                    <span class="action-finished rejected-finished">
                                        <i class="fa-solid fa-lock"></i>
                                        Sudah diproses
                                    </span>

                                @else
                                    <span class="muted">-</span>
                                @endif
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="10" class="empty-cell">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fa-solid fa-file-circle-xmark"></i>
                                    </div>
                                    <h3>Belum Ada Data Perizinan</h3>
                                    <p>
                                        Tidak ada data izin atau sakit
                                        yang sesuai dengan filter.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    {{-- INFORMASI --}}
    <div class="information-card">
        <div class="information-icon">
            <i class="fa-solid fa-circle-info"></i>
        </div>
        <div class="information-content">
            <strong>Informasi Persetujuan</strong>
            <p>
                BK dan Wali Kelas dapat menyetujui atau menolak perizinan.
                Keputusan pertama yang diberikan akan mengunci data perizinan.
                Setelah diproses oleh salah satu pihak, pihak lainnya tidak
                dapat mengubah keputusan tersebut.
            </p>
        </div>
    </div>

</div>

{{-- MODAL TOLAK --}}
<div id="modalTolak" class="modal-overlay">
    <div class="modal-box">

        <div class="modal-heading">
            <div class="modal-icon reject-icon">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <div>
                <h3>Tolak Perizinan</h3>
                <p>Berikan catatan jika diperlukan.</p>
            </div>
        </div>

        <form id="formTolak" method="POST">
            @csrf

            <div class="form-field modal-field">
                <label for="catatanbk">Catatan Penolakan</label>
                <textarea
                    name="catatanbk"
                    id="catatanbk"
                    rows="4"
                    maxlength="1000"
                    placeholder="Tuliskan alasan penolakan (opsional)..."
                ></textarea>
            </div>

            <div class="modal-actions">
                <button
                    type="button"
                    class="bk-secondary-button"
                    onclick="tutupModalTolak()"
                >
                    Batal
                </button>

                <button type="submit" class="action-button reject">
                    <i class="fa-solid fa-xmark"></i>
                    Ya, Tolak
                </button>
            </div>
        </form>

    </div>
</div>

@push('styles')
<style>
    .bk-izin-page {
        width: 100%;
        max-width: 1320px;
        margin: 0 auto;
        padding-bottom: 30px;
    }

    .bk-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .bk-eyebrow {
        margin-bottom: 5px;
        color: #748196;
        font-size: 9px;
        font-weight: 700;
    }

    .bk-page-header h1 {
        margin: 0;
        color: #17233a;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 24px;
        font-weight: 800;
        line-height: 1.2;
    }

    .bk-page-header p {
        max-width: 720px;
        margin: 7px 0 0;
        color: #718096;
        font-size: 11px;
        line-height: 1.55;
    }

    .bk-header-info {
        min-width: 145px;
        padding: 10px 13px;
        border: 1px solid #e0e7f0;
        border-radius: 11px;
        background: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 2px 8px rgba(30, 64, 175, .04);
    }

    .bk-header-info-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 9px;
        background: #edf4ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .bk-header-info span {
        display: block;
        color: #8b96a7;
        font-size: 8px;
        font-weight: 600;
    }

    .bk-header-info strong {
        display: block;
        margin-top: 2px;
        color: #26354d;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 16px;
        font-weight: 800;
    }

    .bk-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        margin-bottom: 17px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 600;
    }

    .bk-alert.success {
        border: 1px solid #cdebd9;
        background: #effaf3;
        color: #187044;
    }

    .bk-alert.danger {
        border: 1px solid #f2cfd2;
        background: #fff3f4;
        color: #b32f3e;
    }

    .bk-alert-icon {
        width: 26px;
        height: 26px;
        min-width: 26px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bk-alert.success .bk-alert-icon {
        background: #d9f4e3;
    }

    .bk-alert.danger .bk-alert-icon {
        background: #fbe0e3;
    }

    .bk-card {
        width: 100%;
        border: 1px solid #e1e7ef;
        border-radius: 13px;
        background: #fff;
        box-shadow: 0 2px 9px rgba(20, 48, 90, .035);
        overflow: hidden;
    }

    .filter-card {
        margin-bottom: 18px;
    }

    .bk-card-header {
        min-height: 74px;
        padding: 14px 17px;
        border-bottom: 1px solid #edf0f4;
        display: flex;
        align-items: center;
    }

    .bk-card-heading {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .bk-card-icon {
        width: 35px;
        height: 35px;
        min-width: 35px;
        border-radius: 9px;
        background: #edf4ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .bk-card-heading h3 {
        margin: 0;
        color: #24334b;
        font-size: 12px;
        font-weight: 800;
    }

    .bk-card-heading p {
        margin: 4px 0 0;
        color: #8a95a6;
        font-size: 9px;
    }

    .bk-card-body {
        padding: 17px;
    }

    .izin-filter-form {
        display: grid;
        grid-template-columns: minmax(220px, 1.5fr) minmax(160px, 1fr) minmax(150px, 1fr) minmax(150px, 1fr) auto;
        gap: 11px;
        align-items: end;
    }

    .form-field label {
        display: block;
        margin-bottom: 6px;
        color: #34425a;
        font-size: 10px;
        font-weight: 700;
    }

    .input-wrap {
        position: relative;
    }

    .input-wrap > i {
        position: absolute;
        top: 50%;
        left: 11px;
        transform: translateY(-50%);
        color: #8995a7;
        font-size: 11px;
        pointer-events: none;
        z-index: 2;
    }

    .input-wrap input,
    .input-wrap select {
        width: 100%;
        height: 39px;
        padding: 0 10px 0 32px;
        border: 1px solid #dce2eb;
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: #334158;
        font-family: inherit;
        font-size: 10px;
        transition: .2s ease;
    }

    .input-wrap input:focus,
    .input-wrap select:focus {
        border-color: #1769d1;
        box-shadow: 0 0 0 3px rgba(23, 105, 209, .08);
    }

    .input-wrap select {
        cursor: pointer;
    }

    .filter-actions {
        display: flex;
        gap: 7px;
    }

    .bk-primary-button,
    .bk-secondary-button {
        min-height: 39px;
        padding: 8px 12px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        font-family: inherit;
        font-size: 9px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        transition: .2s ease;
    }

    .bk-primary-button {
        border: 1px solid #1769d1;
        background: #1769d1;
        color: #fff;
    }

    .bk-primary-button:hover {
        background: #1259b4;
        color: #fff;
    }

    .bk-secondary-button {
        border: 1px solid #dce2eb;
        background: #f8fafc;
        color: #68758a;
    }

    .bk-secondary-button:hover {
        background: #f1f4f8;
        color: #35445d;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 22px;
    }

    .summary-card {
        padding: 15px;
        border: 1px solid #e1e7ef;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 2px 9px rgba(20, 48, 90, .035);
    }

    .summary-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .summary-label {
        display: block;
        color: #778397;
        font-size: 9px;
        font-weight: 700;
    }

    .summary-number {
        display: block;
        margin-top: 4px;
        color: #26354d;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 21px;
        font-weight: 800;
        line-height: 1;
    }

    .summary-icon {
        width: 35px;
        height: 35px;
        min-width: 35px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .summary-icon.blue {
        background: #edf4ff;
        color: #1769d1;
    }

    .summary-icon.green {
        background: #edf9f2;
        color: #188653;
    }

    .summary-icon.orange {
        background: #fff5e8;
        color: #b87505;
    }

    .summary-card p {
        margin: 7px 0 0;
        color: #99a3b1;
        font-size: 8px;
    }

    .result-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 12px;
    }

    .result-heading h2 {
        margin: 0;
        color: #17233a;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 15px;
        font-weight: 800;
    }

    .result-heading p {
        margin: 4px 0 0;
        color: #8a95a6;
        font-size: 10px;
    }

    .result-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 7px;
        background: #edf4ff;
        color: #1769d1;
        font-size: 8px;
        font-weight: 800;
        white-space: nowrap;
    }

    .table-card {
        overflow: hidden;
    }

    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    .izin-table {
        width: 100%;
        min-width: 1250px;
        border-collapse: collapse;
    }

    .izin-table th {
        padding: 11px 13px;
        background: #f8fafc;
        border-bottom: 1px solid #e8edf3;
        color: #7b8799;
        font-size: 8px;
        font-weight: 800;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .25px;
        white-space: nowrap;
    }

    .izin-table td {
        padding: 12px 13px;
        border-bottom: 1px solid #edf0f4;
        color: #596579;
        font-size: 9px;
        vertical-align: middle;
    }

    .izin-table tbody tr:last-child td {
        border-bottom: none;
    }

    .izin-table tbody tr {
        transition: background .15s ease;
    }

    .izin-table tbody tr:hover {
        background: #fbfcfe;
    }

    .number-cell {
        width: 45px;
        color: #9aa4b2 !important;
        text-align: center;
        font-weight: 700;
    }

    .student-cell {
        min-width: 175px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .student-avatar {
        width: 31px;
        height: 31px;
        min-width: 31px;
        border-radius: 50%;
        background: #edf4ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: 800;
    }

    .student-info strong {
        display: block;
        color: #29374e;
        font-size: 9px;
        font-weight: 800;
    }

    .student-info span {
        display: block;
        margin-top: 3px;
        color: #98a2b1;
        font-size: 7px;
    }

    .class-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 8px;
        border-radius: 6px;
        background: #edf4ff;
        color: #1769d1;
        font-size: 8px;
        font-weight: 800;
        white-space: nowrap;
    }

    .class-badge i {
        font-size: 7px;
    }

    .jurusan-text {
        color: #596579;
        font-size: 9px;
        font-weight: 700;
    }

    .date-text {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #69758a;
        font-size: 8px;
        white-space: nowrap;
    }

    .date-text i {
        color: #1769d1;
    }

    .reason-text {
        max-width: 230px;
        color: #596579;
        font-size: 8px;
        line-height: 1.5;
    }

    .muted {
        color: #a0a9b6;
    }

    .type-badge,
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 8px;
        border-radius: 6px;
        font-size: 7px;
        font-weight: 800;
        white-space: nowrap;
    }

    .type-badge.izin {
        background: #edf4ff;
        color: #1769d1;
    }

    .type-badge.sakit {
        background: #fff5e8;
        color: #a96800;
    }

    .type-badge.default,
    .status-badge.default {
        background: #f1f4f8;
        color: #68758a;
    }

    .status-badge.approved {
        background: #edf9f2;
        color: #188653;
    }

    .status-badge.rejected {
        background: #fff0f1;
        color: #c23948;
    }

    .status-badge.waiting {
        background: #fff5e8;
        color: #a96800;
    }

    .evidence-list {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .evidence-button {
        min-height: 29px;
        padding: 5px 8px;
        border: 1px solid #d8e3f3;
        border-radius: 7px;
        background: #f7faff;
        color: #1769d1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        text-decoration: none;
        font-size: 7px;
        font-weight: 800;
        white-space: nowrap;
        transition: .2s ease;
    }

    .evidence-button:hover {
        background: #edf4ff;
        border-color: #c4d7f2;
        color: #1259b4;
    }

    .no-evidence {
        color: #a0a9b6;
        font-size: 8px;
        white-space: nowrap;
    }

    /* TOMBOL AKSI */
    .action-buttons {
        min-width: 105px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .action-buttons form {
        margin: 0;
    }

    .action-button {
        width: 100%;
        min-height: 29px;
        padding: 6px 9px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        font-family: inherit;
        font-size: 8px;
        font-weight: 800;
        cursor: pointer;
        transition: .2s ease;
        white-space: nowrap;
    }

    .action-button.approve {
        border: 1px solid #ccebd9;
        background: #effaf3;
        color: #188653;
    }

    .action-button.approve:hover {
        background: #dff5e8;
    }

    .action-button.reject {
        border: 1px solid #f2cfd2;
        background: #fff3f4;
        color: #c23948;
    }

    .action-button.reject:hover {
        background: #ffe5e8;
    }

    .action-finished {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 8px;
        border-radius: 7px;
        font-size: 7px;
        font-weight: 800;
        white-space: nowrap;
    }

    .approved-finished {
        background: #edf9f2;
        color: #188653;
    }

    .rejected-finished {
        background: #fff0f1;
        color: #c23948;
    }

    .empty-cell {
        padding: 0 !important;
    }

    .empty-state {
        min-height: 270px;
        padding: 35px 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        text-align: center;
    }

    .empty-icon {
        width: 55px;
        height: 55px;
        margin-bottom: 12px;
        border-radius: 14px;
        background: #edf4ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .empty-state h3 {
        margin: 0;
        color: #26354d;
        font-size: 13px;
        font-weight: 800;
    }

    .empty-state p {
        margin: 6px 0 0;
        color: #8a95a6;
        font-size: 10px;
    }

    .information-card {
        margin-top: 16px;
        padding: 14px 16px;
        border: 1px solid #dce7f8;
        border-radius: 11px;
        background: #f6f9ff;
        display: flex;
        align-items: flex-start;
        gap: 11px;
    }

    .information-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 9px;
        background: #e8f0ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .information-content strong {
        display: block;
        color: #34445e;
        font-size: 10px;
        font-weight: 800;
    }

    .information-content p {
        max-width: 850px;
        margin: 4px 0 0;
        color: #7f8a9c;
        font-size: 8px;
        line-height: 1.6;
    }

    /* MODAL TOLAK */
    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        padding: 20px;
        background: rgba(15, 23, 42, .48);
        display: none;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-box {
        width: 100%;
        max-width: 430px;
        padding: 22px;
        border: 1px solid #e1e7ef;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 15px 45px rgba(15, 23, 42, .18);
    }

    .modal-heading {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 20px;
    }

    .modal-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .reject-icon {
        background: #fff0f1;
        color: #c23948;
    }

    .modal-heading h3 {
        margin: 0;
        color: #26354d;
        font-size: 14px;
        font-weight: 800;
    }

    .modal-heading p {
        margin: 4px 0 0;
        color: #8a95a6;
        font-size: 10px;
    }

    .modal-field textarea {
        width: 100%;
        padding: 11px;
        border: 1px solid #dce2eb;
        border-radius: 8px;
        outline: none;
        resize: vertical;
        font-family: inherit;
        font-size: 10px;
        color: #334158;
    }

    .modal-field textarea:focus {
        border-color: #1769d1;
        box-shadow: 0 0 0 3px rgba(23, 105, 209, .08);
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 16px;
    }

    @media (max-width: 1100px) {
        .izin-filter-form {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .filter-actions {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 800px) {
        .bk-page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .bk-header-info {
            width: 100%;
        }

        .izin-filter-form {
            grid-template-columns: 1fr 1fr;
        }

        .filter-actions {
            grid-column: 1 / -1;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .result-heading {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 550px) {
        .izin-filter-form {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            grid-column: auto;
        }

        .filter-actions .bk-primary-button,
        .filter-actions .bk-secondary-button {
            flex: 1;
        }

        .information-card {
            align-items: flex-start;
        }

        .modal-box {
            padding: 17px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function bukaModalTolak(id) {
        const modal = document.getElementById('modalTolak');
        const form = document.getElementById('formTolak');
        const catatan = document.getElementById('catatanbk');

        form.action = "{{ url('/bk/izin') }}/" + id + "/tolak";
        catatan.value = '';

        modal.classList.add('show');
    }

    function tutupModalTolak() {
        document.getElementById('modalTolak').classList.remove('show');
    }

    document.getElementById('modalTolak').addEventListener('click', function(event) {
        if (event.target === this) {
            tutupModalTolak();
        }
    });
</script>
@endpush

@endsection