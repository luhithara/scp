@extends('layouts.app')

@section('content')

<div class="presensi-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="pagehead">

        <h1>
            Riwayat Presensi
        </h1>

        <p>
            Rekap kehadiran {{ $siswa->nama ?? 'Siswa' }}.
        </p>

    </div>


    {{-- =========================================================
         PANEL TABEL
    ========================================================== --}}

    <div class="panel">

        <div class="tablewrap">

            <table class="table">

                <thead>

                    <tr>

                        <th>
                            Hari
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Keterangan
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($presensi as $item)

                        @php

                            /*
                             * =================================================
                             * TANGGAL
                             * =================================================
                             */

                            $tanggal = $item->waktu
                                ? \Carbon\Carbon::parse($item->waktu)
                                : null;


                            /*
                             * =================================================
                             * NAMA HARI INDONESIA
                             * =================================================
                             */

                            $hari = [

                                'Sunday'    => 'Minggu',

                                'Monday'    => 'Senin',

                                'Tuesday'   => 'Selasa',

                                'Wednesday' => 'Rabu',

                                'Thursday'  => 'Kamis',

                                'Friday'    => 'Jumat',

                                'Saturday'  => 'Sabtu',

                            ];


                            /*
                             * =================================================
                             * STATUS
                             * =================================================
                             */

                            $status = strtolower(
                                trim($item->status ?? '')
                            );


                            /*
                             * =================================================
                             * WARNA STATUS
                             * =================================================
                             */

                            if ($status === 'hadir') {

                                $statusClass = 'success';

                            } elseif (
                                $status === 'alpa' ||
                                $status === 'alpha'
                            ) {

                                $statusClass = 'danger';

                            } else {

                                $statusClass = 'warning';

                            }


                            /*
                             * =================================================
                             * KETERANGAN
                             * =================================================
                             *
                             * Controller sudah menggabungkan:
                             *
                             * presensi.keterangan
                             * +
                             * izin_siswa.alasan
                             *
                             * Jadi Blade cukup membaca:
                             *
                             * $item->keterangan
                             */

                            $keterangan = trim(
                                (string) ($item->keterangan ?? '')
                            );

                        @endphp


                        <tr>

                            {{-- =================================================
                                 HARI
                            ================================================== --}}

                            <td>

                                {{ $tanggal
                                    ? ($hari[$tanggal->format('l')] ?? $tanggal->format('l'))
                                    : '-'
                                }}

                            </td>


                            {{-- =================================================
                                 TANGGAL
                            ================================================== --}}

                            <td>

                                {{ $tanggal
                                    ? $tanggal->format('d/m/Y')
                                    : '-'
                                }}

                            </td>


                            {{-- =================================================
                                 STATUS
                            ================================================== --}}

                            <td>

                                <span class="badge {{ $statusClass }}">

                                    {{ ucfirst($item->status ?? '-') }}

                                </span>

                            </td>


                            {{-- =================================================
                                 KETERANGAN
                            ================================================== --}}

                            <td>

                                @if($keterangan !== '')

                                    <div class="keterangan">

                                        {{ $keterangan }}

                                    </div>

                                @else

                                    <span class="tidak-ada">

                                        Tidak ada keterangan

                                    </span>

                                @endif

                            </td>

                        </tr>


                    @empty

                        {{-- =================================================
                             DATA KOSONG
                        ================================================== --}}

                        <tr>

                            <td
                                colspan="4"
                                class="empty"
                            >

                                Belum ada data presensi.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<style>

/* =========================================================
   HALAMAN
========================================================= */

.presensi-page
{
    width: 100%;
}


/* =========================================================
   HEADER
========================================================= */

.pagehead
{
    margin-bottom: 20px;
}

.pagehead h1
{
    margin: 0 0 6px;

    font-size: 26px;

    color: #1e293b;
}

.pagehead p
{
    margin: 0;

    color: #64748b;

    font-size: 14px;
}


/* =========================================================
   PANEL
========================================================= */

.panel
{
    background: white;

    border-radius: 16px;

    padding: 20px;

    border: 1px solid #e2e8f0;

    box-shadow:
        0 5px 20px rgba(15, 23, 42, .04);
}


/* =========================================================
   TABEL WRAPPER
========================================================= */

.tablewrap
{
    width: 100%;

    overflow-x: auto;
}


/* =========================================================
   TABEL
========================================================= */

.table
{
    width: 100%;

    border-collapse: collapse;

    min-width: 600px;
}


/* =========================================================
   HEADER TABEL
========================================================= */

.table th
{
    text-align: left;

    background: #f1f5f9;

    color: #475569;

    font-size: 13px;

    font-weight: 600;

    padding: 13px 12px;

    white-space: nowrap;
}


/* =========================================================
   ISI TABEL
========================================================= */

.table td
{
    padding: 13px 12px;

    border-bottom: 1px solid #e2e8f0;

    color: #334155;

    font-size: 13px;

    vertical-align: top;
}


/* =========================================================
   BARIS TERAKHIR
========================================================= */

.table tr:last-child td
{
    border-bottom: none;
}


/* =========================================================
   HOVER
========================================================= */

.table tbody tr:hover
{
    background: #f8fafc;
}


/* =========================================================
   BADGE STATUS
========================================================= */

.badge
{
    display: inline-block;

    padding: 5px 10px;

    border-radius: 20px;

    font-size: 12px;

    font-weight: 600;

    white-space: nowrap;
}


/* =========================================================
   HADIR
========================================================= */

.badge.success
{
    background: #dcfce7;

    color: #166534;
}


/* =========================================================
   IZIN / SAKIT
========================================================= */

.badge.warning
{
    background: #fef3c7;

    color: #92400e;
}


/* =========================================================
   ALPA
========================================================= */

.badge.danger
{
    background: #fee2e2;

    color: #991b1b;
}


/* =========================================================
   KETERANGAN
========================================================= */

.keterangan
{
    max-width: 350px;

    line-height: 1.6;

    color: #334155;

    white-space: normal;

    word-break: break-word;
}


/* =========================================================
   TIDAK ADA KETERANGAN
========================================================= */

.tidak-ada
{
    color: #94a3b8;

    font-style: italic;
}


/* =========================================================
   DATA KOSONG
========================================================= */

.empty
{
    text-align: center;

    padding: 45px 20px !important;

    color: #64748b !important;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 700px)
{

    .pagehead h1
    {
        font-size: 22px;
    }


    .panel
    {
        padding: 15px;
    }


    .table
    {
        min-width: 550px;
    }


    .table th,
    .table td
    {
        padding: 11px 10px;
    }


    .keterangan
    {
        max-width: 250px;
    }

}

</style>

@endsection