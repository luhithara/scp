@extends('layouts.app')

@section('title', 'Data Orang Tua')

@section('content')

<div class="page-container">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="page-header">

        <div>
            <h1>Data Orang Tua</h1>
            <p>
                Kelola data orang tua atau wali siswa.
            </p>
        </div>

        <div class="header-actions">

            <button
                type="button"
                class="btn-primary"
                onclick="bukaModalOrangtua()"
            >
                <i class="fa-solid fa-plus"></i>
                Tambah Orang Tua
            </button>

            <a
                href="{{ route('waka.data') }}"
                class="btn-secondary"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>

        </div>

    </div>


    {{-- =========================================================
         NOTIFIKASI SUCCESS
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success">

            <i class="fa-solid fa-circle-check"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =========================================================
         NOTIFIKASI ERROR
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-error">

            <i class="fa-solid fa-circle-exclamation"></i>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERROR
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-error">

            <i class="fa-solid fa-circle-exclamation"></i>

            <div>

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

        </div>

    @endif


    {{-- =========================================================
         STATISTIK
    ========================================================== --}}
    <div class="stat-card">

        <div class="stat-icon">

            <i class="fa-solid fa-users"></i>

        </div>

        <div>

            <span>
                Total Data Orang Tua
            </span>

            <strong>
                {{ $jumlahOrangtua ?? 0 }}
            </strong>

        </div>

    </div>


    {{-- =========================================================
         CARD DATA ORANG TUA
    ========================================================== --}}
    <div class="card">

        <div class="card-header">

            <div>

                <h2>
                    Daftar Orang Tua
                </h2>

                <p>
                    Data orang tua atau wali siswa yang sudah terdaftar.
                </p>

            </div>

        </div>


        {{-- =====================================================
             SEARCH
        ====================================================== --}}
        <form
            action="{{ route('waka.orangtua') }}"
            method="GET"
            class="search-form"
        >

            <div class="search-box">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    name="cari"
                    value="{{ $kataKunci ?? request('cari', '') }}"
                    placeholder="Cari nama orang tua, siswa, NISN, kelas..."
                >

            </div>

            <button
                type="submit"
                class="btn-primary"
            >
                <i class="fa-solid fa-magnifying-glass"></i>
                Cari
            </button>


            @if(!empty($kataKunci ?? request('cari')))

                <a
                    href="{{ route('waka.orangtua') }}"
                    class="btn-secondary"
                >
                    <i class="fa-solid fa-rotate-left"></i>
                    Reset
                </a>

            @endif

        </form>


        {{-- =====================================================
             TABLE
        ====================================================== --}}
        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th width="60">
                            No
                        </th>

                        <th>
                            Nama Orang Tua
                        </th>

                        <th>
                            Hubungan
                        </th>

                        <th>
                            Nama Siswa
                        </th>

                        <th>
                            NISN
                        </th>

                        <th>
                            Kelas
                        </th>

                        <th>
                            No. Telepon
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($dataOrangtua ?? collect() as $index => $item)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>


                            <td>

                                <div class="name-cell">

                                    <div class="avatar-small">
                                        <i class="fa-solid fa-user"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            {{ $item->nama }}
                                        </strong>

                                    </div>

                                </div>

                            </td>


                            <td>

                                <span class="badge">

                                    {{ $item->hubungan }}

                                </span>

                            </td>


                            <td>

                                <strong class="student-name">
                                    {{ $item->namasiswa ?? '-' }}
                                </strong>

                            </td>


                            <td>

                                {{ $item->nisnanak ?? '-' }}

                            </td>


                            <td>

                                {{ $item->namakelas ?? '-' }}

                            </td>


                            <td>

                                {{ $item->nomortelepon ?? '-' }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="empty-data"
                            >

                                <div class="empty-icon">

                                    <i class="fa-solid fa-users"></i>

                                </div>

                                <strong>
                                    Belum ada data orang tua
                                </strong>

                                <span>
                                    Silakan tambahkan data orang tua menggunakan tombol
                                    "Tambah Orang Tua".
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>



{{-- =============================================================
     MODAL TAMBAH ORANG TUA
============================================================= --}}
<div
    id="modalOrangtua"
    class="modal-overlay"
    onclick="klikOverlay(event)"
>

    <div
        class="modal-box"
        onclick="event.stopPropagation()"
    >

        {{-- =====================================================
             MODAL HEADER
        ====================================================== --}}
        <div class="modal-header">

            <div>

                <div class="modal-title">

                    <div class="modal-icon">

                        <i class="fa-solid fa-user-plus"></i>

                    </div>

                    <div>

                        <h2>
                            Tambah Orang Tua
                        </h2>

                        <p>
                            Tambahkan data orang tua atau wali siswa.
                        </p>

                    </div>

                </div>

            </div>


            <button
                type="button"
                class="modal-close"
                onclick="tutupModalOrangtua()"
                aria-label="Tutup"
            >

                <i class="fa-solid fa-xmark"></i>

            </button>

        </div>


        {{-- =====================================================
             MODAL BODY
        ====================================================== --}}
        <div class="modal-body">

            <form
                action="{{ route('waka.orangtua.simpan') }}"
                method="POST"
                id="formOrangtua"
            >

                @csrf


                {{-- =================================================
                     SISWA
                ================================================== --}}
                <div class="form-group">

                    <label for="siswaid">

                        Siswa

                        <span class="required">
                            *
                        </span>

                    </label>


                    <select
                        name="siswaid"
                        id="siswaid"
                        required
                    >

                        <option value="">
                            -- Pilih Siswa --
                        </option>


                        @forelse($dataSiswa ?? collect() as $siswa)

                            <option
                                value="{{ $siswa->id }}"
                                {{ old('siswaid') == $siswa->id ? 'selected' : '' }}
                            >

                                {{ $siswa->nama }}

                                @if(!empty($siswa->nisn))
                                    — NISN: {{ $siswa->nisn }}
                                @endif

                                @if(!empty($siswa->namakelas))
                                    — {{ $siswa->namakelas }}
                                @endif

                            </option>

                        @empty

                            <option
                                value=""
                                disabled
                            >
                                Data siswa belum tersedia
                            </option>

                        @endforelse

                    </select>


                    @if(($dataSiswa ?? collect())->count() === 0)

                        <small class="form-warning">

                            <i class="fa-solid fa-triangle-exclamation"></i>

                            Data siswa belum ditemukan.

                        </small>

                    @endif

                </div>


                {{-- =================================================
                     NAMA ORANG TUA
                ================================================== --}}
                <div class="form-group">

                    <label for="nama">

                        Nama Orang Tua / Wali

                        <span class="required">
                            *
                        </span>

                    </label>


                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        value="{{ old('nama') }}"
                        placeholder="Masukkan nama orang tua / wali"
                        required
                    >

                </div>


                {{-- =================================================
                     HUBUNGAN
                ================================================== --}}
                <div class="form-group">

                    <label for="hubungan">

                        Hubungan

                        <span class="required">
                            *
                        </span>

                    </label>


                    <select
                        name="hubungan"
                        id="hubungan"
                        required
                    >

                        <option value="">
                            -- Pilih Hubungan --
                        </option>

                        <option
                            value="Ayah"
                            {{ old('hubungan') == 'Ayah' ? 'selected' : '' }}
                        >
                            Ayah
                        </option>

                        <option
                            value="Ibu"
                            {{ old('hubungan') == 'Ibu' ? 'selected' : '' }}
                        >
                            Ibu
                        </option>

                        <option
                            value="Wali"
                            {{ old('hubungan') == 'Wali' ? 'selected' : '' }}
                        >
                            Wali
                        </option>

                    </select>

                </div>


                {{-- =================================================
                     NOMOR TELEPON
                ================================================== --}}
                <div class="form-group">

                    <label for="nomortelepon">

                        Nomor Telepon

                    </label>


                    <input
                        type="text"
                        name="nomortelepon"
                        id="nomortelepon"
                        value="{{ old('nomortelepon') }}"
                        placeholder="Contoh: 081234567890"
                    >

                </div>


                {{-- =================================================
                     ALAMAT
                ================================================== --}}
                <div class="form-group">

                    <label for="alamat">

                        Alamat

                    </label>


                    <textarea
                        name="alamat"
                        id="alamat"
                        rows="4"
                        placeholder="Masukkan alamat orang tua / wali"
                    >{{ old('alamat') }}</textarea>

                </div>


                {{-- =================================================
                     MODAL FOOTER
                ================================================== --}}
                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn-secondary"
                        onclick="tutupModalOrangtua()"
                    >

                        <i class="fa-solid fa-xmark"></i>

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="btn-primary"
                    >

                        <i class="fa-solid fa-floppy-disk"></i>

                        Simpan Data

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- =============================================================
     STYLE
============================================================= --}}
<style>

.page-container {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px;
    box-sizing: border-box;
}


/* =========================================================
   HEADER
========================================================= */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}

