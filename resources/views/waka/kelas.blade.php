{{-- =========================================================
     SCP - DATA KELAS WAKA
     File:
     resources/views/waka/kelas.blade.php
========================================================= --}}

@extends('layouts.app')

@section('title', 'Data Kelas - SCP')
@section('page-title', 'Data Kelas')

@section('content')

{{-- HEADER --}}
<div class="page-header">
    <div>
        <h1>Data Kelas</h1>
        <p>Kelola data kelas berdasarkan angkatan dan jurusan.</p>
    </div>
</div>

{{-- NOTIFIKASI --}}
@if(session('success'))
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>{{ session('warning') }}</span>
    </div>
@endif

@if(session('import_errors'))
    <div class="alert alert-danger">
        <div>
            <strong>Detail data yang gagal diimport:</strong>
            <ul class="error-list">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <i class="fa-solid fa-circle-exclamation"></i>
        <div>
            <strong>Data belum bisa disimpan.</strong>
            <ul class="error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- STATISTIK --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-card-top">
            <div>
                <div class="stat-label">Jumlah Kelas</div>
                <div class="stat-number">{{ $jumlahKelas ?? 0 }}</div>
            </div>
            <div class="stat-icon">
                <i class="fa-solid fa-school"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div>
                <div class="stat-label">Jumlah Siswa</div>
                <div class="stat-number">{{ $jumlahSiswa ?? 0 }}</div>
            </div>
            <div class="stat-icon">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div>
                <div class="stat-label">Angkatan</div>
                <div class="stat-number">
                    {{ isset($daftarAngkatan) ? $daftarAngkatan->count() : 0 }}
                </div>
            </div>
            <div class="stat-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
        </div>
    </div>

</div>

{{-- TOOLBAR --}}
<div class="page-toolbar">

    <div>
        <h3 class="toolbar-title">Kelola Kelas</h3>
        <p class="toolbar-description">
            Tambahkan kelas secara manual atau import data dari file.
        </p>
    </div>

    <div class="toolbar-actions">

        <button
            type="button"
            class="btn btn-success"
            onclick="bukaModalImportKelas()"
        >
            <i class="fa-solid fa-file-import"></i>
            Import Data
        </button>

        <button
            type="button"
            class="btn btn-primary"
            onclick="bukaFormKelas()"
        >
            <i class="fa-solid fa-plus"></i>
            Tambah Kelas
        </button>

    </div>

</div>

