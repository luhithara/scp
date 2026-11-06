@extends('layouts.app')

@section('title', 'Jurnal Mengajar - SCP')

@section('content')

<div class="jurnal-page">

    <div class="page-header jurnal-header">
        <div>
            <h1>Jurnal Mengajar</h1>
            <p>Kelola jurnal kegiatan mengajar dan bukti pembelajaran.</p>
        </div>

        <a href="{{ route('jurnal.guru.create') }}" class="btn btn-primary jurnal-add-btn">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Jurnal</span>
        </a>
    </div>

    @if(session('success'))
        <div class="jurnal-alert jurnal-alert-success">
            <div class="jurnal-alert-icon">
                <i class="fa-solid fa-check"></i>
            </div>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="jurnal-alert jurnal-alert-danger">
            <div class="jurnal-alert-icon">
                <i class="fa-solid fa-exclamation"></i>
            </div>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- STATISTIK --}}
    <div class="stats-grid jurnal-stats">

        <div class="stat-card">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Total Jurnal</div>
                    <div class="stat-number">{{ $jumlahJurnal }}</div>
                    <div class="stat-description">Seluruh jurnal yang dibuat</div>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-book"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div>
                    <div class="stat-label">Jurnal Hari Ini</div>
                    <div class="stat-number">{{ $jurnalHariIni }}</div>
                    <div class="stat-description">Jurnal pada hari ini</div>
                </div>
                <div class="stat-icon">
                    <i class="fa-regular fa-calendar-days"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-guru-content">
                    <div class="stat-label">Guru</div>
                    <div class="stat-guru-name">
                        {{ $guru->nama ?? $user->name ?? '-' }}
                    </div>
                    <div class="stat-description">Akun guru yang sedang aktif</div>
                </div>
                <div class="stat-icon">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- CARD JURNAL --}}
    <div class="card jurnal-action-card">
        <div class="jurnal-action-content">
            <div class="jurnal-action-icon">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h3>Jurnal Mengajar</h3>
                <p>
                    Tambahkan jurnal sesuai jadwal mengajar Anda.
                    Isi materi, kegiatan pembelajaran, dan catatan.
                </p>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="card jurnal-filter-card">

        <div class="card-header">
            <div>
                <h3>Filter Jurnal</h3>
                <p>Gunakan filter untuk mencari jurnal tertentu.</p>
            </div>
            <div class="filter-header-icon">
                <i class="fa-solid fa-filter"></i>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('jurnal.guru') }}" method="GET" class="jurnal-filter-form">

                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-calendar"></i>
                        <input
                            type="date"
                            id="tanggal"
                            name="tanggal"
                            value="{{ request('tanggal') }}"
                        >
                    </div>
                </div>

                <div class="form-group jadwal-filter">
                    <label for="jadwalid">Jadwal</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-calendar-check"></i>
                        <select id="jadwalid" name="jadwalid">
                            <option value="">Semua Jadwal</option>

                            @foreach($jadwal as $item)
                                <option
                                    value="{{ $item->id }}"
                                    {{ request('jadwalid') == $item->id ? 'selected' : '' }}
                                >
                                    {{ ucfirst($item->hari) }}
                                    -
                                    {{ $item->namamapel ?? '-' }}

                                    @if($item->namakelas ?? null)
                                        - {{ $item->namakelas }}
                                    @endif

                                    ({{ $item->jamMulai }} - {{ $item->jamSelesai }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>

                    <a href="{{ route('jurnal.guru') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-rotate-left"></i>
                        Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- RIWAYAT JURNAL --}}
    <div class="card jurnal-history-card">

        <div class="card-header jurnal-history-header">
            <div>
                <h3>Riwayat Jurnal</h3>
                <p>Daftar jurnal mengajar yang sudah dibuat.</p>
            </div>

            <div class="history-count">
                <span>{{ $jurnal->total() }}</span>
                jurnal
            </div>
        </div>

        <div class="card-body jurnal-history-body">

            @forelse($jurnal as $item)

                @php
                    $jurnalHariIniItem = \Carbon\Carbon::parse($item->tanggal)
                        ->toDateString() === ($tanggalHariIni ?? now()->toDateString());
                @endphp

                <div class="jurnal-item">

                    {{-- TANGGAL --}}
                    <div class="jurnal-date">
                        <div class="jurnal-date-day">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}
                        </div>
                        <div class="jurnal-date-month">
                            {{ strtoupper(\Carbon\Carbon::parse($item->tanggal)->format('M')) }}
                        </div>
                    </div>

                    {{-- ISI JURNAL --}}
                    <div class="jurnal-main">

                        <div class="jurnal-title-row">
                            <div>
                                <h4>{{ $item->namamapel ?? 'Mata Pelajaran' }}</h4>

                                <div class="jurnal-meta">
                                    <span>
                                        <i class="fa-regular fa-calendar"></i>
                                        {{ ucfirst($item->hari ?? '-') }}
                                    </span>

                                    <span>
                                        <i class="fa-regular fa-clock"></i>
                                        {{ $item->jamMulai ?? '-' }}
                                        -
                                        {{ $item->jamSelesai ?? '-' }}
                                    </span>

                                    @if($item->namakelas)
                                        <span>
                                            <i class="fa-solid fa-users"></i>
                                            {{ $item->namakelas }}
                                        </span>
                                    @endif

                                    @if($item->namajurusan)
                                        <span>
                                            <i class="fa-solid fa-layer-group"></i>
                                            {{ $item->namajurusan }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="jurnal-status">
                                <i class="fa-solid fa-circle-check"></i>
                                Tersimpan
                            </div>
                        </div>

                        <div class="jurnal-detail">
                            <div class="detail-label">
                                <i class="fa-solid fa-book-open"></i>
                                Materi
                            </div>
                            <div class="detail-text">
                                {{ $item->materi ?: '-' }}
                            </div>
                        </div>

                        @if($item->kegiatan)
                            <div class="jurnal-detail">
                                <div class="detail-label">
                                    <i class="fa-solid fa-list-check"></i>
                                    Kegiatan
                                </div>
                                <div class="detail-text">
                                    {{ $item->kegiatan }}
                                </div>
                            </div>
                        @endif

                        @if($item->catatan)
                            <div class="jurnal-detail jurnal-catatan">
                                <div class="detail-label">
                                    <i class="fa-regular fa-note-sticky"></i>
                                    Catatan
                                </div>
                                <div class="detail-text">
                                    {{ $item->catatan }}
                                </div>
                            </div>
                        @endif

                    </div>

                    {{-- FOTO DAN TOMBOL AKSI --}}
                    <div class="jurnal-side">

                        @if($item->fotobukti)
                            <a
                                href="{{ asset('storage/' . $item->fotobukti) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="jurnal-photo-link"
                            >
                                <img
                                    src="{{ asset('storage/' . $item->fotobukti) }}"
                                    alt="Foto bukti jurnal"
                                    class="jurnal-photo"
                                >

                                <span>
                                    <i class="fa-solid fa-eye"></i>
                                    Lihat foto
                                </span>
                            </a>
                        @else
                            <div class="no-photo">
                                <i class="fa-regular fa-image"></i>
                                <span>Tidak ada foto</span>
                            </div>
                        @endif

                        @if($jurnalHariIniItem)

                            {{-- HANYA JURNAL HARI INI YANG BISA DIEDIT --}}
                            <a
                                href="{{ route('jurnal.guru.edit', $item->id) }}"
                                class="btn btn-primary jurnal-edit-btn"
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                                Edit
                            </a>

                            {{-- HANYA JURNAL HARI INI YANG BISA DIHAPUS --}}
                            <form
                                action="{{ route('jurnal.guru.destroy', $item->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus jurnal ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger jurnal-delete-btn"
                                >
                                    <i class="fa-regular fa-trash-can"></i>
                                    Hapus
                                </button>
                            </form>

                        @else

                            <div class="jurnal-locked">
                                <i class="fa-solid fa-lock"></i>
                                Tidak dapat diubah
                            </div>

                        @endif

                    </div>

                </div>

            @empty

                <div class="jurnal-empty">
                    <div class="empty-icon">
                        <i class="fa-regular fa-clipboard"></i>
                    </div>
                    <h3>Belum ada jurnal</h3>
                    <p>Belum ada jurnal mengajar yang dibuat.</p>
                </div>

            @endforelse

        </div>

        {{-- PAGINATION --}}
        @if($jurnal->hasPages())
            <div class="jurnal-pagination">
                {{ $jurnal->links() }}
            </div>
        @endif

    </div>

</div>


@push('styles')
<style>

.jurnal-page {
    width: 100%;
    max-width: 1280px;
    margin: 0 auto;
}

.jurnal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 22px;
}

