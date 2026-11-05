@extends('layouts.app')

@section('title', 'Jadwal Sekolah')

@section('content')

<div class="jadwal-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="page-header">

        <div>

            <div class="page-label">
                JADWAL SEKOLAH
            </div>

            <h1>
                Jadwal Sekolah
            </h1>

            <p>
                Pilih angkatan untuk melihat jadwal berdasarkan kelas.
            </p>

        </div>


        <a
            href="{{ route('waka.dashboard') }}"
            class="btn-back"
        >

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path d="M19 12H5"/>
                <path d="M12 19l-7-7 7-7"/>
            </svg>

            Kembali

        </a>

    </div>


    {{-- =====================================================
         ALERT SUCCESS
    ====================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="M20 6L9 17l-5-5"/>
            </svg>

            <div>

                <strong>
                    Berhasil
                </strong>

                <p>
                    {{ session('success') }}
                </p>

            </div>

        </div>

    @endif


    {{-- =====================================================
         KONTROL KUNCI SELURUH JADWAL
    ====================================================== --}}

    <div style="display:flex;align-items:center;justify-content:space-between;gap:18px;flex-wrap:wrap;margin:0 0 22px;padding:18px 20px;background:#fff;border:1px solid #e1e9f3;border-radius:14px;">
        <div>
            <strong style="display:block;color:#243b53;font-size:13px;">Status Penguncian Jadwal</strong>
            @if($jadwalTerkunci ?? false)
                <span style="display:block;margin-top:5px;color:#b45309;font-size:11px;">Semua jadwal terkunci. Guru tidak dapat menambah atau menghapus jadwal.</span>
            @else
                <span style="display:block;margin-top:5px;color:#15803d;font-size:11px;">Semua jadwal terbuka. Guru dapat mengelola jadwalnya.</span>
            @endif
        </div>
        <form method="POST" action="{{ route('waka.jadwal.kunci') }}" onsubmit="return confirm('{{ ($jadwalTerkunci ?? false) ? 'Buka kunci seluruh jadwal?' : 'Kunci seluruh jadwal untuk semua guru?' }}')">
            @csrf
            <input type="hidden" name="terkunci" value="{{ ($jadwalTerkunci ?? false) ? 0 : 1 }}">
            @if($jadwalTerkunci ?? false)
                <button type="submit" class="btn-back" style="border:0;background:#15803d;color:#fff;cursor:pointer;">Buka Kunci Jadwal</button>
            @else
                <button type="submit" class="btn-back" style="border:0;background:#b45309;color:#fff;cursor:pointer;">Kunci Semua Jadwal</button>
            @endif
        </form>
    </div>


    {{-- =====================================================
         INFO
    ====================================================== --}}

    <div class="info-card">

        <div class="info-icon">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.7"
            >
                <rect
                    x="3"
                    y="4"
                    width="18"
                    height="17"
                    rx="2"
                />

                <path d="M16 2v4"/>
                <path d="M8 2v4"/>
                <path d="M3 10h18"/>

            </svg>

        </div>


        <div>

            <strong>
                Pilih Angkatan
            </strong>

            <p>
                Pilih tingkat kelas terlebih dahulu untuk melihat
                daftar jurusan dan kelas yang tersedia.
            </p>

        </div>

    </div>


    {{-- =====================================================
         ANGKATAN
    ====================================================== --}}

    <div class="section-header">

        <div>

            <h2>
                Angkatan
            </h2>

            <p>
                Pilih tingkat kelas yang ingin dilihat.
            </p>

        </div>

    </div>


    <div class="angkatan-grid">

        @foreach($daftarAngkatan as $angkatan)

            <a
                href="{{ route('waka.jadwal.angkatan', $angkatan->tingkat) }}"
                class="angkatan-card"
            >

                <div class="angkatan-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                    >
                        <path d="M3 10l9-6 9 6"/>
                        <path d="M5 10v9h14v-9"/>
                        <path d="M9 19v-6h6v6"/>
                    </svg>

                </div>


                <div class="angkatan-content">

                    <span>
                        Tingkat
                    </span>

                    <strong>
                        Kelas {{ $angkatan->tingkat }}
                    </strong>

                    <small>
                        Lihat jurusan dan kelas
                    </small>

                </div>


                <div class="arrow-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M9 18l6-6-6-6"/>
                    </svg>

                </div>

            </a>

        @endforeach

    </div>


</div>

@endsection


@push('styles')

<style>

/* =========================================================
   PAGE
========================================================= */

.jadwal-page {
    width: 100%;
}


/* =========================================================
   HEADER
========================================================= */

.page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 20px;

    margin-bottom: 24px;
}

.page-label {
    margin-bottom: 6px;

    color: #2563eb;

    font-size: 9px;
    font-weight: 700;

    letter-spacing: 1.3px;
}

.page-header h1 {
    margin: 0 0 6px;

    color: #102a43;

    font-size: 25px;
    font-weight: 750;

    letter-spacing: -.4px;
}

.page-header p {
    margin: 0;

    color: #718096;

    font-size: 13px;
}


/* =========================================================
   BACK BUTTON
========================================================= */

.btn-back {
    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 10px 15px;

    background: #eef5ff;

    border-radius: 10px;

    color: #2563eb;

    font-size: 12px;
    font-weight: 600;

    text-decoration: none;

    white-space: nowrap;

    transition: .2s;
}

.btn-back:hover {
    background: #e0edff;
}

.btn-back svg {
    width: 16px;
    height: 16px;
}


/* =========================================================
   ALERT
========================================================= */

.alert {
    display: flex;

    align-items: flex-start;

    gap: 10px;

    padding: 13px 15px;

    margin-bottom: 18px;

    border-radius: 10px;

    background: #f0fdf4;

    border: 1px solid #bbf7d0;

    color: #166534;
}

.alert > svg {
    width: 18px;
    height: 18px;

    min-width: 18px;

    margin-top: 1px;
}

.alert strong {
    display: block;

    margin-bottom: 2px;

    font-size: 12px;
}

.alert p {
    margin: 0;

    font-size: 11px;

    line-height: 1.5;
}


/* =========================================================
   INFO CARD
========================================================= */

.info-card {
    display: flex;

    align-items: flex-start;

    gap: 12px;

    padding: 15px 17px;

    margin-bottom: 25px;

    background: #f4f8ff;

    border: 1px solid #dce9f9;

    border-radius: 13px;
}

.info-icon {
    width: 38px;
    height: 38px;

    min-width: 38px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #e6f0ff;

    border-radius: 10px;

    color: #2563eb;
}

.info-icon svg {
    width: 19px;
    height: 19px;
}

.info-card strong {
    display: block;

    margin-bottom: 3px;

    color: #1e3a5f;

    font-size: 12px;
}

.info-card p {
    margin: 0;

    color: #718096;

    font-size: 10px;

    line-height: 1.5;
}


/* =========================================================
   SECTION
========================================================= */

.section-header {
    margin-bottom: 13px;
}

.section-header h2 {
    margin: 0 0 4px;

    color: #243b53;

    font-size: 16px;

    font-weight: 700;
}

.section-header p {
    margin: 0;

    color: #8492a6;

    font-size: 11px;
}


/* =========================================================
   ANGKATAN GRID
========================================================= */

.angkatan-grid {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 15px;
}


/* =========================================================
   ANGKATAN CARD
========================================================= */

.angkatan-card {
    position: relative;

    display: flex;

    align-items: center;

    gap: 13px;

    min-height: 120px;

    padding: 17px;

    background: #ffffff;

    border: 1px solid #e1e9f3;

    border-radius: 14px;

    color: inherit;

    text-decoration: none;

    box-shadow:
        0 5px 18px rgba(15, 50, 90, .04);

    transition:
        transform .2s ease,
        border-color .2s ease,
        box-shadow .2s ease;
}

.angkatan-card:hover {
    transform: translateY(-2px);

    border-color: #c8dcf5;

    box-shadow:
        0 9px 25px rgba(15, 50, 90, .08);
}


/* =========================================================
   ICON
========================================================= */

.angkatan-icon {
    width: 50px;
    height: 50px;

    min-width: 50px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #eef5ff;

    border-radius: 13px;

    color: #2563eb;
}

.angkatan-icon svg {
    width: 24px;
    height: 24px;
}


/* =========================================================
   CONTENT
========================================================= */

.angkatan-content {
    min-width: 0;

    flex: 1;
}

.angkatan-content span {
    display: block;

    margin-bottom: 3px;

    color: #94a3b8;

    font-size: 9px;

    text-transform: uppercase;

    letter-spacing: .7px;
}

.angkatan-content strong {
    display: block;

    margin-bottom: 4px;

    color: #243b53;

    font-size: 17px;

    font-weight: 750;
}

.angkatan-content small {
    display: block;

    color: #718096;

    font-size: 9px;
}


/* =========================================================
   ARROW
========================================================= */

.arrow-icon {
    width: 30px;
    height: 30px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 8px;

    background: #f5f8fc;

    color: #64748b;
}

.arrow-icon svg {
    width: 16px;
    height: 16px;
}

.angkatan-card:hover .arrow-icon {
    background: #eef5ff;

    color: #2563eb;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {

    .angkatan-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


@media (max-width: 600px) {

    .page-header {
        flex-direction: column;
    }

    .btn-back {
        align-self: flex-start;
    }

    .angkatan-grid {
        grid-template-columns: 1fr;
    }

}

</style>

@endpush