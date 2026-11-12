@extends('layouts.app')

@section('content')

@php
    $tanggalTampilan = \Carbon\Carbon::parse($tanggalHariIni)
        ->translatedFormat('l, d F Y');

    // Pastikan data yang digunakan berbentuk Collection.
    $siswa = $siswa ?? collect();
    $presensi = $presensi ?? collect();
    $izinDisetujui = $izinDisetujui ?? collect();

    // Samakan tipe key ID siswa agar pencarian data konsisten.
    $presensi = $presensi->keyBy(function ($item) {
        return (string) $item->siswaid;
    });

    $izinDisetujui = $izinDisetujui->keyBy(function ($item) {
        return (string) $item->siswaid;
    });
@endphp

<div class="pagehead">
    <div class="presensi-pagehead">
        <div>
            <h1>Isi Presensi Siswa</h1>
            <p>Kelola dan catat kehadiran siswa sesuai kondisi hari ini.</p>
        </div>

        <a href="{{ route('presensi') }}" class="presensi-back">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>

@if(session('success'))
    <div class="presensi-alert success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="presensi-alert error">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

{{-- INFORMASI JADWAL --}}
<div class="presensi-panel">

    <div class="panel-heading">
        <div>
            <span class="panel-label">INFORMASI JADWAL</span>

            {{-- PERBAIKAN: menggunakan namamapel --}}
            <h2>
                {{ $jadwal->namamapel ?? 'Mata Pelajaran Belum Diatur' }}
            </h2>

            <p>
                {{ $jadwal->namakelas ?? '-' }}

                @if(!empty($jadwal->namajurusan))
                    · {{ $jadwal->namajurusan }}
                @endif
            </p>
        </div>

        <div class="mapel-icon">
            <i class="fas fa-book-open"></i>
        </div>
    </div>

    <div class="jadwal-info-grid">

        <div class="jadwal-info-item">
            <div class="info-icon">
                <i class="fas fa-calendar"></i>
            </div>

            <div>
                <span>Hari</span>
                <strong>{{ $jadwal->hari ?? '-' }}</strong>
            </div>
        </div>

        <div class="jadwal-info-item">
            <div class="info-icon">
                <i class="fas fa-clock"></i>
            </div>

            <div>
                <span>Jam Pelajaran</span>
                <strong>
                    {{ $jadwal->jamMulai ?? '-' }} -
                    {{ $jadwal->jamSelesai ?? '-' }}
                </strong>
            </div>
        </div>

        <div class="jadwal-info-item">
            <div class="info-icon">
                <i class="fas fa-calendar-day"></i>
            </div>

            <div>
                <span>Tanggal Presensi</span>
                <strong>{{ $tanggalTampilan }}</strong>
            </div>
        </div>

    </div>

    <div class="presensi-notice">
        <i class="fas fa-info-circle"></i>
        Presensi dapat diubah sampai pukul 23.59 hari ini.
    </div>

</div>

