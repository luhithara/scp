{{-- =========================================================
     DATA KELAS - PILIH JURUSAN
     File:
     resources/views/waka/kelas-angkatan.blade.php
     ========================================================= --}}

@extends('layouts.app')

@section('title', 'Pilih Jurusan - SCP')

@section('content')

<div class="page-header">

    <div>

        <div class="back-link-wrapper">

            <a
                href="{{ route('waka.kelas') }}"
                class="back-link"
            >
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Angkatan</span>
            </a>

        </div>

        <h1>Angkatan {{ $tingkat }}</h1>

        <p>
            Pilih jurusan untuk melihat kelas yang tersedia.
        </p>

    </div>

</div>


<div class="card">

    <div class="card-header">

        <div>

            <h3>Pilih Jurusan</h3>

            <p>
                Pilih jurusan untuk melihat daftar kelas.
            </p>

        </div>

        <span class="jumlah-badge">
            {{ $daftarJurusan->count() }} Jurusan
        </span>

    </div>


    <div class="card-body">

        @if($daftarJurusan->count() > 0)

            <div class="jurusan-grid">

                @foreach($daftarJurusan as $jurusan)

                    <a
                        href="{{ route(
                            'waka.kelas.jurusan',
                            [
                                'tingkat' => $tingkat,
                                'jurusanId' => $jurusan->id
                            ]
                        ) }}"
                        class="jurusan-card"
                    >

                        <div class="jurusan-icon">

                            <i class="fa-solid fa-graduation-cap"></i>

                        </div>


                        <div class="jurusan-info">

                            <strong>
                                {{ $jurusan->kodejurusan }}
                            </strong>

                            <span>
                                {{ $jurusan->namajurusan }}
                            </span>

                            <small>
                                {{ $jurusan->jumlah_kelas }}
                                kelas
                            </small>

                        </div>


                        <div class="jurusan-arrow">

                            <i class="fa-solid fa-chevron-right"></i>

                        </div>

                    </a>

                @endforeach

            </div>

        @else

            <div class="empty-state">

                <div class="empty-state-icon">

                    <i class="fa-solid fa-folder-open"></i>

                </div>

                <h3>Belum Ada Jurusan</h3>

                <p>
                    Belum ada jurusan yang memiliki kelas
                    pada angkatan {{ $tingkat }}.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection


@push('styles')

<style>

    .back-link-wrapper {
        margin-bottom: 10px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border: 1px solid #dbe4f0;
        border-radius: 9px;
        background: #ffffff;
        color: #2563eb;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: 0.2s ease;
    }

    .back-link:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
    }

    .jumlah-badge {
        padding: 6px 10px;
        border-radius: 999px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 12px;
        font-weight: 700;
    }

    .jurusan-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .jurusan-card {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #ffffff;
        color: inherit;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .jurusan-card:hover {
        border-color: #2563eb;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.07);
        transform: translateY(-1px);
    }

    .jurusan-icon {
        width: 44px;
        height: 44px;
        min-width: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 17px;
    }

    .jurusan-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .jurusan-info strong {
        color: #0f172a;
        font-size: 15px;
    }

    .jurusan-info span {
        color: #64748b;
        font-size: 12px;
        line-height: 1.4;
    }

    .jurusan-info small {
        color: #2563eb;
        font-size: 11px;
        font-weight: 600;
    }

    .jurusan-arrow {
        color: #94a3b8;
        font-size: 13px;
    }

    @media (max-width: 900px) {

        .jurusan-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 600px) {

        .jurusan-grid {
            grid-template-columns: 1fr;
        }

    }

</style>

@endpush