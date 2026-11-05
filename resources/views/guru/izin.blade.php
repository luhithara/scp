@extends('layouts.app')

@section('content')

<div class="pageheader">
    <div>
        <h2>Izin Siswa</h2>
        <p>Kelola dan lihat pengajuan izin atau sakit dari siswa.</p>
    </div>
</div>


@if(session('success'))
<div class="alert success">
    {{ session('success') }}
</div>
@endif


@if($errors->any())
<div class="alert danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="panel">

    <div class="paneltitle">
        <div>
            <h3>Daftar Pengajuan Izin</h3>
            <p class="muted">
                Pengajuan izin dan sakit siswa.
            </p>
        </div>
    </div>


    @forelse($izin as $item)

    <div class="listitem izinitem">

        <div class="izininfo">

            <div class="avatar">
                {{ strtoupper(substr($item->namasiswa ?? 'S', 0, 1)) }}
            </div>

            <div>

                <b>
                    {{ $item->namasiswa }}
                </b>

                <small>
                    NISN: {{ $item->nisn }}
                </small>

                <small>
                    {{ ucfirst($item->jenis) }}
                    •
                    {{ $item->tanggal }}
                </small>

                <small>
                    {{ $item->alasan }}
                </small>

            </div>

        </div>


        <div class="izinaction">

            {{-- STATUS MENUNGGU --}}
            @if($item->status === 'menunggu')

                <span class="badge warning">
                    Menunggu persetujuan Akun Kelas
                </span>


            {{-- STATUS DISETUJUI --}}
            @elseif($item->status === 'disetujui')

                <span class="badge success">
                    ✓ Disetujui
                </span>


            {{-- STATUS DITOLAK --}}
            @elseif($item->status === 'ditolak')

                <span class="badge danger">
                    ✕ Ditolak
                </span>


                @if($item->catatanbk)

                <small class="catatan">
                    Catatan: {{ $item->catatanbk }}
                </small>

                @endif

            @endif


            {{-- BUKTI IZIN --}}
            @if($item->bukti)

            <a
                href="{{ asset('storage/' . $item->bukti) }}"
                target="_blank"
                class="btn secondary"
            >
                Lihat Bukti
            </a>

            @endif


        </div>

    </div>


    @empty

    <div class="empty">

        <div style="font-size:40px;">
            📋
        </div>

        <p>
            Belum ada pengajuan izin siswa.
        </p>

    </div>

    @endforelse


</div>


<style>

.izinitem {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 18px 0;
    border-bottom: 1px solid #e5e7eb;
}

.izinitem:last-child {
    border-bottom: none;
}

.izininfo {
    display: flex;
    align-items: center;
    gap: 14px;
}

.izininfo small {
    display: block;
    margin-top: 4px;
    color: #64748b;
}

.izinaction {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.izinaction form {
    margin: 0;
}

.formtolak {
    display: flex;
    gap: 8px;
}

.formtolak input {
    padding: 9px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    outline: none;
}

.catatan {
    width: 100%;
    color: #64748b;
}

.alert {
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 16px;
}

.alert.success {
    background: #dcfce7;
    color: #166534;
}

.alert.danger {
    background: #fee2e2;
    color: #991b1b;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

.btn {
    border: none;
    padding: 9px 14px;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
}

.btn.success {
    background: #16a34a;
    color: white;
}

.btn.danger {
    background: #dc2626;
    color: white;
}

.btn.secondary {
    background: #2563eb;
    color: white;
}

.badge.success {
    background: #dcfce7;
    color: #166534;
}

.badge.warning {
    background: #fef3c7;
    color: #92400e;
}

.badge.danger {
    background: #fee2e2;
    color: #991b1b;
}

.muted {
    color: #64748b;
    margin-top: 5px;
}

@media(max-width: 768px) {

    .izinitem {
        flex-direction: column;
        align-items: flex-start;
    }

    .izinaction {
        width: 100%;
    }

}

</style>

@endsection