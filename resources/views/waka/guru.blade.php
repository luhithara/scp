{{-- =========================================================
     SCP - DATA GURU WAKA
     File:
     resources/views/waka/guru.blade.php
========================================================= --}}

@extends('layouts.app')

@section('title', 'Data Guru - SCP')

@section('page-title', 'Data Guru')

@section('content')

<div class="page-header">

    <div>

        <h1>
            Data Guru
        </h1>

        <p>
            Kelola data guru dan akun login guru dalam sistem School Class Progress.
        </p>

    </div>


    <div class="header-actions">

        <a
            href="{{ route('waka.data') }}"
            class="btn btn-secondary"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>


        <button
            type="button"
            class="btn btn-primary"
            onclick="bukaModalGuru()"
        >
            <i class="fa-solid fa-plus"></i>
            Tambah Guru
        </button>

    </div>

</div>


{{-- =========================================================
     NOTIFIKASI
========================================================= --}}

@if(session('success'))

    <div class="alert alert-success">

        <i class="fa-solid fa-circle-check"></i>

        <span>
            {{ session('success') }}
        </span>

    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger">

        <i class="fa-solid fa-circle-exclamation"></i>

        <span>
            {{ session('error') }}
        </span>

    </div>

@endif


@if($errors->any())

    <div class="alert alert-danger">

        <i class="fa-solid fa-circle-exclamation"></i>

        <div>

            <strong>
                Data belum bisa disimpan.
            </strong>

            <ul class="error-list">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    </div>

@endif


{{-- =========================================================
     STATISTIK
========================================================= --}}

<div class="stats-grid">

    <div class="stat-card">

        <div class="stat-card-top">

            <div>

                <div class="stat-label">
                    Total Guru
                </div>

                <div class="stat-number">
                    {{ $jumlahGuru ?? 0 }}
                </div>

            </div>


            <div class="stat-icon">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>

        </div>

    </div>


    <div class="stat-card">

        <div class="stat-card-top">

            <div>

                <div class="stat-label">
                    Hasil Pencarian
                </div>

                <div class="stat-number">
                    {{ isset($dataGuru) ? $dataGuru->count() : 0 }}
                </div>

            </div>


            <div class="stat-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>

        </div>

    </div>


    <div class="stat-card">

        <div class="stat-card-top">

            <div>

                <div class="stat-label">
                    Status
                </div>

                <div class="stat-status">
                    Aktif
                </div>

            </div>


            <div class="stat-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     PENCARIAN
========================================================= --}}

<div class="card">

    <div class="card-header">

        <div>

            <h3>
                Daftar Guru
            </h3>

            <p>
                Data guru yang terdaftar dalam sistem SCP.
            </p>

        </div>

    </div>


    <div class="card-body">

        <form
            method="GET"
            action="{{ route('waka.guru') }}"
            class="search-form"
        >

            <div class="search-box">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    name="cari"
                    value="{{ $kataKunci ?? request('cari') }}"
                    placeholder="Cari nama, NIP, atau kode guru..."
                    autocomplete="off"
                >

            </div>


            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="fa-solid fa-magnifying-glass"></i>
                Cari
            </button>


            @if(($kataKunci ?? request('cari')))

                <a
                    href="{{ route('waka.guru') }}"
                    class="btn btn-secondary"
                >
                    <i class="fa-solid fa-rotate-left"></i>
                    Reset
                </a>

            @endif

        </form>

    </div>

</div>


{{-- =========================================================
     DAFTAR GURU
========================================================= --}}

