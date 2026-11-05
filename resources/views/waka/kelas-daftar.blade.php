@extends('layouts.app')

@section('title', 'Daftar Kelas - SCP')

@section('content')

<div class="page-header">

    <div>

        <div style="margin-bottom:12px;">

            <a
                href="{{ route(
                    'waka.kelas.angkatan',
                    $tingkat
                ) }}"
                class="back-link"
            >
                <span class="back-link-icon">
                    <i class="fa-solid fa-arrow-left"></i>
                </span>

                <span>
                    Kembali ke Jurusan
                </span>
            </a>

        </div>


        <h1>
            {{ $jurusan->kodejurusan }}
        </h1>

        <p>
            {{ $jurusan->namajurusan }}
            · Angkatan {{ $tingkat }}
        </p>

    </div>

</div>


<div class="card">

    <div class="card-header">

        <div>

            <h3>Daftar Kelas</h3>

            <p>
                Pilih kelas untuk melihat dan mengelola data siswa.
            </p>

        </div>

        <span class="data-count">
            {{ $dataKelas->count() }} Kelas
        </span>

    </div>


    <div class="card-body">

        @if($dataKelas->count() > 0)

            <div class="kelas-grid">

                @foreach($dataKelas as $kelas)

                    <div class="kelas-card">

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
                                    'waka.kelas.edit',
                                    $kelas->id
                                ) }}"
                                class="action-btn action-edit"
                                title="Edit kelas"
                            >
                                <i class="fa-solid fa-pen"></i>
                            </a>


                            <form
                                action="{{ route(
                                    'waka.kelas.hapus',
                                    $kelas->id
                                ) }}"
                                method="POST"
                                onsubmit="return konfirmasiHapusKelas('{{ $kelas->namakelas }}')"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="action-btn action-delete"
                                    title="Hapus kelas"
                                >
                                    <i class="fa-solid fa-trash"></i>
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

    .back-link {
        display:inline-flex;
        align-items:center;
        gap:9px;
        text-decoration:none;
        color:#2563eb;
        font-size:14px;
        font-weight:600;
    }

    .back-link-icon {
        width:30px;
        height:30px;
        display:flex;
        align-items:center;
        justify-content:center;
        border:1px solid #dbeafe;
        border-radius:8px;
        background:#eff6ff;
        transition:.2s ease;
    }

    .back-link:hover .back-link-icon {
        background:#2563eb;
        border-color:#2563eb;
        color:#fff;
    }

    .data-count {
        padding:6px 11px;
        border-radius:20px;
        background:#eff6ff;
        color:#2563eb;
        font-size:12px;
        font-weight:600;
    }

    .kelas-grid {
        display:grid;
        grid-template-columns:repeat(2, minmax(0, 1fr));
        gap:14px;
    }

    .kelas-card {
        display:flex;
        align-items:center;
        gap:14px;
        padding:16px;
        border:1px solid #e5e7eb;
        border-radius:14px;
        background:#fff;
        transition:.2s ease;
    }

    .kelas-card:hover {
        border-color:#bfdbfe;
        box-shadow:0 6px 18px rgba(15,23,42,.06);
    }

    .kelas-icon {
        width:46px;
        height:46px;
        min-width:46px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:12px;
        background:#eff6ff;
        color:#2563eb;
    }

    .kelas-info {
        flex:1;
        min-width:0;
    }

    .kelas-info strong {
        display:block;
        font-size:16px;
        color:#0f172a;
        margin-bottom:4px;
    }

    .kelas-info span {
        font-size:13px;
        color:#64748b;
    }

    .kelas-actions {
        display:flex;
        align-items:center;
        gap:7px;
    }

    .kelas-actions form {
        margin:0;
    }

    .action-btn {
        width:34px;
        height:34px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:8px;
        border:1px solid #e2e8f0;
        background:#fff;
        cursor:pointer;
        text-decoration:none;
        transition:.2s ease;
    }

    .action-edit {
        color:#2563eb;
    }

    .action-edit:hover {
        background:#eff6ff;
        border-color:#bfdbfe;
    }

    .action-delete {
        color:#dc2626;
    }

    .action-delete:hover {
        background:#fef2f2;
        border-color:#fecaca;
    }

    @media (max-width:700px) {

        .kelas-grid {
            grid-template-columns:1fr;
        }

    }

</style>

@endpush


@push('scripts')

<script>

function konfirmasiHapusKelas(nama)
{
    return confirm(
        'Hapus kelas "' +
        nama +
        '"?\n\nData kelas akan dihapus.'
    );
}

</script>

@endpush