{{-- DAFTAR SISWA --}}
<div class="presensi-panel">

    <div class="siswa-heading">
        <div>
            <h2>Daftar Siswa</h2>
            <p>Isi status kehadiran masing-masing siswa.</p>
        </div>

        <span class="siswa-count">
            <i class="fas fa-users"></i>
            {{ $siswa->count() }} Siswa
        </span>
    </div>

    @if($siswa->count() > 0)

        <form action="{{ route('presensi.store', $jadwal->id) }}"
              method="POST">

            @csrf

            <input type="hidden"
                   name="tanggal_presensi"
                   value="{{ $tanggalHariIni }}">

            <div class="siswa-table-header">
                <div>No.</div>
                <div>Nama Siswa</div>
                <div>Status Kehadiran</div>
            </div>

            @foreach($siswa as $index => $item)

                @php
                    $idSiswa = (string) $item->id;

                    $dataPresensi = $presensi->get($idSiswa);
                    $izinSiswa = $izinDisetujui->get($idSiswa);

                    if ($izinSiswa) {

                        $jenisIzin = strtolower(
                            trim($izinSiswa->jenis ?? 'izin')
                        );

                        $statusTerpilih = $jenisIzin === 'sakit'
                            ? 'sakit'
                            : 'izin';

                        $keteranganTerpilih = trim(
                            $izinSiswa->alasan ?? ''
                        );

                        if ($keteranganTerpilih === '') {
                            $keteranganTerpilih = ucfirst($statusTerpilih);
                        }

                    } else {

                        $statusTerpilih = old(
                            'status.' . $idSiswa,
                            $dataPresensi->status ?? 'hadir'
                        );

                        $keteranganTerpilih = old(
                            'keterangan.' . $idSiswa,
                            $dataPresensi->keterangan ?? ''
                        );
                    }
                @endphp

                <div class="siswa-item">

                    <div class="siswa-main">

                        <div class="siswa-number">
                            {{ $index + 1 }}
                        </div>

                        <div class="siswa-identity">
                            <strong>{{ $item->nama }}</strong>
                            <span>NISN: {{ $item->nisn ?? '-' }}</span>
                        </div>

                        <div class="status-wrapper">

                            <select
                                name="status[{{ $idSiswa }}]"
                                class="presensi-select {{ $izinSiswa ? 'select-locked' : '' }}"
                                {{ $izinSiswa ? 'disabled' : 'required' }}
                            >

                                <option value="hadir"
                                    {{ $statusTerpilih === 'hadir' ? 'selected' : '' }}>
                                    Hadir
                                </option>

                                <option value="sakit"
                                    {{ $statusTerpilih === 'sakit' ? 'selected' : '' }}>
                                    Sakit
                                </option>

                                <option value="izin"
                                    {{ $statusTerpilih === 'izin' ? 'selected' : '' }}>
                                    Izin
                                </option>

                                <option value="alpa"
                                    {{ $statusTerpilih === 'alpa' ? 'selected' : '' }}>
                                    Alpa
                                </option>

                            </select>

                            @if($izinSiswa)
                                <div class="izin-locked-info">
                                    <i class="fas fa-lock"></i>
                                    Izin telah disetujui
                                </div>
                            @endif

                        </div>

                    </div>

                    <div class="siswa-note">
                        <i class="fas fa-comment-alt"></i>

                        <input
                            type="text"
                            name="keterangan[{{ $idSiswa }}]"
                            value="{{ $keteranganTerpilih }}"
                            maxlength="500"
                            placeholder="Tambahkan keterangan (opsional)"
                            {{ $izinSiswa ? 'readonly' : '' }}
                            class="{{ $izinSiswa ? 'input-locked' : '' }}"
                        >
                    </div>

                    @if($izinSiswa)
                        <div class="izin-reason">
                            <i class="fas fa-info-circle"></i>
                            Keterangan berasal dari pengajuan siswa dan tidak dapat diubah.
                        </div>
                    @endif

                </div>

            @endforeach

            <div class="presensi-footer">

                <a href="{{ route('presensi') }}" class="btn-cancel">
                    Batal
                </a>

                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i>
                    Simpan Presensi
                </button>

            </div>

        </form>

    @else

        <div class="empty-siswa">
            <i class="fas fa-user-graduate"></i>

            <h3>Belum Ada Siswa</h3>

            <p>
                @if(empty($jadwal->kelasid))
                    Jadwal ini belum memiliki kelas.
                @else
                    Tidak ada siswa yang terdaftar di
                    {{ $jadwal->namakelas ?? 'kelas ini' }}.
                @endif
            </p>
        </div>

    @endif

</div>

<style>
.presensi-pagehead {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;
}

.presensi-pagehead h1 {
    margin-bottom: 6px;
}

.presensi-pagehead p {
    margin: 0;
}

.presensi-back,
.btn-cancel {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 16px;
    border: 1px solid #dce5f5;
    border-radius: 8px;
    background: #fff;
    color: #2456a6 !important;
    text-decoration: none !important;
    font-size: 13px;
    font-weight: 600;
}

.presensi-panel {
    background: #fff;
    border: 1px solid #e5eaf1;
    border-radius: 12px;
    padding: 24px;
    margin-top: 20px;
}

.panel-heading,
.siswa-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.panel-label {
    color: #718096;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .7px;
}

.panel-heading h2 {
    color: #243247;
    font-size: 21px;
    margin: 7px 0;
}

.panel-heading p,
.siswa-heading p {
    color: #7b8798;
    font-size: 13px;
    margin: 0;
}

.mapel-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #edf4ff;
    color: #2864c5;
    font-size: 20px;
}

.jadwal-info-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-top: 22px;
}

.jadwal-info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    border: 1px solid #edf0f5;
    border-radius: 9px;
    min-width: 0;
}

.info-icon {
    width: 37px;
    height: 37px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #edf4ff;
    color: #3974ce;
    border-radius: 9px;
}

.jadwal-info-item span,
.jadwal-info-item strong {
    display: block;
}

