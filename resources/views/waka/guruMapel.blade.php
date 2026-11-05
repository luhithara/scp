{{-- =========================================================
     ATUR MATA PELAJARAN GURU - SCHOOL CLASS PROGRESS
     File:
     resources/views/waka/guruMapel.blade.php
     ========================================================= --}}

@extends('layouts.app')

@section('title', 'Atur Mata Pelajaran Guru - SCP')
@section('page-title', 'Atur Mata Pelajaran Guru')
@section('page-subtitle', 'Kelola mata pelajaran yang diampu oleh guru')


@push('styles')
<style>

    /* =====================================================
       PROFIL GURU
       ===================================================== */

    .waka-mapel-profile {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;

        padding: 22px;

        margin-bottom: 18px;

        background: #ffffff;

        border: 1px solid #e2e8f0;
        border-radius: 16px;

        box-shadow: 0 5px 18px rgba(15, 23, 42, .05);
    }

    .waka-mapel-profile-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .waka-mapel-avatar {
        width: 58px;
        height: 58px;

        flex: 0 0 58px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 15px;

        background: #eaf3ff;
        color: #1565c0;

        font-size: 23px;
        font-weight: 800;
    }

    .waka-mapel-profile h2 {
        margin: 0 0 6px;

        color: #0f172a;

        font-size: 19px;
        font-weight: 800;
    }

    .waka-mapel-profile p {
        margin: 0 0 5px;

        color: #64748b;

        font-size: 12px;
    }

    .waka-mapel-code {
        display: inline-flex;

        padding: 5px 9px;

        border-radius: 7px;

        background: #eff6ff;
        color: #1565c0;

        font-size: 10px;
        font-weight: 800;
    }


    /* =====================================================
       KEMBALI
       ===================================================== */

    .waka-mapel-back {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 7px;

        padding: 10px 14px;

        border-radius: 9px;

        background: #f1f5f9;
        color: #334155;

        text-decoration: none;

        font-size: 12px;
        font-weight: 700;

        white-space: nowrap;

        transition: .2s ease;
    }

    .waka-mapel-back:hover {
        background: #e2e8f0;
    }


    /* =====================================================
       INFO
       ===================================================== */

    .waka-mapel-info {
        display: flex;

        align-items: flex-start;

        gap: 11px;

        padding: 14px 16px;

        margin-bottom: 20px;

        border: 1px solid #bfdbfe;
        border-radius: 12px;

        background: #eff6ff;

        color: #1e40af;

        font-size: 12px;

        line-height: 1.6;
    }

    .waka-mapel-info-icon {
        width: 25px;
        height: 25px;

        flex: 0 0 25px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #dbeafe;

        color: #1565c0;

        font-size: 12px;

        font-weight: 800;
    }


    /* =====================================================
       ALERT
       ===================================================== */

    .waka-mapel-alert {
        padding: 13px 16px;

        margin-bottom: 18px;

        border-radius: 10px;

        font-size: 12px;

        line-height: 1.5;
    }

    .waka-mapel-alert-success {
        background: #ecfdf5;

        border: 1px solid #a7f3d0;

        color: #047857;
    }

    .waka-mapel-alert-error {
        background: #fef2f2;

        border: 1px solid #fecaca;

        color: #b91c1c;
    }

    .waka-mapel-alert ul {
        margin: 7px 0 0;

        padding-left: 20px;
    }


    /* =====================================================
       CARD MAPEL
       ===================================================== */

    .waka-mapel-card {
        padding: 23px;

        background: #ffffff;

        border: 1px solid #e2e8f0;
        border-radius: 16px;

        box-shadow: 0 5px 18px rgba(15, 23, 42, .05);
    }

    .waka-mapel-card-header {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        margin-bottom: 20px;
    }

    .waka-mapel-card-title {
        color: #0f172a;

        font-size: 17px;

        font-weight: 800;
    }

    .waka-mapel-card-description {
        margin-top: 5px;

        color: #64748b;

        font-size: 12px;
    }

    .waka-mapel-selected {
        padding: 8px 12px;

        border-radius: 8px;

        background: #eaf3ff;

        color: #1565c0;

        font-size: 11px;

        font-weight: 800;

        white-space: nowrap;
    }


    /* =====================================================
       GRID MAPEL
       ===================================================== */

    .waka-mapel-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 12px;
    }

    .waka-mapel-item {
        position: relative;
    }

    .waka-mapel-checkbox {
        position: absolute;

        width: 1px;
        height: 1px;

        opacity: 0;

        pointer-events: none;
    }

    .waka-mapel-label {
        min-height: 70px;

        display: flex;

        align-items: center;

        gap: 11px;

        padding: 14px;

        border: 1px solid #dbe3ee;

        border-radius: 12px;

        background: #ffffff;

        cursor: pointer;

        transition: .2s ease;
    }

    .waka-mapel-label:hover {
        border-color: #90caf9;

        background: #f8fbff;

        transform: translateY(-1px);
    }

    .waka-mapel-checkbox:checked + .waka-mapel-label {
        border-color: #1976d2;

        background: #eff6ff;

        box-shadow:
            0 0 0 2px rgba(25, 118, 210, .08);
    }

    .waka-mapel-check {
        width: 21px;
        height: 21px;

        flex: 0 0 21px;

        display: flex;

        align-items: center;
        justify-content: center;

        border: 2px solid #cbd5e1;

        border-radius: 6px;

        color: #ffffff;

        font-size: 12px;

        font-weight: 800;
    }

    .waka-mapel-checkbox:checked
    + .waka-mapel-label
    .waka-mapel-check {
        background: #1976d2;

        border-color: #1976d2;
    }

    .waka-mapel-checkbox:checked
    + .waka-mapel-label
    .waka-mapel-check::after {
        content: "✓";
    }

    .waka-mapel-name {
        color: #334155;

        font-size: 12px;

        font-weight: 700;

        line-height: 1.4;
    }

    .waka-mapel-checkbox:checked
    + .waka-mapel-label
    .waka-mapel-name {
        color: #1565c0;
    }


    /* =====================================================
       KOSONG
       ===================================================== */

    .waka-mapel-empty {
        padding: 40px 20px;

        border: 1px dashed #cbd5e1;

        border-radius: 12px;

        color: #94a3b8;

        text-align: center;

        font-size: 12px;

        line-height: 1.7;
    }


    /* =====================================================
       FOOTER FORM
       ===================================================== */

    .waka-mapel-form-footer {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

        padding-top: 20px;

        margin-top: 22px;

        border-top: 1px solid #edf2f7;
    }

    .waka-mapel-footer-info {
        color: #64748b;

        font-size: 11px;

        line-height: 1.5;
    }

    .waka-mapel-buttons {
        display: flex;

        align-items: center;

        gap: 8px;
    }

    .waka-mapel-btn {
        min-height: 40px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        padding: 0 15px;

        border: 0;

        border-radius: 9px;

        font-family: inherit;

        font-size: 11px;

        font-weight: 800;

        text-decoration: none;

        cursor: pointer;

        transition: .2s ease;
    }

    .waka-mapel-btn-secondary {
        background: #f1f5f9;

        color: #475569;
    }

    .waka-mapel-btn-secondary:hover {
        background: #e2e8f0;
    }

    .waka-mapel-btn-primary {
        background: #1565c0;

        color: #ffffff;
    }

    .waka-mapel-btn-primary:hover {
        background: #0d47a1;

        transform: translateY(-1px);
    }


    /* =====================================================
       RESPONSIF
       ===================================================== */

    @media (max-width: 900px) {

        .waka-mapel-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 650px) {

        .waka-mapel-profile {
            flex-direction: column;

            align-items: stretch;
        }

        .waka-mapel-back {
            width: 100%;
        }

        .waka-mapel-grid {
            grid-template-columns: 1fr;
        }

        .waka-mapel-card {
            padding: 17px;
        }

        .waka-mapel-card-header {
            align-items: flex-start;

            flex-direction: column;
        }

        .waka-mapel-selected {
            width: 100%;

            text-align: center;
        }

        .waka-mapel-form-footer {
            align-items: stretch;

            flex-direction: column;
        }

        .waka-mapel-buttons {
            flex-direction: column;
        }

        .waka-mapel-btn {
            width: 100%;
        }

    }

</style>
@endpush


@section('content')

    {{-- =====================================================
         ALERT SUCCESS
         ===================================================== --}}
    @if(session('success'))

        <div class="waka-mapel-alert waka-mapel-alert-success">
            ✓ {{ session('success') }}
        </div>

    @endif


    {{-- =====================================================
         ALERT ERROR
         ===================================================== --}}
    @if(session('error'))

        <div class="waka-mapel-alert waka-mapel-alert-error">
            ! {{ session('error') }}
        </div>

    @endif


    {{-- =====================================================
         VALIDATION ERROR
         ===================================================== --}}
    @if($errors->any())

        <div class="waka-mapel-alert waka-mapel-alert-error">

            <strong>
                Terjadi kesalahan:
            </strong>

            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
         PROFIL GURU
         ===================================================== --}}
    <div class="waka-mapel-profile">

        <div class="waka-mapel-profile-left">

            <div class="waka-mapel-avatar">
                ♙
            </div>

            <div>

                <h2>
                    {{ $guru->nama }}
                </h2>

                <p>
                    NIP:
                    {{ $guru->nip ?: '-' }}
                </p>

                <span class="waka-mapel-code">
                    Kode Guru: {{ $guru->kodeguru }}
                </span>

            </div>

        </div>


        <a
            href="{{ route('waka.guru') }}"
            class="waka-mapel-back"
        >
            ← Kembali ke Data Guru
        </a>

    </div>


    {{-- =====================================================
         INFORMASI
         ===================================================== --}}
    <div class="waka-mapel-info">

        <div class="waka-mapel-info-icon">
            i
        </div>

        <div>

            <strong>
                Pengaturan Mata Pelajaran
            </strong>

            <br>

            Pilih satu atau beberapa mata pelajaran yang
            diampu oleh guru ini. Satu guru dapat mengajar
            lebih dari satu mata pelajaran.

        </div>

    </div>


    {{-- =====================================================
         FORM
         ===================================================== --}}
    <form
        action="{{ route('waka.guru.mapel.simpan', $guru->id) }}"
        method="POST"
    >

        @csrf


        <div class="waka-mapel-card">

            <div class="waka-mapel-card-header">

                <div>

                    <div class="waka-mapel-card-title">
                        Daftar Mata Pelajaran
                    </div>

                    <div class="waka-mapel-card-description">
                        Centang mata pelajaran yang diampu oleh guru.
                    </div>

                </div>


                <div class="waka-mapel-selected">

                    <span id="jumlahDipilih">
                        0
                    </span>

                    mata pelajaran dipilih

                </div>

            </div>


            {{-- =================================================
                 DAFTAR MAPEL
                 ================================================= --}}
            @if($mapel->count() > 0)

                <div class="waka-mapel-grid">

                    @foreach($mapel as $item)

                        <div class="waka-mapel-item">

                            <input
                                type="checkbox"
                                name="mapel[]"
                                value="{{ $item->id }}"
                                id="waka-mapel-{{ $item->id }}"
                                class="waka-mapel-checkbox"
                                {{ in_array($item->id, $mapelTerpilih) ? 'checked' : '' }}
                            >

                            <label
                                for="waka-mapel-{{ $item->id }}"
                                class="waka-mapel-label"
                            >

                                <span class="waka-mapel-check"></span>

                                <span class="waka-mapel-name">
                                    {{ $item->namamapel }}
                                </span>

                            </label>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="waka-mapel-empty">

                    Belum ada data mata pelajaran.

                    <br>

                    Silakan tambahkan mata pelajaran terlebih dahulu.

                </div>

            @endif


            {{-- =================================================
                 FOOTER
                 ================================================= --}}
            <div class="waka-mapel-form-footer">

                <div class="waka-mapel-footer-info">

                    Perubahan akan menggantikan daftar
                    mata pelajaran guru saat ini.

                </div>


                <div class="waka-mapel-buttons">

                    <a
                        href="{{ route('waka.guru') }}"
                        class="waka-mapel-btn waka-mapel-btn-secondary"
                    >
                        Batal
                    </a>


                    <button
                        type="submit"
                        class="waka-mapel-btn waka-mapel-btn-primary"
                    >
                        ✓ Simpan Mata Pelajaran
                    </button>

                </div>

            </div>

        </div>

    </form>

@endsection


@push('scripts')
<script>

    function updateJumlahDipilih() {

        const checkbox =
            document.querySelectorAll(
                '.waka-mapel-checkbox:checked'
            );

        const jumlah =
            checkbox.length;

        const elemen =
            document.getElementById(
                'jumlahDipilih'
            );

        if (elemen) {

            elemen.textContent =
                jumlah;

        }

    }


    document
        .querySelectorAll(
            '.waka-mapel-checkbox'
        )
        .forEach(function (checkbox) {

            checkbox.addEventListener(
                'change',
                updateJumlahDipilih
            );

        });


    updateJumlahDipilih();

</script>
@endpush