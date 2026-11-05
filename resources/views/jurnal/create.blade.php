
@extends('layouts.app')

@section('content')

<div class="content">

    {{-- FORM JURNAL BARU --}}
    <div class="box">

        <h1>📖 Jurnal Mengajar</h1>

        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form
            action="{{ route('jurnal.guru.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <label for="jadwalid">Jadwal</label>

            <select name="jadwalid" id="jadwalid" required>
                <option value="">-- Pilih Jadwal --</option>

                @foreach($jadwal as $item)
                    <option
                        value="{{ $item->id }}"
                        {{ old('jadwalid') == $item->id ? 'selected' : '' }}
                    >
                        {{ ucfirst($item->hari) }}
                        — Jam ke-{{ $item->jamMulai }}
                        sampai Jam ke-{{ $item->jamSelesai }}
                        — {{ $item->namamapel ?? 'Mata Pelajaran' }}
                        — {{ $item->namakelas ?? 'Kelas' }}
                    </option>
                @endforeach
            </select>

            <label for="tanggal">Tanggal</label>

            <input
                type="date"
                name="tanggal"
                id="tanggal"
                value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                required
            >

            <label for="materi">Materi</label>

            <textarea
                name="materi"
                id="materi"
                required
                placeholder="Masukkan materi yang diajarkan..."
            >{{ old('materi') }}</textarea>

            <label for="kegiatan">Kegiatan Pembelajaran</label>

            <textarea
                name="kegiatan"
                id="kegiatan"
                required
                placeholder="Masukkan kegiatan pembelajaran..."
            >{{ old('kegiatan') }}</textarea>

            <label for="catatan">Catatan</label>

            <textarea
                name="catatan"
                id="catatan"
                placeholder="Catatan tambahan..."
            >{{ old('catatan') }}</textarea>

            <label for="fotobukti">Foto Bukti Pembelajaran</label>

            <div class="upload-box">
                <input
                    type="file"
                    name="fotobukti"
                    id="fotobukti"
                    accept="image/jpeg,image/png,image/webp"
                    required
                >

                <div class="upload-info">
                    <strong>📷 Upload foto kegiatan pembelajaran</strong>
                    <span>
                        Format JPG, JPEG, PNG, atau WEBP.
                        Maksimal 5 MB.
                    </span>
                </div>
            </div>

            <div
                id="previewContainer"
                class="preview-container"
                style="display:none;"
            >
                <p class="preview-title">Preview Foto</p>

                <img
                    id="previewFoto"
                    src=""
                    alt="Preview foto jurnal"
                >
            </div>

            <button type="submit" class="btn">
                💾 Simpan Jurnal
            </button>

        </form>

    </div>


    {{-- RIWAYAT JURNAL --}}
    <div class="box">

        <h2>Riwayat Jurnal</h2>

        @forelse($jurnal as $item)

            <div class="jurnal">

                <strong>
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                </strong>

                <br>

                <strong>
                    {{ $item->namamapel ?? '-' }}
                </strong>

                — {{ $item->namakelas ?? '-' }}

                <hr>

                <strong>Materi</strong>

                <p>{{ $item->materi }}</p>

                @if($item->kegiatan)
                    <strong>Kegiatan</strong>
                    <p>{{ $item->kegiatan }}</p>
                @endif

                @if($item->catatan)
                    <strong>Catatan</strong>
                    <p>{{ $item->catatan }}</p>
                @endif

                @if($item->fotobukti)
                    <div class="foto-riwayat">

                        <strong>Foto Bukti Pembelajaran</strong>

                        <img
                            src="{{ asset('storage/' . $item->fotobukti) }}"
                            alt="Foto bukti pembelajaran"
                        >

                    </div>
                @endif

                {{-- TOMBOL EDIT JURNAL --}}
                <div class="aksi-jurnal">

                    <a
                        href="{{ route('jurnal.guru.edit', $item->id) }}"
                        class="btn-edit"
                    >
                        <i class="fa-solid fa-pen-to-square"></i>
                        Edit Jurnal
                    </a>

                </div>

            </div>

        @empty

            <p class="empty">Belum ada jurnal.</p>

        @endforelse

    </div>

</div>


<style>

* {
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    margin: 0;
    background: #f5f7fb;
    color: #1e293b;
}

