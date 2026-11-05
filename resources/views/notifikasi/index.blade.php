
@extends('layouts.app')

@section('title', 'Notifikasi - SCP')
@section('page-title', 'Notifikasi')

@section('content')

<div style="max-width:1000px;margin:0 auto;padding:24px;">

    {{-- HEADER --}}
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px;margin-bottom:24px;">
        <div>
            <h2 style="margin:0 0 6px;font-size:24px;color:#1e293b;">
                Notifikasi
            </h2>

            <p style="margin:0;color:#64748b;font-size:14px;">
                Informasi dan pemberitahuan aktivitas sistem sekolah.
            </p>
        </div>

        <div style="background:#eff6ff;color:#2563eb;padding:10px 16px;border-radius:10px;font-size:14px;font-weight:600;">
            Belum dibaca: {{ $jumlahBelumDibaca }}
        </div>
    </div>

    {{-- PESAN SUKSES --}}
    @if(session('success'))
        <div style="padding:13px 16px;background:#dcfce7;color:#166534;border-radius:10px;margin-bottom:18px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- DAFTAR NOTIFIKASI --}}
    <div style="background:white;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;">

        @forelse($items as $item)

            <div style="padding:20px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:flex-start;gap:15px;flex-wrap:wrap;{{ !$item->sudahdibaca ? 'background:#f0f7ff;' : '' }}">

                <div style="display:flex;gap:14px;flex:1;min-width:220px;">

                    <div style="width:42px;height:42px;min-width:42px;border-radius:50%;background:{{ !$item->sudahdibaca ? '#dbeafe' : '#f1f5f9' }};display:flex;align-items:center;justify-content:center;color:#2563eb;font-size:18px;">
                        <i class="fa-regular fa-bell"></i>
                    </div>

                    <div style="flex:1;">

                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:6px;">

                            <strong style="font-size:15px;color:#1e293b;">
                                {{ $item->judul }}
                            </strong>

                            @if(!$item->sudahdibaca)
                                <span style="font-size:10px;background:#2563eb;color:white;padding:4px 8px;border-radius:20px;">
                                    BARU
                                </span>
                            @endif

                        </div>

                        <p style="margin:0 0 9px;color:#64748b;font-size:14px;line-height:1.6;">
                            {{ $item->pesan }}
                        </p>

                        <span style="font-size:12px;color:#94a3b8;">
                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y, H:i') }}
                        </span>

                    </div>
                </div>

                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">

                    @if(!$item->sudahdibaca)

                        <form action="{{ route('notifikasi.baca', $item->id) }}" method="POST">
                            @csrf

                            <button type="submit"
                                style="border:none;background:#2563eb;color:white;padding:9px 13px;border-radius:8px;cursor:pointer;font-size:12px;">
                                Tandai dibaca
                            </button>
                        </form>

                    @else

                        <span style="font-size:12px;color:#16a34a;">
                            <i class="fa-solid fa-check"></i> Sudah dibaca
                        </span>

                    @endif

                    @if(!empty($item->url))
                        <a href="{{ $item->url }}"
                           style="text-decoration:none;background:#f1f5f9;color:#475569;padding:9px 13px;border-radius:8px;font-size:12px;">
                            Lihat
                        </a>
                    @endif

                </div>

            </div>

        @empty

            <div style="padding:60px 20px;text-align:center;">

                <div style="font-size:42px;color:#cbd5e1;margin-bottom:14px;">
                    <i class="fa-regular fa-bell-slash"></i>
                </div>

                <h3 style="margin:0 0 8px;color:#334155;">
                    Belum Ada Notifikasi
                </h3>

                <p style="margin:0;color:#94a3b8;font-size:14px;">
                    Notifikasi baru akan muncul di halaman ini.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection
