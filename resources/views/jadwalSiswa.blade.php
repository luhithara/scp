@extends('layouts.app')

@section('content')

<div class="jadwal-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="page-header">

        <div>

            <h2>
                Jadwal Kelas
            </h2>

            <p>
                Jadwal pembelajaran sesuai kelas dan putaran yang sedang berlaku.
            </p>

        </div>

        <div class="kelas-info">

            <div class="kelas-label">
                Kelas
            </div>

            <div class="kelas-nama">
                {{ $siswa->namakelas ?? '-' }}
            </div>

        </div>

    </div>


    {{-- =========================================================
         INFORMASI PUTARAN
    ========================================================== --}}

    <div class="info-putaran">

        <div>

            <div class="info-title">
                Putaran Jadwal
            </div>

            @if($putaranSekarang)

                <div class="info-value">
                    Putaran {{ $putaranSekarang }}
                </div>

            @else

                <div class="info-value">
                    Mengikuti jadwal yang sedang aktif
                </div>

            @endif

        </div>

        <div class="info-tanggal">

            {{ \Carbon\Carbon::parse($hariIni)->translatedFormat('l, d F Y') }}

        </div>

    </div>


    {{-- =========================================================
         JADWAL
    ========================================================== --}}

    <div class="panel">

        <div class="panel-header">

            <div>

                <h3>
                    Jadwal Pelajaran
                </h3>

                <p>
                    Jadwal yang tampil hanya untuk kelas
                    <strong>{{ $siswa->namakelas ?? '-' }}</strong>.
                </p>

            </div>

            <div class="jumlah-jadwal">

                {{ $jadwal->count() }} Jadwal

            </div>

        </div>


        @if($jadwal->count())


            {{-- =================================================
                 KELOMPOKKAN BERDASARKAN HARI
            ================================================== --}}

            @php

                $urutanHari = [
                    'senin',
                    'selasa',
                    'rabu',
                    'kamis',
                    'jumat'
                ];

                $namaHari = [
                    'senin' => 'Senin',
                    'selasa' => 'Selasa',
                    'rabu' => 'Rabu',
                    'kamis' => 'Kamis',
                    'jumat' => 'Jumat'
                ];

            @endphp


            <div class="jadwal-list">

                @foreach($urutanHari as $hari)

                    @php

                        $jadwalHari = $jadwal
                            ->where('hari', $hari);

                    @endphp


                    @if($jadwalHari->count())


                        {{-- =====================================
                             HEADER HARI
                        ====================================== --}}

                        <div class="hari-section">

                            <div class="hari-header">

                                <div class="hari-dot"></div>

                                <div class="hari-title">

                                    {{ $namaHari[$hari] }}

                                </div>

                                <div class="hari-line"></div>

                            </div>


                            {{-- =================================
                                 DAFTAR JADWAL
                            ================================== --}}

                            <div class="jadwal-items">

                                @foreach($jadwalHari as $item)

                                    @php
                                        /*
                                         * =====================================================
                                         * MENGUBAH JAM MENJADI "JAM KE-X"
                                         * =====================================================
                                         *
                                         * Mendukung beberapa kemungkinan format database:
                                         *
                                         * 08:00  -> 8
                                         * 10:00  -> 10
                                         * 08     -> 8
                                         * 10     -> 10
                                         *
                                         * Jadi tidak menggunakan "% 100" yang bisa error
                                         * apabila nilai database berupa "08:00".
                                         */

                                        $jamMulai = $item->jamMulai ?? null;
                                        $jamSelesai = $item->jamSelesai ?? null;

                                        if ($jamMulai !== null && $jamMulai !== '') {

                                            if (str_contains((string) $jamMulai, ':')) {
                                                $jamMulaiTampil = (int) explode(':', $jamMulai)[0];
                                            } else {
                                                $jamMulaiTampil = (int) $jamMulai;
                                            }

                                        } else {

                                            $jamMulaiTampil = null;

                                        }


                                        if ($jamSelesai !== null && $jamSelesai !== '') {

                                            if (str_contains((string) $jamSelesai, ':')) {
                                                $jamSelesaiTampil = (int) explode(':', $jamSelesai)[0];
                                            } else {
                                                $jamSelesaiTampil = (int) $jamSelesai;
                                            }

                                        } else {

                                            $jamSelesaiTampil = null;

                                        }


                                        /*
                                         * Nama mata pelajaran.
                                         */

                                        $namaMapel =
                                            $item->namamapel
                                            ?? $item->namamapelmaster
                                            ?? 'Mata pelajaran';

                                    @endphp


                                    <div class="jadwal-item">


                                        {{-- =================================
                                             JAM
                                        ================================== --}}

                                        <div class="jam-box">

                                            <div class="jam">

                                                @if($jamMulaiTampil !== null)

                                                    Jam ke-{{ $jamMulaiTampil }}

                                                @else

                                                    Jam -

                                                @endif

                                            </div>

                                            <div class="sampai">
                                                sampai
                                            </div>

                                            <div class="jam">

                                                @if($jamSelesaiTampil !== null)

                                                    Jam ke-{{ $jamSelesaiTampil }}

                                                @else

                                                    Jam -

                                                @endif

                                            </div>

                                        </div>


                                        {{-- =================================
                                             INFORMASI MAPEL
                                        ================================== --}}

                                        <div class="mapel-info">

                                            <div class="mapel-nama">

                                                {{ $namaMapel }}

                                            </div>


                                            <div class="guru-nama">

                                                👤 {{ $item->namaguru ?? '-' }}

                                            </div>

                                        </div>


                                        {{-- =================================
                                             JAM KE
                                        ================================== --}}

                                        @if($item->jamke)

                                            <div class="jam-ke">

                                                <span>
                                                    Jam ke
                                                </span>

                                                <strong>
                                                    {{ $item->jamke }}
                                                </strong>

                                            </div>

                                        @endif


                                        {{-- =================================
                                             PUTARAN
                                        ================================== --}}

                                        <div class="putaran-box">

                                            @if($item->putaranblok == 1)

                                                <span class="putaran satu">
                                                    Putaran 1
                                                </span>

                                            @elseif($item->putaranblok == 2)

                                                <span class="putaran dua">
                                                    Putaran 2
                                                </span>

                                            @else

                                                <span class="putaran">
                                                    Putaran {{ $item->putaranblok ?? '-' }}
                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif

                @endforeach

            </div>


        @else


            {{-- =================================================
                 BELUM ADA JADWAL
            ================================================== --}}

            <div class="empty">

                <div class="empty-icon">
                    📅
                </div>

                <h3>
                    Jadwal belum tersedia
                </h3>

                <p>
                    Belum ada jadwal aktif untuk
                    <strong>{{ $siswa->namakelas ?? 'kelas kamu' }}</strong>
                    pada putaran yang sedang berlangsung.
                </p>

            </div>

        @endif

    </div>