.content {
    padding: 30px;
    max-width: 1100px;
    margin: auto;
}

.box {
    background: white;
    padding: 25px;
    border-radius: 15px;
    margin-top: 20px;
    box-shadow: 0 3px 10px rgba(0,0,0,.06);
}

.box h1 {
    margin-top: 0;
}

.box h2 {
    margin-top: 0;
}

label {
    display: block;
    margin-top: 15px;
    margin-bottom: 6px;
    font-weight: bold;
}

select,
input[type="date"],
input[type="text"],
textarea {
    width: 100%;
    padding: 11px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    background: white;
    font-size: 14px;
}

select:focus,
input:focus,
textarea:focus {
    outline: none;
    border-color: #2563eb;
}

textarea {
    min-height: 100px;
    resize: vertical;
}

.upload-box {
    border: 2px dashed #bfdbfe;
    background: #eff6ff;
    border-radius: 10px;
    padding: 18px;
    margin-top: 5px;
}

.upload-box input[type="file"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    background: white;
}

.upload-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin-top: 10px;
}

.upload-info strong {
    color: #1e40af;
}

.upload-info span {
    color: #64748b;
    font-size: 13px;
}

.preview-container {
    margin-top: 18px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

.preview-title {
    margin-top: 0;
    font-weight: bold;
}

#previewFoto {
    display: block;
    width: 100%;
    max-width: 400px;
    max-height: 300px;
    object-fit: contain;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

.btn {
    margin-top: 20px;
    padding: 12px 20px;
    background: #2563eb;
    color: white;
    border: 0;
    border-radius: 7px;
    cursor: pointer;
    font-weight: bold;
}

.btn:hover {
    background: #1d4ed8;
}

.alert {
    margin-top: 15px;
    padding: 12px;
    border-radius: 8px;
}

.alert.success {
    background: #dcfce7;
    color: #166534;
}

.alert.error {
    background: #fee2e2;
    color: #991b1b;
}

.jurnal {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px;
    margin-top: 12px;
}

.jurnal hr {
    border: 0;
    border-top: 1px solid #e2e8f0;
    margin: 10px 0;
}

.jurnal p {
    line-height: 1.6;
    white-space: pre-line;
}

.foto-riwayat {
    margin-top: 15px;
}

.foto-riwayat strong {
    display: block;
    margin-bottom: 8px;
}

.foto-riwayat img {
    display: block;
    width: 100%;
    max-width: 450px;
    max-height: 350px;
    object-fit: contain;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

.aksi-jurnal {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 16px;
}

.btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 16px;
    border-radius: 8px;
    background: #2563eb;
    color: white;
    text-decoration: none;
    font-weight: 600;
    font-size: 13px;
    transition: .2s;
}

.btn-edit:hover {
    background: #1d4ed8;
    color: white;
}

.empty {
    color: #64748b;
}

@media (max-width: 600px) {
    .content {
        padding: 15px;
    }

    .box {
        padding: 18px;
    }

    .btn-edit {
        width: 100%;
        justify-content: center;
    }
}

</style>


<script>

const fotoInput = document.getElementById('fotobukti');
const previewContainer = document.getElementById('previewContainer');
const previewFoto = document.getElementById('previewFoto');

fotoInput.addEventListener('change', function () {

    const file = this.files[0];

    if (!file) {
        previewContainer.style.display = 'none';
        previewFoto.src = '';
        return;
    }

    const ukuranMaksimal = 5 * 1024 * 1024;

    if (file.size > ukuranMaksimal) {
        alert('Ukuran foto terlalu besar. Maksimal 5 MB.');
        this.value = '';
        previewContainer.style.display = 'none';
        previewFoto.src = '';
        return;
    }

    const tipeDiizinkan = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    if (!tipeDiizinkan.includes(file.type)) {
        alert('Format foto harus JPG, JPEG, PNG, atau WEBP.');
        this.value = '';
        previewContainer.style.display = 'none';
        previewFoto.src = '';
        return;
    }

    const reader = new FileReader();

    reader.onload = function (event) {
        previewFoto.src = event.target.result;
        previewContainer.style.display = 'block';
    };

    reader.readAsDataURL(file);

});

</script>

@endsection