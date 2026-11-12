@extends('layouts.app')

@section('title', 'Jadwal Sekolah')

@section('content')

<div class="jadwal-page">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <div class="page-label">JADWAL SEKOLAH</div>
            <h1>Jadwal Sekolah</h1>
            <p>Pilih angkatan untuk melihat jadwal berdasarkan kelas.</p>
        </div>

        <a href="{{ route('waka.dashboard') }}" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M19 12H5"/>
                <path d="M12 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- ALERT BERHASIL --}}
    @if(session('success'))
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 6L9 17l-5-5"/>
            </svg>
            <div>
                <strong>Berhasil</strong>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- ALERT PERINGATAN --}}
    @if(session('warning'))
        <div class="alert alert-warning">
            <div>
                <strong>Import selesai dengan beberapa catatan</strong>
                <p>{{ session('warning') }}</p>
            </div>
        </div>
    @endif

    {{-- ALERT GAGAL --}}
    @if(session('error'))
        <div class="alert alert-error">
            <div>
                <strong>Gagal</strong>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- VALIDASI --}}
    @if($errors->any())
        <div class="alert alert-error">
            <div>
                <strong>Terjadi Kesalahan</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- DETAIL ERROR IMPORT --}}
    @if(session('import_errors'))
        <div class="import-errors">
            <strong>Detail data yang perlu diperiksa</strong>

            <ul>
                @foreach(session('import_errors') as $pesanError)
                    <li>{{ $pesanError }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- KONTROL JADWAL --}}
    <div class="jadwal-controls">

        <div class="jadwal-status">
            <strong>Status Penguncian Jadwal</strong>

            @if($jadwalTerkunci ?? false)
                <span class="status-locked">
                    Semua jadwal terkunci. Guru tidak dapat menambah atau menghapus jadwal.
                </span>
            @else
                <span class="status-open">
                    Semua jadwal terbuka. Guru dapat mengelola jadwalnya.
                </span>
            @endif
        </div>

        <div class="jadwal-control-actions">

            <button type="button"
                    class="btn-import-jadwal"
                    onclick="bukaModalImportJadwal()">

                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 16V3"/>
                    <path d="m7 8 5-5 5 5"/>
                    <path d="M4 14v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/>
                </svg>

                Import Jadwal PDF
            </button>

            <form method="POST"
                  action="{{ route('waka.jadwal.kunci') }}"
                  onsubmit="return confirm('{{ ($jadwalTerkunci ?? false) ? 'Buka kunci seluruh jadwal?' : 'Kunci seluruh jadwal untuk semua guru?' }}')">

                @csrf

                <input type="hidden"
                       name="terkunci"
                       value="{{ ($jadwalTerkunci ?? false) ? 0 : 1 }}">

                @if($jadwalTerkunci ?? false)
                    <button type="submit" class="btn-unlock">
                        Buka Kunci Jadwal
                    </button>
                @else
                    <button type="submit" class="btn-lock">
                        Kunci Semua Jadwal
                    </button>
                @endif

            </form>

        </div>
    </div>

    {{-- INFO --}}
    <div class="info-card">

        <div class="info-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                <rect x="3" y="4" width="18" height="17" rx="2"/>
                <path d="M16 2v4"/>
                <path d="M8 2v4"/>
                <path d="M3 10h18"/>
            </svg>
        </div>

        <div>
            <strong>Pilih Angkatan</strong>
            <p>
                Pilih tingkat kelas terlebih dahulu untuk melihat
                daftar jurusan dan kelas yang tersedia.
            </p>
        </div>

    </div>

    {{-- ANGKATAN --}}
    <div class="section-header">
        <div>
            <h2>Angkatan</h2>
            <p>Pilih tingkat kelas yang ingin dilihat.</p>
        </div>
    </div>

    <div class="angkatan-grid">

        @forelse($daftarAngkatan as $angkatan)

            <a href="{{ route('waka.jadwal.angkatan', $angkatan->tingkat) }}"
               class="angkatan-card">

                <div class="angkatan-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path d="M3 10l9-6 9 6"/>
                        <path d="M5 10v9h14v-9"/>
                        <path d="M9 19v-6h6v6"/>
                    </svg>
                </div>

                <div class="angkatan-content">
                    <span>Tingkat</span>
                    <strong>Kelas {{ $angkatan->tingkat }}</strong>
                    <small>Lihat jurusan dan kelas</small>
                </div>

                <div class="arrow-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </div>

            </a>

        @empty

            <div class="empty-angkatan">
                Belum ada data angkatan yang tersedia.
            </div>

        @endforelse

    </div>

