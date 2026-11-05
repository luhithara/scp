{{-- =========================================================
     SCP - JURNAL MENGAJAR GURU
     File:
     resources/views/guru/jurnal.blade.php

     Catatan:
     - Menggunakan layouts.app
     - Mempertahankan variabel $jadwal dan $jurnal
     - Tidak mengubah route/controller
     - Jurnal yang sudah disimpan tetap tidak dapat diedit
========================================================= --}}

@extends('layouts.app')

@section('title', 'Jurnal Mengajar - SCP')


@section('content')

<style>

    /* =====================================================
       CONTAINER
    ====================================================== */

    .guru-jurnal-page {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }


    /* =====================================================
       HEADER
    ====================================================== */

    .guru-jurnal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }


    .guru-jurnal-header h1 {
        margin: 0 0 6px;

        color: #0f172a;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 25px;
        line-height: 1.3;
        font-weight: 800;
    }


    .guru-jurnal-header p {
        margin: 0;

        color: #64748b;

        font-size: 13px;
        line-height: 1.5;
    }


    /* =====================================================
       CARD
    ====================================================== */

    .guru-jurnal-card {
        overflow: hidden;

        margin-bottom: 20px;

        border:
            1px solid #e2e8f0;

        border-radius: 16px;

        background: #ffffff;

        box-shadow:
            0 3px 12px
            rgba(15, 23, 42, 0.04);
    }


    .guru-jurnal-card-header {
        padding: 18px 20px;

        border-bottom:
            1px solid #e2e8f0;
    }


    .guru-jurnal-card-header h2 {
        margin: 0 0 4px;

        color: #0f172a;

        font-size: 16px;
        font-weight: 750;
    }


    .guru-jurnal-card-header p {
        margin: 0;

        color: #64748b;

        font-size: 12px;
    }


    .guru-jurnal-card-body {
        padding: 20px;
    }


    /* =====================================================
       FORM
    ====================================================== */

    .guru-jurnal-form {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 17px;
    }


    .guru-jurnal-form-group {
        display: flex;

        flex-direction: column;

        min-width: 0;
    }


    .guru-jurnal-form-group.full {
        grid-column: 1 / -1;
    }


    .guru-jurnal-label {
        margin-bottom: 7px;

        color: #334155;

        font-size: 11px;
        font-weight: 700;
    }


    .guru-jurnal-input,
    .guru-jurnal-select,
    .guru-jurnal-textarea {
        width: 100%;

        box-sizing: border-box;

        border:
            1px solid #dbe3ec;

        border-radius: 9px;

        outline: none;

        background: #ffffff;

        color: #1e293b;

        font-family: inherit;

        font-size: 12px;

        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }


    .guru-jurnal-input,
    .guru-jurnal-select {
        min-height: 42px;

        padding:
            10px 12px;
    }


    .guru-jurnal-textarea {
        min-height: 105px;

        padding:
            11px 12px;

        line-height: 1.55;

        resize: vertical;
    }


    .guru-jurnal-input:focus,
    .guru-jurnal-select:focus,
    .guru-jurnal-textarea:focus {
        border-color: #2563eb;

        box-shadow:
            0 0 0 3px
            rgba(37, 99, 235, .09);
    }


    .guru-jurnal-help {
        margin-top: 5px;

        color: #94a3b8;

        font-size: 10px;
    }


    /* =====================================================
       BUTTON
    ====================================================== */

    .guru-jurnal-form-actions {
        grid-column: 1 / -1;

        display: flex;

        justify-content: flex-end;

        margin-top: 2px;

        padding-top: 18px;

        border-top:
            1px solid #edf2f7;
    }


    .guru-jurnal-submit {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 8px;

        min-width: 145px;

        padding:
            11px 17px;

        border: none;

        border-radius: 9px;

        background: #2563eb;

        color: #ffffff;

        cursor: pointer;

        font-family: inherit;

        font-size: 11px;

        font-weight: 700;

        transition:
            all .2s ease;
    }


    .guru-jurnal-submit:hover {
        background: #1d4ed8;

        transform:
            translateY(-1px);
    }


    /* =====================================================
       RIWAYAT
    ====================================================== */

    .guru-jurnal-list {
        display: flex;

        flex-direction: column;

        gap: 12px;
    }


    .guru-jurnal-item {
        padding: 17px;

        border:
            1px solid #e2e8f0;

        border-radius: 13px;

        background: #ffffff;

        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }


    .guru-jurnal-item:hover {
        border-color: #cbd5e1;

        box-shadow:
            0 4px 14px
            rgba(15, 23, 42, .04);
    }


    .guru-jurnal-item-top {
        display: flex;

        align-items: flex-start;

        justify-content: space-between;

        gap: 15px;

        margin-bottom: 14px;
    }


    .guru-jurnal-item-date {
        color: #2563eb;

        font-size: 11px;

        font-weight: 700;
    }


    .guru-jurnal-item-title {
        margin-top: 4px;

        color: #0f172a;

        font-size: 14px;

        font-weight: 750;
    }


    .guru-jurnal-item-class {
        margin-top: 3px;

        color: #64748b;

        font-size: 11px;
    }


    .guru-jurnal-locked {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        flex-shrink: 0;

        padding:
            6px 9px;

        border-radius: 7px;

        background: #f1f5f9;

        color: #64748b;

        font-size: 9px;

        font-weight: 700;

        white-space: nowrap;
    }


    /* =====================================================
       DETAIL
    ====================================================== */

    .guru-jurnal-detail-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 10px;

        padding-top: 14px;

        border-top:
            1px solid #edf2f7;
    }


    .guru-jurnal-detail {
        min-width: 0;

        padding: 12px;

        border-radius: 9px;

        background: #f8fafc;
    }


    .guru-jurnal-detail.full {
        grid-column: 1 / -1;
    }


    .guru-jurnal-detail-label {
        margin-bottom: 5px;

        color: #64748b;

        font-size: 9px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .3px;
    }


    .guru-jurnal-detail-value {
        color: #334155;

        font-size: 11px;

        line-height: 1.55;

        white-space: pre-line;

        word-break: break-word;
    }


    /* =====================================================
       EMPTY
    ====================================================== */

    .guru-jurnal-empty {
        padding: 35px 20px;

        text-align: center;

        border:
            1px dashed #dbe3ec;

        border-radius: 12px;

        background: #f8fafc;
    }


    .guru-jurnal-empty-icon {
        width: 46px;
        height: 46px;

        display: flex;

        align-items: center;
        justify-content: center;

        margin: 0 auto 12px;

        border-radius: 12px;

        background: #eff6ff;

        color: #2563eb;

        font-size: 18px;
    }


    .guru-jurnal-empty-title {
        margin-bottom: 5px;

        color: #334155;

        font-size: 13px;

        font-weight: 700;
    }


    .guru-jurnal-empty-text {
        color: #94a3b8;

        font-size: 11px;
    }


    /* =====================================================
       ALERT LOKAL
    ====================================================== */

    .guru-jurnal-alert {
        display: flex;

        align-items: flex-start;

        gap: 9px;

        margin-bottom: 18px;

        padding: 12px 14px;

        border-radius: 10px;

        font-size: 11px;

        line-height: 1.5;
    }


    .guru-jurnal-alert.success {
        border:
            1px solid #bbf7d0;

        background: #f0fdf4;

        color: #166534;
    }


    .guru-jurnal-alert.error {
        border:
            1px solid #fecaca;

        background: #fef2f2;

        color: #991b1b;
    }


    .guru-jurnal-alert ul {
        margin: 4px 0 0;

        padding-left: 18px;
    }


    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 900px) {

        .guru-jurnal-form {
            grid-template-columns: 1fr;
        }


        .guru-jurnal-form-group.full {
            grid-column: auto;
        }


        .guru-jurnal-detail-grid {
            grid-template-columns: 1fr;
        }


        .guru-jurnal-detail.full {
            grid-column: auto;
        }

    }


    @media (max-width: 600px) {

        .guru-jurnal-header h1 {
            font-size: 21px;
        }


        .guru-jurnal-card-body {
            padding: 16px;
        }


        .guru-jurnal-item-top {
            flex-direction: column;
        }


        .guru-jurnal-locked {
            align-self: flex-start;
        }


        .guru-jurnal-submit {
            width: 100%;
        }


        .guru-jurnal-form-actions {
            justify-content: stretch;
        }

    }

