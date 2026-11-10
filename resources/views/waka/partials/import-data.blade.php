
<div class="import-card">
    <div class="import-heading">
        <div class="import-icon">
            <i class="fa-solid fa-file-import"></i>
        </div>

        <div>
            <h3>Impor Data {{ ucfirst($jenisImpor) }}</h3>
            <p>Tambahkan banyak data sekaligus melalui Excel, CSV, atau PDF.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="import-alert success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="import-alert danger">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="import-alert warning">
            <i class="fa-solid fa-triangle-exclamation"></i>
            {{ session('warning') }}
        </div>
    @endif

    <form
        action="{{ route('waka.data.import', ['jenis' => $jenisImpor]) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="import-input-row">
            <input
                type="file"
                name="file"
                accept=".xlsx,.xls,.csv,.pdf"
                required
            >

            <button type="submit" class="import-button">
                <i class="fa-solid fa-upload"></i>
                Impor Data
            </button>
        </div>

        @error('file')
            <small class="import-error">{{ $message }}</small>
        @enderror
    </form>

    <div class="import-info">
        <strong>Format kolom yang diperlukan:</strong>

        @if($jenisImpor === 'siswa')
            <p>NISN, Nama, JenisKelamin, Kelas, TempatLahir, TanggalLahir, Alamat, NomorTelepon</p>
        @elseif($jenisImpor === 'guru')
            <p>NIP, Nama, NomorTelepon, Alamat</p>
        @else
            <p>NamaKelas, Jurusan, Tingkat</p>
        @endif

        <small>
            Maksimal ukuran file 10 MB. Untuk PDF, gunakan tabel teks dengan pemisah kolom tanda |.
            PDF hasil scan gambar tidak didukung.
        </small>
    </div>
</div>

<style>
.import-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 22px;
    margin: 0 0 22px;
    box-shadow: 0 4px 15px rgba(15,23,42,.04);
}

.import-heading {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 18px;
}

.import-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2563eb;
    background: #eff6ff;
    font-size: 19px;
    flex-shrink: 0;
}

.import-heading h3 {
    margin: 0 0 5px;
    color: #173b67;
    font-size: 17px;
}

.import-heading p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
}

.import-input-row {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.import-input-row input {
    flex: 1;
    min-width: 220px;
    padding: 10px;
    border: 1px solid #dbe3ef;
    border-radius: 9px;
    background: #f8fafc;
}

.import-button {
    border: 0;
    border-radius: 9px;
    padding: 12px 18px;
    color: white;
    background: #2563eb;
    font-weight: 600;
    cursor: pointer;
}

.import-button:hover {
    background: #1d4ed8;
}

.import-info {
    margin-top: 16px;
    padding: 13px 15px;
    border-radius: 10px;
    background: #f8fafc;
    color: #475569;
    font-size: 13px;
    line-height: 1.7;
}

.import-info p {
    margin: 5px 0;
    color: #1e40af;
    font-weight: 600;
}

.import-info small {
    color: #64748b;
}

.import-alert {
    padding: 12px 14px;
    border-radius: 9px;
    margin-bottom: 14px;
    font-size: 13px;
}

.import-alert.success {
    background: #ecfdf5;
    color: #047857;
}

.import-alert.danger {
    background: #fef2f2;
    color: #b91c1c;
}

.import-alert.warning {
    background: #fffbeb;
    color: #92400e;
}

.import-error {
    display: block;
    margin-top: 8px;
    color: #dc2626;
}

@media(max-width: 600px) {
    .import-input-row {
        align-items: stretch;
        flex-direction: column;
    }

    .import-button {
        width: 100%;
    }
}
</style>