{{-- =========================================================
     MODAL IMPORT DATA KELAS
========================================================= --}}
<div id="modalImportKelas" class="import-modal">

    <div class="import-modal-content">

        <div class="import-modal-header">

            <div class="import-modal-title">
                <div class="import-modal-icon">
                    <i class="fa-solid fa-file-import"></i>
                </div>

                <div>
                    <h3>Import Data Kelas</h3>
                    <p>
                        Masukkan file untuk menambahkan data kelas secara otomatis.
                    </p>
                </div>
            </div>

            <button
                type="button"
                class="import-modal-close"
                onclick="tutupModalImportKelas()"
                aria-label="Tutup"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>

        <div class="import-modal-body">

            <form
                action="{{ route('waka.data.import', ['jenis' => 'kelas']) }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="import-upload-area">

                    <div class="import-upload-icon">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>

                    <h4>Pilih File Data Kelas</h4>

                    <p>
                        Pilih file Excel, CSV, atau PDF dari perangkatmu.
                    </p>

                    <label
                        for="fileImportKelas"
                        class="import-select-button"
                    >
                        <i class="fa-solid fa-folder-open"></i>
                        Pilih File
                    </label>

                    <input
                        type="file"
                        id="fileImportKelas"
                        name="file"
                        accept=".xlsx,.xls,.csv,.pdf"
                        required
                        hidden
                    >

                    <div
                        id="namaFileImportKelas"
                        class="import-file-name"
                    >
                        Belum ada file yang dipilih
                    </div>

                </div>

                @error('file')
                    <div class="import-error-message">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </div>
                @enderror

                <div class="import-format-info">

                    <div class="import-info-heading">
                        <i class="fa-solid fa-circle-info"></i>
                        Ketentuan Import Data
                    </div>

                    <ul>
                        <li>
                            Format file:
                            <b>.xlsx, .xls, .csv, dan .pdf</b>.
                        </li>
                        <li>
                            Ukuran maksimal file:
                            <b>10 MB</b>.
                        </li>
                        <li>
                            Kolom wajib:
                            <b>namakelas, jurusan, tingkat</b>.
                        </li>
                        <li>
                            Jurusan harus sudah tersedia di database SCP.
                        </li>
                        <li>
                            Pastikan nama kolom dan isi data sesuai sebelum mengimpor.
                        </li>
                    </ul>

                </div>

                <div class="import-example">

                    <strong>Contoh susunan data</strong>

                    <div class="import-example-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>namakelas</th>
                                    <th>jurusan</th>
                                    <th>tingkat</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>XI RPL 1</td>
                                    <td>Rekayasa Perangkat Lunak</td>
                                    <td>11</td>
                                </tr>

                                <tr>
                                    <td>XI RPL 2</td>
                                    <td>Rekayasa Perangkat Lunak</td>
                                    <td>11</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="import-modal-actions">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        onclick="tutupModalImportKelas()"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        <i class="fa-solid fa-upload"></i>
                        Import Sekarang
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- =========================================================
     FORM TAMBAH KELAS MANUAL
========================================================= --}}
<div
    id="formKelas"
    class="card tambah-kelas-card"
    style="{{ $errors->any() ? 'display:block;' : 'display:none;' }}"
>

    <div class="card-header">

        <div>
            <h3>
                <i class="fa-solid fa-school"></i>
                Tambah Data Kelas
            </h3>

            <p>
                Masukkan data kelas baru sesuai angkatan dan jurusan.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-secondary"
            onclick="tutupFormKelas()"
        >
            <i class="fa-solid fa-xmark"></i>
            Tutup
        </button>

    </div>

    <div class="card-body">

        <form
            action="{{ route('waka.kelas.simpan') }}"
            method="POST"
        >

            @csrf

            <div class="form-grid">

                {{-- NAMA KELAS --}}
                <div class="form-group">

                    <label for="namakelas">
                        Nama Kelas
                        <span class="required">*</span>
                    </label>

                    <div class="input-wrapper">
                        <i class="fa-solid fa-school input-icon"></i>

                        <input
                            type="text"
                            id="namakelas"
                            name="namakelas"
                            class="form-input input-with-icon"
                            value="{{ old('namakelas') }}"
                            placeholder="Contoh: XI RPL 1"
                            maxlength="100"
                            required
                        >
                    </div>

                    @error('namakelas')
                        <small class="form-error">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                {{-- TINGKAT --}}
                <div class="form-group">

                    <label for="tingkat">
                        Tingkat
                        <span class="required">*</span>
                    </label>

                    <select
                        id="tingkat"
                        name="tingkat"
                        class="form-input"
                        required
                    >
                        <option value="">Pilih tingkat</option>

                        <option
                            value="10"
                            {{ old('tingkat') == '10' ? 'selected' : '' }}
                        >
                            10
                        </option>

                        <option
                            value="11"
                            {{ old('tingkat') == '11' ? 'selected' : '' }}
                        >
                            11
                        </option>

                        <option
                            value="12"
                            {{ old('tingkat') == '12' ? 'selected' : '' }}
                        >
                            12
                        </option>
                    </select>

                    @error('tingkat')
                        <small class="form-error">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                {{-- JURUSAN --}}
                <div class="form-group">

                    <label for="jurusanid">
                        Jurusan
                        <span class="required">*</span>
                    </label>

                    @php
                        $daftarJurusanForm = DB::table('jurusan')
                            ->select(
                                'id',
                                'kodejurusan',
                                'namajurusan'
                            )
                            ->orderBy('kodejurusan', 'asc')
                            ->get();
                    @endphp

                    <select
                        id="jurusanid"
                        name="jurusanid"
                        class="form-input"
                        required
                    >
                        <option value="">Pilih jurusan</option>

                        @foreach($daftarJurusanForm as $jurusan)
                            <option
                                value="{{ $jurusan->id }}"
                                {{ old('jurusanid') == $jurusan->id ? 'selected' : '' }}
                            >
                                {{ $jurusan->kodejurusan }}
                                -
                                {{ $jurusan->namajurusan }}
                            </option>
                        @endforeach

                    </select>

                    @error('jurusanid')
                        <small class="form-error">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            {{ $message }}
                        </small>
                    @enderror

                </div>

            </div>

            <div class="form-info">

                <div class="form-info-icon">
                    <i class="fa-solid fa-circle-info"></i>
                </div>

                <div>
                    <strong>Informasi kelas</strong>

                    <p>
                        Pastikan nama kelas, tingkat, dan jurusan sudah sesuai
                        sebelum menyimpan data.
                    </p>
                </div>

            </div>

            <div class="form-actions">

                <button
                    type="button"
                    class="btn btn-secondary"
                    onclick="tutupFormKelas()"
                >
                    <i class="fa-solid fa-xmark"></i>
                    Batal
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Kelas
                </button>

            </div>

        </form>

    </div>

