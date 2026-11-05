@extends('layouts.app')

@section('title', 'Jadwal Kelas - SCP')

@section('content')

<div class="page-header">

    <div>

        <div class="breadcrumb">

            <a href="{{ route('waka.jadwal') }}">
                Jadwal
            </a>

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

            <span>
                {{ $kelas->namakelas ?? '-' }}
            </span>

        </div>


        <h1>
            Jadwal {{ $kelas->namakelas ?? 'Kelas' }}
        </h1>

        <p>
            Jadwal pembelajaran lengkap untuk kelas ini.
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


{{-- =========================================================
     INFORMASI KELAS
========================================================= --}}

<div class="info-card">

    <div class="info-icon">
        <i class="fa-solid fa-school"></i>
    </div>


    <div class="info-content">

        <span class="info-label">
            Kelas
        </span>

        <strong>
            {{ $kelas->namakelas ?? '-' }}
        </strong>

    </div>


    <div class="info-content">

        <span class="info-label">
            Tingkat
        </span>

        <strong>
            Kelas {{ $tingkat }}
        </strong>

    </div>


    <div class="info-content">

        <span class="info-label">
            Jurusan
        </span>

        <strong>
            {{ $kelas->namajurusan ?? '-' }}
        </strong>

    </div>


    <div class="info-content">

        <span class="info-label">
            Jumlah Jadwal
        </span>

        <strong>
            {{ $jadwal->count() }} Jadwal
        </strong>

    </div>

</div>


{{-- =========================================================
     KONTROL KUNCI SELURUH JADWAL
========================================================= --}}

@if(session('success'))
    <div style="margin:18px 0;padding:13px 16px;border:1px solid #bbf7d0;background:#ecfdf3;color:#166534;border-radius:10px;">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div style="margin:18px 0;padding:13px 16px;border:1px solid #fecaca;background:#fef2f2;color:#b91c1c;border-radius:10px;">{{ $errors->first() }}</div>
@endif

<div style="display:flex;align-items:center;justify-content:space-between;gap:18px;flex-wrap:wrap;margin:20px 0;padding:18px 20px;background:#fff;border:1px solid #e5eaf2;border-radius:14px;">
    <div>
        <strong style="display:block;color:#172033;font-size:15px;">Status Penguncian Jadwal</strong>
        @if($jadwalTerkunci ?? false)
            <span style="display:inline-block;margin-top:6px;color:#b45309;font-size:13px;">Seluruh jadwal sedang dikunci. Guru tidak dapat menambah atau menghapus jadwal.</span>
        @else
            <span style="display:inline-block;margin-top:6px;color:#15803d;font-size:13px;">Jadwal terbuka. Guru dapat menambahkan dan menghapus jadwal.</span>
        @endif
    </div>

    <form method="POST" action="{{ route('waka.jadwal.kunci') }}" onsubmit="return confirm('{{ ($jadwalTerkunci ?? false) ? 'Buka kunci seluruh jadwal?' : 'Kunci seluruh jadwal untuk semua guru?' }}')">
        @csrf
        <input type="hidden" name="terkunci" value="{{ ($jadwalTerkunci ?? false) ? 0 : 1 }}">
        @if($jadwalTerkunci ?? false)
            <button type="submit" style="border:0;border-radius:9px;padding:11px 17px;background:#15803d;color:#fff;font-weight:700;cursor:pointer;">Buka Kunci Jadwal</button>
        @else
            <button type="submit" style="border:0;border-radius:9px;padding:11px 17px;background:#b45309;color:#fff;font-weight:700;cursor:pointer;">Kunci Semua Jadwal</button>
        @endif
    </form>
</div>

{{-- =========================================================
     JADWAL
========================================================= --}}

<div class="section-header">

    <div>

        <h2>
            Jadwal Pembelajaran
        </h2>

        <p>
            Daftar jadwal pembelajaran untuk
            {{ $kelas->namakelas ?? 'kelas ini' }}.
        </p>

    </div>

</div>


@if ($jadwal->count() > 0)

    <div class="schedule-list">

        @foreach ($jadwal as $item)

            <div class="schedule-card">

                {{-- HARI --}}

                <div class="schedule-day">

                    <div class="day-icon">
                        <i class="fa-regular fa-calendar"></i>
                    </div>

                    <div>

                        <span class="day-label">
                            Hari
                        </span>

                        <strong>
                            {{ ucfirst($item->hari ?? '-') }}
                        </strong>

                    </div>

                </div>


                {{-- JAM --}}

                <div class="schedule-time">

                    <span class="schedule-label">
                        Waktu
                    </span>

                    <strong>

                        @if (!empty($item->jamMulai) && !empty($item->jamSelesai))

                            {{ $item->jamMulai }}
                            -
                            {{ $item->jamSelesai }}

                        @elseif (!empty($item->jamMulai))

                            {{ $item->jamMulai }}

                        @else

                            -

                        @endif

                    </strong>

                </div>


                {{-- MAPEL --}}

                <div class="schedule-subject">

                    <span class="schedule-label">
                        Mata Pelajaran
                    </span>

                    <strong>
                        {{ $item->mapel ?? $item->namamapel ?? '-' }}
                    </strong>

                </div>


                {{-- GURU --}}

                <div class="schedule-teacher">

                    <span class="schedule-label">
                        Guru
                    </span>

                    <strong>

                        @if (isset($item->guru) && $item->guru)

                            {{ $item->guru->nama ?? '-' }}

                        @elseif (!empty($item->namaguru))

                            {{ $item->namaguru }}

                        @else

                            -

                        @endif

                    </strong>

                </div>


                {{-- RUANGAN --}}

                <div class="schedule-room">

                    <span class="schedule-label">
                        Ruangan
                    </span>

                    <strong>

                        @if (isset($item->ruangan) && $item->ruangan)

                            {{ $item->ruangan->namaruangan ?? '-' }}

                        @elseif (!empty($item->namaruangan))

                            {{ $item->namaruangan }}

                        @else

                            Belum ditentukan

                        @endif

                    </strong>

                </div>

            </div>

        @endforeach

    </div>

