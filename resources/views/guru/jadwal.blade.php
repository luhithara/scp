@extends('layouts.app')

@section('title', 'Jadwal Mengajar')
@section('page-title', 'Jadwal Mengajar')

@push('styles')
<style>
    .jadwal-page {
        max-width: 1500px;
        margin: auto;
        padding: 28px 30px 50px;
        color: #172033;
    }

    .jadwal-page * {
        box-sizing: border-box;
    }

    .jadwal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    .jadwal-breadcrumb {
        color: #7b8798;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .jadwal-breadcrumb span {
        color: #2563eb;
        font-weight: 700;
    }

    .jadwal-title {
        margin: 0;
        color: #14213d;
        font-size: 28px;
        font-weight: 750;
    }

    .jadwal-description {
        margin: 8px 0 0;
        color: #6b778c;
        font-size: 14px;
    }

    .jadwal-account {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 16px;
        background: white;
        border: 1px solid #e3ebf5;
        border-radius: 13px;
    }

    .jadwal-account-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        color: #2563eb;
        background: #edf4ff;
        border-radius: 10px;
        font-size: 19px;
    }

    .jadwal-account-label {
        color: #8a96a8;
        font-size: 11px;
    }

    .jadwal-account-name {
        color: #1e293b;
        font-size: 14px;
        font-weight: 700;
    }

    .jadwal-alert {
        padding: 14px 16px;
        margin-bottom: 18px;
        border-radius: 10px;
        font-size: 13px;
    }

    .jadwal-success {
        color: #166534;
        background: #effaf4;
        border: 1px solid #cceedd;
    }

    .jadwal-error {
        color: #b42318;
        background: #fff4f4;
        border: 1px solid #f4cccc;
    }

    .jadwal-panel {
        margin-bottom: 22px;
        overflow: hidden;
        background: white;
        border: 1px solid #e1e9f3;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(30,64,175,.045);
    }

    .jadwal-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 19px 21px;
        border-bottom: 1px solid #edf1f6;
    }

    .jadwal-panel-title {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 750;
    }

    .jadwal-panel-subtitle {
        margin: 5px 0 0;
        color: #7a8799;
        font-size: 12px;
    }

    .jadwal-panel-body {
        padding: 22px;
    }

    .jadwal-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px;
    }

    .jadwal-form-group {
        min-width: 0;
    }

    .jadwal-label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
    }

    .jadwal-control {
        width: 100%;
        height: 43px;
        padding: 0 12px;
        color: #1f2937;
        background: white;
        border: 1px solid #d8e1ec;
        border-radius: 9px;
        outline: none;
        font-size: 13px;
    }

    .jadwal-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,.10);
    }

    .jadwal-hint {
        margin-top: 6px;
        color: #8995a7;
        font-size: 11px;
    }

    .jadwal-form-actions {
        display: flex;
        gap: 9px;
        margin-top: 21px;
        padding-top: 18px;
        border-top: 1px solid #edf1f6;
    }

    .jadwal-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        min-height: 40px;
        padding: 0 17px;
        color: white;
        background: #2563eb;
        border: 1px solid #2563eb;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: .2s;
    }

    .jadwal-btn:hover {
        color: white;
        background: #1d4ed8;
    }

    .jadwal-btn-light {
        color: #475569;
        background: white;
        border-color: #dce3ed;
    }

    .jadwal-btn-light:hover {
        color: #1d4ed8;
        background: #f5f8ff;
    }

    .jadwal-btn-danger {
        min-height: 34px;
        padding: 0 12px;
        color: #b42318;
        background: #fff4f4;
        border-color: #f4cccc;
    }

    .jadwal-btn-danger:hover {
        color: white;
        background: #dc2626;
        border-color: #dc2626;
    }

    .jadwal-count {
        padding: 6px 10px;
        color: #2558b5;
        background: #f1f6ff;
        border: 1px solid #dce9ff;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .jadwal-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        padding: 18px;
    }

    .jadwal-card {
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding: 17px;
        background: white;
        border: 1px solid #e2e9f2;
        border-radius: 12px;
    }

    .jadwal-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .jadwal-day {
        padding: 6px 10px;
        color: #1d4ed8;
        background: #edf4ff;
        border: 1px solid #dbe9ff;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
    }

    .jadwal-time {
        color: #526173;
        font-size: 12px;
        font-weight: 700;
    }

    .jadwal-subject {
        margin: 0 0 7px;
        color: #172033;
        font-size: 16px;
        font-weight: 750;
    }

    .jadwal-detail {
        color: #66758a;
        font-size: 12px;
        line-height: 1.8;
    }

    .jadwal-card-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding-top: 13px;
        border-top: 1px solid #edf1f6;
    }

    .jadwal-empty {
        grid-column: 1 / -1;
        padding: 50px 20px;
        color: #8995a7;
        text-align: center;
        font-size: 13px;
    }

    .jadwal-empty strong {
        display: block;
        margin-bottom: 6px;
        color: #344054;
        font-size: 15px;
    }

    .jadwal-locked {
        padding: 13px 16px;
        margin-bottom: 18px;
        color: #92400e;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 10px;
        font-size: 13px;
    }

    @media(max-width: 850px) {
        .jadwal-list {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 650px) {
        .jadwal-page {
            padding: 20px 15px 40px;
        }

        .jadwal-header {
            align-items: stretch;
            flex-direction: column;
        }

        .jadwal-form-grid {
            grid-template-columns: 1fr;
        }

        .jadwal-panel-body {
            padding: 16px;
        }

        .jadwal-form-actions {
            flex-direction: column;
        }

        .jadwal-form-actions .jadwal-btn {
            width: 100%;
        }

        .jadwal-list {
            padding: 13px;
        }
    }
</style>
@endpush

@section('content')

@php
    $jadwal = $jadwal ?? collect();
    $kelas = $kelas ?? collect();
    $jadwalTerkunci = $jadwalTerkunci ?? false;
@endphp

<div class="jadwal-page">

    <div class="jadwal-header">
        <div>
            <div class="jadwal-breadcrumb">
                Guru / <span>Jadwal Mengajar</span>
            </div>

            <h1 class="jadwal-title">Jadwal Mengajar</h1>

            <p class="jadwal-description">
                Kelola jadwal mengajar dan tambahkan jadwal pelajaran.
            </p>
        </div>

        <div class="jadwal-account">
            <div class="jadwal-account-icon">♙</div>
            <div>
                <div class="jadwal-account-label">Guru yang sedang login</div>
                <div class="jadwal-account-name">
                    {{ $guru->nama ?? auth()->user()->name ?? 'Guru' }}
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="jadwal-alert jadwal-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="jadwal-alert jadwal-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="jadwal-alert jadwal-error">
            <strong>Terjadi kesalahan:</strong>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if($jadwalTerkunci)
        <div class="jadwal-locked">
            <strong>Jadwal sedang dikunci.</strong>
            Saat ini kamu belum bisa menambahkan atau menghapus jadwal.
        </div>
    @else

        {{-- FORM TAMBAH JADWAL --}}
        <section class="jadwal-panel">

            <div class="jadwal-panel-header">
                <div>
                    <h2 class="jadwal-panel-title">Tambah Jadwal Mengajar</h2>
                    <p class="jadwal-panel-subtitle">
                        Isi informasi jadwal pelajaran yang akan kamu ajar.
                    </p>
                </div>
            </div>

            <div class="jadwal-panel-body">

                <form action="{{ route('guru.jadwal.simpan') }}" method="POST">
                    @csrf

                    <div class="jadwal-form-grid">

                        <div class="jadwal-form-group">
                            <label class="jadwal-label">Mata Pelajaran</label>
                            <input
                                type="text"
                                name="namamapel"
                                class="jadwal-control"
                                value="{{ old('namamapel') }}"
                                placeholder="Contoh: Matematika"
                                required
                            >
                        </div>

                        <div class="jadwal-form-group">
                            <label class="jadwal-label">Kelas</label>
                            <select name="kelasid" class="jadwal-control" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $item)
                                    <option
                                        value="{{ $item->id }}"
                                        {{ old('kelasid') == $item->id ? 'selected' : '' }}
                                    >
                                        {{ $item->namakelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="jadwal-form-group">
                            <label class="jadwal-label">Hari</label>
                            <select name="hari" class="jadwal-control" required>
                                <option value="">Pilih Hari</option>
                                @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat'] as $hari)
                                    <option
                                        value="{{ $hari }}"
                                        {{ old('hari') == $hari ? 'selected' : '' }}
                                    >
                                        {{ ucfirst($hari) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="jadwal-form-group">
                            <label class="jadwal-label">Blok Jadwal</label>
                            <select name="blok" class="jadwal-control" required>
                                <option value="">Pilih Blok</option>
                                <option value="1" {{ old('blok') == '1' ? 'selected' : '' }}>
                                    Blok 1
                                </option>
                                <option value="2" {{ old('blok') == '2' ? 'selected' : '' }}>
                                    Blok 2
                                </option>
                            </select>
                            <div class="jadwal-hint">
                                Pilih blok sesuai pembagian jadwal kelas.
                            </div>
                        </div>

                        <div class="jadwal-form-group">
                            <label class="jadwal-label">Jam Pelajaran Mulai</label>
                            <select name="jamMulaiKe" class="jadwal-control" required>
                                <option value="">Pilih Jam Mulai</option>
                                @for($i = 1; $i <= 7; $i++)
                                    <option
                                        value="{{ $i }}"
                                        {{ old('jamMulaiKe') == $i ? 'selected' : '' }}
                                    >
                                        Jam ke-{{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="jadwal-form-group">
                            <label class="jadwal-label">Jam Pelajaran Selesai</label>
                            <select name="jamSelesaiKe" class="jadwal-control" required>
                                <option value="">Pilih Jam Selesai</option>
                                @for($i = 1; $i <= 7; $i++)
                                    <option
                                        value="{{ $i }}"
                                        {{ old('jamSelesaiKe') == $i ? 'selected' : '' }}
                                    >
                                        Jam ke-{{ $i }}
                                    </option>
                                @endfor
                            </select>
                            <div class="jadwal-hint">
                                Jam selesai tidak boleh lebih awal dari jam mulai.
                            </div>
                        </div>

                    </div>

                    <div class="jadwal-form-actions">
                        <button type="submit" class="jadwal-btn">
                            + Simpan Jadwal
                        </button>

                        <button type="reset" class="jadwal-btn jadwal-btn-light">
                            Reset Form
                        </button>
                    </div>

                </form>

            </div>
        </section>

    @endif

    {{-- DAFTAR JADWAL --}}
    <section class="jadwal-panel">

        <div class="jadwal-panel-header">
            <div>
                <h2 class="jadwal-panel-title">Daftar Jadwal Mengajar</h2>
                <p class="jadwal-panel-subtitle">
                    Seluruh jadwal pelajaran yang menjadi tanggung jawabmu.
                </p>
            </div>

            <div class="jadwal-count">
                {{ $jadwal->count() }} Jadwal
            </div>
        </div>

        <div class="jadwal-list">

            @forelse($jadwal as $item)

                <article class="jadwal-card">

                    <div class="jadwal-card-top">
                        <span class="jadwal-day">
                            {{ ucfirst($item->hari ?? '-') }}
                        </span>

                        <span class="jadwal-time">
                            {{ substr($item->jamMulai ?? '', 0, 5) }}
                            –
                            {{ substr($item->jamSelesai ?? '', 0, 5) }}
                        </span>
                    </div>

                    <div>
                        <h3 class="jadwal-subject">
                            {{ $item->mapel->namamapel ?? 'Mata Pelajaran' }}
                        </h3>

                        <div class="jadwal-detail">
                            <strong>Kelas:</strong>
                            {{ $item->kelas->namakelas ?? '-' }}

                            @if(isset($item->kelas->jurusan))
                                <br>
                                <strong>Jurusan:</strong>
                                {{ $item->kelas->jurusan->namajurusan ?? '-' }}
                            @endif

                            @if(isset($item->ruangan))
                                <br>
                                <strong>Ruangan:</strong>
                                {{ $item->ruangan->namaruangan ?? '-' }}
                            @endif
                        </div>
                    </div>

                    <div class="jadwal-card-bottom">
                        <span class="jadwal-detail">
                            Jadwal Mengajar
                        </span>

                        @unless($jadwalTerkunci)
                            <form
                                action="{{ route('guru.jadwal.hapus', $item->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="jadwal-btn jadwal-btn-danger">
                                    Hapus
                                </button>
                            </form>
                        @endunless
                    </div>

                </article>

            @empty

                <div class="jadwal-empty">
                    <strong>Belum Ada Jadwal Mengajar</strong>
                    Silakan tambahkan jadwal melalui formulir di atas.
                </div>

            @endforelse

        </div>

    </section>

</div>

@endsection