@extends('layouts.app')

@section('title', 'Jadwal Kelas - SCP')

@section('content')

@php
    $jadwal = $jadwal ?? collect();

    $urutanHari = [
        'senin' => 1,
        'selasa' => 2,
        'rabu' => 3,
        'kamis' => 4,
        'jumat' => 5,
        'sabtu' => 6,
        'minggu' => 7,
    ];

    // Kelompokkan jadwal berdasarkan hari
    $jadwalPerHari = $jadwal->groupBy(function ($item) {
        return strtolower(trim($item->hari ?? 'lainnya'));
    })->sortBy(function ($items, $hari) use ($urutanHari) {
        return $urutanHari[$hari] ?? 99;
    });
@endphp

<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('waka.jadwal') }}">Jadwal</a>
            <span>/</span>

            <a href="{{ route('waka.jadwal.angkatan', $tingkat) }}">
                Kelas {{ $tingkat }}
            </a>

            <span>/</span>

            <a href="{{ route('waka.jadwal.jurusan', [
                'tingkat' => $tingkat,
                'jurusanId' => $kelas->jurusanid
            ]) }}">
                {{ $kelas->namajurusan ?? '-' }}
            </a>

            <span>/</span>
            <span>{{ $kelas->namakelas ?? '-' }}</span>
        </div>

        <h1>Jadwal {{ $kelas->namakelas ?? 'Kelas' }}</h1>

        <p>
            Daftar jadwal pembelajaran berdasarkan hari.
        </p>
    </div>

    <a
        href="{{ route('waka.jadwal.jurusan', [
            'tingkat' => $tingkat,
            'jurusanId' => $kelas->jurusanid
        ]) }}"
        class="btn-back"
    >
        <i class="fa-solid fa-arrow-left"></i>
        Kembali
    </a>
</div>

{{-- INFORMASI KELAS --}}
<div class="info-card">

    <div class="info-icon">
        <i class="fa-solid fa-school"></i>
    </div>

    <div class="info-content">
        <span>Kelas</span>
        <strong>{{ $kelas->namakelas ?? '-' }}</strong>
    </div>

    <div class="info-content">
        <span>Tingkat</span>
        <strong>Kelas {{ $tingkat }}</strong>
    </div>

    <div class="info-content">
        <span>Jurusan</span>
        <strong>{{ $kelas->namajurusan ?? '-' }}</strong>
    </div>

    <div class="info-content">
        <span>Total Jadwal</span>
        <strong>{{ $jadwal->count() }} Mata Pelajaran</strong>
    </div>

</div>

{{-- NOTIFIKASI --}}
@if(session('success'))
    <div class="alert-success">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        {{ $errors->first() }}
    </div>
@endif

{{-- PENGATURAN KUNCI JADWAL --}}
<div class="lock-card">

    <div class="lock-information">

        <div class="lock-icon">
            <i class="fa-solid fa-lock"></i>
        </div>

        <div>
            <strong>Status Penguncian Jadwal</strong>

            @if($jadwalTerkunci ?? false)
                <p class="lock-closed">
                    Jadwal sedang dikunci. Guru tidak dapat menambah atau menghapus jadwal.
                </p>
            @else
                <p class="lock-open">
                    Jadwal sedang terbuka. Guru dapat menambah atau menghapus jadwal.
                </p>
            @endif
        </div>

    </div>

    <form
        method="POST"
        action="{{ route('waka.jadwal.kunci') }}"
        onsubmit="return confirm('{{ ($jadwalTerkunci ?? false) ? 'Buka kunci seluruh jadwal?' : 'Kunci seluruh jadwal untuk semua guru?' }}')"
    >
        @csrf

        <input
            type="hidden"
            name="terkunci"
            value="{{ ($jadwalTerkunci ?? false) ? 0 : 1 }}"
        >

        @if($jadwalTerkunci ?? false)
            <button type="submit" class="btn-unlock">
                <i class="fa-solid fa-lock-open"></i>
                Buka Kunci Jadwal
            </button>
        @else
            <button type="submit" class="btn-lock">
                <i class="fa-solid fa-lock"></i>
                Kunci Semua Jadwal
            </button>
        @endif
    </form>

</div>

{{-- JUDUL DAFTAR JADWAL --}}
<div class="section-header">
    <div>
        <h2>Jadwal Pembelajaran</h2>
        <p>
            Jadwal kelas {{ $kelas->namakelas ?? '-' }}
            dikelompokkan berdasarkan hari.
        </p>
    </div>
</div>

