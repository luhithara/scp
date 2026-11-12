
{{-- =========================================================
     SCP - DATA SISWA WAKA
     File: resources/views/waka/siswa.blade.php
========================================================= --}}

@extends('layouts.app')

@section('title', 'Data Siswa - SCP')
@section('page-title', 'Data Siswa')

@section('content')

@php
    $jumlahSiswa = $jumlahSiswa ?? 0;
    $dataSiswa = $dataSiswa ?? collect();
    $daftarKelas = $daftarKelas ?? collect();
    $search = $search ?? request('search', '');

    $kelompokSiswa = collect($dataSiswa)
        ->groupBy(function ($siswa) {
            return $siswa->kelasid ?? 'tanpa-kelas';
        })
        ->sortBy(function ($siswaDalamKelas) {
            return $siswaDalamKelas->first()->namakelas ?? 'ZZZ';
        });
@endphp

{{-- HEADER --}}
<div class="siswa-header">
    <div>
        <h1>Data Siswa</h1>
        <p>Kelola data siswa dan akun login yang terdaftar di sekolah.</p>
    </div>
</div>

{{-- NOTIFIKASI --}}
@if(session('success'))
    <div class="siswa-alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="siswa-alert alert-danger">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if(session('warning'))
    <div class="siswa-alert alert-warning">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span>{{ session('warning') }}</span>
    </div>
@endif

@if(session('import_errors'))
    <div class="siswa-alert alert-danger">
        <div>
            <strong>Detail data yang gagal diimport:</strong>
            <ul>
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="siswa-alert alert-danger">
        <i class="fa-solid fa-circle-exclamation"></i>
        <div>
            <strong>Data belum bisa disimpan.</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- =========================================================
     STATISTIK
========================================================= --}}
<div class="siswa-stats">

    <div class="siswa-stat-card">
        <div>
            <span class="stat-label">Jumlah Siswa</span>
            <strong>{{ number_format($jumlahSiswa) }}</strong>
            <small>Total siswa terdaftar</small>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>

    <div class="siswa-stat-card">
        <div>
            <span class="stat-label">Jumlah Kelas</span>
            <strong>{{ $daftarKelas->count() }}</strong>
            <small>Kelas tersedia</small>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-school"></i>
        </div>
    </div>

    <div class="siswa-stat-card">
        <div>
            <span class="stat-label">Data Ditampilkan</span>
            <strong>{{ collect($dataSiswa)->count() }}</strong>
            <small>Hasil pencarian saat ini</small>
        </div>
        <div class="stat-icon">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>
    </div>

</div>

{{-- =========================================================
     TOOLBAR
========================================================= --}}
<div class="siswa-toolbar">

    <div>
        <h3>Kelola Data Siswa</h3>
        <p>Tambahkan siswa, import data, atau cari siswa yang terdaftar.</p>
    </div>

    <div class="siswa-toolbar-actions">

        <button
            type="button"
            class="btn-import-siswa"
            onclick="bukaModalImportSiswa()"
        >
            <i class="fa-solid fa-file-import"></i>
            Import Data
        </button>

        <button
            type="button"
            class="btn-primary-siswa"
            onclick="bukaModalSiswa()"
        >
            <i class="fa-solid fa-plus"></i>
            Form Tambah Siswa
        </button>

    </div>

</div>

