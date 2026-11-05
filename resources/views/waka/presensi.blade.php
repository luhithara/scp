@extends('layouts.app')

@section('title', 'Presensi Siswa - SCP')
@section('page-title', 'Presensi Siswa')
@section('page-subtitle', 'Monitoring kehadiran siswa')

@php
    $dataPresensi = $dataPresensi ?? collect();
    $dataJurusan = $dataJurusan ?? collect();
    $dataKelas = $dataKelas ?? collect();

    $jurusanId = $jurusanId ?? request('jurusanid');
    $tingkat = $tingkat ?? request('tingkat');
    $kelasId = $kelasId ?? request('kelasid');
    $search = $search ?? request('search');
    $tanggal = $tanggal ?? request('tanggal');
@endphp

@push('styles')
<style>
    .presence-page {
        max-width: 1500px;
        margin: auto;
    }

    .intro {
        margin-bottom: 22px;
    }

    .intro h2 {
        margin: 0;
        font-size: 22px;
        color: #172033;
    }

    .intro p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 13px;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .stat {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 12px;
        padding: 18px;
    }

    .label {
        color: #718096;
        font-size: 11px;
        margin-bottom: 7px;
    }

    .value {
        font-size: 25px;
        font-weight: 700;
        color: #172033;
    }

    .filter {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 14px;
        padding: 17px;
        margin-bottom: 18px;
    }

    .filter-title {
        margin-bottom: 14px;
    }

    .filter-title h3 {
        margin: 0;
        color: #172033;
        font-size: 14px;
    }

    .filter-title p {
        margin: 4px 0 0;
        color: #94a3b8;
        font-size: 11px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr 1.2fr 1fr 1fr auto;
        gap: 10px;
        align-items: end;
    }

    .field label {
        display: block;
        margin-bottom: 6px;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
    }

    .field input,
    .field select {
        width: 100%;
        height: 39px;
        border: 1px solid #dce3ec;
        border-radius: 8px;
        padding: 0 11px;
        outline: none;
        font-size: 12px;
        background: #fff;
        color: #334155;
    }

    .field input:focus,
    .field select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
    }

    .btn-area {
        display: flex;
        gap: 7px;
    }

    .btn {
        height: 39px;
        border: 0;
        border-radius: 8px;
        padding: 0 16px;
        background: #2563eb;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn:hover {
        background: #1d4ed8;
    }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 39px;
        padding: 0 13px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-reset:hover {
        background: #e2e8f0;
    }

    .filter-info {
        margin-top: 12px;
        padding: 10px 12px;
        background: #f8fafc;
        border: 1px solid #edf1f6;
        border-radius: 8px;
        color: #64748b;
        font-size: 11px;
    }

    .table-card {
        background: #fff;
        border: 1px solid #e7edf5;
        border-radius: 14px;
        overflow: hidden;
    }

    .table-head {
        padding: 17px 20px;
        border-bottom: 1px solid #edf1f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-head h3 {
        margin: 0;
        color: #172033;
        font-size: 15px;
    }

    .table-head p {
        margin: 4px 0 0;
        color: #7b8798;
        font-size: 12px;
    }

    .count {
        background: #f1f5f9;
        color: #475569;
        padding: 5px 9px;
        border-radius: 6px;
        font-size: 11px;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    th {
        padding: 11px 15px;
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        text-align: left;
        border-bottom: 1px solid #e8edf3;
    }

    td {
        padding: 12px 15px;
        border-bottom: 1px solid #eef2f6;
        color: #374151;
        font-size: 12px;
    }

    tbody tr:hover {
        background: #fafcff;
    }

    .student {
        color: #1e293b;
        font-weight: 600;
    }

    .sub {
        color: #94a3b8;
        font-size: 11px;
        margin-top: 3px;
    }

    .badge {
        display: inline-flex;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 600;
    }

    .hadir {
        background: #f0fdf4;
        color: #15803d;
    }

    .sakit {
        background: #fff7ed;
        color: #c2410c;
    }

    .izin {
        background: #eff6ff;
        color: #2563eb;
    }

    .alpa {
        background: #fef2f2;
        color: #b91c1c;
    }

    .unknown {
        background: #f1f5f9;
        color: #64748b;
    }

    .empty {
        text-align: center;
        color: #94a3b8;
        padding: 35px !important;
    }

    .kelas-disabled {
        background: #f8fafc !important;
        color: #94a3b8 !important;
    }

    @media(max-width:1200px) {
        .filter-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .btn-area {
            grid-column: span 3;
        }

        .btn,
        .btn-reset {
            flex: 1;
        }
    }

    @media(max-width:800px) {
        .stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .btn-area {
            grid-column: auto;
        }

        .btn {
            width: 100%;
        }

        .btn-reset {
            width: 100%;
        }
    }

    @media(max-width:500px) {
        .stats {
            grid-template-columns: 1fr;
        }

        .table-head {
            align-items: flex-start;
            gap: 10px;
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')

<div class="presence-page">

    {{-- =========================================================
         JUDUL
    ========================================================== --}}
    <div class="intro">
        <h2>Presensi Siswa</h2>
        <p>Monitoring dan riwayat kehadiran siswa berdasarkan jurusan, angkatan, dan kelas.</p>
    </div>


    {{-- =========================================================
         STATISTIK
    ========================================================== --}}
    <div class="stats">

        <div class="stat">
            <div class="label">Hadir</div>
            <div class="value">{{ $jumlahHadir ?? 0 }}</div>
        </div>

        <div class="stat">
            <div class="label">Sakit</div>
            <div class="value">{{ $jumlahSakit ?? 0 }}</div>
        </div>

        <div class="stat">
            <div class="label">Izin</div>
            <div class="value">{{ $jumlahIzin ?? 0 }}</div>
        </div>

        <div class="stat">
            <div class="label">Alpa</div>
            <div class="value">{{ $jumlahAlpa ?? 0 }}</div>
        </div>

    </div>


    {{-- =========================================================
         FILTER
    ========================================================== --}}
    <div class="filter">

        <div class="filter-title">
            <h3>Filter Riwayat Presensi</h3>
            <p>Pilih jurusan, angkatan, dan kelas untuk mempersempit data.</p>
        </div>

        <form
            method="GET"
            action="{{ route('waka.presensi') }}"
            id="formFilterPresensi"
        >

            <div class="filter-grid">

                {{-- JURUSAN --}}
                <div class="field">

                    <label for="jurusanid">
                        Jurusan
                    </label>

                    <select
                        name="jurusanid"
                        id="jurusanid"
                    >

                        <option value="">
                            Semua Jurusan
                        </option>

                        @foreach($dataJurusan as $jurusan)

                            <option
                                value="{{ $jurusan->id }}"
                                {{ (string)$jurusanId === (string)$jurusan->id ? 'selected' : '' }}
                            >
                                {{ $jurusan->namajurusan }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ANGKATAN / TINGKAT --}}
                <div class="field">

                    <label for="tingkat">
                        Angkatan
                    </label>

                    <select
                        name="tingkat"
                        id="tingkat"
                    >

                        <option value="">
                            Semua Angkatan
                        </option>

                        <option
                            value="10"
                            {{ (string)$tingkat === '10' ? 'selected' : '' }}
                        >
                            X
                        </option>

                        <option
                            value="11"
                            {{ (string)$tingkat === '11' ? 'selected' : '' }}
                        >
                            XI
                        </option>

                        <option
                            value="12"
                            {{ (string)$tingkat === '12' ? 'selected' : '' }}
                        >
                            XII
                        </option>

                    </select>

                </div>


                {{-- KELAS --}}
                <div class="field">

                    <label for="kelasid">
                        Kelas
                    </label>

                    <select
                        name="kelasid"
                        id="kelasid"
                        {{ empty($jurusanId) && empty($tingkat) ? 'disabled' : '' }}
                    >

                        <option value="">
                            Semua Kelas
                        </option>

                        @foreach($dataKelas as $kelas)

                            <option
                                value="{{ $kelas->id }}"
                                data-jurusan="{{ $kelas->jurusanid }}"
                                data-tingkat="{{ $kelas->tingkat }}"
                                {{ (string)$kelasId === (string)$kelas->id ? 'selected' : '' }}
                            >
                                {{ $kelas->namakelas }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- PENCARIAN --}}
                <div class="field">

                    <label for="search">
                        Pencarian
                    </label>

                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ $search }}"
                        placeholder="Nama / NISN siswa..."
                    >

                </div>


                {{-- TANGGAL --}}
                <div class="field">

                    <label for="tanggal">
                        Tanggal
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        id="tanggal"
                        value="{{ $tanggal }}"
                    >

                </div>


                {{-- TOMBOL --}}
                <div class="btn-area">

                    <button
                        class="btn"
                        type="submit"
                    >
                        Terapkan
                    </button>

                    <a
                        href="{{ route('waka.presensi') }}"
                        class="btn-reset"
                    >
                        Reset
                    </a>

                </div>

            </div>

        </form>

        {{-- KETERANGAN FILTER --}}
        <div class="filter-info" id="filterInfo">
            @if($jurusanId || $tingkat || $kelasId || $search || $tanggal)

                Filter sedang diterapkan.
                Menampilkan {{ count($dataPresensi) }} data presensi.

            @else

                Silakan pilih filter untuk mempersempit data riwayat presensi.

            @endif
        </div>

    </div>


    {{-- =========================================================
         TABEL RIWAYAT
    ========================================================== --}}
    <div class="table-card">

        <div class="table-head">

            <div>
                <h3>Riwayat Presensi</h3>

                <p>
                    Data kehadiran siswa sesuai filter yang dipilih.
                </p>
            </div>

            <span class="count">
                {{ count($dataPresensi) }} data
            </span>

        </div>


        <div class="table-wrap">

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Jurusan</th>
                        <th>Angkatan</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($dataPresensi as $index => $presensi)

                        @php

                            $status = strtolower(
                                $presensi->status ?? ''
                            );

                            $statusClass = match($status) {

                                'hadir' => 'hadir',

                                'sakit' => 'sakit',

                                'izin' => 'izin',

                                'alpa' => 'alpa',

                                default => 'unknown'

                            };

                        @endphp


                        <tr>

                            {{-- NOMOR --}}
                            <td>
                                {{ $index + 1 }}
                            </td>


                            {{-- SISWA --}}
                            <td>

                                <div class="student">
                                    {{ $presensi->namasiswa ?? 'Siswa tidak ditemukan' }}
                                </div>

                                @if(!empty($presensi->nisn))

                                    <div class="sub">
                                        NISN {{ $presensi->nisn }}
                                    </div>

                                @endif

                            </td>


                            {{-- JURUSAN --}}
                            <td>
                                {{ $presensi->namajurusan ?? '-' }}
                            </td>


                            {{-- ANGKATAN --}}
                            <td>

                                @if(isset($presensi->tingkat))

                                    @if($presensi->tingkat == 10)
                                        X
                                    @elseif($presensi->tingkat == 11)
                                        XI
                                    @elseif($presensi->tingkat == 12)
                                        XII
                                    @else
                                        {{ $presensi->tingkat }}
                                    @endif

                                @else
                                    -
                                @endif

                            </td>


                            {{-- KELAS --}}
                            <td>
                                {{ $presensi->namakelas ?? '-' }}
                            </td>


                            {{-- STATUS --}}
                            <td>

                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($status ?: 'Tidak diketahui') }}
                                </span>

                            </td>


                            {{-- TANGGAL --}}
                            <td>

                                @if(!empty($presensi->created_at))

                                    {{ \Carbon\Carbon::parse($presensi->created_at)->format('d/m/Y') }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- WAKTU --}}
                            <td>

                                @if(!empty($presensi->created_at))

                                    {{ \Carbon\Carbon::parse($presensi->created_at)->format('H:i') }}

                                @else

                                    -

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="empty"
                            >
                                Tidak ada data presensi yang sesuai dengan filter.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =========================================================
     JAVASCRIPT FILTER KELAS