.page-header h1 {
    margin: 0 0 6px;
    color: #172554;
    font-size: 28px;
    font-weight: 700;
}

.page-header p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}


/* =========================================================
   BUTTON
========================================================= */

.btn-primary,
.btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    min-height: 42px;

    padding: 10px 16px;

    border-radius: 10px;

    font-size: 14px;
    font-weight: 600;

    cursor: pointer;

    text-decoration: none;

    transition: all 0.2s ease;

    box-sizing: border-box;
}

.btn-primary {
    background: #2563eb;
    color: #ffffff;
    border: 1px solid #2563eb;
}

.btn-primary:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
}

.btn-secondary {
    background: #ffffff;
    color: #334155;
    border: 1px solid #cbd5e1;
}

.btn-secondary:hover {
    background: #f8fafc;
}


/* =========================================================
   ALERT
========================================================= */

.alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;

    padding: 14px 16px;

    border-radius: 12px;

    margin-bottom: 20px;

    font-size: 14px;
}

.alert-success {
    background: #ecfdf5;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.alert-error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.alert ul {
    margin: 8px 0 0 18px;
    padding: 0;
}


/* =========================================================
   STAT CARD
========================================================= */

.stat-card {
    display: flex;
    align-items: center;
    gap: 16px;

    background: #ffffff;

    border: 1px solid #e2e8f0;

    border-radius: 16px;

    padding: 18px 20px;

    margin-bottom: 20px;

    box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
}