</div>


{{-- =====================================================
     MODAL IMPORT JADWAL PDF
====================================================== --}}

<div id="modalImportJadwal" class="import-modal">

    <div class="import-modal-content">

        {{-- HEADER MODAL --}}
        <div class="import-modal-header">

            <div class="import-title">

                <div class="import-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                        <path d="M8 13h8"/>
                        <path d="M8 17h8"/>
                    </svg>
                </div>

                <div>
                    <h3>Import Jadwal PDF</h3>
                    <p>Import jadwal kelas dari dokumen PDF sekolah.</p>
                </div>

            </div>

            <button type="button"
                    class="btn-close-import"
                    onclick="tutupModalImportJadwal()">
                &times;
            </button>

        </div>

        {{-- BODY MODAL --}}
        <div class="import-modal-body">

            <form action="{{ route('waka.data.import', ['jenis' => 'jadwal_kelas']) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="import-form"
                  onsubmit="return konfirmasiImportJadwal()">

                @csrf

                {{-- PUTARAN 1 --}}
                <input type="hidden" name="jenis_jadwal" value="1">

                <div class="import-option">

                    <div class="import-option-heading">

                        <div class="import-option-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M3 10l9-6 9 6"/>
                                <path d="M5 10v9h14v-9"/>
                                <path d="M9 19v-6h6v6"/>
                            </svg>
                        </div>

                        <div>
                            <strong>Jadwal Kelas</strong>
                            <span>Putaran 1</span>
                        </div>

                    </div>

                    <label class="file-label" for="fileJadwalKelas">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 16V3"/>
                            <path d="m7 8 5-5 5 5"/>
                            <path d="M4 14v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/>
                        </svg>
                        Pilih PDF Jadwal Kelas
                    </label>

                    <input type="file"
                           id="fileJadwalKelas"
                           name="file"
                           accept=".pdf,application/pdf"
                           required>

                    <small id="namaFileJadwalKelas" class="file-name">
                        Belum ada file dipilih
                    </small>

                    <button type="submit"
                            id="btnSubmitImport"
                            class="btn-submit-import">

                        Import Jadwal Putaran 1

                    </button>

                </div>

            </form>

            {{-- INFORMASI --}}
            <div class="import-note">

                <strong>Informasi Import</strong>

                <ul>
                    <li>File harus menggunakan format PDF.</li>
                    <li>Import ini khusus untuk jadwal kelas Putaran 1.</li>
                    <li>Semua kelas dalam PDF akan disimpan sebagai Putaran 1.</li>
                    <li>Jam pelajaran mengikuti hasil pembacaan tabel PDF.</li>
                    <li>Nama guru akan dicocokkan dengan data guru di database.</li>
                    <li>Data jadwal lama tidak akan dihapus.</li>
                    <li>Import tidak dapat dilakukan jika jadwal sedang dikunci.</li>
                </ul>

            </div>

        </div>

    </div>

</div>

@endsection


@push('styles')
<style>
.jadwal-page {
    width:100%;
}

.page-header {
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:20px;
    margin-bottom:24px;
}

.page-label {
    margin-bottom:6px;
    color:#2563eb;
    font-size:9px;
    font-weight:700;
    letter-spacing:1.3px;
}

.page-header h1 {
    margin:0 0 6px;
    color:#102a43;
    font-size:25px;
    font-weight:750;
    letter-spacing:-.4px;
}

