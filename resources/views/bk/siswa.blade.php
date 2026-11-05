@extends('layouts.app')

@section('title', 'Data Siswa - BK | SCP')

@section('content')

<style>
    .bk-siswa-page {
        width: 100%;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .bk-page-header {
        margin-bottom: 20px;
    }

    .bk-page-header h1 {
        margin: 0;
        color: #172033;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -.4px;
    }

    .bk-page-header p {
        margin: 7px 0 0;
        color: #718096;
        font-size: 14px;
        line-height: 1.6;
    }


    /* =========================================================
       CARD
    ========================================================= */

    .bk-card {
        background: #fff;
        border: 1px solid #e6ebf2;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
    }

    .bk-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf1f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .bk-card-header h3 {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 800;
    }

    .bk-card-header p {
        margin: 5px 0 0;
        color: #8a94a6;
        font-size: 12px;
    }

    .bk-card-body {
        padding: 20px;
    }


    /* =========================================================
       ALERT
    ========================================================= */

    .bk-alert {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 13px 15px;
        margin-bottom: 16px;
        border-radius: 12px;
        font-size: 13px;
        line-height: 1.5;
    }

    .bk-alert-success {
        background: #ecfdf3;
        border: 1px solid #d1fadf;
        color: #087443;
    }

    .bk-alert-icon {
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: -2px;
        border-radius: 9px;
    }

    .bk-alert-success .bk-alert-icon {
        background: #d1fadf;
    }


    /* =========================================================
       FILTER
    ========================================================= */

    .bk-filter-grid {
        display: grid;
        grid-template-columns: minmax(220px, 2fr) minmax(150px, 1fr) minmax(150px, 1fr) auto;
        gap: 12px;
        align-items: end;
    }

    .bk-field {
        min-width: 0;
    }

    .bk-field label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
    }

    .bk-input,
    .bk-select {
        width: 100%;
        min-height: 42px;
        padding: 10px 12px;
        box-sizing: border-box;
        border: 1px solid #d9e0e9;
        border-radius: 10px;
        outline: none;
        background: #fff;
        color: #273142;
        font-size: 13px;
        transition: .2s ease;
    }

    .bk-input::placeholder {
        color: #a0aaba;
    }

    .bk-input:focus,
    .bk-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .10);
    }

    .bk-filter-actions {
        display: flex;
        gap: 8px;
    }

    .bk-btn {
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 15px;
        border-radius: 10px;
        border: 0;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        transition: .2s ease;
    }

    .bk-btn-primary {
        background: #2563eb;
        color: #fff;
    }

    .bk-btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .bk-btn-secondary {
        background: #f5f7fa;
        border: 1px solid #e2e7ee;
        color: #475467;
    }

    .bk-btn-secondary:hover {
        background: #eef2f7;
    }


    /* =========================================================
       HASIL HEADER
    ========================================================= */

    .bk-result-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 15px;
        margin: 22px 0 12px;
    }

    .bk-result-header h2 {
        margin: 0;
        color: #172033;
        font-size: 18px;
        font-weight: 800;
    }

    .bk-result-header p {
        margin: 5px 0 0;
        color: #8a94a6;
        font-size: 12px;
    }


    /* =========================================================
       BADGE
    ========================================================= */

    .bk-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-height: 27px;
        padding: 5px 9px;
        border-radius: 8px;
        background: #f3f5f8;
        color: #475467;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .bk-badge-info {
        background: #eff8ff;
        color: #175cd3;
    }

    .bk-badge-success {
        background: #ecfdf3;
        color: #087443;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .bk-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .bk-table {
        width: 100%;
        min-width: 950px;
        border-collapse: collapse;
    }

    .bk-table th {
        padding: 13px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e9ef;
        color: #667085;
        font-size: 11px;
        font-weight: 800;
        text-align: left;
        white-space: nowrap;
    }

    .bk-table td {
        padding: 14px;
        border-bottom: 1px solid #edf0f4;
        color: #344054;
        font-size: 12px;
        vertical-align: middle;
    }

    .bk-table tbody tr {
        transition: background .15s ease;
    }

    .bk-table tbody tr:hover {
        background: #fafcff;
    }

    .bk-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .bk-number {
        color: #98a2b3;
        font-weight: 700;
    }

    .bk-nisn {
        color: #344054;
        font-weight: 700;
        white-space: nowrap;
    }

    .bk-name {
        color: #172033;
        font-weight: 800;
    }

    .bk-jurusan {
        color: #172033;
        font-weight: 800;
    }

    .bk-jurusan-name {
        margin-top: 3px;
        color: #98a2b3;
        font-size: 10px;
    }

    .bk-detail-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 11px;
        border: 1px solid #dbe3ee;
        border-radius: 9px;
        background: #fff;
        color: #2563eb;
        text-decoration: none;
        font-size: 11px;
        font-weight: 800;
        transition: .2s ease;
    }

    .bk-detail-btn:hover {
        border-color: #93c5fd;
        background: #eff6ff;
    }


    /* =========================================================
       EMPTY
    ========================================================= */

    .bk-empty {
        padding: 55px 20px !important;
        text-align: center;
    }

    .bk-empty-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 20px;
    }

    .bk-empty-title {
        margin: 0;
        color: #344054;
        font-size: 14px;
        font-weight: 800;
    }

    .bk-empty-description {
        margin: 6px 0 0;
        color: #98a2b3;
        font-size: 12px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 950px) {
        .bk-filter-grid {
            grid-template-columns: 1fr 1fr;
        }

        .bk-filter-actions {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 700px) {
        .bk-filter-grid {
            grid-template-columns: 1fr;
        }

        .bk-filter-actions {
            grid-column: auto;
        }

        .bk-filter-actions .bk-btn {
            flex: 1;
        }

        .bk-result-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .bk-card-body {
            padding: 15px;
        }

        .bk-card-header {
            padding: 15px;
        }

        .bk-page-header h1 {
            font-size: 22px;
        }
    }

    @media (max-width: 450px) {
        .bk-filter-actions {
            flex-direction: column;
        }

        .bk-filter-actions .bk-btn {
            width: 100%;
        }
    }
</style>


<div class="bk-siswa-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="bk-page-header">

        <h1>
            Data Siswa
        </h1>

        <p>
            Menampilkan siswa dari kelas yang menjadi
            tanggung jawab BK {{ $bk->nama }}.
        </p>

    </div>


    {{-- =====================================================
         PESAN SUKSES
    ====================================================== --}}

    @if(session('success'))

        <div class="bk-alert bk-alert-success">

            <div class="bk-alert-icon">
                <i class="fa-solid fa-check"></i>
            </div>

            <div>
                {{ session('success') }}
            </div>

        </div>

    @endif


    {{-- =====================================================
         FILTER
    ====================================================== --}}

    <div class="bk-card">

        <div class="bk-card-header">

            <div>

                <h3>
                    Filter Data Siswa
                </h3>

                <p>
                    Cari berdasarkan nama, NISN, jurusan, atau tingkat.
                </p>

            </div>

        </div>


        <div class="bk-card-body">

            <form
                method="GET"
                action="{{ route('bk.siswa') }}"
            >

                <div class="bk-filter-grid">

                    {{-- CARI --}}

                    <div class="bk-field">

                        <label for="cari">
                            Cari Siswa
                        </label>

                        <input
                            id="cari"
                            type="text"
                            name="cari"
                            value="{{ request('cari', '') }}"
                            class="bk-input"
                            placeholder="Masukkan nama atau NISN..."
                        >

                    </div>


                    {{-- JURUSAN --}}

                    <div class="bk-field">

                        <label for="jurusan">
                            Jurusan
                        </label>

                        <select
                            id="jurusan"
                            name="jurusan"
                            class="bk-select"
                        >

                            <option value="">
                                Semua Jurusan
                            </option>

                            @foreach($jurusan as $item)

                                <option
                                    value="{{ $item->id }}"
                                    {{ request('jurusan') == $item->id ? 'selected' : '' }}
                                >

                                    {{ $item->kodejurusan }}

                                    @if(!empty($item->namajurusan))
                                        - {{ $item->namajurusan }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- TINGKAT --}}

                    <div class="bk-field">

                        <label for="tingkat">
                            Tingkat
                        </label>

                        <select
                            id="tingkat"
                            name="tingkat"
                            class="bk-select"
                        >

                            <option value="">
                                Semua Tingkat
                            </option>

                            <option
                                value="10"
                                {{ request('tingkat') == '10' ? 'selected' : '' }}
                            >
                                Kelas 10
                            </option>

                            <option
                                value="11"
                                {{ request('tingkat') == '11' ? 'selected' : '' }}
                            >
                                Kelas 11
                            </option>

                            <option
                                value="12"
                                {{ request('tingkat') == '12' ? 'selected' : '' }}
                            >
                                Kelas 12
                            </option>

                        </select>

                    </div>


                    {{-- TOMBOL --}}

                    <div class="bk-filter-actions">

                        <button
                            type="submit"
                            class="bk-btn bk-btn-primary"
                        >
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Cari
                        </button>

                        <a
                            href="{{ route('bk.siswa') }}"
                            class="bk-btn bk-btn-secondary"
                        >
                            <i class="fa-solid fa-rotate-left"></i>
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         HASIL
    ====================================================== --}}

    <div class="bk-result-header">

        <div>

            <h2>
                Daftar Siswa
            </h2>

            <p>
                Total {{ $dataSiswa->count() }} siswa
            </p>

        </div>


        @if(request('cari') || request('jurusan') || request('tingkat'))

            <span class="bk-badge bk-badge-info">

                <i class="fa-solid fa-filter"></i>

                Filter aktif

            </span>

        @endif

    </div>


    {{-- =====================================================
         TABEL
    ====================================================== --}}

    <div class="bk-card">

        <div class="bk-table-wrapper">

            <table class="bk-table">

                <thead>

                    <tr>

                        <th>
                            No
                        </th>

                        <th>
                            NISN
                        </th>

                        <th>
                            Nama Siswa
                        </th>

                        <th>
                            Kelas
                        </th>

                        <th>
                            Tingkat
                        </th>

                        <th>
                            Jurusan
                        </th>

                        <th>
                            L/P
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($dataSiswa as $index => $siswa)

                        <tr>

                            {{-- NOMOR --}}

                            <td>
                                <span class="bk-number">
                                    {{ $index + 1 }}
                                </span>
                            </td>


                            {{-- NISN --}}

                            <td>

                                <span class="bk-nisn">
                                    {{ $siswa->nisn ?? '-' }}
                                </span>

                            </td>


                            {{-- NAMA --}}

                            <td>

                                <span class="bk-name">
                                    {{ $siswa->nama }}
                                </span>

                            </td>


                            {{-- KELAS --}}

                            <td>

                                <span class="bk-badge">

                                    <i class="fa-solid fa-school"></i>

                                    {{ $siswa->namakelas ?? '-' }}

                                </span>

                            </td>


                            {{-- TINGKAT --}}

                            <td>

                                {{ $siswa->tingkat ?? '-' }}

                            </td>


                            {{-- JURUSAN --}}

                            <td>

                                @if(!empty($siswa->kodejurusan))

                                    <div class="bk-jurusan">

                                        {{ $siswa->kodejurusan }}

                                    </div>

                                    @if(!empty($siswa->namajurusan))

                                        <div class="bk-jurusan-name">

                                            {{ $siswa->namajurusan }}

                                        </div>

                                    @endif

                                @else

                                    <span class="bk-muted">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- JENIS KELAMIN --}}

                            <td>

                                @if(
                                    isset($siswa->jeniskelamin)
                                    && strtolower($siswa->jeniskelamin) === 'l'
                                )

                                    <span class="bk-badge bk-badge-info">
                                        L
                                    </span>

                                @elseif(
                                    isset($siswa->jeniskelamin)
                                    && strtolower($siswa->jeniskelamin) === 'p'
                                )

                                    <span class="bk-badge">
                                        P
                                    </span>

                                @else

                                    {{ $siswa->jeniskelamin ?? '-' }}

                                @endif

                            </td>


                            {{-- AKSI --}}

                            <td>

                                <a
                                    href="{{ route('bk.siswa.detail', $siswa->id) }}"
                                    class="bk-detail-btn"
                                >

                                    <i class="fa-solid fa-eye"></i>

                                    Detail

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="bk-empty"
                            >

                                <div class="bk-empty-icon">

                                    <i class="fa-solid fa-user-group"></i>

                                </div>

                                <p class="bk-empty-title">
                                    Tidak ada data siswa
                                </p>

                                <p class="bk-empty-description">
                                    Belum ada siswa yang sesuai
                                    dengan filter yang dipilih.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection