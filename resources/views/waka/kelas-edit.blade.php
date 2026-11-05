{{-- =========================================================
     EDIT DATA KELAS - SCHOOL CLASS PROGRESS
     File:
     resources/views/waka/kelas-edit.blade.php
     ========================================================= --}}

@extends('layouts.app')

@section('title', 'Edit Kelas - SCP')

@section('content')

<div class="page-header">

    <div>

        {{-- Tombol kembali --}}
        <div class="back-wrapper">
            <a
                href="{{ route('waka.kelas.jurusan', [
                    'tingkat' => $kelas->tingkat,
                    'jurusanId' => $kelas->jurusanid
                ]) }}"
                class="back-link"
            >
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Daftar Kelas</span>
            </a>
        </div>

        <h1>Edit Kelas</h1>

        <p>
            Ubah nama kelas yang terdaftar pada data kelas.
        </p>

    </div>

</div>


{{-- =========================================================
     INFORMASI KELAS
     ========================================================= --}}

<div class="class-info-card">

    <div class="class-icon">
        <i class="fa-solid fa-school"></i>
    </div>

    <div class="class-info-content">

        <span class="class-label">
            Nama Kelas
        </span>

        <h2>
            {{ $kelas->namakelas }}
        </h2>

        <div class="class-meta">

            <span>
                <i class="fa-solid fa-graduation-cap"></i>
                {{ $kelas->kodejurusan ?? '-' }}
                @if(!empty($kelas->namajurusan))
                    - {{ $kelas->namajurusan }}
                @endif
            </span>

            <span>
                <i class="fa-solid fa-layer-group"></i>
                Angkatan {{ $kelas->tingkat }}
            </span>

        </div>

    </div>

</div>


{{-- =========================================================
     FORM EDIT KELAS
     ========================================================= --}}

<div class="card edit-card">

    <div class="card-header">

        <div>

            <h3>
                <i class="fa-solid fa-pen-to-square"></i>
                Edit Nama Kelas
            </h3>

            <p>
                Silakan ubah nama kelas sesuai data yang benar.
            </p>

        </div>

    </div>


    <div class="card-body">

        @if($errors->any())

            <div class="alert alert-danger">

                <div class="alert-icon">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>

                <div>

                    <strong>
                        Terdapat kesalahan
                    </strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            </div>

        @endif


        <form
            action="{{ route('waka.kelas.update', $kelas->id) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            {{-- =================================================
                 NAMA KELAS
                 ================================================= --}}

            <div class="form-group">

                <label for="namakelas">
                    Nama Kelas
                    <span class="required">*</span>
                </label>

                <div class="input-wrapper">

                    <span class="input-icon">
                        <i class="fa-solid fa-school"></i>
                    </span>

                    <input
                        type="text"
                        id="namakelas"
                        name="namakelas"
                        value="{{ old('namakelas', $kelas->namakelas) }}"
                        class="form-control form-control-icon"
                        placeholder="Contoh: XI RPL 1"
                        maxlength="100"
                        required
                    >

                </div>

                @error('namakelas')

                    <small class="form-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </small>

                @enderror

            </div>


            {{-- =================================================
                 INFORMASI YANG TIDAK DAPAT DIUBAH
                 ================================================= --}}

            <div class="info-notice">

                <div class="info-notice-icon">
                    <i class="fa-solid fa-circle-info"></i>
                </div>

                <div>

                    <strong>
                        Informasi kelas
                    </strong>

                    <p>
                        Angkatan dan jurusan kelas tidak dapat diubah
                        melalui halaman ini.
                    </p>

                </div>

            </div>


            {{-- =================================================
                 TOMBOL
                 ================================================= --}}

            <div class="form-actions">

                <a
                    href="{{ route('waka.kelas.jurusan', [
                        'tingkat' => $kelas->tingkat,
                        'jurusanId' => $kelas->jurusanid
                    ]) }}"
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


{{-- =========================================================
     STYLE KHUSUS HALAMAN EDIT KELAS
     ========================================================= --}}

@push('styles')