</div>

{{-- =========================================================
     PILIH ANGKATAN
========================================================= --}}
<div class="card">

    <div class="card-header">
        <div>
            <h3>Pilih Angkatan</h3>
            <p>
                Pilih tingkat untuk melihat jurusan dan daftar kelas.
            </p>
        </div>
    </div>

    <div class="card-body">

        @if(isset($daftarAngkatan) && $daftarAngkatan->count() > 0)

            <div class="angkatan-grid">

                @foreach($daftarAngkatan as $angkatan)

                    <a
                        href="{{ route(
                            'waka.kelas.angkatan',
                            ['tingkat' => $angkatan->tingkat]
                        ) }}"
                        class="angkatan-card"
                    >

                        <div class="angkatan-icon">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>

                        <div class="angkatan-content">

                            <span class="angkatan-label">
                                Tingkat
                            </span>

                            <strong>
                                {{ $angkatan->tingkat }}
                            </strong>

                            <small>
                                Lihat jurusan dan kelas
                            </small>

                        </div>

                        <div class="angkatan-arrow">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>

                    </a>

                @endforeach

            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    <i class="fa-solid fa-school"></i>
                </div>

                <strong>Belum Ada Angkatan</strong>

                <span>
                    Belum ada data angkatan yang tersedia.
                </span>

            </div>

        @endif

    </div>

</div>

{{-- INFORMASI --}}
<div class="info-card">

    <div class="info-card-icon">
        <i class="fa-solid fa-circle-info"></i>
    </div>

    <div>

        <strong>Pengelolaan data kelas</strong>

        <p>
            Pilih tingkat 10, 11, atau 12 untuk melihat jurusan dan kelas
            yang tersedia. Dari halaman tersebut Waka dapat melihat siswa
            yang berada di masing-masing kelas.
        </p>

    </div>

</div>

@endsection

{{-- =========================================================
     CSS
========================================================= --}}
@push('styles')

<style>

/* HEADER */
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

/* ALERT */
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

.alert-warning {
    background:#fffbeb;
    color:#92400e;
    border:1px solid #fde68a;
}

.error-list {
    margin:8px 0 0 18px;
    padding:0;
}

.error-list li {
    margin-bottom:4px;
}

/* STATISTICS */
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
    box-shadow:0 5px 18px rgba(15,23,42,.04);
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

/* TOOLBAR */
.page-toolbar {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:20px;
    margin-bottom:20px;
}

