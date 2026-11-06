@extends('layouts.app')

@section('title', 'Edit Jurnal Mengajar - SCP')
@section('page-title', 'Edit Jurnal Mengajar')

@section('content')

<div class="edit-jurnal-container">

    <div class="edit-jurnal-card">

        <a href="{{ route('jurnal.guru') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke Daftar Jurnal
        </a>

        <div class="page-heading">
            <div class="heading-icon">
                <i class="fa-solid fa-book-open"></i>
            </div>

            <div>
                <h2>Edit Jurnal Mengajar</h2>
                <p>Perbarui data jurnal pembelajaran hari ini.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i>
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
            id="formEditJurnal"
            action="{{ route('jurnal.guru.update', $jurnal->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            {{-- JADWAL --}}
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
                            — Jam {{ $item->jamMulai }} - {{ $item->jamSelesai }}
                            — {{ $item->namamapel ?? 'Mata Pelajaran' }}
                            — {{ $item->namakelas ?? 'Kelas' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TANGGAL --}}
            <div class="form-group">
                <label>Tanggal Mengajar</label>

                <div class="tanggal-display">
                    <i class="fa-solid fa-calendar-days"></i>

                    <span>
                        {{ \Carbon\Carbon::parse($tanggalHariIni ?? now()->toDateString())->locale('id')->translatedFormat('l, d F Y') }}
                    </span>
                </div>

                <small>
                    Tanggal jurnal mengikuti tanggal sistem dan tidak dapat diubah.
                </small>
            </div>

            {{-- MATERI --}}
            <div class="form-group">
                <label for="materi">Materi Pembelajaran</label>

                <textarea
                    name="materi"
                    id="materi"
                    maxlength="500"
                    required
                    placeholder="Masukkan materi pembelajaran..."
                >{{ old('materi', $jurnal->materi) }}</textarea>
            </div>

            {{-- KEGIATAN --}}
            <div class="form-group">
                <label for="kegiatan">Kegiatan Pembelajaran</label>

                <textarea
                    name="kegiatan"
                    id="kegiatan"
                    maxlength="1000"
                    required
                    placeholder="Jelaskan kegiatan pembelajaran..."
                >{{ old('kegiatan', $jurnal->kegiatan) }}</textarea>
            </div>

            {{-- CATATAN --}}
            <div class="form-group">
                <label for="catatan">Catatan Tambahan</label>

                <textarea
                    name="catatan"
                    id="catatan"
                    maxlength="1000"
                    placeholder="Masukkan catatan tambahan jika ada..."
                >{{ old('catatan', $jurnal->catatan) }}</textarea>
            </div>

            {{-- FOTO LAMA --}}
            <div class="form-group">
                <label>Foto Bukti Saat Ini</label>

                @if($jurnal->fotobukti)
                    <div class="current-photo">
                        <img
                            src="{{ asset('storage/' . $jurnal->fotobukti) }}"
                            alt="Foto bukti jurnal saat ini"
                        >

                        <div class="photo-caption">
                            <i class="fa-solid fa-circle-check"></i>
                            Foto yang tersimpan saat ini
                        </div>
                    </div>
                @else
                    <div class="no-photo">
                        <i class="fa-regular fa-image"></i>
                        Belum ada foto bukti.
                    </div>
                @endif
            </div>

            {{-- GANTI FOTO DENGAN KAMERA --}}
            <div class="form-group">
                <label>Ganti Foto Bukti (Opsional)</label>

                <div class="camera-box">

                    <div class="camera-description">
                        <div class="camera-icon">
                            <i class="fa-solid fa-camera"></i>
                        </div>

                        <div>
                            <strong>Ambil Foto Menggunakan Kamera</strong>

                            <p>
                                Jika ingin mengganti foto lama, ambil foto secara langsung
                                menggunakan kamera perangkat. Galeri tidak dapat digunakan.
                            </p>
                        </div>
                    </div>

                    <div class="camera-preview">

                        <video
                            id="videoKamera"
                            autoplay
                            playsinline
                            muted
                        ></video>

                        <div class="camera-placeholder" id="cameraPlaceholder">
                            <i class="fa-solid fa-camera"></i>
                            <span>Kamera belum diaktifkan</span>
                        </div>

                    </div>

                    <canvas id="canvasKamera" style="display:none;"></canvas>

                    <div class="camera-actions">

                        <button
                            type="button"
                            class="btn-camera"
                            id="btnMulaiKamera"
                        >
                            <i class="fa-solid fa-video"></i>
                            Aktifkan Kamera
                        </button>

                        <button
                            type="button"
                            class="btn-capture"
                            id="btnAmbilFoto"
                            style="display:none;"
                            disabled
                        >
                            <i class="fa-solid fa-camera"></i>
                            Ambil Foto
                        </button>

                        <button
                            type="button"
                            class="btn-retake"
                            id="btnUlangFoto"
                            style="display:none;"
                        >
                            <i class="fa-solid fa-rotate"></i>
                            Ambil Ulang
                        </button>

                        <button
                            type="button"
                            class="btn-remove"
                            id="btnHapusFoto"
                            style="display:none;"
                        >
                            <i class="fa-solid fa-trash"></i>
                            Gunakan Foto Lama
                        </button>

                    </div>

                    <div class="camera-status" id="cameraStatus">
                        Foto lama akan tetap digunakan jika tidak mengambil foto baru.
                    </div>

                    <div
                        class="new-photo-preview"
                        id="previewContainer"
                        style="display:none;"
                    >
                        <div class="preview-heading">
                            <i class="fa-solid fa-circle-check"></i>
                            Foto pengganti berhasil diambil
                        </div>

                        <img
                            src=""
                            id="previewFoto"
                            alt="Preview foto pengganti"
                        >
                    </div>

                    {{-- INPUT FILE DIISI OTOMATIS DARI HASIL KAMERA --}}
                    <input
                        type="file"
                        name="fotobukti"
                        id="fotobukti"
                        accept="image/jpeg,image/png,image/webp"
                        hidden
                    >

                </div>

                <small>
                    Maksimal ukuran foto 5 MB. Kosongkan jika tetap menggunakan foto lama.
                </small>
            </div>

            {{-- TOMBOL --}}
            <div class="button-group">

                <a href="{{ route('jurnal.guru') }}" class="btn-cancel">
                    <i class="fa-solid fa-xmark"></i>
                    Batal
                </a>

                <button type="submit" class="btn-save" id="btnSimpan">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


<style>
.edit-jurnal-container {
    max-width: 950px;
    margin: 0 auto;
    padding: 25px 16px;
    color: #1e293b;
}

.edit-jurnal-card {
    background: #fff;
    border: 1px solid #e5eaf2;
    border-radius: 15px;
    padding: 28px;
    box-shadow: 0 4px 15px rgba(0,0,0,.05);
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #2563eb;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 22px;
}

.back-link:hover {
    color: #1d4ed8;
}

.page-heading {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 25px;
}

.heading-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: #dbeafe;
    color: #2563eb;
    border-radius: 12px;
    font-size: 21px;
}

.page-heading h2 {
    color: #172033;
    margin: 0 0 6px;
    font-size: 23px;
}

.page-heading p {
    color: #64748b;
    font-size: 13px;
    margin: 0;
}

.form-group {
    margin-bottom: 21px;
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
    padding: 12px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 9px;
    background: #fff;
    color: #172033;
    font-family: inherit;
    font-size: 14px;
}

.form-group textarea {
    min-height: 110px;
    resize: vertical;
    line-height: 1.6;
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
    line-height: 1.5;
}

.tanggal-display {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 13px 15px;
    border: 1px solid #dbeafe;
    background: #eff6ff;
    border-radius: 9px;
    color: #1d4ed8;
    font-size: 14px;
    font-weight: 600;
}

.alert-success,
.alert-error {
    padding: 13px 15px;
    border-radius: 9px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-success {
    display: flex;
    align-items: center;
    gap: 9px;
    background: #dcfce7;
    color: #166534;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
}

.alert-error ul {
    margin-bottom: 0;
}

/* FOTO SAAT INI */
.current-photo {
    padding: 12px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
}

.current-photo img {
    display: block;
    max-width: 100%;
    max-height: 300px;
    border-radius: 9px;
    object-fit: contain;
}

.photo-caption {
    margin-top: 10px;
    color: #15803d;
    font-size: 12px;
    font-weight: 600;
}

.no-photo {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 18px;
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 9px;
    color: #94a3b8;
    font-size: 13px;
}

/* KAMERA */
.camera-box {
    padding: 17px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
}

.camera-description {
    display: flex;
    align-items: center;
    gap: 13px;
    margin-bottom: 16px;
}

.camera-icon {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: #dbeafe;
    color: #2563eb;
    border-radius: 11px;
    font-size: 20px;
}

.camera-description strong {
    color: #1e293b;
    font-size: 14px;
}

.camera-description p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 12px;
    line-height: 1.5;
}

