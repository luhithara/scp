@extends('layouts.app')

@section('title', 'Edit Profil - SCP')

@section('content')

<style>
    /* =========================================================
       EDIT PROFIL - SCHOOL CLASS PROGRESS
    ========================================================= */

    .edit-profil-page {
        width: 100%;
        max-width: 1050px;
        margin: 0 auto;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .edit-profil-header {
        margin-bottom: 24px;
    }

    .edit-profil-header h1 {
        margin: 0 0 7px;

        color: #172033;

        font-size: 28px;
        line-height: 1.25;
        font-weight: 800;
        letter-spacing: -0.4px;
    }

    .edit-profil-header p {
        margin: 0;

        color: #718096;

        font-size: 13px;
        line-height: 1.6;
    }

    /* =========================================================
       ERROR
    ========================================================= */

    .edit-profil-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;

        margin-bottom: 20px;
        padding: 15px 17px;

        border: 1px solid #fecaca;
        border-radius: 12px;

        background: #fff7f7;
        color: #991b1b;

        font-size: 13px;
        line-height: 1.55;
    }

    .edit-profil-alert-icon {
        width: 32px;
        height: 32px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #fee2e2;
        color: #dc2626;
    }

    .edit-profil-alert-icon i {
        font-size: 14px;
    }

    .edit-profil-alert-content strong {
        display: block;

        margin-bottom: 5px;

        font-weight: 700;
    }

    .edit-profil-alert-content ul {
        margin: 5px 0 0 18px;
        padding: 0;
    }

    .edit-profil-alert-content li {
        margin-bottom: 2px;
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .edit-profil-card {
        overflow: hidden;

        border: 1px solid #e3eaf2;
        border-radius: 20px;

        background: #ffffff;

        box-shadow:
            0 5px 18px rgba(15, 23, 42, 0.04);
    }

    /* =========================================================
       CARD HEADER
    ========================================================= */

    .edit-profil-card-header {
        display: flex;
        align-items: center;
        gap: 13px;

        padding: 23px 26px;

        border-bottom: 1px solid #edf1f6;
    }

    .edit-profil-card-icon {
        width: 42px;
        height: 42px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #edf4ff;
        color: #2563eb;
    }

    .edit-profil-card-icon i {
        font-size: 17px;
    }

    .edit-profil-card-header h2 {
        margin: 0 0 3px;

        color: #172033;

        font-size: 16px;
        font-weight: 750;
    }

    .edit-profil-card-header p {
        margin: 0;

        color: #8a97a8;

        font-size: 12px;
        line-height: 1.5;
    }

    /* =========================================================
       FORM
    ========================================================= */

    .edit-profil-form {
        padding: 28px 26px 26px;
    }

    .edit-profil-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 20px;
    }

    .edit-field {
        min-width: 0;
    }

    .edit-field.full {
        grid-column: 1 / -1;
    }

    .edit-field label {
        display: block;

        margin-bottom: 8px;

        color: #334155;

        font-size: 13px;
        font-weight: 650;
    }

    .edit-field-label {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .required-mark {
        color: #dc2626;
    }

    /* =========================================================
       INPUT
    ========================================================= */

    .edit-input,
    .edit-select,
    .edit-textarea {
        width: 100%;

        border: 1px solid #dbe2ea;
        border-radius: 11px;

        outline: none;

        background: #ffffff;
        color: #172033;

        font-family: inherit;
        font-size: 13px;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
    }

    .edit-input,
    .edit-select {
        height: 46px;

        padding: 0 14px;
    }

    .edit-textarea {
        min-height: 110px;

        padding: 12px 14px;

        resize: vertical;

        line-height: 1.6;
    }

    .edit-input::placeholder,
    .edit-textarea::placeholder {
        color: #a0aec0;
    }

    .edit-input:hover,
    .edit-select:hover,
    .edit-textarea:hover {
        border-color: #b9c7d9;
    }

    .edit-input:focus,
    .edit-select:focus,
    .edit-textarea:focus {
        border-color: #2563eb;

        box-shadow:
            0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    /* =========================================================
       READONLY
    ========================================================= */

    .edit-input.readonly {
        background: #f5f7fa;
        color: #8a97a8;

        cursor: not-allowed;
    }

    /* =========================================================
       HELPER TEXT
    ========================================================= */

    .edit-helper {
        display: flex;
        align-items: flex-start;
        gap: 6px;

        margin-top: 7px;

        color: #8a97a8;

        font-size: 11px;
        line-height: 1.55;
    }

    .edit-helper i {
        margin-top: 2px;

        color: #94a3b8;
    }

    /* =========================================================
       SECTION PEMISAH
    ========================================================= */

    .edit-section-title {
        grid-column: 1 / -1;

        display: flex;
        align-items: center;
        gap: 10px;

        margin-top: 4px;
        padding-bottom: 10px;

        border-bottom: 1px solid #edf1f6;
    }

    .edit-section-title i {
        color: #2563eb;

        font-size: 13px;
    }

    .edit-section-title span {
        color: #334155;

        font-size: 12px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    /* =========================================================
       FORM ACTION
    ========================================================= */

    .edit-profil-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;

        margin-top: 28px;
        padding-top: 22px;

        border-top: 1px solid #edf1f6;
    }

    .edit-btn {
        min-height: 43px;

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        padding: 0 17px;

        border-radius: 10px;

        font-family: inherit;
        font-size: 13px;
        font-weight: 650;

        text-decoration: none;

        cursor: pointer;

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease,
            border-color 0.2s ease;
    }

    .edit-btn-cancel {
        border: 1px solid #dbe2ea;

        background: #ffffff;
        color: #64748b;
    }

    .edit-btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .edit-btn-save {
        border: 0;

        background:
            linear-gradient(
                135deg,
                #2563eb,
                #1554b5
            );

        color: #ffffff;

        box-shadow:
            0 7px 16px rgba(37, 99, 235, 0.18);
    }

    .edit-btn-save:hover {
        transform: translateY(-1px);

        box-shadow:
            0 10px 22px rgba(37, 99, 235, 0.24);
    }

    .edit-btn i {
        font-size: 12px;
    }

    /* =========================================================
       ROLE INFORMATION
    ========================================================= */

    .role-info {
        grid-column: 1 / -1;

        display: flex;
        align-items: flex-start;
        gap: 12px;

        padding: 14px 16px;

        border: 1px solid #dbe7fb;
        border-radius: 11px;

        background: #f5f8ff;
    }

    .role-info-icon {
        width: 30px;
        height: 30px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        background: #e6efff;
        color: #2563eb;
    }

    .role-info-icon i {
        font-size: 12px;
    }

    .role-info-text {
        color: #64748b;

        font-size: 11px;
        line-height: 1.6;
    }

    .role-info-text strong {
        color: #334155;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 700px) {

        .edit-profil-grid {
            grid-template-columns: 1fr;
        }

        .edit-field.full,
        .edit-section-title,
        .role-info {
            grid-column: auto;
        }

        .edit-profil-card-header {
            padding: 20px;
        }

        .edit-profil-form {
            padding: 22px 20px 20px;
        }

        .edit-profil-actions {
            flex-direction: column-reverse;
        }

        .edit-btn {
            width: 100%;
        }

    }

    @media (max-width: 480px) {

        .edit-profil-header h1 {
            font-size: 24px;
        }

        .edit-profil-header p {
            font-size: 12px;
        }

        .edit-profil-card-header {
            align-items: flex-start;
        }

    }
</style>


@php

    $data = $siswa
        ?? $guru
        ?? $waka
        ?? $bk
        ?? $kelas
        ?? null;

@endphp


<div class="edit-profil-page">


    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="edit-profil-header">

        <h1>
            Edit Profil
        </h1>

        <p>
            Perbarui informasi profil akun Anda.
        </p>

    </div>



    {{-- =========================================================
         ERROR VALIDASI
    ========================================================== --}}

    @if($errors->any())

        <div class="edit-profil-alert">

            <div class="edit-profil-alert-icon">

                <i class="fa-solid fa-triangle-exclamation"></i>

            </div>


            <div class="edit-profil-alert-content">

                <strong>
                    Ada data yang belum benar.
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif



    {{-- =========================================================
         CARD FORM
    ========================================================== --}}

    <div class="edit-profil-card">


        {{-- =====================================================
             HEADER CARD
        ====================================================== --}}

        <div class="edit-profil-card-header">

            <div class="edit-profil-card-icon">

                <i class="fa-solid fa-user-pen"></i>

            </div>


            <div>

                <h2>
                    Informasi Profil
                </h2>

                <p>
                    Ubah informasi yang ingin diperbarui.
                </p>

            </div>

        </div>



        {{-- =====================================================
             FORM
        ====================================================== --}}

        <form
            action="{{ route('profil.update') }}"
            method="POST"
            class="edit-profil-form"
        >

            @csrf

            @method('PUT')


            <div class="edit-profil-grid">


                {{-- =================================================
                     INFORMASI DASAR
                ================================================== --}}

                <div class="edit-section-title">

                    <i class="fa-solid fa-id-card"></i>

                    <span>
                        Informasi Dasar
                    </span>

                </div>



                {{-- =================================================
                     NAMA
                ================================================== --}}

                <div class="edit-field">

                    <label for="nama">

                        <span class="edit-field-label">

                            Nama

                            <span class="required-mark">
                                *
                            </span>

                        </span>

                    </label>


                    <input
                        id="nama"
                        class="edit-input"
                        type="text"
                        name="nama"
                        value="{{ old(
    'nama',
    $data?->nama ?? ''
) }}"
                        placeholder="Masukkan nama lengkap"
                        autocomplete="name"
                        required
                    >

                </div>



                {{-- =================================================
                     EMAIL
                ================================================== --}}

                <div class="edit-field">

                    <label for="email">

                        <span class="edit-field-label">

                            Email

                            <span class="required-mark">
                                *
                            </span>

                        </span>

                    </label>


                    <input
                        id="email"
                        class="edit-input"
                        type="email"
                        name="email"
                        value="{{ old(
                            'email',
                            $user->email ?? ''
                        ) }}"
                        placeholder="Masukkan email"
                        autocomplete="email"
                        required
                    >


                    <div class="edit-helper">

                        <i class="fa-solid fa-circle-info"></i>

                        <span>
                            Email digunakan sebagai informasi akun Anda.
                        </span>

                    </div>

                </div>



                {{-- =================================================
                     SISWA
                ================================================== --}}

                @if($siswa)

                    <div class="edit-section-title">

                        <i class="fa-solid fa-user-graduate"></i>

                        <span>
                            Data Siswa
                        </span>

                    </div>


                    {{-- JENIS KELAMIN --}}

                    <div class="edit-field">

                        <label for="jeniskelamin">

                            <span class="edit-field-label">

                                Jenis Kelamin

                                <span class="required-mark">
                                    *
                                </span>

                            </span>

                        </label>


                        <select
                            id="jeniskelamin"
                            class="edit-select"
                            name="jeniskelamin"
                            required
                        >

                            <option
                                value="L"
                                @selected(
                                    old(
                                        'jeniskelamin',
                                        $siswa->jeniskelamin
                                    ) === 'L'
                                )
                            >
                                Laki-laki
                            </option>

                            <option
                                value="P"
                                @selected(
                                    old(
                                        'jeniskelamin',
                                        $siswa->jeniskelamin
                                    ) === 'P'
                                )
                            >
                                Perempuan
                            </option>

                        </select>

                    </div>



                    {{-- TANGGAL LAHIR --}}

                    <div class="edit-field">

                        <label for="tanggallahir">
                            Tanggal Lahir
                        </label>


                        <input
                            id="tanggallahir"
                            class="edit-input"
                            type="date"
                            name="tanggallahir"
                            value="{{ old(
                                'tanggallahir',
                                $siswa->tanggallahir
                            ) }}"
                        >

                    </div>



                    {{-- TEMPAT LAHIR --}}

                    <div class="edit-field">

                        <label for="tempatlahir">
                            Tempat Lahir
                        </label>


                        <input
                            id="tempatlahir"
                            class="edit-input"
                            type="text"
                            name="tempatlahir"
                            value="{{ old(
                                'tempatlahir',
                                $siswa->tempatlahir
                            ) }}"
                            placeholder="Masukkan tempat lahir"
                        >

                    </div>



                    {{-- NOMOR TELEPON --}}

                    <div class="edit-field">

                        <label for="nomortelepon">
                            Nomor Telepon
                        </label>


                        <input
                            id="nomortelepon"
                            class="edit-input"
                            type="text"
                            name="nomortelepon"
                            value="{{ old(
                                'nomortelepon',
                                $siswa->nomortelepon
                            ) }}"
                            placeholder="Masukkan nomor telepon"
                        >

                    </div>



                    {{-- ALAMAT --}}

                    <div class="edit-field full">

                        <label for="alamat">
                            Alamat
                        </label>


                        <textarea
                            id="alamat"
                            class="edit-textarea"
                            name="alamat"
                            placeholder="Masukkan alamat lengkap"
                        >{{ old(
                            'alamat',
                            $siswa->alamat
                        ) }}</textarea>

                    </div>

                @endif



                {{-- =================================================
                     GURU
                ================================================== --}}

                @if($guru)

                    <div class="edit-section-title">

                        <i class="fa-solid fa-chalkboard-user"></i>

                        <span>
                            Data Guru
                        </span>

                    </div>


                    {{-- NIP --}}

                    <div class="edit-field">

                        <label for="nip">
                            NIP
                        </label>


                        <input
                            id="nip"
                            class="edit-input"
                            type="text"
                            name="nip"
                            value="{{ old(
                                'nip',
                                $guru->nip ?? ''
                            ) }}"
                            placeholder="Masukkan NIP"
                        >

                    </div>



                    {{-- NOMOR TELEPON --}}

                    <div class="edit-field">

                        <label for="nomortelepon">
                            Nomor Telepon
                        </label>


                        <input
                            id="nomortelepon"
                            class="edit-input"
                            type="text"
                            name="nomortelepon"
                            value="{{ old(
                                'nomortelepon',
                                $guru->nomortelepon ?? ''
                            ) }}"
                            placeholder="Masukkan nomor telepon"
                        >

                    </div>



                    {{-- ALAMAT --}}

                    <div class="edit-field full">

                        <label for="alamat">
                            Alamat
                        </label>


                        <textarea
                            id="alamat"
                            class="edit-textarea"
                            name="alamat"
                            placeholder="Masukkan alamat lengkap"
                        >{{ old(
                            'alamat',
                            $guru->alamat ?? ''
                        ) }}</textarea>

                    </div>

                @endif



                {{-- =================================================
                     WAKA
                ================================================== --}}

                @if($waka)

                    <div class="edit-section-title">

                        <i class="fa-solid fa-user-tie"></i>

                        <span>
                            Informasi Waka
                        </span>

                    </div>


                    {{-- KODE WAKA --}}

                    <div class="edit-field">

                        <label for="kodewaka">
                            Kode Waka
                        </label>


                        <input
                            id="kodewaka"
                            class="edit-input readonly"
                            type="text"
                            value="{{ $waka->kodewaka ?? '-' }}"
                            readonly
                        >


                        <div class="edit-helper">

                            <i class="fa-solid fa-lock"></i>

                            <span>
                                Kode Waka tidak dapat diubah melalui profil.
                            </span>

                        </div>

                    </div>

                @endif



                {{-- =================================================
                     BK
                ================================================== --}}

                @if($bk)

                    <div class="edit-section-title">

                        <i class="fa-solid fa-user-shield"></i>

                        <span>
                            Informasi BK
                        </span>

                    </div>


                    {{-- KODE BK --}}

                    <div class="edit-field">

                        <label for="kodebk">
                            Kode BK
                        </label>


                        <input
                            id="kodebk"
                            class="edit-input readonly"
                            type="text"
                            value="{{ $bk->kodebk ?? '-' }}"
                            readonly
                        >


                        <div class="edit-helper">

                            <i class="fa-solid fa-lock"></i>

                            <span>
                                Kode BK tidak dapat diubah melalui profil.
                            </span>

                        </div>

                    </div>



                    {{-- STATUS --}}

                    <div class="edit-field">

                        <label for="status">
                            Status
                        </label>


                        <input
                            id="status"
                            class="edit-input readonly"
                            type="text"
                            value="{{
                                $bk->status === 'aktif'
                                    ? 'Aktif'
                                    : 'Tidak Aktif'
                            }}"
                            readonly
                        >

                    </div>

                @endif



                {{-- =================================================
                     INFO ROLE
                ================================================== --}}

                <div class="role-info">

                    <div class="role-info-icon">

                        <i class="fa-solid fa-shield-halved"></i>

                    </div>


                    <div class="role-info-text">

                        <strong>
                            Informasi keamanan:
                        </strong>

                        Data seperti role, NISN, kode pegawai,
                        kode Waka, dan kode BK dikelola oleh sistem
                        dan tidak dapat diubah melalui halaman ini.

                    </div>

                </div>


            </div>



            {{-- =====================================================
                 BUTTON
            ====================================================== --}}

            <div class="edit-profil-actions">

                <a
                    href="{{ route('profil') }}"
                    class="edit-btn edit-btn-cancel"
                >

                    <i class="fa-solid fa-arrow-left"></i>

                    <span>
                        Batal
                    </span>

                </a>


                <button
                    type="submit"
                    class="edit-btn edit-btn-save"
                >

                    <i class="fa-solid fa-check"></i>

                    <span>
                        Simpan Perubahan
                    </span>

                </button>

            </div>


        </form>

    </div>

</div>

@endsection