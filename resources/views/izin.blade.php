@extends('layouts.app')

@section('content')

{{-- =========================================================
     HALAMAN PENGAJUAN IZIN
     File:
     resources/views/izin.blade.php

     Catatan:
     Variabel $izin dibuat aman menggunakan:
     $izin ?? collect()

     Jadi halaman tidak error "Undefined variable $izin"
     walaupun controller belum mengirim variabel $izin.
========================================================= --}}

@php
    /*
    |--------------------------------------------------------------------------
    | AMANKAN DATA RIWAYAT IZIN
    |--------------------------------------------------------------------------
    | Kalau controller mengirim $izin, gunakan data tersebut.
    | Kalau belum ada, gunakan collection kosong.
    */
    $dataIzin = $izin ?? collect();

    /*
    |--------------------------------------------------------------------------
    | AMANKAN DATA SISWA
    |--------------------------------------------------------------------------
    */
    $namaSiswa = $siswa->nama
        ?? auth()->user()->name
        ?? '-';
@endphp


<div class="izin-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="pageheader">

        <div>

            <h2>
                Pengajuan Izin
            </h2>

            <p>
                Ajukan izin atau sakit untuk hari ini dan lihat
                riwayat pengajuan.
            </p>

        </div>

    </div>


    {{-- =====================================================
         PESAN BERHASIL
    ====================================================== --}}
    @if(session('success'))

        <div class="alert success">

            ✓ {{ session('success') }}

        </div>

    @endif


    {{-- =====================================================
         PESAN ERROR
    ====================================================== --}}
    @if($errors->any())

        <div class="alert danger">

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
         GRID UTAMA
    ====================================================== --}}
    <div class="izin-grid">


        {{-- =================================================
             FORM AJUKAN IZIN
        ================================================== --}}
        <div class="panel">

            <div class="paneltitle">

                <div>

                    <h3>
                        Ajukan Izin
                    </h3>

                    <p class="muted">
                        Isi data pengajuan izin atau sakit.
                    </p>

                </div>

            </div>


            {{-- =============================================
                 FORM
                 enctype diperlukan untuk upload bukti
            ============================================== --}}
            <form
                action="{{ route('izin.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf


                {{-- =========================================
                     NAMA SISWA
                ========================================== --}}
                <div class="student-box">

                    <span>
                        Nama Siswa:
                    </span>

                    <strong>
                        {{ $namaSiswa }}
                    </strong>

                </div>


                {{-- =========================================
                     JENIS PENGAJUAN
                ========================================== --}}
                <div class="form-group">

                    <label for="jenis">
                        Jenis Pengajuan
                    </label>

                    <select
                        name="jenis"
                        id="jenis"
                        required
                    >

                        <option value="">
                            -- Pilih Jenis --
                        </option>

                        <option
                            value="izin"
                            {{ old('jenis') === 'izin' ? 'selected' : '' }}
                        >
                            Izin
                        </option>

                        <option
                            value="sakit"
                            {{ old('jenis') === 'sakit' ? 'selected' : '' }}
                        >
                            Sakit
                        </option>

                    </select>

                </div>


                {{-- =========================================
                     ALASAN
                ========================================== --}}
                <div class="form-group">

                    <label for="alasan">
                        Alasan
                    </label>

                    <textarea
                        name="alasan"
                        id="alasan"
                        rows="5"
                        placeholder="Masukkan alasan izin atau sakit..."
                        required
                    >{{ old('alasan') }}</textarea>

                </div>


                {{-- =========================================
                     BUKTI
                ========================================== --}}
                <div class="form-group">

                    <label for="bukti">
                        Bukti
                    </label>

                    <input
                        type="file"
                        name="bukti"
                        id="bukti"
                        accept=".jpg,.jpeg,.png,.pdf"
                    >

                    <small class="file-info">
                        Format yang diperbolehkan:
                        JPG, JPEG, PNG, PDF.
                        Maksimal 2 MB.
                    </small>

                </div>


                {{-- =========================================
                     TOMBOL SUBMIT
                ========================================== --}}
                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        + Ajukan Izin
                    </button>

                </div>

            </form>

        </div>



        {{-- =================================================
             RIWAYAT IZIN
        ================================================== --}}
        <div class="panel">

            <div class="paneltitle">

                <div>

                    <h3>
                        Riwayat Izin
                    </h3>

                    <p class="muted">
                        Daftar pengajuan izin atau sakit kamu.
                    </p>

                </div>

            </div>


            {{-- =============================================
                 CEK APAKAH ADA RIWAYAT
            ============================================== --}}
            @if($dataIzin->count() > 0)

                <div class="table-wrap">

                    <table class="data-table">

                        <thead>

                            <tr>

                                <th>
                                    Tanggal
                                </th>

                                <th>
                                    Jenis
                                </th>

                                <th>
                                    Alasan
                                </th>

                                <th>
                                    Bukti
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($dataIzin as $item)

                                <tr>

                                    {{-- =================================
                                         TANGGAL
                                    ================================== --}}
                                    <td>

                                        @if(!empty($item->tanggal))

                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- =================================
                                         JENIS
                                    ================================== --}}
                                    <td>

                                        @if(($item->jenis ?? '') === 'izin')

                                            <span class="badge izin">
                                                Izin
                                            </span>

                                        @elseif(($item->jenis ?? '') === 'sakit')

                                            <span class="badge sakit">
                                                Sakit
                                            </span>

                                        @else

                                            <span class="badge">
                                                {{ ucfirst($item->jenis ?? '-') }}
                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================
                                         ALASAN
                                    ================================== --}}
                                    <td>

                                        <div class="alasan-text">

                                            {{ $item->alasan ?? '-' }}

                                        </div>

                                    </td>


                                    {{-- =================================
                                         BUKTI
                                    ================================== --}}
                                    <td>

                                        @if(!empty($item->bukti))

                                            <a
                                                href="{{ asset('storage/' . $item->bukti) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn-bukti"
                                            >
                                                Lihat
                                            </a>

                                        @else

                                            <span class="no-bukti">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================
                                         STATUS
                                    ================================== --}}
                                    <td>

                                        @if(($item->status ?? '') === 'menunggu')

                                            <span class="badge warning">
                                                Menunggu
                                            </span>

                                        @elseif(($item->status ?? '') === 'disetujui')

                                            <span class="badge success">
                                                Disetujui
                                            </span>

                                        @elseif(($item->status ?? '') === 'ditolak')

                                            <span class="badge danger">
                                                Ditolak
                                            </span>

                                            @if(!empty($item->catatanbk))

                                                <div class="catatan">

                                                    {{ $item->catatanbk }}

                                                </div>

                                            @endif

                                        @else

                                            <span class="badge">
                                                {{ ucfirst($item->status ?? '-') }}
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- =========================================
                     BELUM ADA DATA
                ========================================== --}}
                <div class="empty">

                    <div class="empty-icon">
                        📋
                    </div>

                    <p>
                        Belum ada riwayat pengajuan izin.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>