<style>

    /* =========================================================
       TOMBOL KEMBALI
       ========================================================= */

    .back-wrapper {
        margin-bottom: 12px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        color: #2563eb;
        text-decoration: none;

        font-size: 14px;
        font-weight: 600;

        transition: all 0.2s ease;
    }

    .back-link:hover {
        color: #1d4ed8;
        transform: translateX(-2px);
    }


    /* =========================================================
       INFORMASI KELAS
       ========================================================= */

    .class-info-card {
        display: flex;
        align-items: center;
        gap: 18px;

        background: #ffffff;

        border: 1px solid #e5e7eb;
        border-radius: 16px;

        padding: 22px;

        margin-bottom: 20px;

        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
    }

    .class-icon {
        width: 58px;
        height: 58px;

        min-width: 58px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: #eff6ff;
        color: #2563eb;

        font-size: 23px;
    }

    .class-info-content {
        min-width: 0;
    }

    .class-label {
        display: block;

        font-size: 12px;
        font-weight: 600;

        color: #64748b;

        margin-bottom: 4px;
    }

    .class-info-content h2 {
        margin: 0;

        font-size: 22px;
        font-weight: 700;

        color: #0f172a;
    }

    .class-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        gap: 18px;

        margin-top: 9px;
    }

    .class-meta span {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        font-size: 13px;
        color: #64748b;
    }

    .class-meta i {
        color: #2563eb;
    }


    /* =========================================================
       CARD EDIT
       ========================================================= */

    .edit-card {
        max-width: 850px;
    }

    .edit-card .card-header h3 {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .edit-card .card-header h3 i {
        color: #2563eb;
        font-size: 16px;
    }


    /* =========================================================
       FORM
       ========================================================= */

    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;

        margin-bottom: 8px;

        font-size: 14px;
        font-weight: 600;

        color: #334155;
    }

    .required {
        color: #dc2626;
        margin-left: 2px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;

        left: 14px;
        top: 50%;

        transform: translateY(-50%);

        color: #64748b;

        pointer-events: none;

        z-index: 2;
    }

    .form-control-icon {
        padding-left: 42px !important;
    }


    /* =========================================================
       INFORMASI
       ========================================================= */

    .info-notice {
        display: flex;
        align-items: flex-start;
        gap: 12px;

        padding: 14px 16px;

        margin-bottom: 24px;

        border: 1px solid #dbeafe;
        border-radius: 12px;

        background: #eff6ff;
    }

    .info-notice-icon {
        width: 30px;
        height: 30px;

        min-width: 30px;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #2563eb;
    }

    .info-notice strong {
        display: block;

        font-size: 13px;

        color: #1e3a8a;

        margin-bottom: 3px;
    }

    .info-notice p {
        margin: 0;

        font-size: 13px;
        line-height: 1.5;

        color: #475569;
    }


    /* =========================================================
       ALERT
       ========================================================= */

    .alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;

        padding: 14px 16px;

        border-radius: 12px;

        margin-bottom: 20px;
    }

    .alert-danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .alert-icon {
        padding-top: 2px;
    }

    .alert strong {
        display: block;

        font-size: 13px;

        margin-bottom: 5px;
    }

    .alert ul {
        margin: 0;
        padding-left: 18px;
    }

    .alert li {
        font-size: 13px;
        margin-bottom: 2px;
    }

    .form-error {
        display: block;

        margin-top: 6px;

        color: #dc2626;

        font-size: 12px;
    }

    .form-error i {
        margin-right: 3px;
    }


    /* =========================================================
       TOMBOL FORM
       ========================================================= */

    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;

        gap: 10px;

        padding-top: 4px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        min-height: 42px;

        padding: 0 17px;

        border-radius: 10px;

        font-size: 13px;
        font-weight: 600;

        text-decoration: none;

        border: 1px solid transparent;

        cursor: pointer;

        transition: all 0.2s ease;
    }

    .btn-primary {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
    }

    .btn-primary:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .btn-secondary {
        background: #ffffff;
        color: #475569;
        border-color: #d1d5db;
    }

    .btn-secondary:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #1e293b;
    }


    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 768px) {

        .class-info-card {
            padding: 18px;
        }

        .class-icon {
            width: 50px;
            height: 50px;
            min-width: 50px;

            font-size: 20px;
        }

        .class-info-content h2 {
            font-size: 19px;
        }

        .class-meta {
            gap: 8px 14px;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .form-actions .btn {
            width: 100%;
        }

    }


    @media (max-width: 480px) {

        .class-info-card {
            align-items: flex-start;
        }

        .class-meta {
            flex-direction: column;
            align-items: flex-start;
        }

    }

</style>

@endpush