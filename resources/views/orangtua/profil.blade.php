{{-- =========================================================
     SCP - PROFIL ORANGTUA
     File:
     resources/views/orangtua/profil.blade.php
========================================================= --}}

@extends('layouts.app')

@section('title', 'Profil Orangtua - SCP')

@section('page-title', 'Profil Orangtua')


@push('styles')

<style>

    /* =====================================================
       HALAMAN
    ====================================================== */

    .orangtua-profil-page {
        width: 100%;
    }


    /* =====================================================
       HEADER
    ====================================================== */

    .profil-page-header {
        display: flex;

        align-items: flex-start;

        justify-content: space-between;

        gap: 20px;

        margin-bottom: 22px;
    }

    .profil-page-header h1 {
        margin: 0 0 6px;

        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 24px;

        font-weight: 800;
    }

    .profil-page-header p {
        margin: 0;

        color: #64748b;

        font-size: 12px;
    }


    /* =====================================================
       GRID
    ====================================================== */

    .profil-grid {
        display: grid;

        grid-template-columns:
            minmax(0, 1.25fr)
            minmax(300px, 0.75fr);

        gap: 20px;

        align-items: start;
    }


    /* =====================================================
       CARD
    ====================================================== */

    .profil-card {
        overflow: hidden;

        border:
            1px solid #e6edf5;

        border-radius: 16px;

        background: #ffffff;

        box-shadow:
            0 4px 18px
            rgba(15, 47, 95, 0.05);
    }


    .profil-card-header {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        padding: 19px 21px;

        border-bottom:
            1px solid #edf2f7;
    }


    .profil-card-title h2 {
        margin: 0 0 4px;

        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 15px;

        font-weight: 800;
    }


    .profil-card-title p {
        margin: 0;

        color: #64748b;

        font-size: 10px;
    }


    .profil-card-body {
        padding: 21px;
    }


    /* =====================================================
       EDIT BUTTON
    ====================================================== */

    .profil-edit-button {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        padding:
            9px 13px;

        border-radius: 9px;

        background: #edf6ff;

        color: #1769aa;

        text-decoration: none;

        font-size: 10px;

        font-weight: 700;

        white-space: nowrap;

        transition:
            all 0.2s ease;
    }


    .profil-edit-button:hover {
        background: #dfeef9;

        transform:
            translateY(-1px);
    }


    /* =====================================================
       ICON PROFIL
    ====================================================== */

    .profil-icon {
        width: 58px;
        height: 58px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 18px;

        border-radius: 15px;

        background:
            linear-gradient(
                135deg,
                #eaf5ff,
                #dceeff
            );

        color: #1769aa;

        font-size: 22px;
    }


    /* =====================================================
       DATA LIST
    ====================================================== */

    .profil-data-list {
        display: flex;

        flex-direction: column;
    }


    .profil-data-item {
        padding:
            14px 0;

        border-bottom:
            1px solid #edf2f7;
    }


    .profil-data-item:first-child {
        padding-top: 0;
    }


    .profil-data-item:last-child {
        padding-bottom: 0;

        border-bottom: none;
    }


    .profil-data-label {
        margin-bottom: 5px;

        color: #334155;

        font-size: 10px;

        font-weight: 800;
    }


    .profil-data-value {
        color: #64748b;

        font-size: 11px;

        line-height: 1.6;

        word-break: break-word;
    }


    /* =====================================================
       ANAK
    ====================================================== */

    .profil-anak-card {
        margin-top: 20px;
    }


    .profil-anak-header {
        display: flex;

        align-items: center;

        gap: 11px;

        margin-bottom: 17px;
    }


    .profil-anak-icon {
        width: 42px;
        height: 42px;

        display: flex;

        align-items: center;

        justify-content: center;

        flex-shrink: 0;

        border-radius: 11px;

        background: #edf6ff;

        color: #1769aa;

        font-size: 15px;
    }


    .profil-anak-title h2 {
        margin: 0;

        color: #123f68;

        font-family:
            'Plus Jakarta Sans',
            sans-serif;

        font-size: 15px;

        font-weight: 800;
    }


    .profil-anak-title p {
        margin: 3px 0 0;

        color: #64748b;

        font-size: 10px;
    }


    .profil-anak-box {
        padding: 17px;

        border:
            1px solid #e2edf8;

        border-radius: 12px;

        background: #f8fbff;
    }


    .profil-anak-name {
        margin-bottom: 14px;

        color: #123f68;

        font-size: 14px;

        font-weight: 800;
    }


    .profil-anak-info {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));

        gap: 10px;
    }


    .profil-anak-info-item {
        padding: 11px;

        border:
            1px solid #e7eef7;

        border-radius: 9px;

        background: #ffffff;
    }


    .profil-anak-info-label {
        margin-bottom: 5px;

        color: #64748b;

        font-size: 9px;
    }


    .profil-anak-info-value {
        color: #1e293b;

        font-size: 10px;

        font-weight: 800;
    }


    /* =====================================================
       STATUS
    ====================================================== */

    .profil-status {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding:
            5px 9px;

        border-radius: 20px;

        background: #e8f7ee;

        color: #16834b;

        font-size: 9px;

        font-weight: 800;
    }


    .profil-status-dot {
        width: 6px;
        height: 6px;

        border-radius: 50%;

        background: #16834b;
    }


    /* =====================================================
       EMPTY
    ====================================================== */

    .profil-empty {
        padding: 18px;

        border-radius: 11px;

        background: #f8fafc;

        color: #94a3b8;

        font-size: 10px;

        line-height: 1.6;
    }


    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 950px) {

        .profil-grid {
            grid-template-columns: 1fr;
        }

    }


    @media (max-width: 600px) {

        .profil-page-header h1 {
            font-size: 21px;
        }


        .profil-card-header {
            align-items: flex-start;

            flex-direction: column;
        }


        .profil-edit-button {
            width: 100%;
        }


        .profil-card-body {
            padding: 17px;
        }

    }


    @media (max-width: 430px) {

        .profil-anak-info {
            grid-template-columns: 1fr;
        }

    }

