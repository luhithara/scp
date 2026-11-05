@extends('layouts.app')

@section('content')

<div class="pagehead">

    <h1>Isi Jurnal Mengajar</h1>

    <p>
        Isi jurnal berdasarkan jadwal yang dipilih.
    </p>

</div>


@if($errors->any())

    <div class="panel"
         style="margin-bottom:20px; color:#b91c1c;">

        @foreach($errors->all() as $error)

            <div>
                {{ $error }}
            </div>

        @endforeach

    </div>

@endif


<div class="panel">

    {{-- INFORMASI JADWAL --}}

    <div style="margin-bottom:24px;">

        <span class="badge">
            {{ $jadwal->hari }}
        </span>

        <h2 style="margin:14px 0 6px;">

            {{ $jadwal->namamapel ?? '-' }}

        </h2>

        <p style="color:var(--muted);">

            Tingkat {{ $jadwal->tingkat ?? '-' }}

            @if($jadwal->namajurusan)

                • {{ $jadwal->namajurusan }}

            @endif

        </p>

        <p style="color:var(--muted);">

            {{ $jadwal->jamMulai }}
            -
            {{ $jadwal->jamSelesai }}

        </p>

    </div>


    {{-- FORM --}}

    <form
        action="{{ route('jurnal.guru.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        {{-- ID JADWAL --}}

        <input
            type="hidden"
            name="jadwalid"
            value="{{ $jadwal->id }}"
        >


        {{-- TANGGAL --}}

        <div style="margin-bottom:18px;">

            <label for="tanggal">
                Tanggal
            </label>

            <input
                type="date"
                id="tanggal"
                name="tanggal"
                value="{{ old('tanggal', date('Y-m-d')) }}"
                required
            >

        </div>


        {{-- MATERI --}}

        <div style="margin-bottom:18px;">

            <label for="materi">
                Materi
            </label>

            <textarea
                id="materi"
                name="materi"
                rows="4"
                placeholder="Masukkan materi yang diajarkan..."
                required
            >{{ old('materi') }}</textarea>

        </div>


        {{-- KEGIATAN --}}

        <div style="margin-bottom:18px;">

            <label for="kegiatan">
                Kegiatan
            </label>

            <textarea
                id="kegiatan"
                name="kegiatan"
                rows="5"
                placeholder="Masukkan kegiatan pembelajaran..."
                required
            >{{ old('kegiatan') }}</textarea>

        </div>


        {{-- CATATAN --}}

        <div style="margin-bottom:18px;">

            <label for="catatan">
                Catatan
            </label>

            <textarea
                id="catatan"
                name="catatan"
                rows="4"
                placeholder="Catatan tambahan (opsional)..."
            >{{ old('catatan') }}</textarea>

        </div>


        {{-- FOTO BUKTI --}}

        <div style="margin-bottom:24px;">

            <label for="fotobukti">
                Foto Bukti Mengajar
            </label>

            <input
                type="file"
                id="fotobukti"
                name="fotobukti"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                required
            >

            <small style="display:block; margin-top:6px; color:var(--muted);">

                Format JPG, JPEG, PNG, atau WEBP.
                Maksimal 5 MB.

            </small>

        </div>


        {{-- BUTTON --}}

        <div>

            <button
                type="submit"
                class="btn"
            >
                Simpan Jurnal
            </button>


            <a
                href="{{ route('jurnal.guru') }}"
                class="btn"
                style="margin-left:8px;"
            >
                Batal
            </a>

        </div>

    </form>

</div>

@endsection