.jurnal-header h1 {
    margin: 0;
}

.jurnal-header p {
    margin-top: 7px;
}

.jurnal-add-btn {
    flex-shrink: 0;
}

.jurnal-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 13px 16px;
    border-radius: 10px;
    margin-bottom: 16px;
    border: 1px solid;
    font-size: 12px;
    font-weight: 600;
}

.jurnal-alert-success {
    background: #eefaf3;
    border-color: #ccebd9;
    color: #187044;
}

.jurnal-alert-danger {
    background: #fff1f2;
    border-color: #f4cdd2;
    color: #b42332;
}

.jurnal-alert-icon {
    width: 28px;
    height: 28px;
    min-width: 28px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.75);
}

.jurnal-stats {
    grid-template-columns: repeat(3, minmax(0, 1fr));
    margin-bottom: 18px;
}

.jurnal-stats .stat-card {
    min-height: 118px;
}

.jurnal-stats .stat-card-top {
    height: 100%;
    align-items: center;
}

.jurnal-stats .stat-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 12px;
    font-size: 17px;
}

.jurnal-stats .stat-number {
    margin-top: 7px;
    font-size: 25px;
    line-height: 1.1;
}

.stat-description {
    margin-top: 6px;
    color: var(--muted);
    font-size: 10px;
}