<div class="card">

    <div class="card-header">

        <div>

            <h3>
                Data Guru
            </h3>

            <p>
                Daftar guru yang terdaftar dalam sistem SCP.
            </p>

        </div>


        <div class="table-count">

            {{ isset($dataGuru) ? $dataGuru->count() : 0 }}

            data

        </div>

    </div>


    <div class="card-body" style="padding:0;">

        @if(isset($dataGuru) && $dataGuru->count() > 0)

            <div class="table-wrapper">

                <table class="table">

                    <thead>

                        <tr>

                            <th style="width:55px;">
                                No
                            </th>

                            <th>
                                Guru
                            </th>

                            <th>
                                Kode Guru
                            </th>

                            <th>
                                NIP
                            </th>

                            <th style="width:90px;">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($dataGuru as $index => $guru)

                            <tr>

                                {{-- NO --}}

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                {{-- GURU --}}

                                <td>

                                    <div class="guru-profile">

                                        <div class="guru-avatar">

                                            <i class="fa-solid fa-user"></i>

                                        </div>


                                        <div>

                                            <strong class="guru-name">

                                                {{ $guru->nama ?? '-' }}

                                            </strong>


                                            <span class="guru-label">

                                                Guru SCP

                                            </span>

                                        </div>

                                    </div>

                                </td>


                                {{-- KODE GURU --}}

                                <td>

                                    <span class="code-badge">

                                        {{ $guru->kodeguru ?? '-' }}

                                    </span>

                                </td>


                                {{-- NIP --}}

                                <td>

                                    <span class="nip-text">

                                        {{ $guru->nip ?? '-' }}

                                    </span>

                                </td>


                                {{-- AKSI HANYA HAPUS --}}

                                <td>

                                    <div class="action-buttons">

                                        @if(!empty($guru->id))

                                            <form
                                                method="POST"
                                                action="{{ route('waka.guru.hapus', $guru->id) }}"
                                                onsubmit="return konfirmasiHapusGuru()"
                                                style="display:inline;"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="action-btn delete"
                                                    title="Hapus data guru"
                                                >

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


        @else

            <div class="empty-state">

                <div class="empty-icon">

                    <i class="fa-solid fa-chalkboard-user"></i>

                </div>


                <strong>
                    Data guru belum tersedia
                </strong>


                <span>

                    Silakan tambahkan data guru menggunakan tombol
                    <strong>Tambah Guru</strong>.

                </span>

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
     INFORMASI AKUN GURU
========================================================= --}}

<div class="info-card">

    <div class="info-icon">

        <i class="fa-solid fa-circle-info"></i>

    </div>


    <div>

        <strong>
            Informasi akun guru
        </strong>

        <p>

            Saat guru ditambahkan, sistem otomatis membuat akun login guru.
            Kode guru dan kode pegawai menggunakan NIP, sedangkan password
            awal akun guru adalah NIP.

        </p>

    </div>

</div>


{{-- =========================================================
     MODAL TAMBAH GURU
========================================================= --}}

<div
    id="modalGuru"
    class="modal-overlay"
    aria-hidden="true"
>

    <div
        class="modal-card"
        role="dialog"
        aria-modal="true"
        aria-labelledby="judulModalGuru"
    >

        <div class="modal-header">

            <div>

                <h2 id="judulModalGuru">
                    Tambah Guru
                </h2>

                <p>
                    Tambahkan data guru sekaligus membuat akun login.
                </p>

            </div>


            <button
                type="button"
                class="modal-close"
                onclick="tutupModalGuru()"
                aria-label="Tutup"
            >

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>


        <form
            action="{{ route('waka.guru.simpan') }}"
            method="POST"
        >

            @csrf


            <div class="modal-body">


                {{-- NAMA --}}

                <div class="form-group">

                    <label for="namaGuru">
                        Nama Lengkap Guru
                    </label>


                    <input
                        type="text"
                        id="namaGuru"
                        name="nama"
                        value="{{ old('nama') }}"
                        placeholder="Contoh: Budi Santoso"
                        maxlength="255"
                        required
                    >


                    <small>
                        Nama akan digunakan sebagai nama akun guru.
                    </small>

                </div>


                {{-- NIP --}}

                <div class="form-group">

                    <label for="nipGuru">
                        NIP
                    </label>


                    <input
                        type="text"
                        id="nipGuru"
                        name="nip"
                        value="{{ old('nip') }}"
                        placeholder="Masukkan NIP guru"
                        maxlength="100"
                        required
                    >


                    <small>

                        NIP digunakan sebagai kode guru, kode pegawai,
                        dan password awal akun.

                    </small>

                </div>


                {{-- INFORMASI AKUN --}}

                <div class="account-info">

                    <div class="account-info-title">

                        <i class="fa-solid fa-user-lock"></i>

                        Informasi Akun Login

                    </div>


                    <div class="account-info-row">

                        <span>
                            Nama akun
                        </span>

                        <strong>
                            Nama Guru
                        </strong>

                    </div>


                    <div class="account-info-row">

                        <span>
                            Kode guru
                        </span>

                        <strong>
                            NIP
                        </strong>

                    </div>


                    <div class="account-info-row">

                        <span>
                            Kode pegawai
                        </span>

                        <strong>
                            NIP
                        </strong>

                    </div>


                    <div class="account-info-row">

                        <span>
                            Password awal
                        </span>

                        <strong>
                            NIP
                        </strong>

                    </div>

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    onclick="tutupModalGuru()"
                >
                    Batal
                </button>


                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="fa-solid fa-floppy-disk"></i>

                    Simpan Guru

                </button>

            </div>

        </form>

    </div>

</div>


@endsection


{{-- =========================================================
     CSS
========================================================= --}}

@push('styles')

<style>

/* =========================================================
   HEADER
========================================================= */

.page-header {
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:20px;
    margin-bottom:24px;
}

.page-header h1 {
    margin:0;
    color:#173b67;
    font-size:28px;
    font-weight:700;
}

.page-header p {
    margin:6px 0 0;
    color:#718096;
    font-size:14px;
}

.header-actions {
    display:flex;
    align-items:center;
    gap:10px;
}


/* =========================================================
   BUTTON
========================================================= */

.btn {
    border:0;
    border-radius:10px;
    min-height:42px;
    padding:10px 15px;

    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;

    text-decoration:none;
    cursor:pointer;

    font-size:14px;
    font-weight:600;

    transition:
        background .2s ease,
        color .2s ease,
        transform .2s ease,
        box-shadow .2s ease;

    box-sizing:border-box;
}

.btn:hover {
    transform:translateY(-1px);
}

.btn-primary {
    background:#2563eb;
    color:#fff;
    box-shadow:0 5px 12px rgba(37,99,235,.15);
}

.btn-primary:hover {
    background:#1d4ed8;
}

.btn-secondary {
    background:#eef2f7;
    color:#334155;
}

.btn-secondary:hover {
    background:#e2e8f0;
}


/* =========================================================
   ALERT
========================================================= */

.alert {
    display:flex;
    align-items:flex-start;
    gap:10px;

    padding:14px 16px;

    border-radius:12px;

    margin-bottom:20px;

    font-size:14px;
}

.alert-success {
    background:#ecfdf5;
    color:#047857;
    border:1px solid #a7f3d0;
}

.alert-danger {
    background:#fef2f2;
    color:#b91c1c;
    border:1px solid #fecaca;
}

.error-list {
    margin:8px 0 0 18px;
    padding:0;
}


/* =========================================================
   STATISTIK
========================================================= */

.stats-grid {
    display:grid;
    grid-template-columns:repeat(3, minmax(0, 1fr));

    gap:18px;

    margin-bottom:20px;
}

.stat-card {
    background:#fff;

    border:1px solid #e5eaf1;

    border-radius:16px;

    padding:20px;

    box-shadow:
        0 5px 18px rgba(15,23,42,.04);
}

.stat-card-top {
    display:flex;
    align-items:center;
    justify-content:space-between;

    gap:15px;
}

.stat-label {
    color:#64748b;
    font-size:13px;
    margin-bottom:5px;
}

.stat-number {
    color:#0f172a;

    font-size:26px;

    line-height:1.1;

    font-weight:700;
}

.stat-status {
    color:#059669;

    font-size:21px;

    font-weight:700;
}

.stat-icon {
    width:48px;
    height:48px;

    flex:0 0 48px;

    border-radius:12px;

    background:#eff6ff;

    color:#2563eb;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:19px;
}


/* =========================================================
   CARD
========================================================= */

.card {
    background:#fff;

    border:1px solid #e5eaf1;

    border-radius:16px;

    margin-bottom:20px;

    overflow:hidden;

    box-shadow:
        0 5px 18px rgba(15,23,42,.04);
}

.card-header {
    display:flex;
    align-items:center;
    justify-content:space-between;

    gap:15px;

    padding:20px;

    border-bottom:1px solid #edf2f7;
}

.card-header h3 {
    margin:0;

    color:#173b67;

    font-size:19px;
}

.card-header p {
    margin:5px 0 0;

    color:#718096;

    font-size:14px;
}

.card-body {
    padding:20px;
}

.table-count {
    padding:6px 10px;

    border-radius:8px;

    background:#eff6ff;

    color:#2563eb;

    font-size:12px;

    font-weight:700;
}


/* =========================================================
   SEARCH
========================================================= */

.search-form {
    display:flex;
    align-items:center;

    gap:10px;
}

.search-box {
    flex:1;

    position:relative;
}

.search-box i {
    position:absolute;

    left:14px;
    top:50%;

    transform:translateY(-50%);

    color:#94a3b8;

    pointer-events:none;
}

