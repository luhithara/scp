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

<div class="presensi-container">

    <div class="presensi-heading">
        <div>
            <h3>Daftar Jadwal Mengajar</h3>
            <p>Berikut jadwal mengajar yang dapat digunakan untuk mengisi presensi.</p>
        </div>

        <span class="presensi-total">
            {{ $jadwal->count() }} Jadwal
        </span>
    </div>

    @forelse($jadwal as $item)
        <div class="presensi-card">

            <div class="presensi-card-info">
                <div class="presensi-mapel">
                    {{ $item->namamapel }}
                </div>

                <div class="presensi-detail">
                    <span>
                        <i class="fas fa-calendar"></i>
                        {{ $item->hari }}
                    </span>

                    <span>
                        <i class="fas fa-clock"></i>
                        {{ $item->jamMulai }} - {{ $item->jamSelesai }}
                    </span>

                    <span>
                        <i class="fas fa-users"></i>
                        {{ $item->tingkat }} {{ $item->jurusanid }}
                    </span>
                </div>
            </div>

            <a href="{{ route('presensi.form', $item->id) }}"
               class="presensi-button">
                Isi Presensi
                <i class="fas fa-arrow-right"></i>
            </a>

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
    transition: .2s;
}

.presensi-button:hover {
    background: #1d4ed8;
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

@media (max-width: 650px) {
    .presensi-container {
        padding: 16px;
    }

    .presensi-card {
        align-items: flex-start;
        flex-direction: column;
    }

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