<style>

/* =========================================================
   HALAMAN
========================================================= */

.izin-page {
    width: 100%;
}


/* =========================================================
   HEADER
========================================================= */

.pageheader {
    margin-bottom: 20px;
}

.pageheader h2 {
    margin: 0;
    font-size: 26px;
    color: #1e293b;
}

.pageheader p {
    margin-top: 6px;
    color: #64748b;
    font-size: 14px;
}


/* =========================================================
   GRID
========================================================= */

.izin-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 20px;
    align-items: start;
}


/* =========================================================
   PANEL
========================================================= */

.panel {
    background: white;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #e2e8f0;

    box-shadow:
        0 5px 20px rgba(15, 23, 42, .04);
}


/* =========================================================
   JUDUL PANEL
========================================================= */

.paneltitle {
    margin-bottom: 20px;
}

.paneltitle h3 {
    margin: 0;
    font-size: 17px;
    color: #1e293b;
}


/* =========================================================
   TEKS MUTED
========================================================= */

.muted {
    color: #64748b;
    margin-top: 5px;
    font-size: 13px;
}


/* =========================================================
   NAMA SISWA
========================================================= */

.student-box {
    background: #eff6ff;
    border: 1px solid #dbeafe;
    border-radius: 10px;

    padding: 13px 15px;
    margin-bottom: 18px;

    color: #1d4ed8;
    font-size: 14px;
}

.student-box span {
    font-weight: 600;
}

.student-box strong {
    font-weight: 500;
}


/* =========================================================
   FORM
========================================================= */