.toolbar-title {
    margin:0;
    color:#173b67;
    font-size:18px;
}

.toolbar-description {
    margin:5px 0 0;
    color:#718096;
    font-size:13px;
}

.toolbar-actions {
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
}

/* BUTTON */
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
    transition:.2s;
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

.btn-success {
    background:#059669;
    color:#fff;
    box-shadow:0 5px 12px rgba(5,150,105,.15);
}

.btn-success:hover {
    background:#047857;
}

.btn-secondary {
    background:#eef2f7;
    color:#334155;
}

.btn-secondary:hover {
    background:#e2e8f0;
}

/* CARD */
.card {
    background:#fff;
    border:1px solid #e5eaf1;
    border-radius:16px;
    margin-bottom:20px;
    overflow:hidden;
    box-shadow:0 5px 18px rgba(15,23,42,.04);
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

.card-header h3 i {
    margin-right:6px;
    color:#2563eb;
    font-size:16px;
}

.card-header p {
    margin:5px 0 0;
    color:#718096;
    font-size:14px;
}

.card-body {
    padding:20px;
}

/* FORM */
.tambah-kelas-card {
    display:none;
}

.form-grid {
    display:grid;
    grid-template-columns:1.2fr .8fr 1.3fr;
    gap:16px;
    align-items:start;
}

.form-group {
    min-width:0;
}

.form-group label {
    display:block;
    margin-bottom:8px;
    color:#334155;
    font-size:13px;
    font-weight:600;
}

.required {
    color:#dc2626;
}

.input-wrapper {
    position:relative;
}

.input-icon {
    position:absolute;
    left:13px;
    top:50%;
    transform:translateY(-50%);
    color:#94a3b8;
    pointer-events:none;
}

.form-input {
    width:100%;
    min-height:44px;
    padding:0 13px;
    border:1px solid #dbe4f0;
    border-radius:10px;
    background:#fff;
    color:#0f172a;
    font-size:13px;
    outline:none;
    box-sizing:border-box;
    transition:.2s;
}

.input-with-icon {
    padding-left:39px;
}

.form-input:focus {
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.08);
}

.form-input::placeholder {
    color:#94a3b8;
}

.form-error {
    display:block;
    margin-top:6px;
    color:#dc2626;
    font-size:12px;
}

/* FORM INFO */
.form-info {
    display:flex;
    align-items:flex-start;
    gap:11px;
    margin-top:20px;
    padding:13px 15px;
    background:#f8fbff;
    border:1px solid #dbeafe;
    border-radius:11px;
}

.form-info-icon {
    width:34px;
    height:34px;
    flex:0 0 34px;
    border-radius:9px;
    background:#eff6ff;
    color:#2563eb;
    display:flex;
    align-items:center;
    justify-content:center;
}

.form-info strong {
    display:block;
    color:#1e3a8a;
    font-size:13px;
}

.form-info p {
    margin:4px 0 0;
    color:#64748b;
    font-size:12px;
    line-height:1.5;
}

/* FORM ACTION */
.form-actions {
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:20px;
    padding-top:18px;
    border-top:1px solid #edf2f7;
}

/* MODAL IMPORT */
.import-modal {
    display:none;
    position:fixed;
    inset:0;
    z-index:9999;
    background:rgba(15,23,42,.55);
    backdrop-filter:blur(3px);
    align-items:center;
    justify-content:center;
    padding:20px;
    overflow-y:auto;
}

.import-modal.show {
    display:flex;
}

.import-modal-content {
    width:100%;
    max-width:620px;
    max-height:90vh;
    overflow-y:auto;
    background:#fff;
    border-radius:18px;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
    animation:importModalMasuk .2s ease;
}

@keyframes importModalMasuk {
    from {
        opacity:0;
        transform:translateY(15px);
    }
    to {
        opacity:1;
        transform:translateY(0);
    }
}

