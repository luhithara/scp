<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah Akun SCP</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family: Arial, sans-serif;

            background: #f1f6ff;

        }


        .container {

            max-width: 700px;

            margin: 40px auto;

            background: white;

            padding: 30px;

            border-radius: 15px;

            box-shadow:
                0 10px 30px
                rgba(0,0,0,0.08);

        }


        h1 {

            margin-top: 0;

            color: #1e3a8a;

        }


        label {

            display: block;

            margin-top: 15px;

            margin-bottom: 7px;

            font-weight: bold;

        }


        input,
        select {

            width: 100%;

            padding: 12px;

            border: 1px solid #cbd5e1;

            border-radius: 8px;

            font-size: 15px;

        }


        button {

            margin-top: 25px;

            padding: 12px 25px;

            background: #2563eb;

            color: white;

            border: none;

            border-radius: 8px;

            cursor: pointer;

            font-size: 15px;

        }


        button:hover {

            background: #1d4ed8;

        }


        .error {

            background: #fee2e2;

            color: #991b1b;

            padding: 12px;

            border-radius: 8px;

            margin-bottom: 15px;

        }


        .back {

            display: inline-block;

            margin-bottom: 20px;

            text-decoration: none;

            color: #2563eb;

        }

    </style>

</head>


<body>


<div class="container">


    <a
        href="{{ route('users.index') }}"
        class="back"
    >
        ← Kembali
    </a>


    <h1>Tambah Akun</h1>


    @if(session('error'))

        <div class="error">

            {{ session('error') }}

        </div>

    @endif


    @if($errors->any())

        <div class="error">

            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        method="POST"
        action="{{ route('users.store') }}"
    >

        @csrf


        <label>
            Nama
        </label>

        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
            required
        >


        <label>
            Email
        </label>

        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
        >


        <label>
            Role
        </label>

        <select
            name="role"
            id="role"
            required
        >

            <option value="">
                -- Pilih Role --
            </option>

            <option value="siswa">
                Siswa
            </option>

            <option value="orangtua">
                Orang Tua
            </option>

            <option value="guru">
                Guru
            </option>

            <option value="waka">
                Waka
            </option>

            <option value="bk">
                BK
            </option>

            <option value="walikelas">
                Wali Kelas
            </option>

        </select>


        <label>
            NISN
        </label>

        <input
            type="text"
            name="nisn"
            value="{{ old('nisn') }}"
            placeholder="Khusus siswa"
        >


        <label>
            Kode Pegawai / Kode Akun
        </label>

        <input
            type="text"
            name="kodepegawai"
            value="{{ old('kodepegawai') }}"
            placeholder="Guru, Waka, BK, atau Wali Kelas"
        >


        <label>
            Password
        </label>

        <input
            type="password"
            name="password"
            required
        >


        <button type="submit">

            Simpan Akun

        </button>


    </form>


</div>


</body>

</html>