.form-group {
    display: flex;
    flex-direction: column;
    gap: 7px;

    margin-bottom: 16px;
}

.form-group label {
    font-size: 14px;
    font-weight: 600;
    color: #334155;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;

    border: 1px solid #cbd5e1;
    border-radius: 9px;

    padding: 11px 12px;

    background: white;
    color: #1e293b;

    font-size: 14px;

    outline: none;
    font-family: inherit;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #2563eb;

    box-shadow:
        0 0 0 3px rgba(37, 99, 235, .10);
}

.form-group textarea {
    resize: vertical;
}


/* =========================================================
   INFORMASI FILE
========================================================= */

.file-info {
    color: #64748b;
    font-size: 12px;
    line-height: 1.5;
}


/* =========================================================
   TOMBOL
========================================================= */

.form-actions {
    margin-top: 20px;
}

.btn-primary {
    border: none;

    background: #2563eb;
    color: white;

    padding: 11px 18px;

    border-radius: 9px;

    font-size: 14px;
    font-weight: 600;

    cursor: pointer;
}

.btn-primary:hover {
    background: #1d4ed8;
}


/* =========================================================
   TABLE
========================================================= */

.table-wrap {
    width: 100%;
    overflow-x: auto;
}

.data-table {
    width: 100%;

    border-collapse: collapse;

    min-width: 650px;
}

.data-table th {
    text-align: left;

    background: #f1f5f9;
    color: #475569;

    font-size: 13px;
    font-weight: 600;

    padding: 13px 12px;

    white-space: nowrap;
}

.data-table td {
    padding: 13px 12px;

    border-bottom: 1px solid #e2e8f0;

    color: #334155;

    font-size: 13px;

    vertical-align: top;
}

.data-table tr:last-child td {
    border-bottom: none;
}

.data-table tbody tr:hover {
    background: #f8fafc;
}


/* =========================================================
   ALASAN
========================================================= */

.alasan-text {
    max-width: 220px;
    line-height: 1.5;
}


/* =========================================================
   BADGE
========================================================= */

.badge {
    display: inline-block;

    padding: 5px 10px;

    border-radius: 20px;

    font-size: 12px;
    font-weight: 600;

    background: #f1f5f9;
    color: #475569;

    white-space: nowrap;
}

.badge.izin {
    background: #dbeafe;
    color: #1d4ed8;
}

.badge.sakit {
    background: #fef3c7;
    color: #92400e;
}

.badge.warning {
    background: #fef3c7;
    color: #92400e;
}

.badge.success {
    background: #dcfce7;
    color: #166534;
}

.badge.danger {
    background: #fee2e2;
    color: #991b1b;
}


/* =========================================================
   BUKTI
========================================================= */

.btn-bukti {
    display: inline-block;

    background: #2563eb;
    color: white;

    text-decoration: none;

    padding: 6px 11px;

    border-radius: 7px;

    font-size: 12px;
    font-weight: 600;
}

.btn-bukti:hover {
    background: #1d4ed8;
}

.no-bukti {
    color: #94a3b8;
}


/* =========================================================
   CATATAN
========================================================= */

.catatan {
    margin-top: 7px;

    padding: 6px 8px;

    background: #fff1f2;

    border-radius: 6px;

    color: #be123c;

    font-size: 11px;

    max-width: 150px;
}


/* =========================================================
   ALERT
========================================================= */

.alert {
    padding: 14px 16px;

    border-radius: 10px;

    margin-bottom: 18px;

    font-size: 14px;
}

.alert.success {
    background: #dcfce7;
    color: #166534;

    border: 1px solid #bbf7d0;
}

.alert.danger {
    background: #fee2e2;
    color: #991b1b;

    border: 1px solid #fecaca;
}

.alert ul {
    margin: 8px 0 0;
    padding-left: 20px;
}


/* =========================================================
   DATA KOSONG
========================================================= */

.empty {
    text-align: center;

    padding: 50px 20px;

    color: #64748b;
}

.empty-icon {
    font-size: 40px;
    margin-bottom: 10px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .izin-grid {
        grid-template-columns: 1fr;
    }

}


@media (max-width: 700px) {

    .pageheader h2 {
        font-size: 22px;
    }

    .panel {
        padding: 15px;
    }

}

</style>

@endsection