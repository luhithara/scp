@extends('layouts.app')

@section('content')

<div class="presensi-page">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h2>Riwayat Presensi</h2>
            <p>Rekap kehadiran dan catatan presensi kamu.</p>
        </div>

        <div class="siswa-info">
            <div class="siswa-label">Nama Siswa</div>
            <div class="siswa-nama">{{ $siswa->nama ?? '-' }}</div>
            <div class="siswa-nisn">NISN: {{ $siswa->nisn ?? '-' }}</div>
        </div>
    </div>

    {{-- RINGKASAN --}}
    @php
        $dataHalaman = $presensi->getCollection();

        $total = $presensi->total();
        $hadir = $dataHalaman->where('status', 'hadir')->count();
        $sakit = $dataHalaman->where('status', 'sakit')->count();
        $izin = $dataHalaman->where('status', 'izin')->count();

        $alpa = $dataHalaman->filter(function ($item) {
            return in_array(strtolower($item->status ?? ''), ['alpa', 'alpha']);
        })->count();
    @endphp

    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-icon blue">📋</div>
            <div>
                <div class="summary-label">Total Presensi</div>
                <div class="summary-value">{{ $total }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon green">✓</div>
            <div>
                <div class="summary-label">Hadir</div>
                <div class="summary-value">{{ $hadir }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon yellow">✉</div>
            <div>
                <div class="summary-label">Izin</div>
                <div class="summary-value">{{ $izin }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon orange">＋</div>
            <div>
                <div class="summary-label">Sakit</div>
                <div class="summary-value">{{ $sakit }}</div>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-icon red">!</div>
            <div>
                <div class="summary-label">Alpa</div>
                <div class="summary-value">{{ $alpa }}</div>
            </div>
        </div>

    </div>

    <p class="summary-note">
        Jumlah hadir, izin, sakit, dan alpa dihitung berdasarkan data pada halaman yang sedang dibuka.
    </p>

    {{-- TABEL PRESENSI --}}
    <div class="panel">

        <div class="panel-header">
            <div>
                <h3>Daftar Riwayat Kehadiran</h3>
                <p>Berikut adalah catatan presensi yang telah tercatat.</p>
            </div>

            <div class="jumlah-data">
                {{ $total }} Data
            </div>
        </div>

        @if($presensi->count())

            <div class="table-wrap">
                <table class="presensi-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Hari</th>
                            <th>Tanggal</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($presensi as $index => $item)

                            @php
                                $tanggalRaw = $item->tanggal_presensi
                                    ?? $item->waktu
                                    ?? null;

                                $tanggal = $tanggalRaw
                                    ? \Carbon\Carbon::parse($tanggalRaw)
                                    : null;

                                $status = strtolower(trim($item->status ?? ''));

                                $statusClass = match($status) {
                                    'hadir' => 'success',
                                    'izin' => 'info',
                                    'sakit' => 'warning',
                                    'alpa', 'alpha' => 'danger',
                                    default => 'secondary'
                                };

                                $namaStatus = match($status) {
                                    'hadir' => 'Hadir',
                                    'izin' => 'Izin',
                                    'sakit' => 'Sakit',
                                    'alpa', 'alpha' => 'Alpa',
                                    default => ucfirst($status ?: '-')
                                };

                                $keterangan = trim((string) ($item->keterangan ?? ''));
                            @endphp

                            <tr>
                                <td>{{ $presensi->firstItem() + $index }}</td>

                                <td>
                                    <span class="hari-text">
                                        {{ $tanggal ? $tanggal->translatedFormat('l') : '-' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $tanggal ? $tanggal->translatedFormat('d F Y') : '-' }}
                                </td>

                                <td>
                                    <strong class="mapel-text">
                                        {{ $item->namamapel ?? '-' }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $item->namaguru ?? '-' }}
                                </td>

                                <td>
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $namaStatus }}
                                    </span>
                                </td>

                                <td>
                                    @if($keterangan !== '')
                                        <div class="keterangan">
                                            {{ $keterangan }}
                                        </div>
                                    @else
                                        <span class="tidak-ada">
                                            Tidak ada keterangan
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($presensi->hasPages())
                <div class="pagination-wrapper">
                    {{ $presensi->links() }}
                </div>
            @endif

        @else

            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3>Belum Ada Riwayat Presensi</h3>
                <p>Catatan kehadiran kamu akan muncul di halaman ini setelah guru mengisi presensi.</p>
            </div>

        @endif

    </div>

</div>

<style>

/* HALAMAN */
.presensi-page {
    width: 100%;
}

/* HEADER */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 22px;
}

.page-header h2 {
    margin: 0;
    font-size: 26px;
    color: #172554;
}

.page-header p {
    margin: 6px 0 0;
    font-size: 14px;
    color: #64748b;
}

