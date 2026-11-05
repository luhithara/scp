{{-- =========================================================
     SCP - EDIT PROFIL ORANGTUA
     File:
     resources/views/orangtua/profil-edit.blade.php
========================================================= --}}

@extends('layouts.app')

@section('title', 'Edit Profil Orangtua - SCP')

@section('page-title', 'Edit Profil Orangtua')


@push('styles')

<style>

    /* =====================================================
       HALAMAN
    ====================================================== */

    .orangtua-profil-edit-page {
        width: 100%;
    }


    /* =====================================================
       HEADER
    ====================================================== */

    .profil-edit-page-header {
        margin-bottom: 22px;
    }


    .profil-edit-page-header h1 {
        margin: 0 0 6px;

        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 24px;

        font-weight: 800;
    }


    .profil-edit-page-header p {
        margin: 0;

        color: #64748b;

        font-size: 12px;
    }


    /* =====================================================
       CARD
    ====================================================== */

    .profil-edit-card {
        width: 100%;

        max-width: 950px;

        overflow: hidden;

        border:
            1px solid #e6edf5;

        border-radius: 16px;

        background: #ffffff;

        box-shadow:
            0 4px 18px
            rgba(15, 47, 95, 0.05);
    }


    .profil-edit-card-header {
        padding: 20px 22px;

        border-bottom:
            1px solid #edf2f7;
    }


    .profil-edit-card-header h2 {
        margin: 0 0 5px;

        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 16px;

        font-weight: 800;
    }


    .profil-edit-card-header p {
        margin: 0;

        color: #64748b;

        font-size: 10px;
    }


    .profil-edit-card-body {
        padding: 22px;
    }


    /* =====================================================
       FORM GRID
    ====================================================== */

    .profil-form-grid {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 18px;
    }


    .profil-form-section {
        grid-column: 1 / -1;

        padding-bottom: 10px;

        border-bottom:
            1px solid #e7eef7;

        color: #123f68;

        font-size: 13px;

        font-weight: 800;
    }


    .profil-form-group {
        display: flex;

        flex-direction: column;

        min-width: 0;
    }


    .profil-form-group.full {
        grid-column: 1 / -1;
    }


    .profil-form-label {
        margin-bottom: 7px;

        color: #334155;

        font-size: 10px;

        font-weight: 800;
    }


    .profil-form-control {
        width: 100%;

        padding:
            11px 12px;

        border:
            1px solid #dbe5ee;

        border-radius: 9px;

        outline: none;

        background: #ffffff;

        color: #1e293b;

        font-family: inherit;

        font-size: 11px;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }


    .profil-form-control:focus {
        border-color: #1769aa;

        box-shadow:
            0 0 0 3px
            rgba(
                23,
                105,
                170,
                0.10
            );
    }


    .profil-form-control.readonly {
        background: #f5f8fc;

        color: #64748b;

        cursor: not-allowed;
    }


    .profil-form-help {
        margin-top: 6px;

        color: #94a3b8;

        font-size: 9px;

        line-height: 1.5;
    }


    /* =====================================================
       ERROR
    ====================================================== */

    .profil-edit-error {
        display: flex;

        align-items: flex-start;

        gap: 10px;

        margin-bottom: 20px;

        padding: 13px 15px;

        border:
            1px solid #f3caca;

        border-radius: 10px;

        background: #fff3f3;

        color: #a62d2d;

        font-size: 10px;

        line-height: 1.6;
    }


    .profil-edit-error i {
        margin-top: 2px;

        font-size: 13px;
    }


    .profil-edit-error ul {
        margin: 5px 0 0;

        padding-left: 17px;
    }


    /* =====================================================
       BUTTON
    ====================================================== */

    .profil-edit-button-area {
        display: flex;

        align-items: center;

        justify-content: flex-end;

        gap: 10px;

        margin-top: 24px;

        padding-top: 20px;

        border-top:
            1px solid #e7eef7;
    }


    .profil-edit-button {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        min-width: 120px;

        padding:
            11px 16px;

        border: none;

        border-radius: 9px;

        cursor: pointer;

        font-family: inherit;

        font-size: 10px;

        font-weight: 800;

        text-decoration: none;

        transition:
            all 0.2s ease;
    }


    .profil-edit-button.cancel {
        background: #f1f5f9;

        color: #64748b;
    }


    .profil-edit-button.cancel:hover {
        background: #e8edf2;
    }


    .profil-edit-button.save {
        background: #1769aa;

        color: #ffffff;
    }


    .profil-edit-button.save:hover {
        background: #12598f;

        transform:
            translateY(-1px);
    }


    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 700px) {

        .profil-edit-page-header h1 {
            font-size: 21px;
        }


        .profil-form-grid {
            grid-template-columns: 1fr;
        }


        .profil-form-group.full {
            grid-column: auto;
        }


        .profil-edit-card-body {
            padding: 17px;
        }


        .profil-edit-button-area {
            flex-direction: column-reverse;
        }


        .profil-edit-button {
            width: 100%;
        }

    }

</style>

@endpush


@section('content')

