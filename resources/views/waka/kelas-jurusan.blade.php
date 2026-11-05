{{-- =========================================================
     DATA KELAS - DAFTAR KELAS DALAM JURUSAN
     File:
     resources/views/waka/kelas-jurusan.blade.php
     ========================================================= --}}

@extends('layouts.app')

@section('title', 'Daftar Kelas - SCP')

@section('content')

<div class="page-header">

    <div>

        <div class="back-link-wrapper">

            <a
                href="{{ route(
                    'waka.kelas.angkatan',
                    ['tingkat' => $tingkat]
                ) }}"
                class="back-link"
            >
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Jurusan</span>
            </a>

        </div>

        <h1>
            {{ $jurusan->kodejurusan }}
        </h1>

        <p>
            {{ $jurusan->namajurusan }}
            <span class="separator">•</span>
            Angkatan {{ $tingkat }}
        </p>

    </div>

</div>


<div class="class-summary">

    <div class="summary-card">

        <div class="summary-icon">
            <i class="fa-solid fa-layer-group"></i>
        </div>

        <div>

            <span>Jumlah Kelas</span>

            <strong>
                {{ $dataKelas->count() }}
            </strong>

        </div>

    </div>


    <div class="summary-card">

        <div class="summary-icon">
            <i class="fa-solid fa-users"></i>
        </div>

        <div>

            <span>Jumlah Siswa</span>

            <strong>
                {{ $dataKelas->sum('jumlah_siswa') }}
            </strong>

        </div>

    </div>

</div>


<div class="card">

    <div class="card-header">

        <div>

            <h3>Daftar Kelas</h3>

            <p>
                Pilih kelas untuk melihat siswa atau mengubah nama kelas.
            </p>

        </div>

        <span class="jumlah-badge">
            {{ $dataKelas->count() }} Kelas
        </span>

    </div>


    <div class="card-body">

        @if($dataKelas->count() > 0)

            <div class="kelas-list">

                @foreach($dataKelas as $kelas)

                    <div class="kelas-item">

                        <div class="kelas-icon">

                            <i class="fa-solid fa-school"></i>

                        </div>


                        <div class="kelas-info">

                            <strong>
                                {{ $kelas->namakelas }}
                            </strong>

                            <span>
                                {{ $kelas->jumlah_siswa }} siswa
                            </span>

                        </div>


                        <div class="kelas-actions">

                            <a
                                href="{{ route(
                                    'waka.kelas.siswa',
                                    ['id' => $kelas->id]
                                ) }}"
                                class="btn btn-secondary btn-sm"
                            >
                                <i class="fa-solid fa-users"></i>
                                Lihat Siswa
                            </a>


                            <a
                                href="{{ route(
                                    'waka.kelas.edit',
                                    ['id' => $kelas->id]
                                ) }}"
                                class="btn btn-primary btn-sm"
                            >
                                <i class="fa-solid fa-pen"></i>
                                Edit
                            </a>


                            <form
                                action="{{ route(
                                    'waka.kelas.hapus',
                                    ['id' => $kelas->id]
                                ) }}"
                                method="POST"
                                onsubmit="return konfirmasiHapusKelas();"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                    Hapus
                                </button>

                            </form>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="empty-state">

                <div class="empty-state-icon">

                    <i class="fa-solid fa-school"></i>

                </div>

                <h3>Belum Ada Kelas</h3>

                <p>
                    Belum ada kelas untuk jurusan
                    {{ $jurusan->kodejurusan }}
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
        transform: translateX(-1px);
    }

    .separator {
        margin: 0 5px;
        color: #94a3b8;
    }

    .class-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .summary-card {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 15px 17px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
    }

    .summary-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
    }

    .summary-card span {
        display: block;
        color: #64748b;
        font-size: 12px;
        margin-bottom: 3px;
    }

    .summary-card strong {
        color: #0f172a;
        font-size: 20px;
    }

    .jumlah-badge {
        padding: 6px 10px;
        border-radius: 999px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 12px;
        font-weight: 700;
    }

    .kelas-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .kelas-item {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 11px;
        background: #ffffff;
        transition: 0.2s ease;
    }

    .kelas-item:hover {
        border-color: #bfdbfe;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
    }

    .kelas-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
    }

    .kelas-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .kelas-info strong {
        color: #0f172a;
        font-size: 15px;
    }

    .kelas-info span {
        color: #64748b;
        font-size: 12px;
    }

    .kelas-actions {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
    }

    .btn-sm {
        padding: 8px 11px;
        font-size: 12px;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: 1px solid #dbe4f0;
        background: #ffffff;
        color: #334155;
        border-radius: 8px;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: 1px solid #fecaca;
        background: #fff1f2;
        color: #dc2626;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn-danger:hover {
        background: #fee2e2;
    }

    @media (max-width: 700px) {

        .class-summary {
            grid-template-columns: 1fr;
        }

        .kelas-item {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .kelas-info {
            min-width: calc(100% - 60px);
        }

        .kelas-actions {
            width: 100%;
            margin-left: 55px;
        }

    }

</style>

@endpush


@push('scripts')

<script>

    function konfirmasiHapusKelas()
    {
        return confirm(
            'Apakah kamu yakin ingin menghapus kelas ini?'
        );
    }

</script>

@endpush