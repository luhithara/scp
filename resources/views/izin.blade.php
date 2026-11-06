@extends('layouts.app')

@section('content')

@php
    $dataIzin = $riwayatIzin ?? $izin ?? collect();

    $namaSiswa = $siswa->nama
        ?? auth()->user()->name
        ?? '-';

    $totalIzin = $dataIzin->count();
    $menunggu = $dataIzin->where('status', 'menunggu')->count();
    $disetujui = $dataIzin->where('status', 'disetujui')->count();
    $ditolak = $dataIzin->where('status', 'ditolak')->count();
@endphp

<div class="izin-page">

    <div class="izin-header">
        <div>
            <h2>Pengajuan Izin</h2>
            <p>Ajukan izin atau sakit dan pantau status pengajuan kamu.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert success">
            <span>✓</span>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert danger">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- RINGKASAN --}}
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-icon blue">📋</div>
            <div>
                <span>Total Pengajuan</span>
                <strong>{{ $totalIzin }}</strong>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon yellow">⏳</div>
            <div>
                <span>Menunggu</span>
                <strong>{{ $menunggu }}</strong>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon green">✓</div>
            <div>
                <span>Disetujui</span>
                <strong>{{ $disetujui }}</strong>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon red">✕</div>
            <div>
                <span>Ditolak</span>
                <strong>{{ $ditolak }}</strong>
            </div>
        </div>
    </div>

    <div class="izin-grid">

        {{-- FORM PENGAJUAN --}}
        <div class="panel form-panel">

            <div class="panel-heading">
                <div>
                    <h3>Ajukan Izin</h3>
                    <p>Lengkapi informasi pengajuan kamu.</p>
                </div>
                <div class="heading-icon">✎</div>
            </div>

            <form
                action="{{ route('izin.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="student-box">
                    <span>Nama Siswa</span>
                    <strong>{{ $namaSiswa }}</strong>

                    @if(!empty($siswa->nisn))
                        <small>NISN: {{ $siswa->nisn }}</small>
                    @endif

                    @if(!empty($siswa->namakelas))
                        <small>Kelas: {{ $siswa->namakelas }}</small>
                    @endif
                </div>

                <div class="form-group">
                    <label for="jenis">Jenis Pengajuan <span>*</span></label>

                    <select name="jenis" id="jenis" required>
                        <option value="">Pilih jenis pengajuan</option>
                        <option value="izin" {{ old('jenis') === 'izin' ? 'selected' : '' }}>
                            Izin
                        </option>
                        <option value="sakit" {{ old('jenis') === 'sakit' ? 'selected' : '' }}>
                            Sakit
                        </option>
                    </select>

                    @error('jenis')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alasan">Alasan Pengajuan <span>*</span></label>

                    <textarea
                        name="alasan"
                        id="alasan"
                        rows="5"
                        maxlength="1000"
                        placeholder="Jelaskan alasan izin atau sakit kamu..."
                        required
                    >{{ old('alasan') }}</textarea>

                    @error('alasan')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="bukti">Lampiran Bukti</label>

                    <input
                        type="file"
                        name="bukti"
                        id="bukti"
                        accept=".jpg,.jpeg,.png,.pdf"
                    >

                    <small class="file-info">
                        JPG, JPEG, PNG, atau PDF. Maksimal 5 MB.
                    </small>

                    @error('bukti')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <span>＋</span> Kirim Pengajuan
                    </button>
                </div>
            </form>

        </div>

        {{-- RIWAYAT PENGAJUAN --}}
        <div class="panel history-panel">

            <div class="panel-heading">
                <div>
                    <h3>Riwayat Pengajuan</h3>
                    <p>Pantau perkembangan pengajuan izin kamu.</p>
                </div>
                <div class="heading-icon">▤</div>
            </div>

            @if($dataIzin->count() > 0)

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Alasan</th>
                                <th>Bukti</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($dataIzin as $item)
                                <tr>
                                    <td class="date-cell">
                                        {{ !empty($item->tanggal)
                                            ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y')
                                            : '-' }}
                                    </td>

                                    <td>
                                        @if(($item->jenis ?? '') === 'izin')
                                            <span class="type-badge izin">Izin</span>
                                        @elseif(($item->jenis ?? '') === 'sakit')
                                            <span class="type-badge sakit">Sakit</span>
                                        @else
                                            <span class="type-badge">
                                                {{ ucfirst($item->jenis ?? '-') }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="reason-text">
                                            {{ $item->alasan ?? '-' }}
                                        </div>
                                    </td>

                                    <td>
                                        @if(!empty($item->bukti))
                                            <a
                                                href="{{ asset('storage/' . $item->bukti) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn-proof"
                                            >
                                                Lihat Bukti
                                            </a>
                                        @else
                                            <span class="muted-text">Tidak ada</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if(($item->status ?? '') === 'menunggu')
                                            <span class="status-badge waiting">Menunggu</span>
                                        @elseif(($item->status ?? '') === 'disetujui')
                                            <span class="status-badge approved">Disetujui</span>
                                        @elseif(($item->status ?? '') === 'ditolak')
                                            <span class="status-badge rejected">Ditolak</span>
                                        @else
                                            <span class="status-badge">
                                                {{ ucfirst($item->status ?? '-') }}
                                            </span>
                                        @endif

                                        @if(($item->status ?? '') === 'ditolak' && !empty($item->catatanbk))
                                            <div class="rejection-note">
                                                <strong>Catatan:</strong>
                                                {{ $item->catatanbk }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else

                <div class="empty-state">
                    <div class="empty-icon">📋</div>
                    <h4>Belum Ada Pengajuan</h4>
                    <p>Riwayat pengajuan izin atau sakit akan muncul di sini.</p>
                </div>

            @endif

        </div>

    </div>

</div>

<style>
.izin-page {
    width: 100%;
    color: #1e293b;
}

.izin-header {
    margin-bottom: 22px;
}

.izin-header h2 {
    margin: 0;
    font-size: 25px;
    font-weight: 700;
    color: #1e293b;
}

.izin-header p {
    margin: 7px 0 0;
    color: #64748b;
    font-size: 14px;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.summary-card {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 17px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 13px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
}

.summary-icon {
    width: 43px;
    height: 43px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.summary-icon.blue {
    background: #dbeafe;
    color: #1d4ed8;
}

.summary-icon.yellow {
    background: #fef3c7;
    color: #a16207;
}

.summary-icon.green {
    background: #dcfce7;
    color: #15803d;
}

.summary-icon.red {
    background: #fee2e2;
    color: #b91c1c;
}

.summary-card span {
    display: block;
    font-size: 12px;
    color: #64748b;
    margin-bottom: 4px;
}

.summary-card strong {
    display: block;
    font-size: 23px;
    color: #1e293b;
}

.izin-grid {
    display: grid;
    grid-template-columns: minmax(300px, 370px) minmax(0, 1fr);
    gap: 20px;
    align-items: start;
}

.panel {
    min-width: 0;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 15px;
    padding: 21px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, .035);
}

.panel-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.panel-heading h3 {
    margin: 0;
    font-size: 17px;
    color: #1e293b;
}

.panel-heading p {
    margin: 6px 0 0;
    font-size: 12px;
    color: #64748b;
}

.heading-icon {
    width: 37px;
    height: 37px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eff6ff;
    color: #2563eb;
    border-radius: 10px;
    font-size: 19px;
    flex-shrink: 0;
}

.student-box {
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 14px;
    margin-bottom: 19px;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    border-radius: 10px;
}

.student-box span,
.student-box small {
    color: #64748b;
    font-size: 12px;
}

.student-box strong {
    color: #1e40af;
    font-size: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 7px;
    margin-bottom: 17px;
}

.form-group label {
    color: #334155;
    font-size: 13px;
    font-weight: 600;
}

.form-group label span {
    color: #dc2626;
}

.form-group input,
.form-group select,
.form-group textarea {
    box-sizing: border-box;
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 9px;
    background: #fff;
    color: #1e293b;
    font-family: inherit;
    font-size: 13px;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
}

.form-group textarea {
    resize: vertical;
    min-height: 110px;
}

.file-info,
.field-error {
    font-size: 11px;
    line-height: 1.5;
}

.file-info {
    color: #64748b;
}

.field-error {
    color: #dc2626;
}

.form-actions {
    margin-top: 20px;
}

.btn-submit {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 12px 16px;
    border: none;
    border-radius: 9px;
    background: #2563eb;
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s;
}

.btn-submit:hover {
    background: #1d4ed8;
}

.table-wrap {
    width: 100%;
    overflow-x: auto;
}

.data-table {
    width: 100%;
    min-width: 650px;
    border-collapse: collapse;
}

.data-table th {
    padding: 13px 12px;
    text-align: left;
    white-space: nowrap;
    background: #f1f5f9;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
}

.data-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    font-size: 12px;
    vertical-align: top;
}

.data-table tbody tr:hover {
    background: #f8fafc;
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

.date-cell {
    white-space: nowrap;
    font-weight: 600;
}

.reason-text {
    max-width: 210px;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.type-badge,
.status-badge {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.type-badge.izin {
    background: #dbeafe;
    color: #1d4ed8;
}

.type-badge.sakit {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.waiting {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.approved {
    background: #dcfce7;
    color: #166534;
}

.status-badge.rejected {
    background: #fee2e2;
    color: #991b1b;
}

.status-badge:not(.waiting):not(.approved):not(.rejected) {
    background: #f1f5f9;
    color: #475569;
}

.btn-proof {
    display: inline-block;
    padding: 7px 10px;
    border-radius: 7px;
    background: #eff6ff;
    color: #1d4ed8;
    text-decoration: none;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.btn-proof:hover {
    background: #dbeafe;
}

.muted-text {
    color: #94a3b8;
}

.rejection-note {
    max-width: 160px;
    margin-top: 8px;
    padding: 8px;
    border-radius: 7px;
    background: #fff1f2;
    color: #be123c;
    font-size: 11px;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.empty-state {
    padding: 55px 15px;
    text-align: center;
    color: #64748b;
}

.empty-icon {
    margin-bottom: 12px;
    font-size: 38px;
}

.empty-state h4 {
    margin: 0 0 7px;
    color: #334155;
    font-size: 15px;
}

.empty-state p {
    margin: 0;
    font-size: 12px;
}

.alert {
    padding: 13px 16px;
    margin-bottom: 18px;
    border-radius: 10px;
    font-size: 13px;
}

.alert.success {
    display: flex;
    gap: 9px;
    background: #dcfce7;
    border: 1px solid #bbf7d0;
    color: #166534;
}

.alert.danger {
    background: #fee2e2;
    border: 1px solid #fecaca;
    color: #991b1b;
}

.alert ul {
    margin: 7px 0 0;
    padding-left: 20px;
}

@media (max-width: 1100px) {
    .izin-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 800px) {
    .summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 500px) {
    .izin-header h2 {
        font-size: 22px;
    }

    .summary-grid {
        gap: 10px;
    }

    .summary-card {
        padding: 12px;
        gap: 9px;
    }

    .summary-icon {
        width: 36px;
        height: 36px;
        font-size: 17px;
    }

    .summary-card strong {
        font-size: 20px;
    }

    .summary-card span {
        font-size: 11px;
    }

    .panel {
        padding: 15px;
    }
}
</style>

@endsection