.jadwal-info-item span {
    font-size: 11px;
    color: #8591a2;
    margin-bottom: 5px;
}

.jadwal-info-item strong {
    font-size: 13px;
    color: #344054;
    overflow-wrap: anywhere;
}

.presensi-notice {
    display: flex;
    align-items: center;
    gap: 9px;
    background: #f0f6ff;
    color: #285da8;
    padding: 12px 15px;
    border-radius: 8px;
    margin-top: 15px;
    font-size: 12px;
}

.siswa-heading {
    margin-bottom: 22px;
}

.siswa-heading h2 {
    color: #243247;
    font-size: 18px;
    margin: 0 0 6px;
}

.siswa-count {
    background: #edf4ff;
    color: #2864c5;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.siswa-table-header,
.siswa-main {
    display: grid;
    grid-template-columns: 48px minmax(0, 1fr) 190px;
    gap: 15px;
    align-items: center;
}

.siswa-table-header {
    background: #f5f7fb;
    color: #66758a;
    padding: 13px 15px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
}

.siswa-item {
    padding: 17px 15px;
    border-bottom: 1px solid #edf0f5;
}

.siswa-item:last-child {
    border-bottom: 0;
}

.siswa-number {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #f0f4fa;
    color: #53657d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
}

.siswa-identity strong,
.siswa-identity span {
    display: block;
}

.siswa-identity strong {
    color: #344054;
    font-size: 13px;
}

.siswa-identity span {
    margin-top: 5px;
    color: #8994a5;
    font-size: 11px;
}

.presensi-select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #dce2ec;
    border-radius: 8px;
    background: #fff;
    color: #344054;
    font-size: 13px;
    outline: none;
}

.presensi-select:focus,
.siswa-note input:focus {
    border-color: #6b9ce8;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
}

.presensi-select.select-locked {
    background: #f0f6ff;
    color: #2456a6;
    border-color: #c7dcfa;
    font-weight: 700;
    cursor: not-allowed;
    opacity: 1;
}

.izin-locked-info {
    color: #16834b;
    font-size: 10px;
    font-weight: 600;
    margin-top: 7px;
}

.izin-locked-info i {
    margin-right: 4px;
}

.siswa-note {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 12px 0 0 63px;
    color: #9aa5b5;
}

.siswa-note input {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #e2e7ef;
    border-radius: 7px;
    background: #fff;
    font-size: 12px;
    outline: none;
    box-sizing: border-box;
}

.siswa-note input.input-locked {
    background: #f8fafc;
    border-color: #dce5f0;
    color: #475569;
    cursor: not-allowed;
}

.izin-reason {
    margin: 8px 0 0 63px;
    color: #718096;
    font-size: 11px;
}

.izin-reason i {
    color: #3974ce;
    margin-right: 4px;
}

.presensi-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid #edf0f5;
}

.btn-save {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    border: 0;
    border-radius: 8px;
    background: #2563eb;
    color: #fff;
    padding: 11px 18px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.btn-save:hover {
    background: #1d4ed8;
}

.presensi-alert {
    padding: 13px 16px;
    border-radius: 8px;
    margin-top: 15px;
    font-size: 13px;
}

.presensi-alert.error {
    background: #fff1f1;
    border: 1px solid #ffd1d1;
    color: #b42318;
}

.presensi-alert.success {
    background: #ecfdf3;
    border: 1px solid #abefc6;
    color: #067647;
}

.empty-siswa {
    text-align: center;
    padding: 55px 15px;
}

.empty-siswa > i {
    font-size: 42px;
    color: #aab6c7;
    margin-bottom: 15px;
}

.empty-siswa h3 {
    color: #344054;
    font-size: 17px;
}

.empty-siswa p {
    color: #8490a2;
    font-size: 13px;
}

@media(max-width: 700px) {
    .jadwal-info-grid {
        grid-template-columns: 1fr;
    }

    .presensi-panel {
        padding: 17px;
    }

    .siswa-table-header {
        display: none;
    }

    .siswa-main {
        grid-template-columns: 35px minmax(0, 1fr);
        gap: 10px;
    }

    .status-wrapper {
        grid-column: 2;
    }

    .siswa-note {
        margin-left: 45px;
    }

    .izin-reason {
        margin-left: 45px;
    }

    .presensi-footer {
        flex-direction: column-reverse;
    }

    .presensi-footer a,
    .presensi-footer button {
        width: 100%;
        box-sizing: border-box;
    }
}
</style>

@endsection