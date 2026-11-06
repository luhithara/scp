
@extends('layouts.app')

@section('content')

<div class="pagehead">
    <h1>Izin Siswa</h1>
    <p>Kelola dan pantau pengajuan izin atau sakit dari siswa.</p>
</div>

@if(session('success'))
    <div class="izin-alert success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="izin-alert danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
@endif

<div class="izin-panel">

    <div class="izin-heading">
        <div>
            <h2>Daftar Pengajuan Izin</h2>
            <p>Informasi pengajuan izin dan sakit siswa.</p>
        </div>

        <span class="izin-total">
            <i class="fas fa-clipboard-list"></i>
            {{ $izin->count() }} Pengajuan
        </span>
    </div>

    @forelse($izin as $item)

        <div class="izin-card">

            <div class="izin-main">

                <div class="izin-avatar">
                    {{ strtoupper(substr($item->namasiswa ?? 'S', 0, 1)) }}
                </div>

                <div class="izin-identity">
                    <h3>{{ $item->namasiswa ?? '-' }}</h3>

                    <span class="izin-nisn">
                        NISN: {{ $item->nisn ?? '-' }}
                    </span>

                    <div class="izin-meta">
                        <span>
                            <i class="fas fa-calendar"></i>
                            {{ $item->tanggal ?? '-' }}
                        </span>

                        <span>
                            <i class="fas fa-file-alt"></i>
                            {{ ucfirst($item->jenis ?? '-') }}
                        </span>
                    </div>

                    <div class="izin-reason">
                        <span>Keterangan</span>
                        <p>{{ $item->alasan ?? '-' }}</p>
                    </div>
                </div>

            </div>

            <div class="izin-action">

                <div class="izin-status">

                    @if($item->status === 'menunggu')
                        <span class="status-badge warning">
                            <i class="fas fa-clock"></i>
                            Menunggu Persetujuan Akun Kelas
                        </span>

                    @elseif($item->status === 'disetujui')
                        <span class="status-badge success">
                            <i class="fas fa-check-circle"></i>
                            Disetujui
                        </span>

                    @elseif($item->status === 'ditolak')
                        <span class="status-badge danger">
                            <i class="fas fa-times-circle"></i>
                            Ditolak
                        </span>

                        @if($item->catatanbk)
                            <div class="izin-catatan">
                                <strong>Catatan BK:</strong>
                                {{ $item->catatanbk }}
                            </div>
                        @endif
                    @endif

                </div>

                @if($item->bukti)
                    <a href="{{ asset('storage/' . $item->bukti) }}"
                       target="_blank"
                       class="izin-bukti">
                        <i class="fas fa-paperclip"></i>
                        Lihat Bukti
                    </a>
                @endif

            </div>

        </div>

    @empty

        <div class="izin-empty">
            <div class="empty-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>

            <h3>Belum Ada Pengajuan Izin</h3>
            <p>Pengajuan izin atau sakit dari siswa akan muncul di halaman ini.</p>
        </div>

    @endforelse

</div>

<style>
.izin-panel {
    background: #fff;
    border: 1px solid #e5eaf1;
    border-radius: 12px;
    padding: 24px;
    margin-top: 22px;
}

.izin-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.izin-heading h2 {
    font-size: 18px;
    color: #243247;
    margin: 0 0 6px;
}

.izin-heading p {
    color: #7b8798;
    font-size: 13px;
    margin: 0;
}

.izin-total {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #edf4ff;
    color: #2864c5;
    padding: 9px 13px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.izin-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 22px;
    padding: 20px 16px;
    border: 1px solid #e9edf4;
    border-radius: 10px;
    margin-bottom: 14px;
    transition: .2s;
}

.izin-card:last-child {
    margin-bottom: 0;
}

.izin-card:hover {
    border-color: #bfd3f5;
    background: #fcfdff;
}

.izin-main {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    min-width: 0;
}

.izin-avatar {
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: #edf4ff;
    color: #2864c5;
    font-size: 17px;
    font-weight: 700;
}

.izin-identity {
    min-width: 0;
}

.izin-identity h3 {
    color: #344054;
    font-size: 14px;
    margin: 2px 0 5px;
}

.izin-nisn {
    color: #8994a5;
    font-size: 11px;
}

.izin-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 12px;
}

.izin-meta span {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #718096;
    font-size: 12px;
}

.izin-meta i {
    color: #3974ce;
}

.izin-reason {
    margin-top: 13px;
}

.izin-reason span {
    color: #8994a5;
    font-size: 11px;
    font-weight: 600;
}

.izin-reason p {
    color: #475467;
    font-size: 13px;
    margin: 5px 0 0;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.izin-action {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
    min-width: 190px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 8px 11px;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 600;
    text-align: center;
}

.status-badge.success {
    background: #e8f7ee;
    color: #21834b;
}

.status-badge.warning {
    background: #fff6df;
    color: #a36b08;
}

.status-badge.danger {
    background: #fff0f0;
    color: #c03939;
}

.izin-bukti {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 13px;
    background: #edf4ff;
    color: #2864c5 !important;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none !important;
    transition: .2s;
}

.izin-bukti:hover {
    background: #dceaff;
}

.izin-catatan {
    max-width: 220px;
    color: #9b4545;
    background: #fff5f5;
    border-radius: 7px;
    padding: 9px 11px;
    font-size: 11px;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.izin-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 13px 16px;
    border-radius: 9px;
    margin-top: 15px;
    font-size: 13px;
}

.izin-alert.success {
    background: #e8f7ee;
    color: #21834b;
    border: 1px solid #c8eed6;
}

.izin-alert.danger {
    background: #fff1f1;
    color: #b42318;
    border: 1px solid #ffd1d1;
}

.izin-empty {
    text-align: center;
    padding: 60px 15px;
}

.empty-icon {
    width: 65px;
    height: 65px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 17px;
    background: #f0f4fa;
    color: #91a0b4;
    border-radius: 16px;
    font-size: 27px;
}

.izin-empty h3 {
    color: #344054;
    font-size: 17px;
    margin: 0 0 7px;
}

.izin-empty p {
    color: #8490a2;
    font-size: 13px;
    margin: 0;
}

@media(max-width: 700px) {
    .izin-panel {
        padding: 16px;
    }

    .izin-card {
        flex-direction: column;
        align-items: flex-start;
    }

    .izin-action {
        width: 100%;
        align-items: flex-start;
        min-width: 0;
        padding-left: 59px;
    }

    .izin-catatan {
        max-width: 100%;
    }
}

@media(max-width: 450px) {
    .izin-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .izin-meta {
        flex-direction: column;
        gap: 8px;
    }

    .izin-action {
        padding-left: 0;
    }
}
</style>

@endsection