.camera-preview {
    position: relative;
    width: 100%;
    min-height: 220px;
    max-height: 480px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0f172a;
    border-radius: 10px;
}

#videoKamera {
    display: none;
    width: 100%;
    max-height: 480px;
    object-fit: contain;
}

.camera-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 220px;
    color: #cbd5e1;
    font-size: 14px;
}

.camera-placeholder i {
    font-size: 42px;
}

.camera-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
    margin-top: 15px;
}

.btn-camera,
.btn-capture,
.btn-retake,
.btn-remove {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 14px;
    border: 0;
    border-radius: 8px;
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s;
}

.btn-camera {
    background: #2563eb;
}

.btn-camera:hover {
    background: #1d4ed8;
}

.btn-capture {
    background: #16a34a;
}

.btn-capture:hover {
    background: #15803d;
}

.btn-retake {
    background: #64748b;
}

.btn-retake:hover {
    background: #475569;
}

.btn-remove {
    background: #dc2626;
}

.btn-remove:hover {
    background: #b91c1c;
}

.btn-camera:disabled,
.btn-capture:disabled {
    opacity: .5;
    cursor: not-allowed;
}

.camera-status {
    margin-top: 12px;
    color: #64748b;
    font-size: 12px;
}

.new-photo-preview {
    margin-top: 17px;
    padding: 14px;
    border: 1px solid #bbf7d0;
    background: #f0fdf4;
    border-radius: 10px;
}

.preview-heading {
    margin-bottom: 12px;
    color: #15803d;
    font-size: 14px;
    font-weight: 600;
}

#previewFoto {
    display: block;
    width: 100%;
    max-width: 450px;
    max-height: 350px;
    object-fit: contain;
    border-radius: 9px;
    border: 1px solid #e2e8f0;
}

