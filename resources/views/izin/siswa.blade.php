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
            max-width: 1000px;
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

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        select,
        textarea {
            width: 100%;
            padding: 12px;

            border: 1px solid #d1d5db;
            border-radius: 8px;

            font-size: 14px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .button {
            border: none;

            background: #2563eb;

            color: white;

            padding: 11px 18px;

            border-radius: 8px;

            cursor: pointer;
        }

        .success {
            background: #dcfce7;
            color: #166534;

            padding: 13px;
            border-radius: 8px;

            margin-bottom: 20px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;

            padding: 13px;
            border-radius: 8px;

            margin-bottom: 20px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 650px;
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
            padding: 35px;
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
        href="{{ route('dashboard') }}"
        class="back"
    >
        ← Kembali ke Dashboard
    </a>



    <div class="card">

        <h1>
            📨 Perizinan Siswa
        </h1>

        <p>
            Nama:
            <strong>
                {{ $siswa->nama }}
            </strong>
        </p>

        <p>
            NISN:
            <strong>
                {{ $siswa->nisn }}
            </strong>
        </p>

    </div>



    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif


    @if($errors->any())

        <div class="error">

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif



    @if($user->role === 'siswa')

        <div class="card">

            <h2>
                Ajukan Izin / Sakit
            </h2>

            <p>
                Perizinan otomatis tercatat untuk
                <strong>hari ini</strong>.
            </p>


            <form
                action="{{ route('izin.store') }}"
                method="POST"
            >

                @csrf


                <div class="form-group">

                    <label>
                        Jenis
                    </label>

                    <select
                        name="jenis"
                        required
                    >

                        <option value="">
                            -- Pilih --
                        </option>

                        <option value="izin">
                            Izin
                        </option>

                        <option value="sakit">
                            Sakit
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label>
                        Alasan
                    </label>

                    <textarea
                        name="alasan"
                        placeholder="Masukkan alasan..."
                        required
                    >{{ old('alasan') }}</textarea>

                </div>


                <button
                    type="submit"
                    class="button"
                >
                    Kirim Perizinan
                </button>


            </form>

        </div>

    @endif



    <div class="card">

        <h2>
            Riwayat Perizinan
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
                                Waktu
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
                                {{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}
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

                📭

                <p>
                    Belum ada perizinan.
                </p>

            </div>

        @endif

    </div>


</div>


</body>

</html>