.stat-guru-name {
    margin-top: 7px;
    color: var(--text);
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-size: 15px;
    font-weight: 800;
    line-height: 1.3;
}

.jurnal-action-card {
    padding: 17px 20px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.jurnal-action-content {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.jurnal-action-icon {
    width: 43px;
    height: 43px;
    min-width: 43px;
    border-radius: 11px;
    background: var(--blue-soft);
    color: var(--blue);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.jurnal-action-content h3 {
    margin: 0;
    font-size: 14px;
    font-weight: 800;
}

.jurnal-action-content p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

.jurnal-filter-card {
    margin-bottom: 18px;
}

.filter-header-icon {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: var(--blue-soft);
    color: var(--blue);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.jurnal-filter-form {
    display: grid;
    grid-template-columns: 190px minmax(260px, 1fr) auto;
    align-items: end;
    gap: 13px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    color: var(--text);
    font-size: 11px;
    font-weight: 700;
}

.input-wrapper {
    position: relative;
}

.input-wrapper > i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #8b96a8;
    font-size: 12px;
    pointer-events: none;
    z-index: 2;
}

.input-wrapper input,
.input-wrapper select {
    width: 100%;
    height: 40px;
    padding: 0 12px 0 35px;
    border: 1px solid #dce2eb;
    border-radius: 9px;
    background: #fff;
    color: var(--text);
    font-family: inherit;
    font-size: 11px;
    outline: none;
    transition: .2s ease;
}

.input-wrapper select {
    cursor: pointer;
}

.input-wrapper input:focus,
.input-wrapper select:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(37,99,235,.08);
}

.filter-buttons {
    display: flex;
    gap: 8px;
}

.filter-buttons .btn {
    white-space: nowrap;
}

.jurnal-history-card {
    overflow: hidden;
}

.jurnal-history-header {
    min-height: 72px;
}

.history-count {
    padding: 7px 10px;
    border-radius: 8px;
    background: #f5f7fb;
    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.history-count span {
    color: var(--blue);
    font-size: 12px;
    margin-right: 3px;
}

.jurnal-history-body {
    padding: 0;
}

.jurnal-item {
    display: grid;
    grid-template-columns: 58px minmax(0, 1fr) 125px;
    gap: 18px;
    padding: 20px;
    border-bottom: 1px solid var(--line);
    transition: background .2s ease;
}

.jurnal-item:last-child {
    border-bottom: none;
}

.jurnal-item:hover {
    background: #fbfcfe;
}

.jurnal-date {
    width: 58px;
    height: 64px;
    border-radius: 11px;
    background: var(--blue-soft);
    border: 1px solid #dbe8ff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--blue);
}

.jurnal-date-day {
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-size: 20px;
    line-height: 1;
    font-weight: 800;
}

.jurnal-date-month {
    margin-top: 5px;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .5px;
}

.jurnal-main {
    min-width: 0;
}

.jurnal-title-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 15px;
}

.jurnal-title-row h4 {
    margin: 0;
    color: var(--text);
    font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 800;
}

.jurnal-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 7px;
    color: var(--muted);
    font-size: 10px;
}

.jurnal-meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.jurnal-meta i {
    color: var(--blue);
}

.jurnal-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 8px;
    border-radius: 7px;
    background: #edf9f2;
    color: #198754;
    font-size: 9px;
    font-weight: 700;
    white-space: nowrap;
}

.jurnal-detail {
    margin-top: 13px;
    padding: 10px 12px;
    border-radius: 9px;
    background: #f8fafc;
    border: 1px solid #edf0f4;
}

.detail-label {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
    color: var(--text);
    font-size: 10px;
    font-weight: 800;
}

.detail-label i {
    color: var(--blue);
}

