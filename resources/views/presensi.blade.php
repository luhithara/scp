@extends('layouts.app')

@section('content')

<div class="pagehead">
    <h1>Presensi Siswa</h1>
    <p>Pilih jadwal mengajar untuk mengisi kehadiran siswa.</p>
</div>

@if(session('success'))
    <div class="presensi-alert">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="presensi-error">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="presensi-container">

    <div class="presensi-heading">
        <div>
            <h3>Daftar Jadwal Mengajar</h3>
            <p>
                Jadwal yang dapat diisi hanya jadwal pada hari
                <strong>{{ ucfirst($hariIni) }}</strong>.
            </p>
        </div>

        <span class="presensi-total">
            {{ $jadwal->count() }} Jadwal
        </span>
    </div>

    @forelse($jadwal as $item)

        @php
            $jadwalHariIni = strtolower(trim($item->hari ?? '')) === $hariIni;
        @endphp

        <div class="presensi-card {{ !$jadwalHariIni ? 'presensi-card-locked' : '' }}">

            <div class="presensi-card-info">

                <div class="presensi-mapel">
                    {{ $item->namamapel ?: 'Nama mapel belum diisi' }}
                </div>

                <div class="presensi-detail">

                    <span>
                        <i class="fas fa-calendar"></i>
                        {{ ucfirst($item->hari) }}
                    </span>

                    <span>
                        <i class="fas fa-clock"></i>
                        {{ $item->jamMulai }} - {{ $item->jamSelesai }}
                    </span>

                    <span>
                        <i class="fas fa-users"></i>
                        {{ $item->tingkat ?? '-' }}
                        {{ $item->namajurusan ?? '' }}
                    </span>

                    <span>
                        <i class="fas fa-user-graduate"></i>
                        {{ $item->jumlah_siswa ?? 0 }} Siswa
                    </span>

                </div>

            </div>

            <div class="presensi-card-action">

                @if($jadwalHariIni)

                    <a href="{{ route('presensi.form', $item->id) }}"
                       class="presensi-button">
                        Isi Presensi
                        <i class="fas fa-arrow-right"></i>
                    </a>

                @else

                    <button type="button"
                            class="presensi-button presensi-disabled"
                            disabled
                            title="Presensi hanya dapat diisi pada hari jadwal">
                        Bukan Jadwal Hari Ini
                        <i class="fas fa-lock"></i>
                    </button>

                @endif

            </div>

        </div>

    @empty

        <div class="presensi-empty">
            <i class="fas fa-calendar-times"></i>
            <h3>Belum Ada Jadwal</h3>
            <p>Belum ada jadwal mengajar yang tersedia untuk akun Anda.</p>
        </div>

    @endforelse

</div>

<style>
.presensi-container {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    margin-top: 22px;
    border: 1px solid #e5eaf1;
}

.presensi-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
    gap: 12px;
}

.presensi-heading h3 {
    margin: 0 0 6px;
    font-size: 18px;
    color: #243247;
}

.presensi-heading p {
    margin: 0;
    color: #7b8798;
    font-size: 13px;
}

.presensi-total {
    background: #eaf2ff;
    color: #2563eb;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

.presensi-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
    padding: 20px;
    border: 1px solid #e8edf4;
    border-radius: 10px;
    margin-bottom: 14px;
    transition: .2s;
}

.presensi-card:last-child {
    margin-bottom: 0;
}

.presensi-card:hover {
    border-color: #b8d0ff;
    background: #fbfdff;
}

.presensi-card-locked {
    background: #fafafa;
}

.presensi-mapel {
    font-size: 16px;
    font-weight: 700;
    color: #243247;
    margin-bottom: 12px;
}

.presensi-detail {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    color: #778397;
    font-size: 13px;
}

.presensi-detail span {
    display: flex;
    align-items: center;
    gap: 7px;
}

.presensi-detail i {
    color: #3977d5;
}

.presensi-card-action {
    flex-shrink: 0;
}

.presensi-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    background: #2563eb;
    color: white !important;
    padding: 11px 17px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none !important;
    white-space: nowrap;
    border: none;
    transition: .2s;
}

a.presensi-button:hover {
    background: #1d4ed8;
}

.presensi-disabled,
.presensi-disabled:disabled {
    background: #e5e7eb !important;
    color: #9ca3af !important;
    cursor: not-allowed !important;
    opacity: 1;
}

.presensi-empty {
    text-align: center;
    padding: 55px 15px;
    color: #8490a2;
}

.presensi-empty i {
    font-size: 38px;
    margin-bottom: 15px;
    color: #aab6c7;
}

.presensi-empty h3 {
    color: #344054;
    font-size: 17px;
}

.presensi-empty p {
    font-size: 13px;
}

.presensi-alert {
    background: #e9f8ef;
    color: #21834b;
    border: 1px solid #c8eed6;
    padding: 13px 17px;
    border-radius: 8px;
    margin-top: 15px;
}

.presensi-error {
    background: #fff0f0;
    color: #c62828;
    border: 1px solid #ffd1d1;
    padding: 13px 17px;
    border-radius: 8px;
    margin-top: 15px;
}

@media (max-width: 650px) {
    .presensi-container {
        padding: 16px;
    }

    .presensi-heading {
        align-items: flex-start;
    }

    .presensi-card {
        align-items: flex-start;
        flex-direction: column;
    }

    .presensi-card-action,
    .presensi-button {
        width: 100%;
    }

    .presensi-detail {
        flex-direction: column;
        gap: 9px;
    }
}
</style>

@endsection