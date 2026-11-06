
@extends('layouts.app')

@section('content')

<div class="content">

    {{-- FORM JURNAL BARU --}}
    <div class="box">

        <div class="page-title">
            <div>
                <h1>📖 Jurnal Mengajar</h1>
                <p>Isi jurnal pembelajaran sesuai jadwal mengajar hari ini.</p>
            </div>
        </div>

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

        @if($jadwal->isEmpty())
            <div class="alert warning">
                <strong>Belum ada jadwal mengajar hari ini.</strong>
                <p>Jurnal hanya dapat dibuat apabila Anda memiliki jadwal mengajar pada hari ini.</p>
            </div>
        @endif

        <form
            id="formJurnal"
            action="{{ route('jurnal.guru.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            {{-- JADWAL --}}
            <div class="form-group">
                <label for="jadwalid">Jadwal Mengajar</label>

                <select name="jadwalid" id="jadwalid" required {{ $jadwal->isEmpty() ? 'disabled' : '' }}>
                    <option value="">-- Pilih Mata Pelajaran --</option>

                    @foreach($jadwal as $item)
                        <option
                            value="{{ $item->id }}"
                            {{ old('jadwalid') == $item->id ? 'selected' : '' }}
                        >
                            {{ $item->namamapel ?? 'Mata Pelajaran' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TANGGAL OTOMATIS --}}
            <div class="form-group">
                <label>Tanggal Jurnal</label>

                <div class="tanggal-display">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>
                        {{ \Carbon\Carbon::parse($tanggalHariIni ?? now()->toDateString())->locale('id')->translatedFormat('l, d F Y') }}
                    </span>
                </div>

                <small class="form-help">
                    Tanggal jurnal ditentukan otomatis oleh sistem.
                </small>
            </div>

            {{-- MATERI --}}
            <div class="form-group">
                <label for="materi">Materi Pembelajaran</label>

                <textarea
                    name="materi"
                    id="materi"
                    required
                    placeholder="Masukkan materi yang diajarkan..."
                >{{ old('materi') }}</textarea>
            </div>

            {{-- KEGIATAN --}}
            <div class="form-group">
                <label for="kegiatan">Kegiatan Pembelajaran</label>

                <textarea
                    name="kegiatan"
                    id="kegiatan"
                    required
                    placeholder="Jelaskan kegiatan pembelajaran yang dilakukan..."
                >{{ old('kegiatan') }}</textarea>
            </div>

            {{-- CATATAN --}}
            <div class="form-group">
                <label for="catatan">Catatan Tambahan</label>

                <textarea
                    name="catatan"
                    id="catatan"
                    placeholder="Masukkan catatan tambahan jika ada..."
                >{{ old('catatan') }}</textarea>
            </div>

            {{-- FOTO KAMERA --}}
            <div class="form-group">
                <label>Foto Bukti Pembelajaran</label>

                <div class="camera-box">

                    <div class="camera-description">
                        <div class="camera-icon">
                            <i class="fa-solid fa-camera"></i>
                        </div>

                        <div>
                            <strong>Ambil Foto dengan Kamera</strong>
                            <p>
                                Foto harus diambil secara langsung menggunakan kamera perangkat.
                                Galeri tidak dapat digunakan.
                            </p>
                        </div>
                    </div>

                    <div class="camera-preview" id="cameraPreview">
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
                            {{ $jadwal->isEmpty() ? 'disabled' : '' }}
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
                    </div>

                    <div class="camera-status" id="cameraStatus">
                        Silakan aktifkan kamera untuk mengambil foto.
                    </div>

                    <div id="previewContainer" class="preview-container" style="display:none;">
                        <div class="preview-heading">
                            <i class="fa-solid fa-circle-check"></i>
                            Foto berhasil diambil
                        </div>

                        <img
                            id="previewFoto"
                            src=""
                            alt="Preview foto bukti pembelajaran"
                        >
                    </div>

                    <input
                        type="file"
                        name="fotobukti"
                        id="fotobukti"
                        accept="image/jpeg,image/png,image/webp"
                        hidden
                    >

                </div>
            </div>

            <button
                type="submit"
                class="btn-submit"
                id="btnSimpan"
                {{ $jadwal->isEmpty() ? 'disabled' : '' }}
            >
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Jurnal
            </button>

        </form>

    </div>


    {{-- RIWAYAT JURNAL --}}
    <div class="box">

        <div class="history-heading">
            <div>
                <h2>Riwayat Jurnal</h2>
                <p>Daftar jurnal mengajar yang sudah dibuat.</p>
            </div>
        </div>

        @forelse($jurnal as $item)

            <div class="jurnal">

                <div class="jurnal-header">
                    <div>
                        <span class="jurnal-tanggal">
                            <i class="fa-regular fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}
                        </span>

                        <h3>{{ $item->namamapel ?? '-' }}</h3>

                        <p class="jurnal-kelas">
                            <i class="fa-solid fa-users"></i>
                            {{ $item->namakelas ?? '-' }}
                        </p>
                    </div>

                    @if(\Carbon\Carbon::parse($item->tanggal)->toDateString() === ($tanggalHariIni ?? now()->toDateString()))
                        <span class="status-jurnal status-hari-ini">
                            <i class="fa-solid fa-unlock"></i>
                            Hari ini
                        </span>
                    @else
                        <span class="status-jurnal status-terkunci">
                            <i class="fa-solid fa-lock"></i>
                            Terkunci
                        </span>
                    @endif
                </div>

                <div class="jurnal-content">

                    <div class="jurnal-section">
                        <strong>Materi</strong>
                        <p>{{ $item->materi }}</p>
                    </div>

                    @if($item->kegiatan)
                        <div class="jurnal-section">
                            <strong>Kegiatan Pembelajaran</strong>
                            <p>{{ $item->kegiatan }}</p>
                        </div>
                    @endif

                    @if($item->catatan)
                        <div class="jurnal-section">
                            <strong>Catatan</strong>
                            <p>{{ $item->catatan }}</p>
                        </div>
                    @endif

                    @if($item->fotobukti)
                        <div class="foto-riwayat">
                            <strong>Foto Bukti Pembelajaran</strong>

                            <img
                                src="{{ asset('storage/' . $item->fotobukti) }}"
                                alt="Foto bukti pembelajaran"
                                loading="lazy"
                            >
                        </div>
                    @endif

                </div>

                @if(\Carbon\Carbon::parse($item->tanggal)->toDateString() === ($tanggalHariIni ?? now()->toDateString()))
                    <div class="aksi-jurnal">
                        <a
                            href="{{ route('jurnal.guru.edit', $item->id) }}"
                            class="btn-edit"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                            Edit Jurnal
                        </a>
                    </div>
                @else
                    <div class="jurnal-locked-info">
                        <i class="fa-solid fa-lock"></i>
                        Jurnal dari tanggal sebelumnya tidak dapat diedit.
                    </div>
                @endif

            </div>

        @empty

            <div class="empty">
                <i class="fa-regular fa-folder-open"></i>
                <p>Belum ada jurnal mengajar.</p>
            </div>

        @endforelse

        @if(method_exists($jurnal, 'links') && $jurnal->hasPages())
            <div class="pagination-wrapper">
                {{ $jurnal->appends(request()->except('riwayat_page'))->links() }}
            </div>
        @endif

    </div>