.detail-text {
    color: #596579;
    font-size: 11px;
    line-height: 1.55;
    word-break: break-word;
}

.jurnal-catatan {
    background: #fffdf5;
    border-color: #f1e7bd;
}

.jurnal-catatan .detail-label i {
    color: #a67c00;
}

/* FOTO DAN TOMBOL AKSI */

.jurnal-side {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.jurnal-photo-link {
    display: block;
    text-decoration: none;
    text-align: center;
}

.jurnal-photo {
    display: block;
    width: 115px;
    height: 82px;
    object-fit: cover;
    border-radius: 9px;
    border: 1px solid var(--line);
}

.jurnal-photo-link span {
    display: block;
    margin-top: 5px;
    color: var(--blue);
    font-size: 9px;
    font-weight: 700;
}

.no-photo {
    width: 115px;
    height: 82px;
    border-radius: 9px;
    background: #f8fafc;
    border: 1px dashed #dce2eb;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5px;
    color: #9aa4b2;
    font-size: 9px;
}

.no-photo i {
    font-size: 18px;
}

.jurnal-edit-btn {
    min-height: 34px;
    width: 115px;
    padding: 7px 10px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
}

.jurnal-delete-btn {
    min-height: 34px;
    width: 115px;
    padding: 7px 10px;
    font-size: 10px;
}

.jurnal-side form {
    width: 115px;
}

.jurnal-locked {
    width: 115px;
    min-height: 34px;
    padding: 7px 5px;
    border-radius: 8px;
    background: #f1f3f6;
    color: #7b8493;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    font-size: 9px;
    font-weight: 700;
    text-align: center;
}

.jurnal-empty {
    padding: 60px 20px;
    text-align: center;
}

.empty-icon {
    width: 58px;
    height: 58px;
    margin: 0 auto 14px;
    border-radius: 14px;
    background: var(--blue-soft);
    color: var(--blue);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.jurnal-empty h3 {
    margin: 0;
    color: var(--text);
    font-size: 15px;
    font-weight: 800;
}

.jurnal-empty p {
    margin: 6px 0 18px;
    color: var(--muted);
    font-size: 11px;
}

/* PAGINATION */

.jurnal-pagination {
    padding: 18px 20px;
    border-top: 1px solid var(--line);
}

.jurnal-pagination nav {
    display: flex;
    justify-content: center;
}

.jurnal-pagination nav > div:first-child {
    display: none;
}

.jurnal-pagination nav > div:last-child {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
    gap: 5px;
}

.jurnal-pagination a,
.jurnal-pagination span[aria-current="page"] span,
.jurnal-pagination span[aria-disabled="true"] span {
    min-width: 34px;
    min-height: 34px;
    padding: 7px 11px;
    border: 1px solid var(--line);
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--text);
    background: #fff;
    text-decoration: none;
    font-size: 11px;
    font-weight: 700;
}

.jurnal-pagination a:hover {
    background: var(--blue-soft);
    border-color: var(--blue);
    color: var(--blue);
}

.jurnal-pagination span[aria-current="page"] span {
    background: var(--blue);
    border-color: var(--blue);
    color: #fff;
}

.jurnal-pagination span[aria-disabled="true"] span {
    color: #a0a8b5;
    background: #f7f8fa;
    cursor: not-allowed;
}

@media (max-width: 1000px) {
    .jurnal-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .jurnal-filter-form {
        grid-template-columns: 1fr 1fr;
    }

    .filter-buttons {
        grid-column: 1 / -1;
    }
}

@media (max-width: 760px) {
    .jurnal-header {
        flex-direction: column;
    }

    .jurnal-add-btn {
        width: 100%;
    }

    .jurnal-stats {
        grid-template-columns: 1fr;
    }

    .jurnal-action-card {
        align-items: flex-start;
        flex-direction: column;
    }

    .jurnal-filter-form {
        grid-template-columns: 1fr;
    }

    .filter-buttons {
        grid-column: auto;
        width: 100%;
    }

    .filter-buttons .btn {
        flex: 1;
    }

    .jurnal-item {
        grid-template-columns: 52px minmax(0, 1fr);
    }

    .jurnal-date {
        width: 52px;
        height: 58px;
    }

    .jurnal-side {
        grid-column: 1 / -1;
        flex-direction: row;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .jurnal-title-row {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .jurnal-item {
        padding: 15px;
        gap: 12px;
    }

    .jurnal-meta {
        flex-direction: column;
        align-items: flex-start;
    }

    .jurnal-side {
        flex-wrap: wrap;
    }

    .jurnal-pagination {
        padding: 14px 10px;
    }
}

</style>
@endpush

@endsection