</div>


<style>

/* =========================================================
   HALAMAN
========================================================= */

.jadwal-page
{
    width: 100%;
}


/* =========================================================
   HEADER
========================================================= */

.page-header
{
    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 20px;
}

.page-header h2
{
    margin: 0;

    font-size: 26px;

    color: #172554;
}

.page-header p
{
    margin: 6px 0 0;

    color: #64748b;

    font-size: 14px;
}


/* =========================================================
   KELAS INFO
========================================================= */

.kelas-info
{
    background: white;

    border: 1px solid #dbeafe;

    border-radius: 12px;

    padding: 10px 18px;

    text-align: right;

    min-width: 130px;
}

.kelas-label
{
    font-size: 11px;

    color: #64748b;

    margin-bottom: 3px;
}

.kelas-nama
{
    font-size: 15px;

    font-weight: 700;

    color: #2563eb;
}


/* =========================================================
   INFORMASI PUTARAN
========================================================= */

.info-putaran
{
    display: flex;

    align-items: center;

    gap: 14px;

    background: #eff6ff;

    border: 1px solid #dbeafe;

    border-radius: 14px;

    padding: 15px 18px;

    margin-bottom: 20px;
}

.info-icon
{
    width: 42px;

    height: 42px;

    border-radius: 11px;

    background: white;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 20px;
}

.info-title
{
    color: #64748b;

    font-size: 12px;

    margin-bottom: 2px;
}

.info-value
{
    color: #1e40af;

    font-size: 15px;

    font-weight: 700;
}

.info-tanggal
{
    margin-left: auto;

    color: #64748b;

    font-size: 12px;
}


/* =========================================================
   PANEL
========================================================= */

.panel
{
    background: white;

    border: 1px solid #e2e8f0;

    border-radius: 16px;

    padding: 20px;

    margin-bottom: 20px;

    box-shadow:
        0 5px 20px rgba(15, 23, 42, .04);
}


/* =========================================================
   PANEL HEADER
========================================================= */

.panel-header
{
    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 15px;

    margin-bottom: 20px;
}

.panel-header h3
{
    margin: 0;

    font-size: 17px;

    color: #1e293b;
}

.panel-header p
{
    margin: 5px 0 0;

    font-size: 13px;

    color: #64748b;
}

.jumlah-jadwal
{
    background: #eff6ff;

    color: #2563eb;

    border-radius: 20px;

    padding: 6px 11px;

    font-size: 12px;

    font-weight: 600;

    white-space: nowrap;
}


/* =========================================================
   HARI
========================================================= */

.hari-section
{
    margin-bottom: 25px;
}

.hari-section:last-child
{
    margin-bottom: 0;
}

.hari-header
{
    display: flex;

    align-items: center;

    gap: 9px;

    margin-bottom: 10px;
}

.hari-dot
{
    width: 8px;

    height: 8px;

    background: #2563eb;

    border-radius: 50%;
}

.hari-title
{
    font-size: 14px;

    font-weight: 700;

    color: #1e3a8a;
}

