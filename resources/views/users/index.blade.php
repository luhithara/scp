<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Data Akun SCP</title>


    <style>

        body {

            margin: 0;

            padding: 30px;

            font-family: Arial, sans-serif;

            background: #f1f6ff;

        }


        .container {

            background: white;

            padding: 25px;

            border-radius: 15px;

            box-shadow:
                0 10px 30px
                rgba(0,0,0,0.08);

        }


        h1 {

            color: #1e3a8a;

        }


        .tambah {

            display: inline-block;

            margin-bottom: 20px;

            padding: 10px 18px;

            background: #2563eb;

            color: white;

            text-decoration: none;

            border-radius: 8px;

        }


        table {

            width: 100%;

            border-collapse: collapse;

        }


        th,
        td {

            padding: 12px;

            border-bottom:
                1px solid #e2e8f0;

            text-align: left;

        }


        th {

            background: #eff6ff;

        }


        .success {

            background: #dcfce7;

            color: #166534;

            padding: 12px;

            border-radius: 8px;

            margin-bottom: 20px;

        }

    </style>

</head>


<body>


<div class="container">


    <h1>Data Akun SCP</h1>


    @if(session('success'))

        <div class="success">

            {{ session('success') }}

        </div>

    @endif


    <a
        href="{{ route('users.create') }}"
        class="tambah"
    >
        + Tambah Akun
    </a>


    <table>

        <thead>

            <tr>

                <th>Nama</th>

                <th>NISN / Kode Pegawai</th>

                <th>Role</th>

                <th>Status</th>

            </tr>

        </thead>


        <tbody>


        @forelse($users as $user)


            <tr>

                <td>

                    {{ $user->name }}

                </td>


                <td>

                    {{ $user->nisn ?? $user->kodepegawai }}

                </td>


                <td>

                    {{ strtoupper($user->role) }}

                </td>


                <td>

                    {{ strtoupper($user->status) }}

                </td>

            </tr>


        @empty


            <tr>

                <td
                    colspan="4"
                    style="text-align:center"
                >

                    Belum ada data akun.

                </td>

            </tr>


        @endforelse


        </tbody>


    </table>


</div>


</body>

</html>