</style>


<div class="guru-jurnal-page">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="guru-jurnal-header">

        <div>

            <h1>
                Jurnal Mengajar
            </h1>

            <p>
                Catat materi dan kegiatan pembelajaran
                yang telah dilaksanakan.
            </p>

        </div>

    </div>


    {{-- =====================================================
         ALERT
    ====================================================== --}}

    @if(session('success'))

        <div class="guru-jurnal-alert success">

            <i class="fa-solid fa-circle-check"></i>

            <div>
                {{ session('success') }}
            </div>

        </div>

    @endif


    @if($errors->any())

        <div class="guru-jurnal-alert error">

            <i class="fa-solid fa-circle-exclamation"></i>

            <div>

                <strong>
                    Data belum dapat disimpan.
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =====================================================
         FORM INPUT JURNAL
    ====================================================== --}}

    <section class="guru-jurnal-card">


        <div class="guru-jurnal-card-header">

            <h2>
                Input Jurnal Mengajar
            </h2>

            <p>
                Isi jurnal berdasarkan jadwal mengajar.
            </p>

        </div>


        <div class="guru-jurnal-card-body">


            <form
                action="{{ route('jurnal.guru.store') }}"
                method="POST"
                class="guru-jurnal-form"
            >

                @csrf


                {{-- JADWAL --}}

                <div class="guru-jurnal-form-group full">

                    <label
                        for="jadwalid"
                        class="guru-jurnal-label"
                    >
                        Jadwal Mengajar
                    </label>


                    <select
                        id="jadwalid"
                        name="jadwalid"
                        class="guru-jurnal-select"
                        required
                    >

                        <option value="">
                            -- Pilih Jadwal Mengajar --
                        </option>


                        @foreach($jadwal as $item)

                            <option
                                value="{{ $item->id }}"
                                @selected(
                                    old('jadwalid') == $item->id
                                )
                            >

                                {{ ucfirst($item->hari) }}

                                —

                                {{ $item->jamMulai }}
                                -
                                {{ $item->jamSelesai }}

                                —

                                {{ $item->mapel->namamapel }}

                                —

                                {{ $item->kelas->namakelas }}

                            </option>

                        @endforeach

                    </select>


                    <span class="guru-jurnal-help">
                        Pilih jadwal yang sesuai dengan kegiatan mengajar.
                    </span>

                </div>


                {{-- TANGGAL --}}

                <div class="guru-jurnal-form-group">

                    <label
                        for="tanggal"
                        class="guru-jurnal-label"
                    >
                        Tanggal
                    </label>


                    <input
                        type="date"
                        id="tanggal"
                        name="tanggal"
                        class="guru-jurnal-input"
                        value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                        required
                    >

                </div>


                {{-- MATERI --}}

                <div class="guru-jurnal-form-group">

                    <label
                        for="materi"
                        class="guru-jurnal-label"
                    >
                        Materi
                    </label>


                    <textarea
                        id="materi"
                        name="materi"
                        class="guru-jurnal-textarea"
                        required
                        placeholder="Masukkan materi yang diajarkan..."
                    >{{ old('materi') }}</textarea>

                </div>


                {{-- KEGIATAN --}}

                <div class="guru-jurnal-form-group">

                    <label
                        for="kegiatan"
                        class="guru-jurnal-label"
                    >
                        Kegiatan Pembelajaran
                    </label>


                    <textarea
                        id="kegiatan"
                        name="kegiatan"
                        class="guru-jurnal-textarea"
                        placeholder="Masukkan kegiatan pembelajaran..."
                    >{{ old('kegiatan') }}</textarea>

                </div>


                {{-- CATATAN --}}

                <div class="guru-jurnal-form-group">

                    <label
                        for="catatan"
                        class="guru-jurnal-label"
                    >
                        Catatan
                    </label>


                    <textarea
                        id="catatan"
                        name="catatan"
                        class="guru-jurnal-textarea"
                        placeholder="Catatan tambahan..."
                    >{{ old('catatan') }}</textarea>

                </div>


                {{-- BUTTON --}}

                <div class="guru-jurnal-form-actions">

                    <button
                        type="submit"
                        class="guru-jurnal-submit"
                    >

                        <i class="fa-solid fa-floppy-disk"></i>

                        <span>
                            Simpan Jurnal
                        </span>

                    </button>

                </div>


            </form>


        </div>

    </section>


    {{-- =====================================================
         RIWAYAT JURNAL
    ====================================================== --}}

    <section class="guru-jurnal-card">


        <div class="guru-jurnal-card-header">

            <h2>
                Riwayat Jurnal
            </h2>

            <p>
                Jurnal yang telah disimpan oleh Anda.
            </p>

        </div>


        <div class="guru-jurnal-card-body">


            @forelse($jurnal as $item)


                <div class="guru-jurnal-list">


                    <article class="guru-jurnal-item">


                        <div class="guru-jurnal-item-top">


                            <div>

                                <div class="guru-jurnal-item-date">

                                    {{ $item->tanggal->format('d-m-Y') }}

                                </div>


                                <div class="guru-jurnal-item-title">

                                    {{ $item->jadwal->mapel->namamapel }}

                                </div>


                                <div class="guru-jurnal-item-class">

                                    Kelas:
                                    {{ $item->jadwal->kelas->namakelas }}

                                </div>

                            </div>


                            <div class="guru-jurnal-locked">

                                <i class="fa-solid fa-lock"></i>

                                Jurnal tersimpan

                            </div>


                        </div>


                        <div class="guru-jurnal-detail-grid">


                            {{-- MATERI --}}

                            <div class="guru-jurnal-detail full">

                                <div class="guru-jurnal-detail-label">
                                    Materi
                                </div>

                                <div class="guru-jurnal-detail-value">

                                    {{ $item->materi }}

                                </div>

                            </div>


                            {{-- KEGIATAN --}}

                            @if($item->kegiatan)

                                <div class="guru-jurnal-detail">

                                    <div class="guru-jurnal-detail-label">
                                        Kegiatan
                                    </div>

                                    <div class="guru-jurnal-detail-value">

                                        {{ $item->kegiatan }}

                                    </div>

                                </div>

                            @endif


                            {{-- CATATAN --}}

                            @if($item->catatan)

                                <div class="guru-jurnal-detail">

                                    <div class="guru-jurnal-detail-label">
                                        Catatan
                                    </div>

                                    <div class="guru-jurnal-detail-value">

                                        {{ $item->catatan }}

                                    </div>

                                </div>

                            @endif


                        </div>


                    </article>


                </div>


            @empty


                <div class="guru-jurnal-empty">


                    <div class="guru-jurnal-empty-icon">

                        <i class="fa-solid fa-book-open"></i>

                    </div>


                    <div class="guru-jurnal-empty-title">

                        Belum Ada Jurnal

                    </div>


                    <div class="guru-jurnal-empty-text">

                        Jurnal mengajar yang sudah disimpan
                        akan muncul di sini.

                    </div>


                </div>


            @endforelse


        </div>

    </section>


</div>

@endsection