</div>


<style>
* {
    box-sizing: border-box;
}

.content {
    width: 100%;
    max-width: 1100px;
    padding: 25px;
    margin: auto;
    color: #1e293b;
}

.box {
    background: #fff;
    padding: 25px;
    border-radius: 14px;
    margin-bottom: 22px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .06);
}

.page-title h1 {
    margin: 0;
    font-size: 25px;
    font-weight: 700;
    color: #1e293b;
}

.page-title p,
.history-heading p {
    margin: 7px 0 0;
    color: #64748b;
    font-size: 14px;
}

.history-heading h2 {
    margin: 0;
    font-size: 21px;
    color: #1e293b;
}

.form-group {
    margin-top: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 14px;
    color: #334155;
}

.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 9px;
    background: #fff;
    color: #1e293b;
    font-size: 14px;
    font-family: inherit;
    transition: .2s;
}

.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
}

.form-group textarea {
    min-height: 105px;
    resize: vertical;
    line-height: 1.6;
}

.form-help {
    display: block;
    margin-top: 7px;
    color: #64748b;
    font-size: 12px;
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

.tanggal-display i {
    font-size: 17px;
}

.alert {
    padding: 13px 15px;
    border-radius: 9px;
    margin-top: 18px;
    font-size: 14px;
}

.alert p {
    margin: 6px 0 0;
}

.alert.success {
    color: #166534;
    background: #dcfce7;
    border: 1px solid #bbf7d0;
}

.alert.error {
    color: #991b1b;
    background: #fee2e2;
    border: 1px solid #fecaca;
}

.alert.warning {
    color: #92400e;
    background: #fef3c7;
    border: 1px solid #fde68a;
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
    gap: 10px;
    margin-top: 15px;
}

.btn-camera,
.btn-capture,
.btn-retake {
    border: 0;
    padding: 11px 16px;
    border-radius: 8px;
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
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

.preview-container {
    margin-top: 18px;
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

/* TOMBOL SIMPAN */
.btn-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 23px;
    padding: 12px 21px;
    border: 0;
    border-radius: 9px;
    background: #2563eb;
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s;
}

.btn-submit:hover {
    background: #1d4ed8;
}

.btn-submit:disabled {
    background: #94a3b8;
    cursor: not-allowed;
}

/* RIWAYAT */
.jurnal {
    margin-top: 17px;
    padding: 19px;
    border: 1px solid #e2e8f0;
    border-radius: 11px;
    background: #fff;
}

.jurnal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
}

.jurnal-tanggal {
    color: #64748b;
    font-size: 12px;
}

.jurnal-header h3 {
    margin: 8px 0 5px;
    font-size: 17px;
    color: #1e293b;
}

.jurnal-kelas {
    margin: 0;
    color: #64748b;
    font-size: 13px;
}

.status-jurnal {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.status-hari-ini {
    background: #dcfce7;
    color: #166534;
}

.status-terkunci {
    background: #f1f5f9;
    color: #64748b;
}

.jurnal-content {
    margin-top: 17px;
    border-top: 1px solid #e2e8f0;
    padding-top: 15px;
}

.jurnal-section {
    margin-bottom: 14px;
}

.jurnal-section strong,
.foto-riwayat strong {
    display: block;
    margin-bottom: 6px;
    color: #334155;
    font-size: 13px;
}

.jurnal-section p {
    margin: 0;
    color: #475569;
    font-size: 14px;
    line-height: 1.7;
    white-space: pre-line;
}

.foto-riwayat {
    margin-top: 15px;
}

.foto-riwayat img {
    display: block;
    width: 100%;
    max-width: 450px;
    max-height: 350px;
    object-fit: contain;
    border-radius: 9px;
    border: 1px solid #e2e8f0;
}

.aksi-jurnal {
    margin-top: 16px;
}

.btn-edit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 9px 15px;
    background: #2563eb;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: .2s;
}

.btn-edit:hover {
    background: #1d4ed8;
    color: white;
}

.jurnal-locked-info {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-top: 15px;
    padding-top: 13px;
    border-top: 1px solid #e2e8f0;
    color: #94a3b8;
    font-size: 12px;
}

.empty {
    padding: 35px 15px;
    text-align: center;
    color: #94a3b8;
}

.empty i {
    font-size: 30px;
}

.empty p {
    margin-bottom: 0;
}

.pagination-wrapper {
    margin-top: 20px;
}

@media(max-width: 600px) {
    .content {
        padding: 14px;
    }

    .box {
        padding: 17px;
    }

    .page-title h1 {
        font-size: 21px;
    }

    .camera-actions button {
        flex: 1;
    }

    .jurnal-header {
        flex-direction: column;
    }

    .btn-submit {
        width: 100%;
    }
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formJurnal');
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
    const btnSimpan = document.getElementById('btnSimpan');

    let cameraStream = null;
    let fotoSudahDiambil = false;

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

    function perbaruiTombolSimpan() {
        btnSimpan.disabled = !fotoSudahDiambil;
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

            btnAmbil.style.display = 'inline-flex';
            btnAmbil.disabled = false;

            btnMulai.style.display = 'none';
            btnUlang.style.display = 'none';

            previewContainer.style.display = 'none';

            fotoSudahDiambil = false;
            fotoInput.value = '';

            perbaruiTombolSimpan();

            cameraStatus.textContent = 'Kamera aktif. Silakan ambil foto pembelajaran.';

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

            previewFoto.src = URL.createObjectURL(blob);
            previewContainer.style.display = 'block';

            fotoSudahDiambil = true;
            perbaruiTombolSimpan();

            hentikanKamera();

            btnAmbil.style.display = 'none';
            btnMulai.style.display = 'none';
            btnUlang.style.display = 'inline-flex';

            cameraStatus.textContent = 'Foto berhasil diambil dan siap disimpan.';

        }, 'image/jpeg', 0.85);
    }

    function ambilUlang() {

        fotoInput.value = '';
        fotoSudahDiambil = false;

        previewContainer.style.display = 'none';
        previewFoto.src = '';

        perbaruiTombolSimpan();

        mulaiKamera();
    }

    btnMulai.addEventListener('click', mulaiKamera);
    btnAmbil.addEventListener('click', ambilFoto);
    btnUlang.addEventListener('click', ambilUlang);

    form.addEventListener('submit', function (event) {

        if (!fotoSudahDiambil || !fotoInput.files.length) {
            event.preventDefault();

            alert('Silakan ambil foto menggunakan kamera sebelum menyimpan jurnal.');
            return;
        }

        if (!document.getElementById('jadwalid').value) {
            event.preventDefault();

            alert('Silakan pilih jadwal mengajar terlebih dahulu.');
            return;
        }

        hentikanKamera();
    });

    window.addEventListener('beforeunload', hentikanKamera);

    perbaruiTombolSimpan();

});
</script>

@endsection