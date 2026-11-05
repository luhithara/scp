@extends('layouts.app')

@section('content')

<div class="pagehead">
    <h1>Presensi Siswa</h1>
    <p>Pilih jadwal untuk mengisi kehadiran siswa.</p>
</div>

@if(session('success'))
    <div class="success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="error">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="grid3">

    @forelse($jadwal as $item)

        <div class="panel">

            <span class="badge">
                {{ $item->hari }}
            </span>

            <h3 style="margin:14px 0 6px">
                {{ $item->namamapel ?? '-' }}
            </h3>

            <p style="color:var(--muted)">
                Kelas {{ $item->tingkat ?? '-' }}
                •
                {{ $item->namajurusan ?? '-' }}
                •
                Jam {{ $item->jamMulai }} - {{ $item->jamSelesai }}
            </p>

            <a
                class="btn"
                href="{{ route('presensi.form', $item->id) }}"
            >
                Isi Presensi
            </a>

        </div>

    @empty

        <div class="panel empty">
            Belum ada jadwal.
        </div>

    @endforelse

</div>

@endsection