.search-box input {
    width:100%;
    height:44px;

    border:1px solid #dbe2ea;

    border-radius:10px;

    padding:0 14px 0 40px;

    outline:none;

    box-sizing:border-box;

    color:#334155;

    background:#fff;
}

.search-box input:focus {
    border-color:#2563eb;

    box-shadow:
        0 0 0 3px rgba(37,99,235,.1);
}


/* =========================================================
   TABLE
========================================================= */

.table-wrapper {
    width:100%;
    overflow-x:auto;
}

.table {
    width:100%;

    min-width:650px;

    border-collapse:collapse;
}

.table th {
    background:#f8fafc;

    color:#475569;

    font-size:13px;

    font-weight:700;

    text-align:left;

    padding:14px;

    border-bottom:1px solid #e2e8f0;

    white-space:nowrap;
}

.table td {
    padding:14px;

    border-bottom:1px solid #edf2f7;

    color:#475569;

    vertical-align:middle;
}

.table tbody tr:last-child td {
    border-bottom:0;
}

.table tbody tr:hover {
    background:#f8fbff;
}


/* =========================================================
   GURU PROFILE
========================================================= */

.guru-profile {
    display:flex;

    align-items:center;

    gap:11px;

    min-width:190px;
}

.guru-avatar {
    width:40px;
    height:40px;

    flex:0 0 40px;

    border-radius:11px;

    background:#eff6ff;

    color:#2563eb;

    display:flex;
    align-items:center;
    justify-content:center;
}

.guru-name {
    display:block;

    color:#172554;

    font-size:14px;
}

.guru-label {
    display:block;

    margin-top:3px;

    color:#94a3b8;

    font-size:12px;
}


/* =========================================================
   BADGE
========================================================= */

.code-badge {
    display:inline-flex;

    align-items:center;

    padding:6px 9px;

    border-radius:8px;

    background:#f1f5f9;

    color:#475569;

    font-size:12px;

    font-weight:700;
}

.nip-text {
    white-space:nowrap;

    color:#334155;
}


/* =========================================================
   ACTION
========================================================= */

.action-buttons {
    display:flex;

    align-items:center;

    justify-content:center;

    gap:7px;
}

.action-btn {
    width:36px;
    height:36px;

    border:0;

    border-radius:9px;

    display:inline-flex;

    align-items:center;
    justify-content:center;

    cursor:pointer;

    text-decoration:none;

    transition:.2s;
}

.action-btn.delete {
    background:#fef2f2;

    color:#dc2626;
}

.action-btn.delete:hover {
    background:#fee2e2;
}


/* =========================================================
   EMPTY
========================================================= */

.empty-state {
    display:flex;

    flex-direction:column;

    align-items:center;
    justify-content:center;

    text-align:center;

    padding:55px 20px;
}

.empty-icon {
    width:58px;
    height:58px;

    border-radius:14px;

    background:#f1f5f9;

    color:#94a3b8;

    display:flex;

    align-items:center;
    justify-content:center;

    margin-bottom:15px;

    font-size:22px;
}

.empty-state strong {
    color:#334155;

    font-size:16px;
}

.empty-state span {
    margin-top:7px;

    color:#94a3b8;

    font-size:13px;
}


/* =========================================================
   INFO
========================================================= */

.info-card {
    display:flex;

    align-items:flex-start;

    gap:13px;

    padding:16px 18px;

    margin-bottom:20px;

    background:#eff6ff;

    border:1px solid #dbeafe;

    border-radius:14px;
}

.info-icon {
    width:38px;
    height:38px;

    flex:0 0 38px;

    border-radius:10px;

    background:#fff;

    color:#2563eb;

    display:flex;

    align-items:center;
    justify-content:center;
}

.info-card strong {
    display:block;

    color:#1e3a8a;

    font-size:14px;
}

.info-card p {
    margin:5px 0 0;

    color:#475569;

    font-size:13px;

    line-height:1.6;
}


/* =========================================================
   MODAL
========================================================= */

.modal-overlay {
    position:fixed;

    inset:0;

    display:none;

    align-items:center;
    justify-content:center;

    padding:20px;

    background:rgba(15,23,42,.55);

    z-index:9999;
}

.modal-overlay.active {
    display:flex;
}

.modal-card {
    width:100%;

    max-width:520px;

    max-height:90vh;

    overflow-y:auto;

    background:#fff;

    border-radius:18px;

    box-shadow:
        0 25px 60px rgba(15,23,42,.20);
}