/* IDENTITAS SISWA */
.siswa-info {
    min-width: 190px;
    padding: 12px 17px;
    background: white;
    border: 1px solid #dbeafe;
    border-radius: 12px;
}

.siswa-label {
    font-size: 11px;
    color: #64748b;
}

.siswa-nama {
    margin-top: 3px;
    font-size: 15px;
    font-weight: 700;
    color: #1e40af;
}

.siswa-nisn {
    margin-top: 3px;
    font-size: 11px;
    color: #94a3b8;
}

/* RINGKASAN */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 10px;
}

.summary-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 13px;
    box-shadow: 0 4px 15px rgba(15, 23, 42, .03);
}

.summary-icon {
    width: 39px;
    height: 39px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    font-size: 19px;
    font-weight: 700;
}

.summary-icon.blue {
    background: #dbeafe;
    color: #1d4ed8;
}

.summary-icon.green {
    background: #dcfce7;
    color: #15803d;
}

.summary-icon.yellow {
    background: #fef3c7;
    color: #b45309;
}

.summary-icon.orange {
    background: #ffedd5;
    color: #c2410c;
}

.summary-icon.red {
    background: #fee2e2;
    color: #b91c1c;
}

.summary-label {
    font-size: 11px;
    color: #64748b;
}

.summary-value {
    margin-top: 3px;
    font-size: 22px;
    line-height: 1.2;
    font-weight: 700;
    color: #1e293b;
}

.summary-note {
    margin: 0 0 20px;
    font-size: 11px;
    color: #94a3b8;
}

/* PANEL */
.panel {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 5px 20px rgba(15, 23, 42, .04);
}

/* PANEL HEADER */
.panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 20px;
}

.panel-header h3 {
    margin: 0;
    font-size: 17px;
    color: #1e293b;
}

.panel-header p {
    margin: 5px 0 0;
    font-size: 13px;
    color: #64748b;
}

.jumlah-data {
    padding: 7px 12px;
    background: #eff6ff;
    color: #2563eb;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

/* TABEL */
.table-wrap {
    width: 100%;
    overflow-x: auto;
}

.presensi-table {
    width: 100%;
    min-width: 850px;
    border-collapse: collapse;
}

.presensi-table th {
    padding: 13px 12px;
    background: #f1f5f9;
    color: #475569;
    text-align: left;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.presensi-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #475569;
    font-size: 13px;
    vertical-align: middle;
}

.presensi-table tbody tr:last-child td {
    border-bottom: none;
}

.presensi-table tbody tr:hover {
    background: #f8fafc;
}

.hari-text {
    font-weight: 600;
    color: #1e3a8a;
}

.mapel-text {
    color: #1e293b;
}

/* STATUS */
.status-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.status-badge.success {
    background: #dcfce7;
    color: #166534;
}

.status-badge.info {
    background: #dbeafe;
    color: #1d4ed8;
}

.status-badge.warning {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.danger {
    background: #fee2e2;
    color: #991b1b;
}

.status-badge.secondary {
    background: #f1f5f9;
    color: #475569;
}

/* KETERANGAN */
.keterangan {
    max-width: 230px;
    line-height: 1.5;
    color: #475569;
    overflow-wrap: anywhere;
}

.tidak-ada {
    color: #94a3b8;
    font-style: italic;
    font-size: 12px;
}

/* PAGINATION */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 24px;
    padding-top: 18px;
    border-top: 1px solid #e2e8f0;
}

.pagination-wrapper nav {
    display: flex;
    justify-content: center;
}

.pagination-wrapper svg {
    width: 18px;
    height: 18px;
}

.pagination-wrapper a,
.pagination-wrapper span {
    font-size: 13px;
}

/* KOSONG */
.empty-state {
    text-align: center;
    padding: 55px 20px;
}

.empty-icon {
    margin-bottom: 12px;
    font-size: 42px;
}

.empty-state h3 {
    margin: 0 0 7px;
    font-size: 16px;
    color: #334155;
}

.empty-state p {
    max-width: 420px;
    margin: auto;
    color: #64748b;
    font-size: 13px;
    line-height: 1.6;
}

/* RESPONSIVE */
@media(max-width: 1100px) {
    .summary-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media(max-width: 700px) {
    .page-header {
        align-items: stretch;
        flex-direction: column;
    }

    .page-header h2 {
        font-size: 22px;
    }

    .siswa-info {
        width: 100%;
    }

    .summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 9px;
    }

    .summary-card {
        padding: 12px;
        gap: 9px;
    }

    .summary-icon {
        width: 34px;
        height: 34px;
    }

    .summary-value {
        font-size: 19px;
    }

    .panel {
        padding: 15px;
    }

    .panel-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .presensi-table {
        min-width: 800px;
    }
}

@media(max-width: 380px) {
    .summary-grid {
        grid-template-columns: 1fr;
    }
}

</style>

@endsection