.stat-icon {
    width: 48px;
    height: 48px;

    flex-shrink: 0;

    border-radius: 12px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #eff6ff;
    color: #2563eb;

    font-size: 20px;
}

.stat-card span {
    display: block;

    color: #64748b;

    font-size: 13px;

    margin-bottom: 4px;
}

.stat-card strong {
    color: #172554;

    font-size: 24px;
}


/* =========================================================
   CARD
========================================================= */

.card {
    background: #ffffff;

    border: 1px solid #e2e8f0;

    border-radius: 16px;

    padding: 22px;

    margin-bottom: 22px;

    box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
}

.card-header {
    margin-bottom: 20px;
}

.card-header h2 {
    margin: 0 0 5px;

    color: #172554;

    font-size: 19px;
}

.card-header p {
    margin: 0;

    color: #64748b;

    font-size: 13px;
}


/* =========================================================
   SEARCH
========================================================= */

.search-form {
    display: flex;
    align-items: center;
    gap: 10px;

    margin-bottom: 20px;
}

.search-box {
    position: relative;
    flex: 1;
}

.search-box i {
    position: absolute;

    left: 14px;
    top: 50%;

    transform: translateY(-50%);

    color: #94a3b8;
}

.search-box input {
    width: 100%;

    box-sizing: border-box;

    padding: 11px 13px 11px 40px;

    border: 1px solid #cbd5e1;

    border-radius: 10px;

    background: #ffffff;

    color: #1e293b;

    font-size: 14px;

    outline: none;
}

.search-box input:focus {
    border-color: #2563eb;

    box-shadow:
        0 0 0 3px rgba(37, 99, 235, 0.10);
}


/* =========================================================
   TABLE
========================================================= */

.table-wrapper {
    width: 100%;

    overflow-x: auto;
}

table {
    width: 100%;

    min-width: 900px;

    border-collapse: collapse;
}

thead th {
    padding: 13px 12px;

    text-align: left;

    background: #f8fafc;

    color: #475569;

    font-size: 13px;

    font-weight: 700;

    border-bottom: 1px solid #e2e8f0;
}

tbody td {
    padding: 14px 12px;

    color: #334155;

    font-size: 14px;

    border-bottom: 1px solid #f1f5f9;
}

tbody tr:hover {
    background: #f8fafc;
}

.name-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.avatar-small {
    width: 36px;
    height: 36px;

    flex-shrink: 0;

    border-radius: 10px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #eff6ff;

    color: #2563eb;

    font-size: 14px;
}

.student-name {
    color: #1e3a8a;
}

.badge {
    display: inline-flex;

    align-items: center;

    padding: 5px 9px;

    border-radius: 7px;

    background: #eff6ff;

    color: #1d4ed8;

    font-size: 12px;

    font-weight: 600;
}


/* =========================================================
   EMPTY DATA
========================================================= */

.empty-data {
    text-align: center;

    padding: 50px 20px !important;
}

.empty-icon {
    width: 56px;
    height: 56px;

    margin: 0 auto 14px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    background: #f1f5f9;

    color: #94a3b8;

    font-size: 22px;
}

.empty-data strong {
    display: block;

    margin-bottom: 5px;

    color: #475569;

    font-size: 15px;
}

.empty-data span {
    display: block;

    color: #94a3b8;

    font-size: 13px;
}


/* =========================================================
   MODAL OVERLAY
========================================================= */

.modal-overlay {
    position: fixed;

    inset: 0;

    z-index: 9999;

    display: none;

    align-items: center;
    justify-content: center;

    padding: 20px;

    background: rgba(15, 23, 42, 0.55);

    backdrop-filter: blur(3px);

    box-sizing: border-box;
}

.modal-overlay.active {
    display: flex;
}


/* =========================================================
   MODAL BOX
========================================================= */

.modal-box {
    width: 100%;

    max-width: 620px;

    max-height: calc(100vh - 40px);

    overflow-y: auto;

    background: #ffffff;

    border-radius: 18px;

    box-shadow:
        0 25px 60px rgba(15, 23, 42, 0.25);

    animation: modalMasuk 0.2s ease-out;
}

@keyframes modalMasuk {

    from {
        opacity: 0;
        transform: translateY(15px) scale(0.98);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

}


/* =========================================================
   MODAL HEADER
========================================================= */

.modal-header {
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 20px 22px;

    border-bottom: 1px solid #e2e8f0;
}

.modal-title {
    display: flex;

    align-items: center;

    gap: 12px;
}

.modal-icon {
    width: 44px;
    height: 44px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #eff6ff;

    color: #2563eb;

    font-size: 18px;
}

.modal-header h2 {
    margin: 0 0 4px;

    color: #172554;

    font-size: 19px;
}

.modal-header p {
    margin: 0;

    color: #64748b;

    font-size: 13px;
}

.modal-close {
    width: 38px;
    height: 38px;

    flex-shrink: 0;

    border: none;

    border-radius: 9px;

    background: #f8fafc;

    color: #64748b;

    cursor: pointer;

    font-size: 16px;

    transition: 0.2s;
}

.modal-close:hover {
    background: #fee2e2;

    color: #dc2626;
}


/* =========================================================
   MODAL BODY
========================================================= */

.modal-body {
    padding: 22px;
}


/* =========================================================
   FORM
========================================================= */

#formOrangtua {
    display: flex;

    flex-direction: column;

    gap: 17px;
}