.hari-line
{
    height: 1px;

    flex: 1;

    background: #e2e8f0;
}


/* =========================================================
   ITEM JADWAL
========================================================= */

.jadwal-items
{
    display: flex;

    flex-direction: column;

    gap: 8px;
}

.jadwal-item
{
    display: flex;

    align-items: center;

    gap: 16px;

    border: 1px solid #e2e8f0;

    border-radius: 12px;

    padding: 14px;

    transition: .2s;
}

.jadwal-item:hover
{
    border-color: #bfdbfe;

    box-shadow:
        0 4px 14px rgba(37, 99, 235, .07);
}


/* =========================================================
   JAM
========================================================= */

.jam-box
{
    min-width: 135px;

    text-align: center;

    padding-right: 15px;

    border-right: 1px solid #e2e8f0;
}

.jam
{
    font-size: 14px;

    font-weight: 700;

    color: #1e3a8a;

    white-space: nowrap;
}

.sampai
{
    font-size: 10px;

    color: #94a3b8;

    margin: 2px 0;
}


/* =========================================================
   MAPEL
========================================================= */

.mapel-info
{
    flex: 1;

    min-width: 0;
}

.mapel-nama
{
    font-size: 15px;

    font-weight: 700;

    color: #1e293b;

    margin-bottom: 5px;
}

.guru-nama
{
    font-size: 12px;

    color: #64748b;
}


/* =========================================================
   JAM KE
========================================================= */

.jam-ke
{
    text-align: center;

    padding: 0 12px;

    border-left: 1px solid #e2e8f0;

    border-right: 1px solid #e2e8f0;
}

.jam-ke span
{
    display: block;

    font-size: 10px;

    color: #94a3b8;
}

.jam-ke strong
{
    display: block;

    margin-top: 2px;

    font-size: 15px;

    color: #2563eb;
}


/* =========================================================
   PUTARAN
========================================================= */

.putaran-box
{
    min-width: 85px;

    text-align: center;
}

.putaran
{
    display: inline-block;

    padding: 5px 9px;

    border-radius: 20px;

    background: #f1f5f9;

    color: #475569;

    font-size: 11px;

    font-weight: 600;
}

.putaran.satu
{
    background: #dbeafe;

    color: #1d4ed8;
}

.putaran.dua
{
    background: #ede9fe;

    color: #6d28d9;
}


/* =========================================================
   EMPTY
========================================================= */

.empty
{
    text-align: center;

    padding: 55px 20px;

    color: #64748b;
}

.empty.small
{
    padding: 35px 20px;
}

.empty-icon
{
    font-size: 40px;

    margin-bottom: 10px;
}

.empty h3
{
    margin: 0 0 7px;

    color: #334155;

    font-size: 16px;
}

.empty p
{
    margin: 0;

    font-size: 13px;

    line-height: 1.6;
}


/* =========================================================
   TABLE JURNAL
========================================================= */

.jurnal-panel
{
    overflow: hidden;
}

.table-wrap
{
    width: 100%;

    overflow-x: auto;
}

.data-table
{
    width: 100%;

    border-collapse: collapse;

    min-width: 750px;
}

.data-table th
{
    text-align: left;

    background: #f8fafc;

    color: #475569;

    font-size: 12px;

    font-weight: 700;

    padding: 12px;
}

.data-table td
{
    padding: 13px 12px;

    border-top: 1px solid #e2e8f0;

    color: #475569;

    font-size: 13px;

    vertical-align: top;
}

.data-table td strong
{
    color: #1e3a8a;
}

.materi,
.kegiatan
{
    max-width: 230px;

    line-height: 1.5;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 850px)
{
    .page-header
    {
        align-items: flex-start;

        flex-direction: column;
    }

    .kelas-info
    {
        text-align: left;

        width: 100%;
    }

    .info-putaran
    {
        flex-wrap: wrap;
    }

    .info-tanggal
    {
        width: 100%;

        margin-left: 56px;
    }

    .jadwal-item
    {
        align-items: flex-start;

        flex-wrap: wrap;
    }

    .jam-box
    {
        border-right: none;

        border-bottom: 1px solid #e2e8f0;

        padding-right: 0;

        padding-bottom: 8px;

        width: 100%;

        display: flex;

        justify-content: flex-start;

        gap: 7px;

        min-width: 0;
    }

    .sampai
    {
        margin: 0;
    }

    .jam-ke
    {
        border-left: none;

        padding-left: 0;
    }

    .putaran-box
    {
        margin-left: auto;
    }
}


@media(max-width: 600px)
{
    .panel
    {
        padding: 15px;
    }

    .panel-header
    {
        align-items: flex-start;

        flex-direction: column;
    }

    .jumlah-jadwal
    {
        align-self: flex-start;
    }

    .info-putaran
    {
        align-items: flex-start;
    }

    .info-tanggal
    {
        margin-left: 0;
    }
}

</style>

@endsection