{{-- =========================================================
     MODAL IMPORT DATA SISWA
========================================================= --}}
<div id="modalImportSiswa" class="import-modal-siswa">

    <div class="import-modal-siswa-content">

        <div class="import-modal-siswa-header">

            <div class="import-modal-siswa-title">

                <div class="import-modal-siswa-icon">
                    <i class="fa-solid fa-file-import"></i>
                </div>

                <div>
                    <h3>Import Data Siswa</h3>
                    <p>Import daftar siswa dari file Excel secara otomatis.</p>
                </div>

            </div>

            <button
                type="button"
                class="import-close-siswa"
                onclick="tutupModalImportSiswa()"
                aria-label="Tutup"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>

        <div class="import-modal-siswa-body">

            <form
                action="{{ route('waka.data.import', ['jenis' => 'siswa']) }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="import-upload-siswa">

                    <div class="import-upload-siswa-icon">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>

                    <h4>Pilih File Data Siswa</h4>
                    <p>Pilih file Excel dari perangkatmu.</p>

                    <label for="fileImportSiswa" class="import-select-siswa">
                        <i class="fa-solid fa-folder-open"></i>
                        Pilih File
                    </label>

                    <input
                        type="file"
                        id="fileImportSiswa"
                        name="file"
                        accept=".xlsx,.xls,.csv"
                        required
                        hidden
                    >

                    <div id="namaFileImportSiswa" class="import-file-siswa-name">
                        Belum ada file yang dipilih
                    </div>

                </div>

                @error('file')
                    <div class="import-file-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </div>
                @enderror

                <div class="import-info-siswa">

                    <div class="import-info-siswa-title">
                        <i class="fa-solid fa-circle-info"></i>
                        Ketentuan Import Data
                    </div>

                    <ul>
                        <li>Format file: <b>.xlsx, .xls, atau .csv</b>.</li>
                        <li>Ukuran maksimal: <b>20 MB</b>.</li>
                        <li>File Excel dapat berisi beberapa sheet kelas.</li>
                        <li>Data Excel menggunakan kolom NIS, NAMA SISWA, dan L / P.</li>
                        <li>Nama kelas dibaca dari keterangan KELAS pada sheet.</li>
                        <li>NIS yang sudah terdaftar tidak akan dibuat ulang.</li>
                        <li>Akun login siswa dibuat otomatis menggunakan NIS.</li>
                    </ul>

                </div>

                <div class="import-example-siswa">

                    <strong>Format file Excel yang didukung</strong>

                    <div class="import-example-siswa-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>NIS</th>
                                    <th>NAMA SISWA</th>
                                    <th>L / P</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>17770</td>
                                    <td>Nama Siswa</td>
                                    <td>P</td>
                                </tr>
                                <tr>
                                    <td>17771</td>
                                    <td>Nama Siswa 2</td>
                                    <td>L</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p>
                        Sistem membaca nama kelas dari masing-masing bagian
                        data Excel sehingga siswa dapat dikelompokkan
                        berdasarkan kelasnya.
                    </p>

                </div>

                <div class="import-actions-siswa">

                    <button
                        type="button"
                        class="btn-secondary-siswa"
                        onclick="tutupModalImportSiswa()"
                    >
                        Batal
                    </button>

                    <button type="submit" class="btn-import-siswa">
                        <i class="fa-solid fa-upload"></i>
                        Import Sekarang
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- =========================================================
     MODAL TAMBAH SISWA