.modal-header {
    display:flex;

    align-items:flex-start;

    justify-content:space-between;

    gap:15px;

    padding:20px 22px;

    border-bottom:1px solid #edf2f7;
}

.modal-header h2 {
    margin:0;

    color:#173b67;

    font-size:20px;
}

.modal-header p {
    margin:5px 0 0;

    color:#718096;

    font-size:13px;
}

.modal-close {
    width:34px;
    height:34px;

    flex:0 0 34px;

    border:0;

    border-radius:9px;

    background:#f1f5f9;

    color:#64748b;

    cursor:pointer;

    display:flex;

    align-items:center;
    justify-content:center;
}

.modal-close:hover {
    background:#e2e8f0;
}

.modal-body {
    padding:22px;
}

.form-group {
    margin-bottom:18px;
}

.form-group label {
    display:block;

    margin-bottom:7px;

    color:#334155;

    font-size:14px;

    font-weight:600;
}

.form-group input {
    width:100%;

    height:45px;

    border:1px solid #dbe2ea;

    border-radius:10px;

    padding:0 13px;

    outline:none;

    box-sizing:border-box;

    color:#334155;

    background:#fff;
}

.form-group input:focus {
    border-color:#2563eb;

    box-shadow:
        0 0 0 3px rgba(37,99,235,.1);
}

.form-group small {
    display:block;

    margin-top:6px;

    color:#94a3b8;

    font-size:12px;

    line-height:1.5;
}


/* =========================================================
   ACCOUNT INFO
========================================================= */

.account-info {
    padding:15px;

    background:#f8fafc;

    border:1px solid #e2e8f0;

    border-radius:12px;
}

.account-info-title {
    display:flex;

    align-items:center;

    gap:8px;

    margin-bottom:10px;

    color:#2563eb;

    font-size:13px;

    font-weight:700;
}

.account-info-row {
    display:flex;

    align-items:center;

    justify-content:space-between;

    gap:20px;

    padding:7px 0;

    font-size:13px;
}

.account-info-row span {
    color:#64748b;
}

.account-info-row strong {
    color:#334155;
}

.modal-footer {
    display:flex;

    justify-content:flex-end;

    gap:10px;

    padding:16px 22px;

    border-top:1px solid #edf2f7;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width:900px) {

    .stats-grid {
        grid-template-columns:1fr 1fr;
    }

}


@media(max-width:700px) {

    .page-header {
        flex-direction:column;

        align-items:stretch;
    }


    .header-actions {
        width:100%;
    }


    .header-actions .btn {
        flex:1;
    }


    .stats-grid {
        grid-template-columns:1fr;
    }


    .search-form {
        flex-direction:column;

        align-items:stretch;
    }


    .search-form .btn {
        width:100%;
    }


    .card-header {
        align-items:flex-start;
    }


    .modal-overlay {
        padding:12px;
    }


    .modal-card {
        max-height:94vh;
    }

}

</style>

@endpush


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

@push('scripts')

<script>

function bukaModalGuru()
{
    const modal = document.getElementById('modalGuru');

    if (!modal) {
        return;
    }

    modal.classList.add('active');

    modal.setAttribute('aria-hidden', 'false');


    setTimeout(function () {

        const input = document.getElementById('namaGuru');

        if (input) {
            input.focus();
        }

    }, 100);
}


function tutupModalGuru()
{
    const modal = document.getElementById('modalGuru');

    if (!modal) {
        return;
    }

    modal.classList.remove('active');

    modal.setAttribute('aria-hidden', 'true');
}


function konfirmasiHapusGuru()
{
    return confirm(
        'Apakah kamu yakin ingin menghapus data guru ini? Akun login guru juga akan dihapus.'
    );
}


/* =========================================================
   KLIK AREA LUAR MODAL
========================================================= */

document.addEventListener('click', function(event) {

    const modal = document.getElementById('modalGuru');

    if (!modal) {
        return;
    }

    if (event.target === modal) {

        tutupModalGuru();

    }

});


/* =========================================================
   TOMBOL ESC
========================================================= */

document.addEventListener('keydown', function(event) {

    if (event.key === 'Escape') {

        tutupModalGuru();

    }

});


/* =========================================================
   BUKA MODAL JIKA VALIDASI GAGAL
========================================================= */

@if($errors->any())

    document.addEventListener('DOMContentLoaded', function() {

        bukaModalGuru();

    });

@endif

</script>

@endpush