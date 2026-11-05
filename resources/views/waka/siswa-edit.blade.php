{{-- =========================================================
     SCP - EDIT DATA SISWA WAKA
     File:
     resources/views/waka/siswa-edit.blade.php
========================================================= --}}

@extends('layouts.app')


@section('title', 'Edit Siswa - SCP')

@section('page-title', 'Edit Data Siswa')


@section('content')


<div class="page-header">

    <h1>
        Edit Data Siswa
    </h1>

    <p>
        Perbarui informasi siswa yang tersimpan dalam sistem.
    </p>

</div>


{{-- =========================================================
     INFORMASI SISWA
========================================================= --}}

<div class="card">

    <div class="card-header">

        <div>

            <h3>
                Informasi Siswa
            </h3>

            <p>
                Silakan periksa kembali data sebelum menyimpan perubahan.
            </p>

        </div>


        <a
            href="{{ route('waka.siswa') }}"
            class="btn btn-secondary"
        >

            <i class="fa-solid fa-arrow-left"></i>

            Kembali

        </a>

    </div>


    <div class="card-body">


        <form
            method="POST"
            action="{{ route('waka.siswa.update', $siswa->id) }}"
        >

            @csrf

            @method('PUT')


            <div class="form-grid">


                {{-- =================================================
                     NISN
                ================================================== --}}

                <div class="form-group">

                    <label class="form-label">
                        NISN
                    </label>

                    <input
                        type="text"
                        name="nisn"
                        class="form-control"
                        value="{{ old('nisn', $siswa->nisn) }}"
                        maxlength="20"
                        required
                    >

                </div>


                {{-- =================================================
                     NAMA
                ================================================== --}}

                <div class="form-group">

                    <label class="form-label">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="{{ old('nama', $siswa->nama) }}"
                        required
                    >

                </div>


                {{-- =================================================
                     JENIS KELAMIN
                ================================================== --}}

                <div class="form-group">

                    <label class="form-label">
                        Jenis Kelamin
                    </label>

                    <select
                        name="jeniskelamin"
                        class="form-control"
                        required
                    >

                        <option value="">
                            Pilih jenis kelamin
                        </option>

                        <option
                            value="L"
                            {{
                                old(
                                    'jeniskelamin',
                                    $siswa->jeniskelamin
                                ) === 'L'
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            Laki-laki
                        </option>

                        <option
                            value="P"
                            {{
                                old(
                                    'jeniskelamin',
                                    $siswa->jeniskelamin
                                ) === 'P'
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            Perempuan
                        </option>

                    </select>

                </div>


                {{-- =================================================
                     KELAS
                ================================================== --}}

                <div class="form-group">

                    <label class="form-label">
                        Kelas
                    </label>


                    <select
                        name="kelasid"
                        class="form-control"
                        required
                    >

                        <option value="">
                            Pilih kelas
                        </option>


                        @php

                            $daftarKelas = DB::table('kelas')
                                ->leftJoin(
                                    'jurusan',
                                    'jurusan.id',
                                    '=',
                                    'kelas.jurusanid'
                                )
                                ->select(
                                    'kelas.id',
                                    'kelas.namakelas',
                                    'kelas.tingkat',
                                    'jurusan.kodejurusan'
                                )
                                ->orderBy('kelas.tingkat')
                                ->orderBy('kelas.namakelas')
                                ->get();

                        @endphp


                        @foreach($daftarKelas as $kelas)

                            <option
                                value="{{ $kelas->id }}"
                                {{
                                    old(
                                        'kelasid',
                                        $siswa->kelasid
                                    ) == $kelas->id
                                        ? 'selected'
                                        : ''
                                }}
                            >

                                {{ $kelas->namakelas }}

                                @if($kelas->kodejurusan)

                                    -
                                    {{ $kelas->kodejurusan }}

                                @endif

                                @if($kelas->tingkat)

                                    (Kelas
                                    {{ $kelas->tingkat }}
                                    )

                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- =================================================
                     TEMPAT LAHIR
                ================================================== --}}

                <div class="form-group">

                    <label class="form-label">
                        Tempat Lahir
                    </label>

                    <input
                        type="text"
                        name="tempatlahir"
                        class="form-control"
                        value="{{ old('tempatlahir', $siswa->tempatlahir) }}"
                    >

                </div>


                {{-- =================================================
                     TANGGAL LAHIR
                ================================================== --}}

                <div class="form-group">

                    <label class="form-label">
                        Tanggal Lahir
                    </label>

                    <input
                        type="date"
                        name="tanggallahir"
                        class="form-control"
                        value="{{ old('tanggallahir', $siswa->tanggallahir) }}"
                    >

                </div>


                {{-- =================================================
                     NOMOR TELEPON
                ================================================== --}}

                <div class="form-group">

                    <label class="form-label">
                        Nomor Telepon
                    </label>

                    <input
                        type="text"
                        name="nomortelepon"
                        class="form-control"
                        value="{{ old('nomortelepon', $siswa->nomortelepon) }}"
                    >

                </div>


                {{-- =================================================
                     ALAMAT
                ================================================== --}}

                <div class="form-group full">

                    <label class="form-label">
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        class="form-control"
                        rows="4"
                    >{{ old('alamat', $siswa->alamat) }}</textarea>

                </div>


            </div>


            {{-- =================================================
                 TOMBOL
            ================================================== --}}

            <div
                style="
                    display:flex;
                    justify-content:flex-end;
                    align-items:center;
                    gap:8px;
                    margin-top:8px;
                "
            >

                <a
                    href="{{ route('waka.siswa') }}"
                    class="btn btn-secondary"
                >

                    <i class="fa-solid fa-xmark"></i>

                    Batal

                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="fa-solid fa-floppy-disk"></i>

                    Simpan Perubahan

                </button>

            </div>


        </form>


    </div>

</div>


@endsection