.form-group {
    display: flex;

    flex-direction: column;

    gap: 7px;
}

.form-group label {
    color: #334155;

    font-size: 14px;

    font-weight: 600;
}

.required {
    color: #dc2626;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;

    box-sizing: border-box;

    padding: 11px 13px;

    border: 1px solid #cbd5e1;

    border-radius: 10px;

    background: #ffffff;

    color: #1e293b;

    font-size: 14px;

    outline: none;

    transition: 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #2563eb;

    box-shadow:
        0 0 0 3px rgba(37, 99, 235, 0.10);
}

.form-group textarea {
    resize: vertical;

    min-height: 100px;
}

.form-warning {
    display: flex;

    align-items: center;

    gap: 6px;

    color: #b45309;

    font-size: 12px;
}


/* =========================================================
   MODAL FOOTER
========================================================= */

.modal-footer {
    display: flex;

    justify-content: flex-end;

    gap: 10px;

    padding-top: 4px;
}


/* =========================================================
   BODY SAAT MODAL TERBUKA
========================================================= */

body.modal-open {
    overflow: hidden;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .page-container {
        padding: 16px;
    }

    .page-header {
        flex-direction: column;

        align-items: flex-start;
    }

    .header-actions {
        width: 100%;

        flex-direction: column;
    }

    .header-actions .btn-primary,
    .header-actions .btn-secondary {
        width: 100%;
    }

    .search-form {
        flex-direction: column;

        align-items: stretch;
    }

    .search-form .btn-primary,
    .search-form .btn-secondary {
        width: 100%;
    }

    .modal-overlay {
        padding: 12px;
    }

    .modal-box {
        max-height: calc(100vh - 24px);

        border-radius: 15px;
    }

    .modal-header {
        padding: 17px;
    }

    .modal-body {
        padding: 17px;
    }

    .modal-footer {
        flex-direction: column-reverse;
    }

    .modal-footer button {
        width: 100%;
    }

}

</style>



{{-- =============================================================
     JAVASCRIPT MODAL
============================================================= --}}
<script>

function bukaModalOrangtua() {

    const modal = document.getElementById('modalOrangtua');

    if (!modal) {
        return;
    }

    modal.classList.add('active');

    document.body.classList.add('modal-open');

    setTimeout(function () {

        const siswaSelect = document.getElementById('siswaid');

        if (siswaSelect) {
            siswaSelect.focus();
        }

    }, 100);

}


function tutupModalOrangtua() {

    const modal = document.getElementById('modalOrangtua');

    if (!modal) {
        return;
    }

    modal.classList.remove('active');

    document.body.classList.remove('modal-open');

}


function klikOverlay(event) {

    const modal = document.getElementById('modalOrangtua');

    if (event.target === modal) {

        tutupModalOrangtua();

    }

}


/*
|--------------------------------------------------------------------------
| Tombol ESC untuk menutup modal
|--------------------------------------------------------------------------
*/

document.addEventListener('keydown', function(event) {

    if (event.key === 'Escape') {

        tutupModalOrangtua();

    }

});


/*
|--------------------------------------------------------------------------
| Jika validasi Laravel gagal
|--------------------------------------------------------------------------
| Modal otomatis dibuka kembali supaya data yang sudah diisi
| tidak terasa hilang.
|--------------------------------------------------------------------------
*/

@if($errors->any())

    document.addEventListener('DOMContentLoaded', function() {

        bukaModalOrangtua();

    });

@endif

</script>

@endsection