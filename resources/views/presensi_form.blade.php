@extends('layouts.app')

@section('content')

<div class="pagehead">

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:20px;
    ">

        <div>

            <h1>
                Isi Presensi Siswa
            </h1>

            <p>
                Silakan isi status kehadiran siswa sesuai kondisi hari ini.
            </p>

        </div>

        <a
            href="{{ route('presensi') }}"
            class="btn"
            style="
                background:#fff;
                color:#173b7a;
                border:1px solid #dce5f5;
            "
        >
            ← Kembali
        </a>

    </div>

</div>


{{-- SUCCESS --}}
@if(session('success'))

    <div class="success">
        {{ session('success') }}
    </div>

@endif


{{-- ERROR --}}
@if($errors->any())

    <div
        style="
            background:#fff1f1;
            border:1px solid #ffd1d1;
            color:#b42318;
            padding:14px 18px;
            border-radius:12px;
            margin-bottom:20px;
        "
    >

        @foreach($errors->all() as $error)

            <div>
                {{ $error }}
            </div>

        @endforeach

    </div>

@endif


{{-- INFORMASI JADWAL --}}
<div
    class="panel"
    style="margin-bottom:20px;"
>

    <div
        style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:20px;
            flex-wrap:wrap;
        "
    >

        <div>

            <div
                style="
                    font-size:13px;
                    color:var(--muted);
                    margin-bottom:8px;
                "
            >
                Mata Pelajaran
            </div>

            <h2 style="margin:0;">

                {{ $jadwal->namamapel ?? '-' }}

            </h2>

            <div
                style="
                    margin-top:8px;
                    color:var(--muted);
                "
            >

                Kelas:
                <strong>
                    {{ $jadwal->namakelas ?? '-' }}
                </strong>

                @if(!empty($jadwal->namajurusan))

                    •
                    {{ $jadwal->namajurusan }}

                @endif

            </div>

        </div>


        <div
            style="
                text-align:right;
            "
        >

            <div
                style="
                    font-size:13px;
                    color:var(--muted);
                "
            >
                Hari
            </div>

            <strong>
                {{ $jadwal->hari ?? '-' }}
            </strong>


            <div
                style="
                    margin-top:10px;
                    font-size:13px;
                    color:var(--muted);
                "
            >
                Jam
            </div>

            <strong>

                {{ $jadwal->jamMulai ?? '-' }}

                -

                {{ $jadwal->jamSelesai ?? '-' }}

            </strong>

        </div>

    </div>

</div>


{{-- FORM --}}
<div class="panel">

    <div
        style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        "
    >

        <div>

            <h2 style="margin:0;">
                Daftar Siswa
            </h2>

            <p
                style="
                    margin:5px 0 0;
                    color:var(--muted);
                "
            >
                {{ $siswa->count() }} siswa
            </p>

        </div>

    </div>


    @if($siswa->count() > 0)

        <form
            action="{{ route('presensi.store', $jadwal->id) }}"
            method="POST"
        >

            @csrf


            {{-- HEADER --}}
            <div
                style="
                    display:grid;
                    grid-template-columns:60px 1fr 180px;
                    gap:15px;
                    padding:12px 15px;
                    background:#f5f7fb;
                    border-radius:10px;
                    font-size:13px;
                    font-weight:600;
                    margin-bottom:5px;
                "
            >

                <div>
                    No
                </div>

                <div>
                    Nama Siswa
                </div>

                <div>
                    Status
                </div>

            </div>


            {{-- SISWA --}}
            @foreach($siswa as $index => $item)

                @php

                    $dataPresensi =
                        $presensi->get($item->id);

                @endphp


                <div
                    style="
                        padding:16px 15px;
                        border-bottom:1px solid #edf0f5;
                    "
                >

                    <div
                        style="
                            display:grid;
                            grid-template-columns:60px 1fr 180px;
                            gap:15px;
                            align-items:center;
                        "
                    >

                        {{-- NO --}}
                        <div>

                            {{ $index + 1 }}

                        </div>


                        {{-- NAMA --}}
                        <div>

                            <strong>
                                {{ $item->nama }}
                            </strong>

                            <div
                                style="
                                    color:var(--muted);
                                    font-size:12px;
                                    margin-top:4px;
                                "
                            >

                                NISN:
                                {{ $item->nisn ?? '-' }}

                            </div>

                        </div>


                        {{-- STATUS --}}
                        <div>

                            <select
                                name="status[{{ $item->id }}]"
                                required
                                style="
                                    width:100%;
                                    padding:10px 12px;
                                    border:1px solid #dce2ec;
                                    border-radius:8px;
                                    background:#fff;
                                "
                            >

                                <option
                                    value="hadir"
                                    {{ ($dataPresensi->status ?? 'hadir') == 'hadir'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Hadir
                                </option>

                                <option
                                    value="sakit"
                                    {{ ($dataPresensi->status ?? '') == 'sakit'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Sakit
                                </option>

                                <option
                                    value="izin"
                                    {{ ($dataPresensi->status ?? '') == 'izin'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Izin
                                </option>

                                <option
                                    value="alpa"
                                    {{ ($dataPresensi->status ?? '') == 'alpa'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Alpa
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- KETERANGAN --}}
                    <div
                        style="
                            margin-top:10px;
                            margin-left:75px;
                        "
                    >

                        <input
                            type="text"
                            name="keterangan[{{ $item->id }}]"
                            value="{{ $dataPresensi->keterangan ?? '' }}"
                            placeholder="Keterangan (opsional)"
                            style="
                                width:100%;
                                padding:10px 12px;
                                border:1px solid #dce2ec;
                                border-radius:8px;
                                box-sizing:border-box;
                            "
                        >

                    </div>

                </div>

            @endforeach


            {{-- BUTTON --}}
            <div
                style="
                    display:flex;
                    justify-content:flex-end;
                    margin-top:25px;
                    gap:10px;
                "
            >

                <a
                    href="{{ route('presensi') }}"
                    class="btn"
                    style="
                        background:#fff;
                        color:#173b7a;
                        border:1px solid #dce5f5;
                    "
                >
                    Batal
                </a>


                <button
                    type="submit"
                    class="btn"
                >
                    ✓ Simpan Presensi
                </button>

            </div>

        </form>


    @else

        {{-- TIDAK ADA SISWA --}}
        <div
            style="
                text-align:center;
                padding:70px 20px;
            "
        >

            <div
                style="
                    font-size:50px;
                    margin-bottom:15px;
                "
            >
                👨‍🎓
            </div>

            <h3 style="margin-bottom:8px;">
                Belum ada siswa
            </h3>

            <p
                style="
                    color:var(--muted);
                    margin:0;
                "
            >

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

@endsection