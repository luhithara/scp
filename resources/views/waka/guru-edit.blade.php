{{-- =========================================================
    EDIT DATA GURU - SCHOOL CLASS PROGRESS
    File:
    resources/views/waka/guru-edit.blade.php
    ========================================================= --}}

@extends('layouts.app')

@section('title', 'Edit Data Guru')
@section('page-title', 'Edit Data Guru')
@section('page-subtitle', 'Mengubah informasi guru dan akun akses guru')

@section('content')

<div class="edit-guru-page">

    {{-- =====================================================
         HEADER
         ===================================================== --}}
    <div class="page-header">

        <div>
            <h1>Edit Data Guru</h1>

            <p>
                Perbarui informasi guru yang dipilih.
            </p>
        </div>

        <a
            href="{{ route('waka.guru') }}"
            class="btn-back"
        >

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
            >
                <path d="M19 12H5"/>
                <path d="M12 19l-7-7 7-7"/>
            </svg>

            Kembali

        </a>

    </div>


    {{-- =====================================================
         ERROR VALIDASI
         ===================================================== --}}
    @if($errors->any())

        <div class="alert alert-error">

            <div class="alert-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    />

                    <path d="M12 8v4"/>
                    <path d="M12 16h.01"/>
                </svg>

            </div>

            <div>

                <strong>
                    Data belum dapat diperbarui.
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

            <button
                type="button"
                class="alert-close"
                onclick="this.parentElement.remove()"
            >
                ×
            </button>

        </div>

    @endif


    {{-- =====================================================
         KARTU EDIT
         ===================================================== --}}
    <div class="edit-card">

        {{-- =================================================
             HEADER KARTU
             ================================================= --}}
        <div class="card-header">

            <div class="guru-profile">

                <div class="guru-avatar">

                    {{ strtoupper(
                        substr(
                            $guru->nama ?? '?',
                            0,
                            1
                        )
                    ) }}

                </div>

                <div>

                    <h2>
                        {{ $guru->nama }}
                    </h2>

                    <p>
                        Kode Guru:
                        <strong>
                            {{ $guru->kodeguru }}
                        </strong>
                    </p>

                </div>

            </div>

        </div>


        {{-- =================================================
             FORM
             ================================================= --}}
        <div class="card-body">

            <form
                action="{{ route('waka.guru.update', $guru->id) }}"
                method="POST"
            >

                @csrf

                @method('PUT')


                {{-- =========================================
                     KODE GURU
                     ========================================= --}}
                <div class="form-group">

                    <label for="kodeguru">
                        Kode Guru
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        id="kodeguru"
                        name="kodeguru"
                        value="{{ old('kodeguru', $guru->kodeguru) }}"
                        maxlength="30"
                        required
                    >

                    <small>
                        Kode guru digunakan sebagai ID login guru.
                    </small>

                </div>


                {{-- =========================================
                     NAMA GURU
                     ========================================= --}}
                <div class="form-group">

                    <label for="nama">
                        Nama Guru
                        <span>*</span>
                    </label>

                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama', $guru->nama) }}"
                        maxlength="100"
                        required
                    >

                </div>


                {{-- =========================================
                     NIP
                     ========================================= --}}
                <div class="form-group">

                    <label for="nip">
                        NIP
                    </label>

                    <input
                        type="text"
                        id="nip"
                        name="nip"
                        value="{{ old('nip', $guru->nip) }}"
                        maxlength="30"
                    >

                </div>


                {{-- =========================================
                     NOMOR TELEPON
                     ========================================= --}}
                <div class="form-group">

                    <label for="nomortelepon">
                        Nomor Telepon
                    </label>

                    <input
                        type="text"
                        id="nomortelepon"
                        name="nomortelepon"
                        value="{{ old('nomortelepon', $guru->nomortelepon) }}"
                        maxlength="20"
                    >

                </div>


                {{-- =========================================
                     ALAMAT
                     ========================================= --}}
                <div class="form-group">

                    <label for="alamat">
                        Alamat
                    </label>

                    <textarea
                        id="alamat"
                        name="alamat"
                        rows="5"
                    >{{ old('alamat', $guru->alamat) }}</textarea>

                </div>


                {{-- =========================================
                     INFORMASI
                     ========================================= --}}
                <div class="info-box">

                    <div class="info-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            />

                            <path d="M12 10v6"/>
                            <path d="M12 7h.01"/>
                        </svg>

                    </div>

                    <div>

                        <strong>
                            Informasi Akun
                        </strong>

                        <p>
                            Perubahan kode guru juga akan memperbarui
                            ID login guru yang tersimpan pada akun sistem.
                        </p>

                    </div>

                </div>


                {{-- =========================================
                     BUTTON
                     ========================================= --}}
                <div class="form-actions">

                    <a
                        href="{{ route('waka.guru') }}"
                        class="btn-cancel"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn-save"
                    >

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>

                            <polyline points="17 21 17 13 7 13 7 21"/>

                            <polyline points="7 3 7 8 15 8"/>
                        </svg>

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   HALAMAN
   ========================================================= */

.edit-guru-page {
    width: 100%;

    max-width: 900px;

    margin: 0 auto;
}


/* =========================================================
   HEADER
   ========================================================= */

.page-header {
    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 22px;
}

.page-header h1 {
    margin: 0 0 5px;

    color: #102a43;

    font-size: 25px;

    font-weight: 700;
}

.page-header p {
    margin: 0;

    color: #718096;

    font-size: 13px;
}


/* =========================================================
   KEMBALI
   ========================================================= */