========================================================= --}}
<div id="modalTambahSiswa" class="modal-siswa">

    <div class="modal-siswa-content">

        <div class="modal-siswa-header">

            <div>
                <h3>
                    <i class="fa-solid fa-user-plus"></i>
                    Tambah Data Siswa
                </h3>

                <p>Lengkapi informasi siswa dan akun login.</p>
            </div>

            <button
                type="button"
                class="btn-close-modal"
                onclick="tutupModalSiswa()"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>

        <div class="modal-siswa-body">

            <form
                action="{{ route('waka.siswa.simpan') }}"
                method="POST"
            >

                @csrf

                <div class="form-siswa-grid">

                    <div class="form-siswa-group">
                        <label for="nis">NIS <span>*</span></label>
                        <input
                            type="text"
                            id="nis"
                            name="nis"
                            value="{{ old('nis') }}"
                            placeholder="Masukkan NIS siswa"
                            maxlength="30"
                            required
                        >
                        @error('nis')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-siswa-group">
                        <label for="nama">Nama Lengkap <span>*</span></label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            value="{{ old('nama') }}"
                            placeholder="Masukkan nama siswa"
                            maxlength="100"
                            required
                        >
                        @error('nama')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-siswa-group">
                        <label for="jeniskelamin">Jenis Kelamin <span>*</span></label>
                        <select id="jeniskelamin" name="jeniskelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jeniskelamin') == 'L' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="P" {{ old('jeniskelamin') == 'P' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                        @error('jeniskelamin')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-siswa-group">
                        <label for="kelasid">Kelas <span>*</span></label>
                        <select id="kelasid" name="kelasid" required>
                            <option value="">-- Pilih Kelas --</option>

                            @foreach($daftarKelas as $kelas)
                                <option
                                    value="{{ $kelas->id }}"
                                    {{ old('kelasid') == $kelas->id ? 'selected' : '' }}
                                >
                                    {{ $kelas->namakelas }}
                                    ({{ $kelas->namajurusan ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('kelasid')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-siswa-group">
                        <label for="tempatlahir">Tempat Lahir</label>
                        <input
                            type="text"
                            id="tempatlahir"
                            name="tempatlahir"
                            value="{{ old('tempatlahir') }}"
                            placeholder="Tempat lahir"
                            maxlength="100"
                        >
                    </div>

                    <div class="form-siswa-group">
                        <label for="tanggallahir">Tanggal Lahir</label>
                        <input
                            type="date"
                            id="tanggallahir"
                            name="tanggallahir"
                            value="{{ old('tanggallahir') }}"
                        >
                    </div>

                    <div class="form-siswa-group">
                        <label for="nomortelepon">Nomor Telepon</label>
                        <input
                            type="text"
                            id="nomortelepon"
                            name="nomortelepon"
                            value="{{ old('nomortelepon') }}"
                            placeholder="08xxxxxxxxxx"
                            maxlength="20"
                        >
                    </div>

                    <div class="form-siswa-group">
                        <label for="email">Email Login (Opsional)</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Email siswa"
                        >
                        <small>Jika kosong, email login dibuat otomatis oleh sistem.</small>
                        @error('email')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-siswa-group">
                        <label for="password">Password Login (Opsional)</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Kosongkan untuk memakai NIS"
                        >
                        <small>Jika kosong, password awal menggunakan NIS.</small>
                        @error('password')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-siswa-group full-width">
                        <label for="alamat">Alamat</label>
                        <textarea
                            id="alamat"
                            name="alamat"
                            rows="3"
                            placeholder="Masukkan alamat siswa"
                        >{{ old('alamat') }}</textarea>
                    </div>

                </div>

                <div class="modal-form-info">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>
                        Akun login siswa akan dibuat otomatis setelah data berhasil
                        disimpan. Identitas login siswa menggunakan NIS.
                    </span>
                </div>

                <div class="modal-siswa-actions">

                    <button
                        type="button"
                        class="btn-secondary-siswa"
                        onclick="tutupModalSiswa()"
                    >
                        Batal
                    </button>

                    <button type="submit" class="btn-primary-siswa">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Data Siswa
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- =========================================================
     DAFTAR SISWA PER KELAS
     SETIAP KELAS BERUPA BUTTON
========================================================= --}}
<div class="siswa-card">

    <div class="siswa-card-header siswa-table-header">

        <div>
            <h3>
                <i class="fa-solid fa-users"></i>
                Daftar Siswa per Kelas
            </h3>
            <p>Klik tombol kelas untuk melihat daftar siswanya.</p>
        </div>

        <form
            action="{{ route('waka.siswa') }}"
            method="GET"
            class="search-siswa-form"
        >

            <div class="search-siswa-input">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama, NIS, kelas..."
                >
            </div>

            <button type="submit" class="btn-search-siswa">
                Cari
            </button>

            @if($search !== '')
                <a href="{{ route('waka.siswa') }}" class="btn-reset-siswa">
                    Reset
                </a>
            @endif

        </form>

    </div>

    <div class="siswa-per-kelas">

        @forelse($kelompokSiswa as $kelasid => $siswaDalamKelas)

            @php
                $kelasPertama = $siswaDalamKelas->first();
                $namaKelas = $kelasPertama->namakelas ?? 'Belum Ada Kelas';
                $namaJurusan = $kelasPertama->namajurusan ?? '-';
                $panelId = 'panel-kelas-' . $loop->index;
            @endphp

            <div class="kelas-siswa-card">

                {{-- TOMBOL KELAS --}}
                <button
                    type="button"
                    class="kelas-siswa-button"
                    aria-expanded="false"
                    aria-controls="{{ $panelId }}"
                    onclick="toggleSiswaKelas(this, '{{ $panelId }}')"
                >

                    <div class="kelas-siswa-identitas">

                        <div class="kelas-siswa-icon">
                            <i class="fa-solid fa-school"></i>
                        </div>

                        <div class="kelas-siswa-info">
                            <h4>{{ $namaKelas }}</h4>
                            <span>Jurusan {{ $namaJurusan }}</span>
                        </div>

                    </div>

                    <div class="kelas-siswa-kanan">

                        <div class="kelas-siswa-jumlah">
                            <strong>{{ $siswaDalamKelas->count() }}</strong>
                            <span>Siswa</span>
                        </div>

                        <div class="kelas-siswa-chevron">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>

                    </div>

                </button>

                {{-- TABEL SISWA: AWALNYA TERTUTUP --}}
                <div
                    id="{{ $panelId }}"
                    class="kelas-siswa-panel"
                    hidden
                >

                    <div class="siswa-table-wrapper">

                        <table class="siswa-table">

                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($siswaDalamKelas as $index => $siswa)

                                    <tr>

                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            <span class="nisn-badge">
                                                {{ $siswa->nis ?? '-' }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="nama-siswa-cell">
                                                <div class="avatar-siswa">
                                                    {{ strtoupper(substr($siswa->nama ?? 'S', 0, 1)) }}
                                                </div>
                                                <strong>{{ $siswa->nama }}</strong>
                                            </div>
                                        </td>

                                        <td>
                                            @if($siswa->jeniskelamin == 'L')
                                                Laki-laki
                                            @elseif($siswa->jeniskelamin == 'P')
                                                Perempuan
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            <div class="aksi-siswa">

                                                <a
                                                    href="{{ route('waka.siswa.edit', $siswa->id) }}"
                                                    class="btn-aksi edit"
                                                    title="Edit siswa"
                                                >
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>

                                                <form
                                                    action="{{ route('waka.siswa.hapus', $siswa->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus data siswa ini?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn-aksi delete"
                                                        title="Hapus siswa"
                                                    >
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        @empty

            <div class="empty-siswa">

                <div class="empty-siswa-icon">
                    <i class="fa-solid fa-users"></i>
                </div>

                <strong>
                    {{ $search ? 'Data Tidak Ditemukan' : 'Belum Ada Data Siswa' }}
                </strong>

                <p>
                    {{ $search
                        ? 'Tidak ada siswa yang sesuai dengan pencarian.'
                        : 'Silakan tambahkan atau import data siswa terlebih dahulu.' }}
                </p>

            </div>

        @endforelse

    </div>

    <div class="siswa-table-footer">
        Menampilkan {{ collect($dataSiswa)->count() }} dari
        {{ $jumlahSiswa }} siswa dalam
        {{ $kelompokSiswa->count() }} kelompok kelas.
    </div>

</div>

@endsection

{{-- =========================================================
     CSS
========================================================= --}}
@push('styles')

<style>

/* HEADER */

.siswa-header {
    margin-bottom:22px;
}

.siswa-header h1 {
    margin:0;
    color:#173b67;
    font-size:28px;
    font-weight:700;
}

.siswa-header p {
    margin:6px 0 0;
    color:#718096;
    font-size:14px;
}

/* ALERT */

.siswa-alert {
    display:flex;
    align-items:flex-start;
    gap:10px;
    padding:13px 16px;
    border-radius:11px;
    margin-bottom:18px;
    font-size:13px;
}

.siswa-alert ul {
    margin:7px 0 0 18px;
    padding:0;
}

.alert-success {
    color:#047857;
    background:#ecfdf5;
    border:1px solid #a7f3d0;
}

.alert-danger {
    color:#b91c1c;
    background:#fef2f2;
    border:1px solid #fecaca;
}

.alert-warning {
    color:#92400e;
    background:#fffbeb;
    border:1px solid #fde68a;
}

/* STATISTIK */

.siswa-stats {
    display:grid;
    grid-template-columns:repeat(3, minmax(0, 1fr));
    gap:16px;
    margin-bottom:24px;
}

.siswa-stat-card {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding:19px;
    background:#fff;
    border:1px solid #e5eaf1;
    border-radius:15px;
    box-shadow:0 4px 15px rgba(15,23,42,.035);
}

.stat-label {
    display:block;
    margin-bottom:7px;
    color:#64748b;
    font-size:13px;
}

.siswa-stat-card strong {
    display:block;
    color:#0f172a;
    font-size:27px;
}

.siswa-stat-card small {
    display:block;
    margin-top:5px;
    color:#94a3b8;
    font-size:11px;
}

.stat-icon {
    display:flex;
    align-items:center;
    justify-content:center;
    width:47px;
    height:47px;
    flex-shrink:0;
    border-radius:12px;
    background:#eff6ff;
    color:#2563eb;
    font-size:19px;
}

/* TOOLBAR */

.siswa-toolbar {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:15px;
    margin-bottom:18px;
}

.siswa-toolbar h3 {
    margin:0;
    color:#173b67;
    font-size:18px;
}

.siswa-toolbar p {
    margin:5px 0 0;
    color:#718096;
    font-size:13px;
}

.siswa-toolbar-actions {
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
}

.btn-primary-siswa,
.btn-import-siswa,
.btn-secondary-siswa,
.btn-search-siswa,
.btn-reset-siswa {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    min-height:40px;
    padding:10px 15px;
    border:none;
    border-radius:9px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    cursor:pointer;
    transition:.2s;
}

.btn-primary-siswa {
    background:#2563eb;
    color:#fff;
    box-shadow:0 4px 10px rgba(37,99,235,.12);
}

.btn-primary-siswa:hover {
    background:#1d4ed8;
    color:#fff;
}

.btn-import-siswa {
    background:#059669;
    color:#fff;
    box-shadow:0 4px 10px rgba(5,150,105,.12);
}

.btn-import-siswa:hover {
    background:#047857;
    color:#fff;
}

.btn-secondary-siswa {
    background:#eef2f7;
    color:#475569;
}

.btn-secondary-siswa:hover {
    background:#e2e8f0;
}

/* CARD UTAMA */

.siswa-card {
    margin-bottom:20px;
    overflow:hidden;
    background:#fff;
    border:1px solid #e5eaf1;
    border-radius:15px;
    box-shadow:0 4px 15px rgba(15,23,42,.035);
}

.siswa-card-header {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:15px;
    padding:19px 21px;
    border-bottom:1px solid #edf2f7;
}

.siswa-card-header h3 {
    margin:0;
    color:#173b67;
    font-size:16px;
}

.siswa-card-header h3 i {
    margin-right:7px;
    color:#2563eb;
}

.siswa-card-header p {
    margin:5px 0 0;
    color:#718096;
    font-size:12px;
}

/* PENCARIAN */

.search-siswa-form {
    display:flex;
    align-items:center;
    gap:8px;
}

.search-siswa-input {
    position:relative;
    width:245px;
}

.search-siswa-input i {
    position:absolute;
    top:50%;
    left:12px;
    transform:translateY(-50%);
    color:#94a3b8;
    font-size:12px;
}

.search-siswa-input input {
    width:100%;
    height:39px;
    padding:0 12px 0 34px;
    border:1px solid #dbe4f0;
    border-radius:9px;
    outline:none;
    box-sizing:border-box;
    font-size:12px;
}

.search-siswa-input input:focus {
    border-color:#2563eb;
}

.btn-search-siswa {
    background:#2563eb;
    color:#fff;
}

.btn-reset-siswa {
    background:#f1f5f9;
    color:#475569;
}

/* =========================================================
   TOMBOL KELAS
========================================================= */

.siswa-per-kelas {
    display:flex;
    flex-direction:column;
    gap:12px;
    padding:20px;
}

.kelas-siswa-card {
    overflow:hidden;
    border:1px solid #e2e8f0;
    border-radius:13px;
    background:#fff;
    transition:border-color .2s, box-shadow .2s;
}

.kelas-siswa-card:hover {
    border-color:#bfdbfe;
    box-shadow:0 3px 12px rgba(37,99,235,.06);
}

/* Tombol kelas */

.kelas-siswa-button {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:15px;
    width:100%;
    padding:16px 19px;
    border:0;
    background:#f8fbff;
    text-align:left;
    cursor:pointer;
    transition:background .2s;
}

.kelas-siswa-button:hover {
    background:#eff6ff;
}

.kelas-siswa-button[aria-expanded="true"] {
    background:#eff6ff;
    border-bottom:1px solid #dbeafe;
}

.kelas-siswa-identitas {
    display:flex;
    align-items:center;
    gap:12px;
    min-width:0;
}

.kelas-siswa-icon {
    display:flex;
    align-items:center;
    justify-content:center;
    width:43px;
    height:43px;
    flex-shrink:0;
    border-radius:11px;
    background:#eaf3ff;
    color:#2563eb;
    font-size:17px;
}

.kelas-siswa-info {
    min-width:0;
}

.kelas-siswa-info h4 {
    margin:0;
    color:#173b67;
    font-size:15px;
    font-weight:700;
}

.kelas-siswa-info span {
    display:block;
    margin-top:4px;
    color:#718096;
    font-size:11px;
}

.kelas-siswa-kanan {
    display:flex;
    align-items:center;
    gap:13px;
    flex-shrink:0;
}

.kelas-siswa-jumlah {
    display:flex;
    align-items:center;
    gap:7px;
    padding:7px 12px;
    border-radius:8px;
    background:#eff6ff;
    color:#1d4ed8;
}

.kelas-siswa-jumlah strong {
    font-size:15px;
    font-weight:700;
}

.kelas-siswa-jumlah span {
    font-size:11px;
    font-weight:600;
}

.kelas-siswa-chevron {
    display:flex;
    align-items:center;
    justify-content:center;
    width:32px;
    height:32px;
    border-radius:8px;
    background:#fff;
    color:#64748b;
    transition:transform .2s, background .2s;
}

.kelas-siswa-button[aria-expanded="true"] .kelas-siswa-chevron {
    transform:rotate(180deg);
    background:#dbeafe;
    color:#1d4ed8;
}

/* Panel tabel */

.kelas-siswa-panel {
    width:100%;
    background:#fff;
}

.kelas-siswa-panel[hidden] {
    display:none !important;
}

.kelas-siswa-panel:not([hidden]) {
    display:block;
    animation:panelKelasMasuk .2s ease;
}

@keyframes panelKelasMasuk {
    from {
        opacity:0;
        transform:translateY(-5px);
    }

    to {
        opacity:1;
        transform:translateY(0);
    }
}

/* TABEL */

.siswa-table-wrapper {
    width:100%;
    overflow-x:auto;
}

.siswa-table {
    width:100%;
    min-width:650px;
    border-collapse:collapse;
}

.siswa-table thead {
    background:#f8fafc;
}

.siswa-table th {
    padding:13px 15px;
    color:#64748b;
    text-align:left;
    white-space:nowrap;
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
}

.siswa-table td {
    padding:13px 15px;
    border-top:1px solid #edf2f7;
    color:#475569;
    font-size:12px;
    vertical-align:middle;
}

.siswa-table tbody tr:hover {
    background:#fafcff;
}

.nisn-badge {
    display:inline-block;
    padding:5px 8px;
    border-radius:6px;
    background:#eff6ff;
    color:#1d4ed8;
    font-weight:600;
}

.nama-siswa-cell {
    display:flex;
    align-items:center;
    gap:9px;
    min-width:160px;
}

.nama-siswa-cell strong {
    color:#334155;
    font-size:12px;
}

.avatar-siswa {
    display:flex;
    align-items:center;
    justify-content:center;
    width:31px;
    height:31px;
    flex-shrink:0;
    border-radius:9px;
    background:#eaf3ff;
    color:#2563eb;
    font-size:12px;
    font-weight:700;
}

/* AKSI */

.aksi-siswa {
    display:flex;
    align-items:center;
    gap:7px;
}

.aksi-siswa form {
    margin:0;
}

.btn-aksi {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:31px;
    height:31px;
    border:none;
    border-radius:7px;
    cursor:pointer;
    text-decoration:none;
    font-size:11px;
}

.btn-aksi.edit {
    background:#eff6ff;
    color:#2563eb;
}

.btn-aksi.delete {
    background:#fef2f2;
    color:#dc2626;
}

.btn-aksi:hover {
    filter:brightness(.95);
}

/* DATA KOSONG */

.empty-siswa {
    padding:45px 15px;
    text-align:center;
}

.empty-siswa-icon {
    display:flex;
    align-items:center;
    justify-content:center;
    width:55px;
    height:55px;
    margin:0 auto 13px;
    border-radius:14px;
    background:#f1f5f9;
    color:#94a3b8;
    font-size:21px;
}

.empty-siswa strong {
    color:#334155;
    font-size:14px;
}

.empty-siswa p {
    margin:6px 0 0;
    color:#94a3b8;
    font-size:12px;
}

.siswa-table-footer {
    padding:13px 18px;
    border-top:1px solid #edf2f7;
    color:#94a3b8;
    font-size:11px;
}

/* =========================================================
   MODAL TAMBAH SISWA
========================================================= */

.modal-siswa {
    display:none;
    position:fixed;
    inset:0;
    z-index:2000;
    align-items:center;
    justify-content:center;
    padding:20px;
    background:rgba(15,23,42,.55);
    backdrop-filter:blur(3px);
}

.modal-siswa.aktif {
    display:flex;
}

.modal-siswa-content {
    width:100%;
    max-width:760px;
    max-height:90vh;
    overflow-y:auto;
    background:#fff;
    border-radius:16px;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
    animation:modalMasuk .2s ease;
}

@keyframes modalMasuk {
    from {
        opacity:0;
        transform:translateY(12px);
    }
    to {
        opacity:1;
        transform:translateY(0);
    }
}

.modal-siswa-header {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:15px;
    padding:19px 23px;
    border-bottom:1px solid #edf2f7;
}

.modal-siswa-header h3 {
    margin:0;
    color:#173b67;
    font-size:18px;
}

.modal-siswa-header h3 i {
    margin-right:7px;
    color:#2563eb;
}

.modal-siswa-header p {
    margin:5px 0 0;
    color:#718096;
    font-size:12px;
}

.btn-close-modal {
    display:flex;
    align-items:center;
    justify-content:center;
    width:35px;
    height:35px;
    border:none;
    border-radius:9px;
    background:#f1f5f9;
    color:#475569;
    cursor:pointer;
    font-size:16px;
}

.btn-close-modal:hover {
    background:#fee2e2;
    color:#dc2626;
}

.modal-siswa-body {
    padding:22px 23px;
}

.form-siswa-grid {
    display:grid;
    grid-template-columns:repeat(2, minmax(0, 1fr));
    gap:15px;
}

.form-siswa-group {
    min-width:0;
}

.form-siswa-group.full-width {
    grid-column:1 / -1;
}

.form-siswa-group label {
    display:block;
    margin-bottom:7px;
    color:#334155;
    font-size:12px;
    font-weight:600;
}

.form-siswa-group label span {
    color:#dc2626;
}

.form-siswa-group input,
.form-siswa-group select,
.form-siswa-group textarea {
    width:100%;
    min-height:40px;
    padding:10px 12px;
    border:1px solid #dbe4f0;
    border-radius:8px;
    outline:none;
    background:#fff;
    color:#334155;
    box-sizing:border-box;
    font-family:inherit;
    font-size:12px;
}

.form-siswa-group textarea {
    resize:vertical;
}

.form-siswa-group input:focus,
.form-siswa-group select:focus,
.form-siswa-group textarea:focus {
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.08);
}

.form-siswa-group input::placeholder,
.form-siswa-group textarea::placeholder {
    color:#94a3b8;
}

.form-siswa-group > small {
    display:block;
    margin-top:5px;
    color:#94a3b8;
    font-size:10px;
}

.form-siswa-group .field-error {
    color:#dc2626;
    font-size:11px;
}

.modal-form-info {
    display:flex;
    align-items:flex-start;
    gap:9px;
    margin-top:17px;
    padding:12px;
    border:1px solid #dbeafe;
    border-radius:9px;
    background:#f8fbff;
    color:#475569;
    font-size:11px;
    line-height:1.5;
}

.modal-form-info i {
    color:#2563eb;
    margin-top:1px;
}

.modal-siswa-actions {
    display:flex;
    justify-content:flex-end;
    gap:9px;
    margin-top:20px;
    padding-top:17px;
    border-top:1px solid #edf2f7;
}

/* =========================================================
   MODAL IMPORT SISWA
========================================================= */

.import-modal-siswa {
    display:none;
    position:fixed;
    inset:0;
    z-index:2100;
    align-items:center;
    justify-content:center;
    padding:20px;
    background:rgba(15,23,42,.55);
    backdrop-filter:blur(3px);
    overflow-y:auto;
}

.import-modal-siswa.show {
    display:flex;
}

.import-modal-siswa-content {
    width:100%;
    max-width:620px;
    max-height:90vh;
    overflow-y:auto;
    background:#fff;
    border-radius:18px;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
    animation:modalMasuk .2s ease;
}

.import-modal-siswa-header {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:15px;
    padding:20px 22px;
    border-bottom:1px solid #edf2f7;
}

.import-modal-siswa-title {
    display:flex;
    align-items:center;
    gap:13px;
}

.import-modal-siswa-icon {
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

.import-modal-siswa-title h3 {
    margin:0;
    color:#173b67;
    font-size:18px;
}

.import-modal-siswa-title p {
    margin:5px 0 0;
    color:#718096;
    font-size:12px;
    line-height:1.5;
}

.import-close-siswa {
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

.import-close-siswa:hover {
    background:#fee2e2;
    color:#dc2626;
}

.import-modal-siswa-body {
    padding:22px;
}

.import-upload-siswa {
    display:flex;
    flex-direction:column;
    align-items:center;
    text-align:center;
    padding:25px 15px;
    border:2px dashed #a7f3d0;
    border-radius:14px;
    background:#f0fdf4;
}

.import-upload-siswa-icon {
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

.import-upload-siswa h4 {
    margin:0;
    color:#173b67;
    font-size:16px;
}

.import-upload-siswa p {
    margin:6px 0 15px;
    color:#718096;
    font-size:12px;
}

.import-select-siswa {
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

.import-select-siswa:hover {
    background:#047857;
}

.import-file-siswa-name {
    margin-top:12px;
    color:#64748b;
    font-size:12px;
    max-width:100%;
    overflow-wrap:anywhere;
}

.import-file-error {
    margin-top:10px;
    color:#dc2626;
    font-size:12px;
}

.import-info-siswa {
    margin-top:17px;
    padding:15px;
    background:#eff6ff;
    border:1px solid #dbeafe;
    border-radius:11px;
}

.import-info-siswa-title {
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom:9px;
    color:#1e40af;
    font-size:13px;
    font-weight:700;
}

.import-info-siswa ul {
    margin:0;
    padding-left:22px;
    color:#475569;
    font-size:12px;
    line-height:1.8;
}

.import-info-siswa li {
    padding-left:3px;
}

.import-example-siswa {
    margin-top:15px;
}

.import-example-siswa > strong {
    display:block;
    margin-bottom:9px;
    color:#334155;
    font-size:13px;
}

.import-example-siswa-table {
    width:100%;
    overflow-x:auto;
    border:1px solid #e2e8f0;
    border-radius:9px;
}

.import-example-siswa table {
    width:100%;
    border-collapse:collapse;
    font-size:11px;
    text-align:left;
}

.import-example-siswa th {
    background:#f1f5f9;
    color:#334155;
    font-weight:700;
}

.import-example-siswa th,
.import-example-siswa td {
    padding:10px;
    border-bottom:1px solid #e2e8f0;
    white-space:nowrap;
}

.import-example-siswa tr:last-child td {
    border-bottom:0;
}

.import-example-siswa > p {
    margin:9px 0 0;
    color:#64748b;
    font-size:11px;
    line-height:1.6;
}

.import-actions-siswa {
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:20px;
    padding-top:17px;
    border-top:1px solid #edf2f7;
}

/* RESPONSIVE */

@media(max-width:900px) {

    .siswa-stats {
        grid-template-columns:repeat(2, minmax(0, 1fr));
    }

    .siswa-card-header.siswa-table-header {
        align-items:flex-start;
        flex-direction:column;
    }

    .search-siswa-form {
        width:100%;
    }

    .search-siswa-input {
        flex:1;
        width:auto;
    }

}

@media(max-width:600px) {

    .siswa-stats {
        grid-template-columns:1fr;
    }

    .siswa-toolbar {
        align-items:stretch;
        flex-direction:column;
    }

    .siswa-toolbar-actions {
        width:100%;
        flex-direction:column;
    }

    .siswa-toolbar-actions button {
        width:100%;
    }

    .form-siswa-grid {
        grid-template-columns:1fr;
    }

    .form-siswa-group.full-width {
        grid-column:auto;
    }

    .modal-siswa {
        padding:10px;
    }

    .modal-siswa-content {
        max-height:95vh;
    }

    .modal-siswa-body {
        padding:16px;
    }

    .modal-siswa-actions {
        flex-direction:column-reverse;
    }

    .modal-siswa-actions button {
        width:100%;
    }

    .search-siswa-form {
        flex-wrap:wrap;
    }

    .import-modal-siswa {
        padding:10px;
    }

    .import-modal-siswa-content {
        max-height:95vh;
    }

    .import-modal-siswa-header,
    .import-modal-siswa-body {
        padding:16px;
    }

    .import-actions-siswa {
        flex-direction:column-reverse;
    }

    .import-actions-siswa button {
        width:100%;
    }

    .siswa-per-kelas {
        padding:12px;
        gap:10px;
    }

    .kelas-siswa-button {
        padding:13px;
    }

    .kelas-siswa-info h4 {
        font-size:13px;
    }

    .kelas-siswa-kanan {
        gap:7px;
    }

    .kelas-siswa-jumlah {
        padding:6px 8px;
    }

    .kelas-siswa-jumlah strong {
        font-size:13px;
    }

    .kelas-siswa-jumlah span {
        font-size:10px;
    }

    .kelas-siswa-chevron {
        width:28px;
        height:28px;
    }

}

</style>

@endpush

{{-- =========================================================
     JAVASCRIPT
========================================================= --}}
@push('scripts')

<script>

/* =========================================================
   BUKA / TUTUP DAFTAR SISWA PER KELAS
========================================================= */

function toggleSiswaKelas(button, panelId)
{
    const panel = document.getElementById(panelId);

    if (!panel || !button) {
        return;
    }

    const sedangTerbuka = button.getAttribute('aria-expanded') === 'true';

    button.setAttribute('aria-expanded', sedangTerbuka ? 'false' : 'true');

    panel.hidden = sedangTerbuka;

}

/* =========================================================
   MODAL TAMBAH SISWA
========================================================= */

function bukaModalSiswa()
{
    const modal = document.getElementById('modalTambahSiswa');

    if (!modal) return;

    modal.classList.add('aktif');
    document.body.style.overflow = 'hidden';
}

function tutupModalSiswa()
{
    const modal = document.getElementById('modalTambahSiswa');

    if (!modal) return;

    modal.classList.remove('aktif');

    if (!document.querySelector('.import-modal-siswa.show')) {
        document.body.style.overflow = '';
    }
}

/* =========================================================
   MODAL IMPORT SISWA
========================================================= */

function bukaModalImportSiswa()
{
    const modal = document.getElementById('modalImportSiswa');

    if (!modal) return;

    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function tutupModalImportSiswa()
{
    const modal = document.getElementById('modalImportSiswa');

    if (!modal) return;

    modal.classList.remove('show');

    if (!document.querySelector('.modal-siswa.aktif')) {
        document.body.style.overflow = '';
    }
}

/* =========================================================
   EVENT DOM
========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    const inputFile = document.getElementById('fileImportSiswa');
    const namaFile = document.getElementById('namaFileImportSiswa');

    if (inputFile && namaFile) {

        inputFile.addEventListener('change', function () {

            if (this.files.length > 0) {
                namaFile.textContent = this.files[0].name;
            } else {
                namaFile.textContent = 'Belum ada file yang dipilih';
            }

        });

    }

    /* KLIK AREA LUAR MODAL TAMBAH */

    const modalTambah = document.getElementById('modalTambahSiswa');

    if (modalTambah) {
        modalTambah.addEventListener('click', function (event) {
            if (event.target === modalTambah) {
                tutupModalSiswa();
            }
        });
    }

    /* KLIK AREA LUAR MODAL IMPORT */

    const modalImport = document.getElementById('modalImportSiswa');

    if (modalImport) {
        modalImport.addEventListener('click', function (event) {
            if (event.target === modalImport) {
                tutupModalImportSiswa();
            }
        });
    }

    /* BUKA MODAL KEMBALI JIKA VALIDASI GAGAL */

    @if($errors->has('file'))
        bukaModalImportSiswa();
    @elseif($errors->any())
        bukaModalSiswa();
    @endif

});

/* TOMBOL ESCAPE */

document.addEventListener('keydown', function (event) {

    if (event.key === 'Escape') {
        tutupModalSiswa();
        tutupModalImportSiswa();
    }

});

</script>

@endpush