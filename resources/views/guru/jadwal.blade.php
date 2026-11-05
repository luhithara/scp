@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

@push('styles')
<style>
    .guru-jadwal-page {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        padding: 28px 30px 50px;
        color: #172033;
    }

    .guru-jadwal-page *,
    .guru-jadwal-page *::before,
    .guru-jadwal-page *::after {
        box-sizing: border-box;
    }

    .guru-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .guru-breadcrumb {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
        color: #7b8798;
        font-size: 13px;
    }

    .guru-breadcrumb .active {
        color: #1d4ed8;
        font-weight: 600;
    }

    .guru-page-title {
        margin: 0;
        color: #14213d;
        font-size: 28px;
        font-weight: 750;
    }

    .guru-page-description {
        margin: 8px 0 0;
        color: #6b778c;
        font-size: 14px;
    }

    .guru-account-info {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 240px;
        padding: 13px 16px;
        background: #fff;
        border: 1px solid #e3ebf5;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(30,64,175,.05);
    }

    .guru-account-icon,
    .guru-panel-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        color: #2563eb;
        background: #edf4ff;
        border-radius: 11px;
    }

    .guru-account-icon svg,
    .guru-panel-icon svg {
        width: 21px;
        height: 21px;
    }

    .guru-account-label {
        color: #8a96a8;
        font-size: 11px;
    }

    .guru-account-name {
        color: #1e293b;
        font-size: 14px;
        font-weight: 700;
    }

    .guru-alert {
        display: flex;
        gap: 12px;
        padding: 14px 16px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 12px;
        font-size: 13px;
    }

    .guru-alert-success {
        color: #166534;
        background: #effaf4;
        border-color: #cceedd;
    }

    .guru-alert-error {
        color: #b42318;
        background: #fff4f4;
        border-color: #f4cccc;
    }

    .guru-main-grid {
        display: grid;
        grid-template-columns: minmax(330px,.82fr) minmax(500px,1.5fr);
        gap: 22px;
        align-items: start;
    }

    .guru-panel {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e1e9f3;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(30,64,175,.055);
    }

    .guru-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 20px 21px;
        border-bottom: 1px solid #edf1f6;
    }

    .guru-panel-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .guru-panel-title {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 750;
    }

    .guru-panel-description {
        margin: 4px 0 0;
        color: #7a8799;
        font-size: 12px;
    }

    .guru-panel-body {
        padding: 21px;
    }

    .guru-form-grid {
        display: grid;
        grid-template-columns: repeat(2,minmax(0,1fr));
        gap: 16px;
    }

    .guru-form-group {
        min-width: 0;
    }

    .guru-form-group.full {
        grid-column: 1 / -1;
    }

    .guru-form-label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
    }

    .guru-required {
        color: #dc2626;
    }

    .guru-form-control {
        width: 100%;
        height: 43px;
        padding: 0 12px;
        color: #1f2937;
        background: #fff;
        border: 1px solid #d8e1ec;
        border-radius: 9px;
        outline: none;
        font-size: 13px;
    }

    .guru-form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,.10);
    }

    .guru-form-control:disabled {
        background: #f7f9fc;
        cursor: not-allowed;
    }

    .guru-form-help {
        margin-top: 6px;
        color: #8a96a8;
        font-size: 11px;
        line-height: 1.5;
    }

    .guru-form-actions {
        padding-top: 18px;
        margin-top: 20px;
        border-top: 1px solid #edf1f6;
    }

    .guru-btn-primary {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: 100%;
        min-height: 44px;
        color: #fff;
        background: #2563eb;
        border: 0;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .guru-btn-primary:hover {
        background: #1d4ed8;
    }

    .guru-btn-primary:disabled {
        opacity: .55;
        cursor: not-allowed;
    }

    .guru-info-note {
        display: flex;
        gap: 9px;
        padding: 12px;
        margin-top: 16px;
        color: #5f7188;
        background: #f7faff;
        border: 1px solid #dfebfb;
        border-radius: 9px;
        font-size: 11px;
        line-height: 1.6;
    }

    .guru-info-note strong {
        color: #344054;
    }

    .guru-time-preview {
        padding: 13px;
        margin-top: 16px;
        color: #2457ad;
        background: #f1f6ff;
        border: 1px solid #dfebff;
        border-radius: 10px;
        font-size: 12px;
        line-height: 1.8;
    }

    .guru-schedule-count {
        padding: 6px 10px;
        color: #2558b5;
        background: #f1f6ff;
        border: 1px solid #dce9ff;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .guru-schedule-body {
        padding: 16px;
    }

    .guru-day-group {
        margin-bottom: 18px;
    }

    .guru-day-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 0 0 9px;
        color: #344054;
        font-size: 13px;
        font-weight: 750;
    }

    .guru-day-line {
        flex: 1;
        height: 1px;
        background: #e8edf4;
    }

    .guru-schedule-card {
        display: grid;
        grid-template-columns: 105px minmax(0,1fr) auto;
        gap: 14px;
        align-items: center;
        padding: 14px;
        margin-bottom: 9px;
        background: #fff;
        border: 1px solid #e2e9f2;
        border-radius: 12px;
    }

    .guru-schedule-time {
        padding: 10px 8px;
        text-align: center;
        color: #2457ad;
        background: #f1f6ff;
        border: 1px solid #dfebff;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 800;
    }

    .guru-schedule-main {
        min-width: 0;
    }

    .guru-schedule-subject {
        margin: 0 0 5px;
        overflow: hidden;
        color: #172033;
        font-size: 14px;
        font-weight: 750;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .guru-schedule-class {
        color: #66758a;
        font-size: 11px;
    }

    .guru-schedule-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
    }

    .guru-badge {
        display: inline-flex;
        justify-content: center;
        min-width: 76px;
        padding: 5px 8px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .guru-badge-blue {
        color: #1d4ed8;
        background: #edf4ff;
        border: 1px solid #dbe9ff;
    }

    .guru-badge-room {
        color: #526173;
        background: #f5f7fa;
        border: 1px solid #e4e9ef;
    }

    .guru-badge-room-empty {
        color: #9a6700;
        background: #fff8e7;
        border: 1px solid #f5e4b5;
    }

    .guru-btn-delete {
        width: 55px;
        height: 30px;
        color: #c53030;
        background: #fff7f7;
        border: 1px solid #f0d2d2;
        border-radius: 8px;
        cursor: pointer;
    }

    .guru-empty-state {
        padding: 45px 20px;
        text-align: center;
        color: #8995a7;
        font-size: 12px;
    }

    .guru-empty-state strong {
        display: block;
        margin-bottom: 5px;
        color: #344054;
        font-size: 14px;
    }

    @media(max-width:1100px) {
        .guru-main-grid {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width:760px) {
        .guru-jadwal-page {
            padding: 20px 15px 40px;
        }

        .guru-page-header {
            flex-direction: column;
        }

        .guru-account-info {
            width: 100%;
        }

        .guru-form-grid {
            grid-template-columns: 1fr;
        }

        .guru-form-group.full {
            grid-column: auto;
        }
    }

    @media(max-width:560px) {
        .guru-schedule-card {
            grid-template-columns: 1fr;
        }

        .guru-schedule-meta {
            flex-direction: row;
            align-items: center;
            flex-wrap: wrap;
        }

        .guru-schedule-time {
            text-align: left;
        }
    }
</style>
@endpush

@section('content')

@php
    $jurusan = $jurusan ?? collect();
    $kelas = $kelas ?? collect();
    $jadwal = $jadwal ?? collect();
    $jadwalTerkunci = $jadwalTerkunci ?? false;
@endphp

<div class="guru-jadwal-page">

    <div class="guru-page-header">
        <div>
            <div class="guru-breadcrumb">
                <span>Guru</span>
                <span>/</span>
                <span class="active">Jadwal Mengajar</span>
            </div>

            <h1 class="guru-page-title">Jadwal Mengajar</h1>

            <p class="guru-page-description">
                Atur jadwal mengajar dan lihat seluruh jadwal yang dimiliki.
            </p>
        </div>

        <div class="guru-account-info">
            <div class="guru-account-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M20 21a8 8 0 0 0-16 0"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>

            <div>
                <div class="guru-account-label">Guru yang sedang login</div>
                <div class="guru-account-name">
                    {{ $guru->nama ?? auth()->user()->name ?? 'Guru' }}
                </div>
            </div>
        </div>
    </div>

    @if($jadwalTerkunci)
        <div class="guru-alert guru-alert-error">
            <div>
                <strong>Jadwal sedang dikunci oleh Waka.</strong><br>
                Kamu masih dapat melihat jadwal, tetapi tidak dapat menambah atau menghapus jadwal.
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="guru-alert guru-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="guru-alert guru-alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="guru-alert guru-alert-error">
            <div>
                <strong>Data belum dapat disimpan:</strong>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="guru-main-grid">

        {{-- FORM INPUT JADWAL --}}

        <section class="guru-panel">

            <div class="guru-panel-header">
                <div class="guru-panel-header-left">
                    <div class="guru-panel-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="4" y="4" width="16" height="16" rx="2"/>
                            <path d="M12 8v8M8 12h8"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="guru-panel-title">Input Jadwal</h2>
                        <p class="guru-panel-description">Tambahkan jadwal mengajar baru.</p>
                    </div>
                </div>
            </div>

            <div class="guru-panel-body">

                <form action="{{ route('guru.jadwal.simpan') }}" method="POST">
                    @csrf

                    <div class="guru-form-grid">

                        <div class="guru-form-group full">
                            <label class="guru-form-label" for="namamapel">
                                Mata Pelajaran <span class="guru-required">*</span>
                            </label>

                            <input
                                type="text"
                                id="namamapel"
                                name="namamapel"
                                class="guru-form-control"
                                value="{{ old('namamapel') }}"
                                placeholder="Contoh: Matematika"
                                maxlength="100"
                                autocomplete="off"
                                required
                                {{ $jadwalTerkunci ? 'disabled' : '' }}
                            >

                            <div class="guru-form-help">
                                Ketik nama mata pelajaran. Jika belum terdaftar, sistem akan menambahkannya secara otomatis.
                            </div>
                        </div>

                        <div class="guru-form-group">
                            <label class="guru-form-label" for="tingkat">
                                Tingkat <span class="guru-required">*</span>
                            </label>

                            <select
                                id="tingkat"
                                class="guru-form-control"
                                required
                                {{ $jadwalTerkunci ? 'disabled' : '' }}
                            >
                                <option value="">Pilih Tingkat</option>

                                @foreach([10,11,12] as $tingkatItem)
                                    <option
                                        value="{{ $tingkatItem }}"
                                        {{ old('tingkat') == $tingkatItem ? 'selected' : '' }}
                                    >
                                        Kelas {{ $tingkatItem }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="guru-form-group">
                            <label class="guru-form-label" for="jurusanid">
                                Jurusan <span class="guru-required">*</span>
                            </label>

                            <select
                                id="jurusanid"
                                class="guru-form-control"
                                required
                                {{ $jadwalTerkunci ? 'disabled' : '' }}
                            >
                                <option value="">Pilih Jurusan</option>

                                @foreach($jurusan as $jurusanItem)
                                    <option
                                        value="{{ $jurusanItem->id }}"
                                        {{ old('jurusanid') == $jurusanItem->id ? 'selected' : '' }}
                                    >
                                        {{ $jurusanItem->namajurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="guru-form-group full">
                            <label class="guru-form-label" for="kelasid">
                                Kelas <span class="guru-required">*</span>
                            </label>

                            <select
                                id="kelasid"
                                name="kelasid"
                                class="guru-form-control"
                                required
                                {{ $jadwalTerkunci ? 'disabled' : '' }}
                            >
                                <option value="">Pilih Kelas</option>

                                @foreach($kelas as $kelasItem)
                                    @php
                                        $namaJurusanKelas = data_get($kelasItem, 'jurusan.namajurusan')
                                            ?? data_get($kelasItem, 'namajurusan');
                                    @endphp

                                    <option
                                        value="{{ $kelasItem->id }}"
                                        data-tingkat="{{ $kelasItem->tingkat ?? '' }}"
                                        data-jurusan="{{ $kelasItem->jurusanid ?? '' }}"
                                        {{ old('kelasid') == $kelasItem->id ? 'selected' : '' }}
                                    >
                                        {{ $kelasItem->namakelas }}
                                        @if($namaJurusanKelas)
                                            — {{ $namaJurusanKelas }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            <div class="guru-form-help">
                                Pilih tingkat dan jurusan terlebih dahulu.
                            </div>
                        </div>

                        <div class="guru-form-group">
                            <label class="guru-form-label" for="blok">
                                Putaran Blok <span class="guru-required">*</span>
                            </label>

                            <select
                                id="blok"
                                name="blok"
                                class="guru-form-control"
                                required
                                {{ $jadwalTerkunci ? 'disabled' : '' }}
                            >
                                <option value="">Pilih Putaran</option>

                                <option value="1" {{ old('blok') == '1' ? 'selected' : '' }}>
                                    Putaran 1
                                </option>

                                <option value="2" {{ old('blok') == '2' ? 'selected' : '' }}>
                                    Putaran 2
                                </option>
                            </select>

                            <div class="guru-form-help">
                                Kelas dengan nomor ganjil menggunakan Putaran 1, nomor genap Putaran 2.
                            </div>
                        </div>

                        <div class="guru-form-group">
                            <label class="guru-form-label" for="hari">
                                Hari <span class="guru-required">*</span>
                            </label>

                            <select
                                id="hari"
                                name="hari"
                                class="guru-form-control"
                                required
                                {{ $jadwalTerkunci ? 'disabled' : '' }}
                            >
                                <option value="">Pilih Hari</option>

                                @foreach(['senin','selasa','rabu','kamis','jumat'] as $namaHari)
                                    <option
                                        value="{{ $namaHari }}"
                                        {{ old('hari') == $namaHari ? 'selected' : '' }}
                                    >
                                        {{ ucfirst($namaHari) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="guru-form-group">
                            <label class="guru-form-label" for="jamMulaiKe">
                                Jam Pelajaran Mulai <span class="guru-required">*</span>
                            </label>

                            <select
                                id="jamMulaiKe"
                                name="jamMulaiKe"
                                class="guru-form-control"
                                required
                                {{ $jadwalTerkunci ? 'disabled' : '' }}
                            >
                                <option value="">Pilih Jam Mulai</option>

                                @for($i = 1; $i <= 7; $i++)
                                    <option
                                        value="{{ $i }}"
                                        {{ old('jamMulaiKe') == $i ? 'selected' : '' }}
                                    >
                                        Jam Pelajaran ke-{{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="guru-form-group">
                            <label class="guru-form-label" for="jamSelesaiKe">
                                Jam Pelajaran Selesai <span class="guru-required">*</span>
                            </label>

                            <select
                                id="jamSelesaiKe"
                                name="jamSelesaiKe"
                                class="guru-form-control"
                                required
                                {{ $jadwalTerkunci ? 'disabled' : '' }}
                            >
                                <option value="">Pilih Jam Selesai</option>

                                @for($i = 1; $i <= 7; $i++)
                                    <option
                                        value="{{ $i }}"
                                        {{ old('jamSelesaiKe') == $i ? 'selected' : '' }}
                                    >
                                        Jam Pelajaran ke-{{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                    </div>

                    <div class="guru-time-preview" id="previewWaktu">
                        Pilih hari dan jam pelajaran untuk melihat waktu mulai dan selesai.
                    </div>

                    <div class="guru-info-note">
                        <div>
                            <strong>Pengaturan waktu otomatis</strong><br>
                            Senin–Jumat: 45 menit per jam pelajaran, total 7 JP.
                            Jam dimulai pukul 07.15. Istirahat pertama 15 menit setelah
                            JP 2 dan istirahat kedua 60 menit setelah JP 5.
                            Ruangan akan ditentukan oleh Waka.
                        </div>
                    </div>

                    <div class="guru-form-actions">
                        <button
                            type="submit"
                            class="guru-btn-primary"
                            {{ $jadwalTerkunci ? 'disabled' : '' }}
                        >
                            + Simpan Jadwal
                        </button>
                    </div>

                </form>

            </div>
        </section>

        {{-- DAFTAR JADWAL --}}

        <section class="guru-panel">

            <div class="guru-panel-header">
                <div class="guru-panel-header-left">
                    <div class="guru-panel-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3.5" y="5" width="17" height="15" rx="2"/>
                            <path d="M7 3.5v3M17 3.5v3M3.5 9h17"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="guru-panel-title">Jadwal Mengajar Saya</h2>
                        <p class="guru-panel-description">
                            Jadwal yang sudah dimasukkan oleh guru.
                        </p>
                    </div>
                </div>

                @if($jadwal->count())
                    <div class="guru-schedule-count">
                        {{ $jadwal->count() }} Jadwal
                    </div>
                @endif
            </div>

            <div class="guru-schedule-body">

                @php
                    $urutanHari = [
                        'senin' => 'Senin',
                        'selasa' => 'Selasa',
                        'rabu' => 'Rabu',
                        'kamis' => 'Kamis',
                        'jumat' => 'Jumat',
                    ];

                    $jadwalTerurut = $jadwal->groupBy(function ($item) {
                        return strtolower($item->hari);
                    });
                @endphp

                @if($jadwal->count())

                    @foreach($urutanHari as $hariKey => $hariLabel)

                        @if(isset($jadwalTerurut[$hariKey]))

                            <div class="guru-day-group">

                                <div class="guru-day-title">
                                    {{ $hariLabel }}
                                    <div class="guru-day-line"></div>
                                </div>

                                @foreach($jadwalTerurut[$hariKey]->sortBy('jamMulai') as $item)

                                    @php
                                        $namaKelas = data_get($item, 'kelas.namakelas')
                                            ?? 'Kelas belum tersedia';

                                        $namaMapel = data_get($item, 'mapel.namamapel')
                                            ?? 'Mata Pelajaran';

                                        $namaJurusan = data_get($item, 'kelas.jurusan.namajurusan')
                                            ?? data_get($item, 'kelas.namajurusan');

                                        $namaRuangan = data_get($item, 'ruangan.namaruangan');
                                    @endphp

                                    <div class="guru-schedule-card">

                                        <div class="guru-schedule-time">
                                            {{ substr($item->jamMulai, 0, 5) }}
                                            –
                                            {{ substr($item->jamSelesai, 0, 5) }}
                                        </div>

                                        <div class="guru-schedule-main">

                                            <h3 class="guru-schedule-subject">
                                                {{ $namaMapel }}
                                            </h3>

                                            <div class="guru-schedule-class">
                                                <strong>{{ $namaKelas }}</strong>

                                                @if($namaJurusan)
                                                    • {{ $namaJurusan }}
                                                @endif
                                            </div>

                                        </div>

                                        <div class="guru-schedule-meta">

                                            <span class="guru-badge guru-badge-blue">
                                                Putaran {{ $item->blok }}
                                            </span>

                                            @if($namaRuangan)
                                                <span class="guru-badge guru-badge-room">
                                                    {{ $namaRuangan }}
                                                </span>
                                            @else
                                                <span class="guru-badge guru-badge-room-empty">
                                                    Ruangan belum diatur
                                                </span>
                                            @endif

                                            @if(!$jadwalTerkunci)
                                                <form
                                                    action="{{ route('guru.jadwal.hapus', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Apakah kamu yakin ingin menghapus jadwal ini?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="guru-btn-delete"
                                                        title="Hapus jadwal"
                                                    >
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @endif

                    @endforeach

                @else

                    <div class="guru-empty-state">
                        <strong>Belum Ada Jadwal</strong>
                        Jadwal mengajar yang kamu tambahkan akan muncul di bagian ini.
                    </div>

                @endif

            </div>
        </section>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tingkat = document.getElementById('tingkat');
    const jurusan = document.getElementById('jurusanid');
    const kelas = document.getElementById('kelasid');

    const hari = document.getElementById('hari');
    const jamMulai = document.getElementById('jamMulaiKe');
    const jamSelesai = document.getElementById('jamSelesaiKe');
    const preview = document.getElementById('previewWaktu');

    /*
    |--------------------------------------------------------------------------
    | FILTER KELAS BERDASARKAN TINGKAT DAN JURUSAN
    |--------------------------------------------------------------------------
    */

    function filterKelas() {

        if (!tingkat || !jurusan || !kelas) {
            return;
        }

        const tingkatDipilih = tingkat.value;
        const jurusanDipilih = jurusan.value;

        Array.from(kelas.options).forEach(function (option) {

            if (!option.dataset.tingkat) {
                option.hidden = false;
                return;
            }

            const cocokTingkat =
                !tingkatDipilih ||
                option.dataset.tingkat === tingkatDipilih;

            const cocokJurusan =
                !jurusanDipilih ||
                option.dataset.jurusan === jurusanDipilih;

            const cocok = cocokTingkat && cocokJurusan;

            option.hidden = !cocok;

            if (!cocok && kelas.value === option.value) {
                kelas.value = '';
            }
        });
    }

    if (tingkat) {
        tingkat.addEventListener('change', filterKelas);
    }

    if (jurusan) {
        jurusan.addEventListener('change', filterKelas);
    }

    filterKelas();

    /*
    |--------------------------------------------------------------------------
    | HITUNG JAM PELAJARAN OTOMATIS
    |--------------------------------------------------------------------------
    */

    function buatDaftarWaktu() {

        let menitSekarang = (7 * 60) + 15;

        const daftar = {};

        for (let i = 1; i <= 7; i++) {

            const mulai = menitSekarang;
            const selesai = mulai + 45;

            daftar[i] = {
                mulai: formatWaktu(mulai),
                selesai: formatWaktu(selesai)
            };

            menitSekarang = selesai;

            if (i === 2) {
                menitSekarang += 15;
            }

            if (i === 5) {
                menitSekarang += 60;
            }
        }

        return daftar;
    }

    function formatWaktu(totalMenit) {

        const jam = Math.floor(totalMenit / 60);
        const menit = totalMenit % 60;

        return String(jam).padStart(2, '0') +
            ':' +
            String(menit).padStart(2, '0');
    }

    function perbaruiPreview() {

        if (!hari || !jamMulai || !jamSelesai || !preview) {
            return;
        }

        if (!hari.value || !jamMulai.value || !jamSelesai.value) {
            preview.textContent =
                'Pilih hari dan jam pelajaran untuk melihat waktu mulai dan selesai.';
            return;
        }

        const mulaiNomor = parseInt(jamMulai.value, 10);
        const selesaiNomor = parseInt(jamSelesai.value, 10);

        if (selesaiNomor < mulaiNomor) {
            preview.textContent =
                'Jam pelajaran selesai tidak boleh lebih awal dari jam mulai.';
            return;
        }

        const daftar = buatDaftarWaktu();

        preview.innerHTML =
            '<strong>Perkiraan waktu jadwal:</strong><br>' +
            'Mulai: ' + daftar[mulaiNomor].mulai + '<br>' +
            'Selesai: ' + daftar[selesaiNomor].selesai;
    }

    if (hari) {
        hari.addEventListener('change', perbaruiPreview);
    }

    if (jamMulai) {
        jamMulai.addEventListener('change', perbaruiPreview);
    }

    if (jamSelesai) {
        jamSelesai.addEventListener('change', perbaruiPreview);
    }

    perbaruiPreview();
});
</script>

@endsection