.import-modal-header {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:15px;
    padding:20px 22px;
    border-bottom:1px solid #edf2f7;
}

.import-modal-title {
    display:flex;
    align-items:center;
    gap:13px;
}

.import-modal-icon {
    width:46px;
    height:46px;
    flex:0 0 46px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:12px;
    background:#d1fae5;
    color:#059669;
    font-size:19px;
}

.import-modal-title h3 {
    margin:0;
    color:#173b67;
    font-size:18px;
}

.import-modal-title p {
    margin:5px 0 0;
    color:#718096;
    font-size:12px;
    line-height:1.5;
}

.import-modal-close {
    width:36px;
    height:36px;
    flex:0 0 36px;
    border:0;
    border-radius:9px;
    background:#f1f5f9;
    color:#64748b;
    cursor:pointer;
    font-size:16px;
}

.import-modal-close:hover {
    background:#fee2e2;
    color:#dc2626;
}

.import-modal-body {
    padding:22px;
}

.import-upload-area {
    display:flex;
    flex-direction:column;
    align-items:center;
    text-align:center;
    padding:25px 15px;
    border:2px dashed #a7f3d0;
    border-radius:14px;
    background:#f0fdf4;
}

.import-upload-icon {
    width:58px;
    height:58px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    background:#d1fae5;
    color:#059669;
    font-size:24px;
    margin-bottom:12px;
}

.import-upload-area h4 {
    margin:0;
    color:#173b67;
    font-size:16px;
}

.import-upload-area p {
    margin:6px 0 15px;
    color:#718096;
    font-size:12px;
}

.import-select-button {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:10px 17px;
    border-radius:9px;
    background:#059669;
    color:#fff;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
    transition:.2s;
}

.import-select-button:hover {
    background:#047857;
}

.import-file-name {
    margin-top:12px;
    color:#64748b;
    font-size:12px;
    max-width:100%;
    overflow-wrap:anywhere;
}

.import-format-info {
    margin-top:17px;
    padding:15px;
    background:#eff6ff;
    border:1px solid #dbeafe;
    border-radius:11px;
}

.import-info-heading {
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom:9px;
    color:#1e40af;
    font-size:13px;
    font-weight:700;
}

.import-format-info ul {
    margin:0;
    padding-left:22px;
    color:#475569;
    font-size:12px;
    line-height:1.8;
}

.import-format-info li {
    padding-left:3px;
}

.import-example {
    margin-top:15px;
}

.import-example > strong {
    display:block;
    margin-bottom:9px;
    color:#334155;
    font-size:13px;
}

.import-example-table {
    width:100%;
    overflow-x:auto;
    border:1px solid #e2e8f0;
    border-radius:9px;
}

.import-example table {
    width:100%;
    border-collapse:collapse;
    font-size:11px;
    text-align:left;
}

.import-example th {
    background:#f1f5f9;
    color:#334155;
    font-weight:700;
}

.import-example th,
.import-example td {
    padding:10px;
    border-bottom:1px solid #e2e8f0;
    white-space:nowrap;
}

.import-example tr:last-child td {
    border-bottom:0;
}

.import-error-message {
    margin-top:10px;
    color:#dc2626;
    font-size:12px;
}

.import-modal-actions {
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:20px;
    padding-top:17px;
    border-top:1px solid #edf2f7;
}

/* ANGKATAN */
.angkatan-grid {
    display:grid;
    grid-template-columns:repeat(3, minmax(0, 1fr));
    gap:16px;
}

.angkatan-card {
    display:flex;
    align-items:center;
    gap:14px;
    min-width:0;
    padding:18px;
    border:1px solid #e5e7eb;
    border-radius:14px;
    background:#fff;
    color:#0f172a;
    text-decoration:none;
    transition:.2s;
}

.angkatan-card:hover {
    border-color:#2563eb;
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(15,23,42,.08);
}