@else

    <div class="empty-card">

        <div class="empty-icon">
            <i class="fa-regular fa-calendar-xmark"></i>
        </div>

        <h3>
            Belum Ada Jadwal
        </h3>

        <p>
            Belum terdapat jadwal pembelajaran untuk
            {{ $kelas->namakelas ?? 'kelas ini' }}.
        </p>

    </div>

@endif


<style>

/* =========================================================
   PAGE HEADER
========================================================= */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 25px;
}

.breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    font-size: 13px;
    color: #8a98aa;
}

.breadcrumb a {
    color: #4b72a8;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.page-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #173b72;
}

.page-header p {
    margin: 7px 0 0;
    color: #718096;
    font-size: 14px;
}


/* =========================================================
   BACK BUTTON
========================================================= */

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 10px;
    background: #ffffff;
    border: 1px solid #dbe5f1;
    color: #2459a6;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: .2s ease;
}

.btn-back:hover {
    background: #f3f7fc;
    border-color: #bfd0e6;
}


/* =========================================================
   INFO CARD
========================================================= */

.info-card {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 28px;
    padding: 20px 22px;
    background: #ffffff;
    border: 1px solid #e2eaf3;
    border-radius: 16px;
    box-shadow: 0 5px 18px rgba(30, 70, 110, .05);
}

.info-icon {
    width: 50px;
    height: 50px;
    min-width: 50px;

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

    min-width: 140px;
}

.info-label {
    color: #8795a8;
    font-size: 12px;
}

.info-content strong {
    color: #284b78;
    font-size: 15px;
}


/* =========================================================
   SECTION HEADER
========================================================= */

.section-header {
    margin-bottom: 16px;
}

.section-header h2 {
    margin: 0;

    color: #1f3f6d;
    font-size: 20px;
}

.section-header p {
    margin: 5px 0 0;

    color: #7b8798;
    font-size: 13px;
}


/* =========================================================
   SCHEDULE LIST
========================================================= */

.schedule-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}


/* =========================================================
   SCHEDULE CARD
========================================================= */

.schedule-card {
    display: grid;

    grid-template-columns:
        1.1fr
        1fr
        1.5fr
        1.4fr
        1.2fr;

    align-items: center;

    gap: 20px;

    padding: 20px;

    background: #ffffff;

    border: 1px solid #e2eaf3;

    border-radius: 16px;

    box-shadow: 0 5px 18px rgba(30, 70, 110, .05);

    transition: .2s ease;
}

.schedule-card:hover {
    border-color: #bfd0e6;

    box-shadow:
        0 9px 25px rgba(30, 70, 110, .09);

    transform: translateY(-1px);
}


/* =========================================================
   DAY
========================================================= */

.schedule-day {
    display: flex;
    align-items: center;
    gap: 12px;
}

.day-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #edf4ff;
    color: #2864b2;

    font-size: 17px;
}

.day-label {
    display: block;

    margin-bottom: 4px;

    color: #8a98aa;

    font-size: 11px;
}

.schedule-day strong {
    color: #234875;
    font-size: 15px;
}


/* =========================================================
   OTHER COLUMNS
========================================================= */

.schedule-time,
.schedule-subject,
.schedule-teacher,
.schedule-room {
    min-width: 0;
}

.schedule-label {
    display: block;

    margin-bottom: 5px;

    color: #8a98aa;

    font-size: 11px;
}

.schedule-time strong,
.schedule-subject strong,
.schedule-teacher strong,
.schedule-room strong {
    display: block;

    color: #294d78;

    font-size: 14px;

    font-weight: 600;

    overflow-wrap: anywhere;
}


/* =========================================================
   EMPTY
========================================================= */

.empty-card {
    padding: 60px 25px;

    text-align: center;

    background: #ffffff;

    border: 1px solid #e2eaf3;

    border-radius: 16px;
}

.empty-icon {
    width: 58px;
    height: 58px;

    margin: 0 auto 15px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    background: #f2f5f9;

    color: #8795a8;

    font-size: 22px;
}

.empty-card h3 {
    margin: 0 0 8px;

    color: #34495e;

    font-size: 18px;
}

.empty-card p {
    max-width: 500px;

    margin: 0 auto;

    color: #8795a8;

    font-size: 14px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .schedule-card {
        grid-template-columns:
            1fr
            1fr
            1fr;
    }

}


@media (max-width: 900px) {

    .info-card {
        flex-wrap: wrap;
    }

    .schedule-card {
        grid-template-columns: 1fr 1fr;
    }

}


@media (max-width: 600px) {

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .page-header h1 {
        font-size: 23px;
    }

    .info-card {
        flex-direction: column;
        align-items: flex-start;
    }

    .info-content {
        min-width: 0;
    }

    .schedule-card {
        grid-template-columns: 1fr;
        gap: 16px;
    }

}

</style>

@endsection