========================================================== --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const jurusanSelect =
        document.getElementById('jurusanid');

    const tingkatSelect =
        document.getElementById('tingkat');

    const kelasSelect =
        document.getElementById('kelasid');


    /*
    |--------------------------------------------------------------------------
    | SIMPAN SEMUA PILIHAN KELAS
    |--------------------------------------------------------------------------
    */

    const semuaKelas =
        Array.from(
            kelasSelect.querySelectorAll('option[data-jurusan]')
        );


    /*
    |--------------------------------------------------------------------------
    | FUNGSI FILTER KELAS
    |--------------------------------------------------------------------------
    */

    function filterKelas() {

        const jurusan =
            jurusanSelect.value;

        const tingkat =
            tingkatSelect.value;

        const nilaiSebelumnya =
            kelasSelect.value;


        /*
        |--------------------------------------------------------------------------
        | HAPUS OPTION LAMA
        |--------------------------------------------------------------------------
        */

        semuaKelas.forEach(function (option) {

            option.remove();

        });


        /*
        |--------------------------------------------------------------------------
        | CEK FILTER
        |--------------------------------------------------------------------------
        */

        const hasil =
            semuaKelas.filter(function (option) {

                const optionJurusan =
                    option.dataset.jurusan;

                const optionTingkat =
                    option.dataset.tingkat;


                const cocokJurusan =
                    !jurusan ||
                    optionJurusan === jurusan;


                const cocokTingkat =
                    !tingkat ||
                    optionTingkat === tingkat;


                return cocokJurusan && cocokTingkat;

            });


        /*
        |--------------------------------------------------------------------------
        | MASUKKAN KEMBALI KELAS YANG SESUAI
        |--------------------------------------------------------------------------
        */

        hasil.forEach(function (option) {

            kelasSelect.appendChild(option);

        });


        /*
        |--------------------------------------------------------------------------
        | AKTIFKAN / NONAKTIFKAN DROPDOWN
        |--------------------------------------------------------------------------
        */

        if (jurusan || tingkat) {

            kelasSelect.disabled = false;

            kelasSelect.classList.remove(
                'kelas-disabled'
            );

        } else {

            kelasSelect.disabled = true;

            kelasSelect.classList.add(
                'kelas-disabled'
            );

            kelasSelect.value = '';

        }


        /*
        |--------------------------------------------------------------------------
        | KEMBALIKAN PILIHAN SEBELUMNYA
        |--------------------------------------------------------------------------
        */

        if (
            hasil.some(function (option) {
                return option.value === nilaiSebelumnya;
            })
        ) {

            kelasSelect.value =
                nilaiSebelumnya;

        } else {

            kelasSelect.value = '';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | EVENT PERUBAHAN JURUSAN
    |--------------------------------------------------------------------------
    */

    jurusanSelect.addEventListener(
        'change',
        function () {

            filterKelas();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EVENT PERUBAHAN ANGKATAN
    |--------------------------------------------------------------------------
    */

    tingkatSelect.addEventListener(
        'change',
        function () {

            filterKelas();

        }
    );

    /*
    |--------------------------------------------------------------------------
    | JALANKAN SAAT HALAMAN DIBUKA
    |--------------------------------------------------------------------------
    */
    filterKelas();
});
</script>
@endsection