.page-header p {
    margin:0;
    color:#718096;
    font-size:13px;
}

.btn-back {
    display:inline-flex;
    align-items:center;
    gap:7px;
    padding:10px 15px;
    background:#eef5ff;
    border-radius:10px;
    color:#2563eb;
    font-size:12px;
    font-weight:600;
    text-decoration:none;
    white-space:nowrap;
    transition:.2s;
}

.btn-back:hover {
    background:#e0edff;
}

.btn-back svg {
    width:16px;
    height:16px;
}

.alert {
    display:flex;
    align-items:flex-start;
    gap:10px;
    padding:13px 15px;
    margin-bottom:18px;
    border-radius:10px;
    font-size:12px;
}

.alert svg {
    width:18px;
    height:18px;
    min-width:18px;
}

.alert strong {
    display:block;
    margin-bottom:3px;
}

.alert p {
    margin:0;
    line-height:1.5;
}

.alert ul {
    margin:5px 0 0;
    padding-left:18px;
}

.alert-success {
    background:#f0fdf4;
    border:1px solid #bbf7d0;
    color:#166534;
}

.alert-warning {
    background:#fffbeb;
    border:1px solid #fde68a;
    color:#92400e;
}

.alert-error {
    background:#fef2f2;
    border:1px solid #fecaca;
    color:#b91c1c;
}

.import-errors {
    margin-bottom:18px;
    padding:15px 17px;
    background:#fff;
    border:1px solid #fecaca;
    border-radius:11px;
    color:#991b1b;
    font-size:12px;
}

.import-errors strong {
    display:block;
    margin-bottom:8px;
}

.import-errors ul {
    margin:0;
    padding-left:20px;
    line-height:1.8;
}

.jadwal-controls {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:18px;
    flex-wrap:wrap;
    margin:0 0 22px;
    padding:18px 20px;
    background:#fff;
    border:1px solid #e1e9f3;
    border-radius:14px;
}

.jadwal-status strong {
    display:block;
    color:#243b53;
    font-size:13px;
}

.jadwal-status span {
    display:block;
    margin-top:5px;
    font-size:11px;
    line-height:1.5;
}

.status-locked {
    color:#b45309;
}

.status-open {
    color:#15803d;
}

.jadwal-control-actions {
    display:flex;
    align-items:center;
    gap:9px;
    flex-wrap:wrap;
}

.jadwal-control-actions form {
    margin:0;
}

.btn-import-jadwal,
.btn-lock,
.btn-unlock {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    min-height:40px;
    padding:10px 15px;
    border:0;
    border-radius:9px;
    color:#fff;
    font-size:12px;
    font-weight:600;
    cursor:pointer;
    transition:.2s;
}

.btn-import-jadwal {
    background:#2563eb;
}

.btn-import-jadwal:hover {
    background:#1d4ed8;
}

.btn-import-jadwal svg {
    width:16px;
    height:16px;
}

.btn-lock {
    background:#b45309;
}

.btn-lock:hover {
    background:#92400e;
}

.btn-unlock {
    background:#15803d;
}

.btn-unlock:hover {
    background:#166534;
}

.info-card {
    display:flex;
    align-items:flex-start;
    gap:12px;
    padding:15px 17px;
    margin-bottom:25px;
    background:#f4f8ff;
    border:1px solid #dce9f9;
    border-radius:13px;
}

.info-icon {
    width:38px;
    height:38px;
    min-width:38px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#e6f0ff;
    border-radius:10px;
    color:#2563eb;
}

.info-icon svg {
    width:19px;
    height:19px;
}

.info-card strong {
    display:block;
    margin-bottom:3px;
    color:#1e3a5f;
    font-size:12px;
}

.info-card p {
    margin:0;
    color:#718096;
    font-size:10px;
    line-height:1.5;
}

.section-header {
    margin-bottom:13px;
}

.section-header h2 {
    margin:0 0 4px;
    color:#243b53;
    font-size:16px;
    font-weight:700;
}

.section-header p {
    margin:0;
    color:#8492a6;
    font-size:11px;
}

.angkatan-grid {
    display:grid;
    grid-template-columns:repeat(3,minmax(0,1fr));
    gap:15px;
}

.angkatan-card {
    position:relative;
    display:flex;
    align-items:center;
    gap:13px;
    min-height:120px;
    padding:17px;
    background:#fff;
    border:1px solid #e1e9f3;
    border-radius:14px;
    color:inherit;
    text-decoration:none;
    box-shadow:0 5px 18px rgba(15,50,90,.04);
    transition:.2s;
}

.angkatan-card:hover {
    transform:translateY(-2px);
    border-color:#c8dcf5;
    box-shadow:0 9px 25px rgba(15,50,90,.08);
}

.angkatan-icon {
    width:50px;
    height:50px;
    min-width:50px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#eef5ff;
    border-radius:13px;
    color:#2563eb;
}

.angkatan-icon svg {
    width:24px;
    height:24px;
}

.angkatan-content {
    min-width:0;
    flex:1;
}

.angkatan-content span {
    display:block;
    margin-bottom:3px;
    color:#94a3b8;
    font-size:9px;
    text-transform:uppercase;
    letter-spacing:.7px;
}

.angkatan-content strong {
    display:block;
    margin-bottom:4px;
    color:#243b53;
    font-size:17px;
    font-weight:750;
}

.angkatan-content small {
    display:block;
    color:#718096;
    font-size:9px;
}

.arrow-icon {
    width:30px;
    height:30px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:8px;
    background:#f5f8fc;
    color:#64748b;
}

.arrow-icon svg {
    width:16px;
    height:16px;
}

.angkatan-card:hover .arrow-icon {
    background:#eef5ff;
    color:#2563eb;
}

.empty-angkatan {
    grid-column:1/-1;
    padding:25px;
    background:#fff;
    border:1px dashed #cbd5e1;
    border-radius:12px;
    color:#64748b;
    text-align:center;
    font-size:12px;
}

/* MODAL IMPORT */

.import-modal {
    display:none;
    position:fixed;
    inset:0;
    z-index:3000;
    align-items:center;
    justify-content:center;
    padding:15px;
    background:rgba(15,23,42,.58);
    backdrop-filter:blur(3px);
}

.import-modal.show {
    display:flex;
}

.import-modal-content {
    width:100%;
    max-width:560px;
    max-height:92vh;
    overflow-y:auto;
    background:#fff;
    border-radius:16px;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
    animation:modalImportMasuk .2s ease;
}

