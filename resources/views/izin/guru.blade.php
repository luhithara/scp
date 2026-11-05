<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Perizinan Siswa - SCP</title>

    <style>

        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f5f7fb;
            color: #1f2937;
        }

        .navbar {
            background: white;
            padding: 18px 25px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            box-shadow: 0 2px 10px rgba(0,0,0,.05);
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .user-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            padding: 25px;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;

            color: #2563eb;
            text-decoration: none;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;

            box-shadow: 0 3px 12px rgba(0,0,0,.05);
        }

        h1,
        h2 {
            margin-top: 0;
        }

        .info {
            background: #eff6ff;
            color: #1e40af;

            padding: 15px;
            border-radius: 10px;

            margin-bottom: 20px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 750px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px;

            text-align: left;

            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;
        }

        .izin {
            background: #fef3c7;
            color: #92400e;
        }

        .sakit {
            background: #dbeafe;
            color: #1e40af;
        }

        .kosong {
            text-align: center;
            padding: 40px;

            color: #6b7280;
        }

        .logout {
            border: none;

            background: #ef4444;

            color: white;

            padding: 9px 15px;

            border-radius: 8px;

            cursor: pointer;
        }

        @media (max-width: 600px) {

            .navbar {
                padding: 15px;
            }

            .user-area span {
                display: none;
            }

            .container {
                padding: 15px;
            }

        }

    </style>

</head>


<body>


<nav class="navbar">

    <div class="logo">
        SCP
    </div>


    <div class="user-area">

        <span>
            {{ $user->name }}
        </span>


        <form
            action="{{ route('logout') }}"
            method="POST"
        >

            @csrf

            <button
                type="submit"
                class="logout"
            >
                Keluar
            </button>

        </form>

    </div>

</nav>



<div class="container">


    <a
        href="{{ route('guru.dashboard') }}"
        class="back"
    >
        ← Kembali ke Dashboard Guru
    </a>



    <div class="card">

        <h1>
            📨 Perizinan Siswa
        </h1>

        <p>
            Daftar izin dan sakit siswa yang
            telah masuk ke sistem.
        </p>

    </div>



    <div class="info">

        <strong>
            Informasi
        </strong>

        <br>

        Perizinan siswa tercatat otomatis
        berdasarkan tanggal pengajuan.
        Tidak memerlukan persetujuan guru.

    </div>



    <div class="card">

        <h2>
            Perizinan Hari Ini
        </h2>


        @php

            $izinHariIni = $izin->filter(function ($item) {

                return \Carbon\Carbon::parse(
                    $item->tanggal
                )->isToday();

            });

        @endphp


        @if($izinHariIni->count() > 0)

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                NISN
                            </th>

                            <th>
                                Nama Siswa
                            </th>

                            <th>
                                Kelas
                            </th>

                            <th>
                                Jenis
                            </th>

                            <th>
                                Alasan
                            </th>

                            <th>
                                Waktu
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @foreach($izinHariIni as $index => $item)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>
                                {{ $item->siswa->nisn ?? '-' }}
                            </td>

                            <td>
                                {{ $item->siswa->nama ?? '-' }}
                            </td>

                            <td>
                                {{ $item->siswa->kelas->namakelas ?? '-' }}
                            </td>

                            <td>

                                @if($item->jenis === 'izin')

                                    <span class="badge izin">
                                        Izin
                                    </span>

                                @else

                                    <span class="badge sakit">
                                        Sakit
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $item->alasan }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}
                            </td>

                        </tr>

                    @endforeach


                    </tbody>

                </table>

            </div>

        @else

            <div class="kosong">

                📭

                <p>
                    Belum ada siswa yang mengajukan
                    izin atau sakit hari ini.
                </p>

            </div>

        @endif

    </div>



    <div class="card">

        <h2>
            Riwayat Semua Perizinan
        </h2>


        @if($izin->count() > 0)

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                NISN
                            </th>

                            <th>
                                Nama Siswa
                            </th>

                            <th>
                                Jenis
                            </th>

                            <th>
                                Alasan
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    @foreach($izin as $index => $item)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                            </td>

                            <td>
                                {{ $item->siswa->nisn ?? '-' }}
                            </td>

                            <td>
                                {{ $item->siswa->nama ?? '-' }}
                            </td>

                            <td>

                                @if($item->jenis === 'izin')

                                    <span class="badge izin">
                                        Izin
                                    </span>

                                @else

                                    <span class="badge sakit">
                                        Sakit
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $item->alasan }}
                            </td>

                        </tr>

                    @endforeach


                    </tbody>

                </table>

            </div>

        @else

            <div class="kosong">

                Belum ada riwayat perizinan.

            </div>

        @endif

    </div>


</div>


</body>

</html>