@extends('layouts.app')

@section('title', 'Jadwal ' . $jurusan->namajurusan)

@section('content')

<div class="jadwal-page">

    <div class="page-header">

        <div>

            <div class="page-label">
                JADWAL SEKOLAH
            </div>

            <h1>
                {{ $jurusan->namajurusan }}
            </h1>

            <p>
                Kelas {{ $tingkat }} · Pilih kelas untuk melihat jadwal.
            </p>

        </div>

        <a
            href="{{ route('waka.jadwal.angkatan', $tingkat) }}"
            class="btn-back"
        >
            ← Kembali
        </a>

    </div>


    <div class="kelas-grid">

        @forelse($dataKelas as $kelas)

            <a
                href="{{ route(
                    'waka.jadwal.kelas',
                    [
                        $tingkat,
                        $jurusan->id,
                        $kelas->id
                    ]
                ) }}"
                class="kelas-card"
            >

                <div class="kelas-icon">

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

                <div class="kelas-content">

                    <span>
                        Kelas
                    </span>

                    <strong>
                        {{ $kelas->namakelas }}
                    </strong>

                    <small>
                        {{ $kelas->jumlah_jadwal }} jadwal
                    </small>

                </div>

                <div class="arrow">
                    →
                </div>

            </a>

        @empty

            <div class="empty">
                Belum ada kelas pada jurusan ini.
            </div>

        @endforelse

    </div>

</div>

@endsection


@push('styles')

<style>

.jadwal-page {
    width: 100%;
}

.page-header {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 28px;
}

.page-label {
    margin-bottom: 6px;
    color: #2563eb;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.2px;
}

.page-header h1 {
    margin: 0 0 5px;
    color: #102a43;
    font-size: 25px;
}

.page-header p {
    margin: 0;
    color: #718096;
    font-size: 13px;
}

.btn-back {
    height: 38px;
    display: inline-flex;
    align-items: center;
    padding: 0 14px;
    border-radius: 9px;
    background: #eef5ff;
    color: #2563eb;
    text-decoration: none;
    font-size: 11px;
    font-weight: 600;
}

.kelas-grid {
    display: grid;
    grid-template-columns: repeat(3,minmax(0,1fr));
    gap: 15px;
}

.kelas-card {
    display: flex;
    align-items: center;
    gap: 13px;
    min-height: 110px;
    padding: 17px;
    background: #fff;
    border: 1px solid #e1e9f3;
    border-radius: 14px;
    text-decoration: none;
    box-shadow: 0 5px 18px rgba(15,50,90,.04);
    transition: .2s;
}

.kelas-card:hover {
    transform: translateY(-2px);
    border-color: #c8dcf5;
    box-shadow: 0 9px 25px rgba(15,50,90,.08);
}

.kelas-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef5ff;
    border-radius: 12px;
    color: #2563eb;
}

.kelas-icon svg {
    width: 23px;
    height: 23px;
}

.kelas-content {
    flex: 1;
}

.kelas-content span {
    display: block;
    color: #94a3b8;
    font-size: 9px;
    margin-bottom: 3px;
}

.kelas-content strong {
    display: block;
    color: #243b53;
    font-size: 15px;
}

.kelas-content small {
    display: block;
    margin-top: 5px;
    color: #718096;
    font-size: 9px;
}

.arrow {
    color: #94a3b8;
    font-size: 18px;
}

.kelas-card:hover .arrow {
    color: #2563eb;
}

.empty {
    padding: 40px;
    background: #fff;
    border: 1px solid #e1e9f3;
    border-radius: 14px;
    color: #718096;
    font-size: 12px;
    text-align: center;
}

@media(max-width:900px) {
    .kelas-grid {
        grid-template-columns: repeat(2,1fr);
    }
}

@media(max-width:600px) {
    .page-header {
        flex-direction: column;
    }

    .kelas-grid {
        grid-template-columns: 1fr;
    }
}

</style>

@endpush