</style>

@endpush


@section('content')

<div class="orangtua-profil-page">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="profil-page-header">

        <div>

            <h1>
                Profil Orangtua
            </h1>

            <p>
                Informasi akun dan data orangtua.
            </p>

        </div>

    </div>


    {{-- =====================================================
         GRID
    ====================================================== --}}

    <div class="profil-grid">


        {{-- =================================================
             KOLOM KIRI
        ================================================== --}}

        <div>


            {{-- =================================================
                 INFORMASI ORANGTUA
            ================================================== --}}

            <section class="profil-card">


                <div class="profil-card-header">

                    <div class="profil-card-title">

                        <h2>
                            Informasi Orangtua
                        </h2>

                        <p>
                            Data pribadi akun orangtua.
                        </p>

                    </div>


                    <a
                        href="{{ route('orangtua.profil.edit') }}"
                        class="profil-edit-button"
                    >

                        <i class="fa-solid fa-pen"></i>

                        <span>
                            Edit Profil
                        </span>

                    </a>

                </div>


                <div class="profil-card-body">


                    <div class="profil-icon">

                        <i class="fa-solid fa-user"></i>

                    </div>


                    <div class="profil-data-list">


                        {{-- NAMA --}}

                        <div class="profil-data-item">

                            <div class="profil-data-label">
                                Nama Lengkap
                            </div>

                            <div class="profil-data-value">

                                {{
                                    $orangtua->nama
                                    ?? $user->name
                                }}

                            </div>

                        </div>


                        {{-- HUBUNGAN --}}

                        <div class="profil-data-item">

                            <div class="profil-data-label">
                                Hubungan dengan Anak
                            </div>

                            <div class="profil-data-value">

                                @if(
                                    isset($orangtua->hubungan)
                                    &&
                                    $orangtua->hubungan
                                )

                                    {{
                                        ucfirst(
                                            $orangtua->hubungan
                                        )
                                    }}

                                @else

                                    -

                                @endif

                            </div>

                        </div>


                        {{-- TELEPON --}}

                        <div class="profil-data-item">

                            <div class="profil-data-label">
                                Nomor Telepon
                            </div>

                            <div class="profil-data-value">

                                {{
                                    $orangtua->nomortelepon
                                    ?? '-'
                                }}

                            </div>

                        </div>


                        {{-- ALAMAT --}}

                        <div class="profil-data-item">

                            <div class="profil-data-label">
                                Alamat
                            </div>

                            <div class="profil-data-value">

                                {{
                                    $orangtua->alamat
                                    ?? '-'
                                }}

                            </div>

                        </div>


                    </div>

                </div>

            </section>


            {{-- =================================================
                 DATA ANAK
            ================================================== --}}

            <section class="profil-card profil-anak-card">


                <div class="profil-card-body">


                    <div class="profil-anak-header">

                        <div class="profil-anak-icon">

                            <i class="fa-solid fa-user-graduate"></i>

                        </div>


                        <div class="profil-anak-title">

                            <h2>
                                Data Anak
                            </h2>

                            <p>
                                Anak yang terhubung dengan akun ini.
                            </p>

                        </div>

                    </div>


                    @if($anak)


                        <div class="profil-anak-box">


                            <div class="profil-anak-name">

                                {{ $anak->nama }}

                            </div>


                            <div class="profil-anak-info">


                                <div class="profil-anak-info-item">

                                    <div class="profil-anak-info-label">
                                        NISN
                                    </div>

                                    <div class="profil-anak-info-value">

                                        {{ $anak->nisn ?? '-' }}

                                    </div>

                                </div>


                                <div class="profil-anak-info-item">

                                    <div class="profil-anak-info-label">
                                        Kelas
                                    </div>

                                    <div class="profil-anak-info-value">

                                        {{ $anak->namakelas ?? '-' }}

                                    </div>

                                </div>


                                <div class="profil-anak-info-item">

                                    <div class="profil-anak-info-label">
                                        Jurusan
                                    </div>

                                    <div class="profil-anak-info-value">

                                        {{ $anak->namajurusan ?? '-' }}

                                    </div>

                                </div>


                                <div class="profil-anak-info-item">

                                    <div class="profil-anak-info-label">
                                        Tingkat
                                    </div>

                                    <div class="profil-anak-info-value">

                                        {{ $anak->tingkat ?? '-' }}

                                    </div>

                                </div>


                            </div>

                        </div>


                    @else


                        <div class="profil-empty">

                            Data anak belum terhubung
                            dengan akun Orangtua ini.

                        </div>


                    @endif


                </div>

            </section>


        </div>


        {{-- =================================================
             KOLOM KANAN
        ================================================== --}}

        <section class="profil-card">


            <div class="profil-card-header">

                <div class="profil-card-title">

                    <h2>
                        Informasi Akun
                    </h2>

                    <p>
                        Informasi akun yang digunakan untuk login.
                    </p>

                </div>

            </div>


            <div class="profil-card-body">


                <div class="profil-data-list">


                    {{-- NAMA AKUN --}}

                    <div class="profil-data-item">

                        <div class="profil-data-label">
                            Nama Akun
                        </div>

                        <div class="profil-data-value">

                            {{ $user->name }}

                        </div>

                    </div>


                    {{-- EMAIL --}}

                    <div class="profil-data-item">

                        <div class="profil-data-label">
                            Email
                        </div>

                        <div class="profil-data-value">

                            {{ $user->email }}

                        </div>

                    </div>


                    {{-- IDENTITAS --}}

                    <div class="profil-data-item">

                        <div class="profil-data-label">
                            Identitas Akun
                        </div>

                        <div class="profil-data-value">

                            NISN:
                            {{ $user->nisn ?? '-' }}

                        </div>

                    </div>


                    {{-- ROLE --}}

                    <div class="profil-data-item">

                        <div class="profil-data-label">
                            Role
                        </div>

                        <div class="profil-data-value">

                            Orangtua

                        </div>

                    </div>


                    {{-- STATUS --}}

                    <div class="profil-data-item">

                        <div class="profil-data-label">
                            Status Akun
                        </div>

                        <div class="profil-data-value">

                            <span class="profil-status">

                                <span class="profil-status-dot"></span>

                                {{
                                    ucfirst(
                                        $user->status
                                        ?? 'aktif'
                                    )
                                }}

                            </span>

                        </div>

                    </div>


                </div>


            </div>

        </section>


    </div>


</div>

@endsection