/* TOMBOL FORM */
.button-group {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 28px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.btn-save,
.btn-cancel {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 19px;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    font-size: 14px;
    text-decoration: none;
    cursor: pointer;
    transition: .2s;
}

.btn-save {
    background: #2563eb;
    color: white;
}

.btn-save:hover {
    background: #1d4ed8;
}

.btn-save:disabled {
    opacity: .6;
    cursor: not-allowed;
}

.btn-cancel {
    background: #e2e8f0;
    color: #334155;
}

.btn-cancel:hover {
    background: #cbd5e1;
}

@media(max-width:600px) {
    .edit-jurnal-container {
        padding: 14px;
    }

    .edit-jurnal-card {
        padding: 18px;
    }

    .page-heading h2 {
        font-size: 20px;
    }

    .camera-actions button {
        flex: 1;
    }

    .button-group {
        flex-direction: column-reverse;
    }

    .btn-save,
    .btn-cancel {
        width: 100%;
        box-sizing: border-box;
    }
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formEditJurnal');

    const video = document.getElementById('videoKamera');
    const canvas = document.getElementById('canvasKamera');
    const fotoInput = document.getElementById('fotobukti');

    const placeholder = document.getElementById('cameraPlaceholder');
    const previewContainer = document.getElementById('previewContainer');
    const previewFoto = document.getElementById('previewFoto');
    const cameraStatus = document.getElementById('cameraStatus');

    const btnMulai = document.getElementById('btnMulaiKamera');
    const btnAmbil = document.getElementById('btnAmbilFoto');
    const btnUlang = document.getElementById('btnUlangFoto');
    const btnHapus = document.getElementById('btnHapusFoto');

    let cameraStream = null;
    let fotoSudahDiambil = false;
    let previewUrl = null;

    const ukuranMaksimal = 5 * 1024 * 1024;

    function hentikanKamera() {

        if (cameraStream) {
            cameraStream.getTracks().forEach(function (track) {
                track.stop();
            });

            cameraStream = null;
        }

        video.srcObject = null;
        video.style.display = 'none';
    }

    function hapusPreviewUrl() {

        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }
    }

    async function mulaiKamera() {

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Browser tidak mendukung akses kamera. Gunakan browser terbaru melalui localhost atau HTTPS.');
            return;
        }

        try {

            hentikanKamera();

            cameraStatus.textContent = 'Meminta izin akses kamera...';
            btnMulai.disabled = true;

            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: {
                        ideal: 'environment'
                    },
                    width: {
                        ideal: 1280
                    },
                    height: {
                        ideal: 720
                    }
                },
                audio: false
            });

            video.srcObject = cameraStream;
            video.style.display = 'block';
            placeholder.style.display = 'none';

            await video.play();

            btnMulai.style.display = 'none';
            btnAmbil.style.display = 'inline-flex';
            btnAmbil.disabled = false;

            btnUlang.style.display = 'none';

            cameraStatus.textContent = 'Kamera aktif. Silakan ambil foto pengganti.';

        } catch (error) {

            console.error(error);

            cameraStatus.textContent = 'Kamera tidak dapat diakses.';

            alert('Tidak dapat mengakses kamera. Pastikan izin kamera diberikan pada browser.');

        } finally {

            btnMulai.disabled = false;

        }
    }

    function ambilFoto() {

        if (!cameraStream || !video.videoWidth) {
            alert('Kamera belum siap. Silakan tunggu sebentar.');
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const context = canvas.getContext('2d');

        context.drawImage(
            video,
            0,
            0,
            canvas.width,
            canvas.height
        );

        canvas.toBlob(function (blob) {

            if (!blob) {
                alert('Gagal mengambil foto. Silakan coba kembali.');
                return;
            }

            if (blob.size > ukuranMaksimal) {
                alert('Ukuran foto melebihi 5 MB. Silakan ambil foto dengan resolusi lebih rendah.');
                return;
            }

            const file = new File(
                [blob],
                'jurnal-' + Date.now() + '.jpg',
                {
                    type: 'image/jpeg'
                }
            );

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);

            fotoInput.files = dataTransfer.files;

            hapusPreviewUrl();

            previewUrl = URL.createObjectURL(blob);
            previewFoto.src = previewUrl;

            previewContainer.style.display = 'block';

            fotoSudahDiambil = true;

            hentikanKamera();

            btnAmbil.style.display = 'none';
            btnMulai.style.display = 'none';
            btnUlang.style.display = 'inline-flex';
            btnHapus.style.display = 'inline-flex';

            cameraStatus.textContent = 'Foto pengganti siap disimpan.';

        }, 'image/jpeg', 0.85);
    }

    function ambilUlang() {

        fotoInput.value = '';
        fotoSudahDiambil = false;

        previewContainer.style.display = 'none';

        hapusPreviewUrl();

        btnUlang.style.display = 'none';
        btnHapus.style.display = 'none';

        mulaiKamera();
    }

    function gunakanFotoLama() {

        fotoInput.value = '';
        fotoSudahDiambil = false;

        hentikanKamera();
        hapusPreviewUrl();

        previewContainer.style.display = 'none';
        previewFoto.src = '';

        btnMulai.style.display = 'inline-flex';
        btnAmbil.style.display = 'none';
        btnUlang.style.display = 'none';
        btnHapus.style.display = 'none';

        placeholder.style.display = 'flex';

        cameraStatus.textContent = 'Foto lama akan tetap digunakan.';

    }

    btnMulai.addEventListener('click', mulaiKamera);
    btnAmbil.addEventListener('click', ambilFoto);
    btnUlang.addEventListener('click', ambilUlang);
    btnHapus.addEventListener('click', gunakanFotoLama);

    form.addEventListener('submit', function (event) {

        const jadwal = document.getElementById('jadwalid');

        if (!jadwal.value) {
            event.preventDefault();
            alert('Silakan pilih jadwal mengajar.');
            return;
        }

        if (cameraStream) {
            event.preventDefault();
            alert('Selesaikan pengambilan foto terlebih dahulu.');
            return;
        }

    });

    window.addEventListener('beforeunload', function () {
        hentikanKamera();
        hapusPreviewUrl();
    });

});
</script>

@endsection