.btn-back {
    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 10px 15px;

    background: #eef5ff;

    color: #2563eb;

    border-radius: 10px;

    text-decoration: none;

    font-size: 12px;

    font-weight: 600;

    white-space: nowrap;
}

.btn-back:hover {
    background: #e0edff;

    color: #1d4ed8;
}

.btn-back svg {
    width: 16px;
    height: 16px;
}


/* =========================================================
   ALERT
   ========================================================= */

.alert {
    position: relative;

    display: flex;

    align-items: flex-start;

    gap: 10px;

    padding: 13px 40px 13px 14px;

    margin-bottom: 18px;

    border-radius: 10px;

    font-size: 12px;
}

.alert-error {
    background: #fff7f7;

    border: 1px solid #fecaca;

    color: #991b1b;
}

.alert-icon {
    width: 20px;
    height: 20px;

    min-width: 20px;

    display: flex;

    align-items: center;

    justify-content: center;
}

.alert-icon svg {
    width: 18px;
    height: 18px;
}

.alert strong {
    display: block;

    margin-bottom: 3px;
}

.alert ul {
    margin: 5px 0 0 16px;

    padding: 0;
}

.alert li {
    margin-bottom: 2px;
}

.alert-close {
    position: absolute;

    top: 9px;
    right: 10px;

    width: 26px;
    height: 26px;

    border: none;

    background: transparent;

    color: currentColor;

    cursor: pointer;

    font-size: 20px;
}


/* =========================================================
   CARD
   ========================================================= */

.edit-card {
    background: #ffffff;

    border: 1px solid #e1e9f3;

    border-radius: 15px;

    box-shadow:
        0 6px 22px rgba(15, 50, 90, .05);

    overflow: hidden;
}


/* =========================================================
   CARD HEADER
   ========================================================= */

.card-header {
    padding: 21px;

    background: #f8fbff;

    border-bottom: 1px solid #e5edf6;
}

.guru-profile {
    display: flex;

    align-items: center;

    gap: 12px;
}

.guru-avatar {
    width: 48px;
    height: 48px;

    min-width: 48px;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #eaf2ff;

    color: #2563eb;

    border: 1px solid #d6e7ff;

    border-radius: 12px;

    font-size: 16px;

    font-weight: 700;
}

.guru-profile h2 {
    margin: 0 0 4px;

    color: #102a43;

    font-size: 17px;
}

.guru-profile p {
    margin: 0;

    color: #718096;

    font-size: 11px;
}

.guru-profile strong {
    color: #2563eb;

    font-family: monospace;
}


/* =========================================================
   BODY
   ========================================================= */

.card-body {
    padding: 24px;
}


/* =========================================================
   FORM
   ========================================================= */

.form-group {
    margin-bottom: 17px;
}

.form-group label {
    display: block;

    margin-bottom: 7px;

    color: #334e68;

    font-size: 11px;

    font-weight: 650;
}

.form-group label span {
    color: #dc2626;
}

.form-group input,
.form-group textarea {
    width: 100%;

    box-sizing: border-box;

    background: #ffffff;

    border: 1px solid #d5e0ec;

    border-radius: 9px;

    padding: 11px 12px;

    color: #243b53;

    font-family: inherit;

    font-size: 12px;

    outline: none;

    transition: .2s;
}

.form-group input {
    height: 41px;
}

.form-group textarea {
    resize: vertical;

    min-height: 100px;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #2563eb;

    box-shadow:
        0 0 0 3px rgba(37, 99, 235, .07);
}

.form-group small {
    display: block;

    margin-top: 5px;

    color: #94a3b8;

    font-size: 9px;
}


/* =========================================================
   INFO
   ========================================================= */

.info-box {
    display: flex;

    align-items: flex-start;

    gap: 10px;

    padding: 13px;

    margin: 4px 0 20px;

    background: #f4f8ff;

    border: 1px solid #dce9f9;

    border-radius: 10px;
}

.info-icon {
    width: 22px;
    height: 22px;

    min-width: 22px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #2563eb;
}

.info-icon svg {
    width: 17px;
    height: 17px;
}

.info-box strong {
    display: block;

    margin-bottom: 3px;

    color: #1e3a5f;

    font-size: 11px;
}

.info-box p {
    margin: 0;

    color: #718096;

    font-size: 10px;

    line-height: 1.5;
}


/* =========================================================
   ACTION
   ========================================================= */

.form-actions {
    display: flex;

    justify-content: flex-end;

    gap: 8px;
}

.btn-cancel,
.btn-save {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    height: 40px;

    padding: 0 16px;

    border-radius: 9px;

    font-family: inherit;

    font-size: 11px;

    font-weight: 600;

    text-decoration: none;

    cursor: pointer;
}

.btn-cancel {
    border: 1px solid #d9e3ee;

    background: #ffffff;

    color: #64748b;
}

.btn-cancel:hover {
    background: #f8fafc;

    color: #334155;
}

.btn-save {
    border: none;

    background: #2563eb;

    color: #ffffff;
}

.btn-save:hover {
    background: #1d4ed8;

    box-shadow:
        0 5px 14px rgba(37, 99, 235, .18);
}

.btn-save svg {
    width: 15px;
    height: 15px;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media(max-width: 600px) {

    .page-header {
        flex-direction: column;
    }

    .btn-back {
        align-self: flex-start;
    }

    .card-body {
        padding: 18px;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .btn-cancel,
    .btn-save {
        width: 100%;
    }

}

</style>

@endpush