.angkatan-icon {
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

.angkatan-content {
    flex:1;
    min-width:0;
}

.angkatan-label {
    display:block;
    color:#94a3b8;
    font-size:11px;
    margin-bottom:2px;
}

.angkatan-content strong {
    display:block;
    color:#173b67;
    font-size:20px;
    font-weight:700;
}

.angkatan-content small {
    display:block;
    margin-top:3px;
    color:#64748b;
    font-size:12px;
}

.angkatan-arrow {
    color:#94a3b8;
    font-size:13px;
}

/* EMPTY */
.empty-state {
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    text-align:center;
    padding:50px 20px;
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

/* INFO CARD */
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

.info-card-icon {
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

/* RESPONSIVE */
@media(max-width:900px) {

    .stats-grid {
        grid-template-columns:repeat(2, minmax(0, 1fr));
    }

    .form-grid {
        grid-template-columns:1fr 1fr;
    }

    .form-grid .form-group:first-child {
        grid-column:1 / -1;
    }

    .angkatan-grid {
        grid-template-columns:repeat(2, minmax(0, 1fr));
    }

}

@media(max-width:650px) {

    .page-toolbar {
        flex-direction:column;
        align-items:stretch;
    }

    .toolbar-actions {
        width:100%;
        flex-direction:column;
    }

    .toolbar-actions .btn {
        width:100%;
    }

    .stats-grid {
        grid-template-columns:1fr;
    }

    .form-grid {
        grid-template-columns:1fr;
    }

    .form-grid .form-group:first-child {
        grid-column:auto;
    }

    .angkatan-grid {
        grid-template-columns:1fr;
    }

    .card-header {
        align-items:flex-start;
    }

    .form-actions {
        flex-direction:column-reverse;
    }

    .form-actions .btn {
        width:100%;
    }

    .import-modal {
        padding:10px;
    }

    .import-modal-content {
        max-height:95vh;
    }

    .import-modal-header,
    .import-modal-body {
        padding:16px;
    }

    .import-modal-actions {
        flex-direction:column-reverse;
    }

    .import-modal-actions .btn {
        width:100%;
    }

}

</style>

@endpush

{{-- =========================================================
     JAVASCRIPT
========================================================= --}}
@push('scripts')

<script>

function bukaFormKelas()
{
    const form = document.getElementById('formKelas');

    if (!form) return;

    form.style.display = 'block';

    form.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

function tutupFormKelas()
{
    const form = document.getElementById('formKelas');

    if (!form) return;

    form.style.display = 'none';
}

/* MODAL IMPORT */

function bukaModalImportKelas()
{
    const modal = document.getElementById('modalImportKelas');

    if (!modal) return;

    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function tutupModalImportKelas()
{
    const modal = document.getElementById('modalImportKelas');

    if (!modal) return;

    modal.classList.remove('show');
    document.body.style.overflow = '';
}

/* NAMA FILE */

document.addEventListener('DOMContentLoaded', function () {

    const inputFile = document.getElementById('fileImportKelas');
    const namaFile = document.getElementById('namaFileImportKelas');

    if (inputFile && namaFile) {

        inputFile.addEventListener('change', function () {

            if (this.files.length > 0) {
                namaFile.textContent = this.files[0].name;
            } else {
                namaFile.textContent = 'Belum ada file yang dipilih';
            }

        });

    }

});

/* TUTUP MODAL KETIKA KLIK AREA LUAR */

document.addEventListener('click', function (event) {

    const modal = document.getElementById('modalImportKelas');

    if (modal && event.target === modal) {
        tutupModalImportKelas();
    }

});

/* TUTUP MODAL DENGAN ESCAPE */

document.addEventListener('keydown', function (event) {

    if (event.key === 'Escape') {
        tutupModalImportKelas();
    }

});

/* BUKA FORM TAMBAH KELAS JIKA VALIDASI GAGAL */

@if($errors->any())
document.addEventListener('DOMContentLoaded', function () {
    bukaFormKelas();
});
@endif

</script>

@endpush