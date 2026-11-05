@extends('layouts.app')

@section('title', 'Edit Jurnal Mengajar - SCP')
@section('page-title', 'Edit Jurnal Mengajar')

@section('content')
<div class="edit-jurnal-container">

    <div class="edit-jurnal-card">

        <a href="{{ route('jurnal.guru') }}" class="back-link">
            &larr; Kembali ke Daftar Jurnal
        </a>

        <h2>Edit Jurnal Mengajar</h2>

        <p class="description">
            Ubah jurnal yang sudah tersimpan. Perubahan akan langsung memperbarui jurnal lama.
        </p>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('jurnal.guru.update', $jurnal->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="jadwalid">Jadwal Mengajar</label>

                <select name="jadwalid" id="jadwalid" required>
                    <option value="">-- Pilih Jadwal --</option>

                    @foreach($jadwal as $item)
                        <option
                            value="{{ $item->id }}"
                            {{ old('jadwalid', $jurnal->jadwalid) == $item->id ? 'selected' : '' }}
                        >
                            {{ ucfirst($item->hari) }}
                            |
                            {{ $item->jamMulai }} - {{ $item->jamSelesai }}
                            |
                            {{ $item->namamapel ?? 'Mata Pelajaran' }}
                            |
                            {{ $item->namakelas ?? 'Kelas' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal Mengajar</label>

                <input
                    type="date"
                    name="tanggal"
                    id="tanggal"
                    value="{{ old('tanggal', \Carbon\Carbon::parse($jurnal->tanggal)->format('Y-m-d')) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="materi">Materi Pembelajaran</label>

                <textarea
                    name="materi"
                    id="materi"
                    maxlength="500"
                    required
                >{{ old('materi', $jurnal->materi) }}</textarea>
            </div>

            <div class="form-group">
                <label for="kegiatan">Kegiatan Pembelajaran</label>

                <textarea
                    name="kegiatan"
                    id="kegiatan"
                    maxlength="1000"
                    required
                >{{ old('kegiatan', $jurnal->kegiatan) }}</textarea>
            </div>

            <div class="form-group">
                <label for="catatan">Catatan</label>

                <textarea
                    name="catatan"
                    id="catatan"
                    maxlength="1000"
                >{{ old('catatan', $jurnal->catatan) }}</textarea>
            </div>

            <div class="form-group">
                <label>Foto Bukti Saat Ini</label>

                @if($jurnal->fotobukti)
                    <div class="current-photo">
                        <img
                            src="{{ asset('storage/' . $jurnal->fotobukti) }}"
                            alt="Foto bukti jurnal"
                        >
                    </div>
                @else
                    <p class="no-photo">Belum ada foto bukti.</p>
                @endif
            </div>

            <div class="form-group">
                <label for="fotobukti">Ganti Foto Bukti (Opsional)</label>

                <input
                    type="file"
                    name="fotobukti"
                    id="fotobukti"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                <small>
                    Kosongkan jika ingin tetap menggunakan foto lama.
                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.
                </small>
            </div>

            <div class="button-group">
                <a href="{{ route('jurnal.guru') }}" class="btn-cancel">
                    Batal
                </a>

                <button type="submit" class="btn-save">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</div>

<style>
    .edit-jurnal-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 25px 16px;
    }

    .edit-jurnal-card {
        background: #fff;
        border: 1px solid #e5eaf2;
        border-radius: 15px;
        padding: 28px;
        box-shadow: 0 4px 15px rgba(0,0,0,.05);
    }

    .back-link {
        display: inline-block;
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 18px;
    }

    .edit-jurnal-card h2 {
        color: #172033;
        margin: 0 0 8px;
        font-size: 24px;
    }

    .description {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 14px;
        font-weight: 700;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        box-sizing: border-box;
        width: 100%;
        padding: 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #fff;
        color: #172033;
        font-family: inherit;
        font-size: 14px;
    }

    .form-group textarea {
        min-height: 110px;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    }

    .form-group small {
        display: block;
        margin-top: 7px;
        color: #64748b;
        font-size: 12px;
    }

    .current-photo img {
        display: block;
        max-width: 100%;
        max-height: 280px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        object-fit: contain;
    }

    .no-photo {
        color: #94a3b8;
        font-size: 13px;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-error ul {
        margin-bottom: 0;
    }

    .button-group {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-save,
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-save {
        background: #2563eb;
        color: white;
    }

    .btn-save:hover {
        background: #1d4ed8;
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #334155;
    }

    @media(max-width:600px) {
        .edit-jurnal-card {
            padding: 18px;
        }

        .button-group {
            flex-direction: column;
        }

        .btn-save,
        .btn-cancel {
            width: 100%;
            box-sizing: border-box;
        }
    }
</style>
@endsection