<div class="orangtua-profil-edit-page">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="profil-edit-page-header">

        <h1>
            Edit Profil Orangtua
        </h1>

        <p>
            Perbarui informasi akun dan data orangtua.
        </p>

    </div>


    {{-- =====================================================
         CARD
    ====================================================== --}}

    <section class="profil-edit-card">


        <div class="profil-edit-card-header">

            <h2>
                Edit Profil
            </h2>

            <p>
                Data yang diubah di halaman ini adalah
                data akun Orangtua.
            </p>

        </div>


        <div class="profil-edit-card-body">


            {{-- =================================================
                 ERROR
            ================================================== --}}

            @if($errors->any())

                <div class="profil-edit-error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <div>

                        <strong>
                            Data belum dapat disimpan.
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


            {{-- =================================================
                 FORM
            ================================================== --}}

            <form
                action="{{ route('orangtua.profil.update') }}"
                method="POST"
            >

                @csrf

                @method('PUT')


                <div class="profil-form-grid">


                    {{-- =================================================
                         DATA ORANGTUA
                    ================================================== --}}

                    <div class="profil-form-section">

                        Data Orangtua

                    </div>


                    {{-- NAMA --}}

                    <div class="profil-form-group">

                        <label
                            for="nama"
                            class="profil-form-label"
                        >
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            class="profil-form-control"
                            value="{{
                                old(
                                    'nama',
                                    $orangtua->nama
                                    ?? $user->name
                                )
                            }}"
                            required
                        >

                    </div>


                    {{-- HUBUNGAN --}}

                    <div class="profil-form-group">

                        <label
                            for="hubungan"
                            class="profil-form-label"
                        >
                            Hubungan dengan Anak
                        </label>

                        <select
                            id="hubungan"
                            name="hubungan"
                            class="profil-form-control"
                            required
                        >

                            <option value="">
                                -- Pilih Hubungan --
                            </option>


                            <option
                                value="ayah"
                                @selected(
                                    old(
                                        'hubungan',
                                        $orangtua->hubungan ?? ''
                                    ) === 'ayah'
                                )
                            >
                                Ayah
                            </option>


                            <option
                                value="ibu"
                                @selected(
                                    old(
                                        'hubungan',
                                        $orangtua->hubungan ?? ''
                                    ) === 'ibu'
                                )
                            >
                                Ibu
                            </option>


                            <option
                                value="wali"
                                @selected(
                                    old(
                                        'hubungan',
                                        $orangtua->hubungan ?? ''
                                    ) === 'wali'
                                )
                            >
                                Wali
                            </option>


                        </select>

                    </div>


                    {{-- NOMOR TELEPON --}}

                    <div class="profil-form-group">

                        <label
                            for="nomortelepon"
                            class="profil-form-label"
                        >
                            Nomor Telepon
                        </label>

                        <input
                            type="text"
                            id="nomortelepon"
                            name="nomortelepon"
                            class="profil-form-control"
                            value="{{
                                old(
                                    'nomortelepon',
                                    $orangtua->nomortelepon ?? ''
                                )
                            }}"
                            placeholder="Contoh: 089500000000"
                        >

                    </div>


                    {{-- ALAMAT --}}

                    <div class="profil-form-group">

                        <label
                            for="alamat"
                            class="profil-form-label"
                        >
                            Alamat
                        </label>

                        <input
                            type="text"
                            id="alamat"
                            name="alamat"
                            class="profil-form-control"
                            value="{{
                                old(
                                    'alamat',
                                    $orangtua->alamat ?? ''
                                )
                            }}"
                            placeholder="Masukkan alamat"
                        >

                    </div>


                    {{-- =================================================
                         AKUN LOGIN
                    ================================================== --}}

                    <div class="profil-form-section">

                        Akun Login

                    </div>


                    {{-- EMAIL --}}

                    <div class="profil-form-group full">

                        <label
                            for="email"
                            class="profil-form-label"
                        >
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="profil-form-control"
                            value="{{
                                old(
                                    'email',
                                    $user->email
                                )
                            }}"
                            required
                        >

                        <div class="profil-form-help">

                            Email digunakan untuk akun Orangtua.

                        </div>

                    </div>


                    {{-- IDENTITAS --}}

                    <div class="profil-form-group">

                        <label class="profil-form-label">
                            Identitas Akun
                        </label>

                        <input
                            type="text"
                            class="profil-form-control readonly"
                            value="NISN: {{ $user->nisn ?? '-' }}"
                            readonly
                        >

                        <div class="profil-form-help">

                            NISN tidak dapat diubah dari halaman ini.

                        </div>

                    </div>


                    {{-- ROLE --}}

                    <div class="profil-form-group">

                        <label class="profil-form-label">
                            Role
                        </label>

                        <input
                            type="text"
                            class="profil-form-control readonly"
                            value="Orangtua"
                            readonly
                        >

                        <div class="profil-form-help">

                            Role ditentukan oleh sistem.

                        </div>

                    </div>


                    {{-- =================================================
                         PASSWORD
                    ================================================== --}}

                    <div class="profil-form-section">

                        Ganti Password

                    </div>


                    {{-- PASSWORD --}}

                    <div class="profil-form-group">

                        <label
                            for="password"
                            class="profil-form-label"
                        >
                            Password Baru
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="profil-form-control"
                            placeholder="Kosongkan jika tidak ingin mengubah"
                        >

                        <div class="profil-form-help">

                            Minimal 6 karakter.

                        </div>

                    </div>


                    {{-- KONFIRMASI PASSWORD --}}

                    <div class="profil-form-group">

                        <label
                            for="password_confirmation"
                            class="profil-form-label"
                        >
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="profil-form-control"
                            placeholder="Ulangi password baru"
                        >

                    </div>


                </div>


                {{-- =================================================
                     BUTTON
                ================================================== --}}

                <div class="profil-edit-button-area">


                    <a
                        href="{{ route('orangtua.profil') }}"
                        class="profil-edit-button cancel"
                    >

                        <i class="fa-solid fa-arrow-left"></i>

                        <span>
                            Batal
                        </span>

                    </a>


                    <button
                        type="submit"
                        class="profil-edit-button save"
                    >

                        <i class="fa-solid fa-floppy-disk"></i>

                        <span>
                            Simpan Perubahan
                        </span>

                    </button>


                </div>


            </form>


        </div>

    </section>


</div>

@endsection