@if($jadwal->count() > 0)

    <div class="weekly-schedule">

        @foreach($jadwalPerHari as $hari => $daftarJadwal)

            <section class="day-section">

                {{-- HEADER HARI --}}
                <div class="day-header">

                    <div class="day-heading">

                        <div class="day-icon">
                            <i class="fa-regular fa-calendar-days"></i>
                        </div>

                        <div>
                            <h3>{{ ucfirst($hari) }}</h3>
                            <p>
                                {{ $daftarJadwal->count() }} mata pelajaran
                            </p>
                        </div>

                    </div>

                    <span class="day-total">
                        {{ $daftarJadwal->count() }} Jadwal
                    </span>

                </div>

                {{-- DAFTAR MAPEL HARI TERSEBUT --}}
                <div class="day-schedule-list">

                    @foreach($daftarJadwal as $item)

                        @php
                            $namaMapel = $item->namamapel
                                ?? $item->mapel?->namamapel
                                ?? '';

                            $namaMapel = trim((string) $namaMapel);

                            if ($namaMapel === '') {
                                $namaMapel = 'Mata Pelajaran Belum Diatur';
                            }

                            $namaGuru = $item->namaguru
                                ?? $item->guru?->nama
                                ?? '-';

                            $namaRuangan = $item->namaruangan
                                ?? $item->ruangan?->namaruangan
                                ?? 'Belum ditentukan';

                            $jamMulai = $item->jamMulai ?? null;
                            $jamSelesai = $item->jamSelesai ?? null;
                        @endphp

                        <article class="subject-card">

                            {{-- WAKTU --}}
                            <div class="subject-time">

                                <div class="time-icon">
                                    <i class="fa-regular fa-clock"></i>
                                </div>

                                <div>
                                    <span class="detail-label">
                                        Waktu Pembelajaran
                                    </span>

                                    <strong>
                                        @if($jamMulai && $jamSelesai)
                                            {{ $jamMulai }} - {{ $jamSelesai }}
                                        @elseif($jamMulai)
                                            {{ $jamMulai }}
                                        @else
                                            Belum ditentukan
                                        @endif
                                    </strong>
                                </div>

                            </div>

                            {{-- INFORMASI MAPEL --}}
                            <div class="subject-information">

                                <span class="detail-label">
                                    Mata Pelajaran
                                </span>

                                <h4>{{ $namaMapel }}</h4>

                            </div>

                            {{-- GURU --}}
                            <div class="subject-detail">

                                <div class="detail-icon">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>

                                <div>
                                    <span class="detail-label">Guru Pengajar</span>
                                    <strong>{{ $namaGuru }}</strong>
                                </div>

                            </div>

                            {{-- RUANGAN --}}
                            <div class="subject-detail">

                                <div class="detail-icon">
                                    <i class="fa-solid fa-door-open"></i>
                                </div>

                                <div>
                                    <span class="detail-label">Ruangan</span>
                                    <strong>{{ $namaRuangan }}</strong>
                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

            </section>

        @endforeach

    </div>

@else

    <div class="empty-card">

        <div class="empty-icon">
            <i class="fa-regular fa-calendar-xmark"></i>
        </div>

        <h3>Belum Ada Jadwal</h3>

        <p>
            Belum terdapat jadwal pembelajaran untuk
            {{ $kelas->namakelas ?? 'kelas ini' }}.
        </p>

    </div>

@endif

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 24px;
}

.breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    color: #8997aa;
    font-size: 12px;
}

.breadcrumb a {
    color: #3973b9;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.page-header h1 {
    margin: 0;
    color: #173b72;
    font-size: 27px;
    font-weight: 750;
}

.page-header p {
    margin: 7px 0 0;
    color: #7b899d;
    font-size: 13px;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    border: 1px solid #dce6f1;
    border-radius: 9px;
    background: white;
    color: #2861a5;
    text-decoration: none;
    font-size: 13px;
    font-weight: 650;
    white-space: nowrap;
}

.btn-back:hover {
    background: #f1f6fc;
}

.info-card {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 25px;
    margin-bottom: 22px;
    padding: 19px 22px;
    background: #fff;
    border: 1px solid #e2eaf3;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(30, 70, 110, .04);
}

.info-icon {
    width: 47px;
    height: 47px;
    min-width: 47px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #edf4ff;
    color: #2864b2;
    font-size: 19px;
}

.info-content {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 110px;
}

.info-content span {
    color: #8b98a9;
    font-size: 11px;
}

.info-content strong {
    color: #294b76;
    font-size: 14px;
    font-weight: 650;
}

.alert-success,
.alert-error {
    display: flex;
    align-items: center;
    gap: 9px;
    margin: 15px 0;
    padding: 13px 15px;
    border-radius: 10px;
    font-size: 13px;
}

.alert-success {
    color: #166534;
    background: #ecfdf3;
    border: 1px solid #bbf7d0;
}

.alert-error {
    color: #b91c1c;
    background: #fef2f2;
    border: 1px solid #fecaca;
}

.lock-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 18px;
    margin: 20px 0 27px;
    padding: 17px 20px;
    background: #fff;
    border: 1px solid #e2eaf3;
    border-radius: 13px;
}

.lock-information {
    display: flex;
    align-items: center;
    gap: 13px;
}

.lock-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: #f0f4fa;
    color: #4772a5;
}

.lock-information strong {
    color: #263f60;
    font-size: 14px;
}

.lock-information p {
    margin: 5px 0 0;
    font-size: 12px;
}

.lock-open {
    color: #16834b;
}

.lock-closed {
    color: #b7791f;
}

.btn-lock,
.btn-unlock {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 15px;
    border: 0;
    border-radius: 9px;
    color: white;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.btn-lock {
    background: #b95a0b;
}

.btn-lock:hover {
    background: #984707;
}

.btn-unlock {
    background: #18834c;
}

.btn-unlock:hover {
    background: #11683c;
}

.section-header {
    margin-bottom: 17px;
}

.section-header h2 {
    margin: 0;
    color: #1f416f;
    font-size: 19px;
    font-weight: 750;
}

.section-header p {
    margin: 5px 0 0;
    color: #8491a4;
    font-size: 12px;
}

/* KELOMPOK HARI */
.weekly-schedule {
    display: flex;
    flex-direction: column;
    gap: 23px;
}

.day-section {
    overflow: hidden;
    background: #fff;
    border: 1px solid #e0e8f2;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(30, 70, 110, .035);
}

.day-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 16px 20px;
    background: #f4f8fd;
    border-bottom: 1px solid #e6edf5;
}

.day-heading {
    display: flex;
    align-items: center;
    gap: 12px;
}

.day-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: #e5efff;
    color: #2864b2;
    font-size: 17px;
}

.day-heading h3 {
    margin: 0;
    color: #204777;
    font-size: 16px;
    font-weight: 750;
    text-transform: capitalize;
}

.day-heading p {
    margin: 4px 0 0;
    color: #8996a8;
    font-size: 11px;
}

.day-total {
    padding: 6px 10px;
    border-radius: 20px;
    background: #e7f0fc;
    color: #3469a7;
    font-size: 11px;
    font-weight: 650;
    white-space: nowrap;
}

.day-schedule-list {
    display: flex;
    flex-direction: column;
    padding: 0 20px;
}

.subject-card {
    display: grid;
    grid-template-columns: 190px minmax(180px, 1.5fr) minmax(160px, 1fr) minmax(140px, .8fr);
    align-items: center;
    gap: 20px;
    padding: 19px 0;
    border-bottom: 1px solid #edf1f6;
}

.subject-card:last-child {
    border-bottom: none;
}

.subject-time {
    display: flex;
    align-items: center;
    gap: 11px;
}

.time-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 39px;
    height: 39px;
    min-width: 39px;
    border-radius: 10px;
    background: #edf4ff;
    color: #3974bd;
    font-size: 16px;
}

.subject-time strong {
    display: block;
    margin-top: 5px;
    color: #28568c;
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
}

.detail-label {
    display: block;
    margin-bottom: 6px;
    color: #8997aa;
    font-size: 10px;
    font-weight: 500;
}

.subject-information h4 {
    margin: 0;
    color: #193e6d;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.subject-detail {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.detail-icon {
    width: 33px;
    height: 33px;
    min-width: 33px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #f3f6fa;
    color: #6480a1;
    font-size: 13px;
}

.subject-detail strong {
    display: block;
    color: #405a79;
    font-size: 12px;
    font-weight: 600;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.empty-card {
    padding: 55px 20px;
    text-align: center;
    background: #fff;
    border: 1px solid #e2eaf3;
    border-radius: 14px;
}

.empty-icon {
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    border-radius: 13px;
    background: #f0f4f9;
    color: #8495aa;
    font-size: 22px;
}

.empty-card h3 {
    margin: 0 0 7px;
    color: #344d6d;
    font-size: 17px;
}

.empty-card p {
    margin: 0;
    color: #8795a8;
    font-size: 13px;
}

@media (max-width: 1100px) {
    .subject-card {
        grid-template-columns: 180px 1.3fr 1fr;
    }

    .subject-detail:last-child {
        grid-column: 2 / 4;
    }
}

@media (max-width: 700px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .page-header h1 {
        font-size: 23px;
    }

    .info-card {
        gap: 18px;
    }

    .lock-card {
        align-items: flex-start;
    }

    .lock-card form {
        width: 100%;
    }

    .lock-card button {
        width: 100%;
    }

    .day-schedule-list {
        padding: 0 14px;
    }

    .subject-card {
        grid-template-columns: 1fr 1fr;
        gap: 16px 12px;
        padding: 17px 0;
    }

    .subject-information {
        grid-column: 1 / -1;
        grid-row: 1;
    }

    .subject-time {
        grid-column: 1 / -1;
    }

    .subject-detail:last-child {
        grid-column: auto;
    }

    .day-header {
        padding: 14px;
    }
}

@media (max-width: 420px) {
    .subject-card {
        grid-template-columns: 1fr;
    }

    .subject-information,
    .subject-time,
    .subject-detail,
    .subject-detail:last-child {
        grid-column: 1;
    }

    .day-total {
        font-size: 10px;
    }
}
</style>

@endsection