@keyframes modalImportMasuk {
    from {
        opacity:0;
        transform:translateY(10px);
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
    gap:12px;
    padding:19px 21px;
    border-bottom:1px solid #edf2f7;
}

.import-title {
    display:flex;
    align-items:center;
    gap:12px;
}

.import-icon {
    width:45px;
    height:45px;
    flex:0 0 45px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:12px;
    background:#eff6ff;
    color:#2563eb;
}

.import-icon svg {
    width:22px;
    height:22px;
}

.import-title h3 {
    margin:0;
    color:#173b67;
    font-size:17px;
}

.import-title p {
    margin:5px 0 0;
    color:#718096;
    font-size:11px;
}

.btn-close-import {
    width:34px;
    height:34px;
    border:0;
    border-radius:8px;
    background:#f1f5f9;
    color:#64748b;
    font-size:24px;
    line-height:1;
    cursor:pointer;
}

.import-modal-body {
    padding:20px;
}

.import-form {
    margin:0;
}

.import-option {
    padding:15px;
    border:1px solid #e2e8f0;
    border-radius:12px;
    background:#fbfdff;
}

.import-option-heading {
    display:flex;
    align-items:center;
    gap:11px;
    margin-bottom:14px;
}

.import-option-icon {
    width:40px;
    height:40px;
    flex:0 0 40px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:10px;
    background:#eff6ff;
    color:#2563eb;
}

.import-option-icon svg {
    width:20px;
    height:20px;
}

.import-option-heading strong {
    display:block;
    color:#243b53;
    font-size:13px;
}

.import-option-heading span {
    display:inline-block;
    margin-top:4px;
    color:#64748b;
    font-size:11px;
}

.file-label {
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    width:100%;
    min-height:42px;
    padding:10px;
    border:1px dashed #93c5fd;
    border-radius:9px;
    background:#eff6ff;
    color:#1d4ed8;
    font-size:12px;
    font-weight:600;
    cursor:pointer;
    box-sizing:border-box;
}

.file-label svg {
    width:17px;
    height:17px;
}

.import-option input[type="file"] {
    display:none;
}

.file-name {
    display:block;
    margin-top:8px;
    color:#64748b;
    font-size:11px;
    overflow-wrap:anywhere;
}

.btn-submit-import {
    width:100%;
    min-height:42px;
    margin-top:13px;
    border:0;
    border-radius:9px;
    background:#2563eb;
    color:#fff;
    font-size:12px;
    font-weight:600;
    cursor:pointer;
    transition:.2s;
}

.btn-submit-import:hover {
    background:#1d4ed8;
}

.btn-submit-import:disabled {
    opacity:.65;
    cursor:not-allowed;
}

.import-note {
    margin-top:17px;
    padding:13px 15px;
    border:1px solid #dbeafe;
    border-radius:10px;
    background:#f4f8ff;
}

.import-note strong {
    color:#1e40af;
    font-size:12px;
}

.import-note ul {
    margin:8px 0 0;
    padding-left:19px;
    color:#475569;
    font-size:11px;
    line-height:1.8;
}

@media(max-width:900px) {
    .angkatan-grid {
        grid-template-columns:repeat(2,minmax(0,1fr));
    }
}

@media(max-width:600px) {
    .page-header {
        flex-direction:column;
    }

    .btn-back {
        align-self:flex-start;
    }

    .jadwal-controls {
        align-items:stretch;
        flex-direction:column;
    }

    .jadwal-control-actions {
        width:100%;
        flex-direction:column;
    }

    .jadwal-control-actions button {
        width:100%;
    }

    .jadwal-control-actions form {
        width:100%;
    }

    .angkatan-grid {
        grid-template-columns:1fr;
    }

    .import-modal-content {
        max-height:95vh;
    }

    .import-modal-header,
    .import-modal-body {
        padding:15px;
    }
}
</style>
@endpush


@push('scripts')
<script>
function bukaModalImportJadwal() {
    const modal = document.getElementById('modalImportJadwal');

    if (!modal) return;

    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function tutupModalImportJadwal() {
    const modal = document.getElementById('modalImportJadwal');

    if (!modal) return;

    modal.classList.remove('show');
    document.body.style.overflow = '';
}

function konfirmasiImportJadwal() {
    const input = document.getElementById('fileJadwalKelas');

    if (!input || !input.files.length) {
        alert('Silakan pilih file PDF terlebih dahulu.');
        return false;
    }

    return confirm(
        'Import jadwal kelas Putaran 1 dari file PDF ini?\n\nData jadwal lama tidak akan dihapus.'
    );
}

document.addEventListener('DOMContentLoaded', function () {

    const inputKelas = document.getElementById('fileJadwalKelas');
    const namaKelas = document.getElementById('namaFileJadwalKelas');

    if (inputKelas && namaKelas) {
        inputKelas.addEventListener('change', function () {

            if (!this.files.length) {
                namaKelas.textContent = 'Belum ada file dipilih';
                return;
            }

            const file = this.files[0];

            namaKelas.textContent = file.name;

            if (!file.name.toLowerCase().endsWith('.pdf')) {
                alert('File harus berformat PDF.');
                this.value = '';
                namaKelas.textContent = 'Belum ada file dipilih';
            }
        });
    }

    const modal = document.getElementById('modalImportJadwal');

    if (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                tutupModalImportJadwal();
            }
        });
    }

    @if($errors->any())
        bukaModalImportJadwal();
    @endif
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        tutupModalImportJadwal();
    }
});
</script>
@endpush