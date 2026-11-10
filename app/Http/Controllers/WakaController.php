<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class WakaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CEK AKSES WAKA
    |--------------------------------------------------------------------------
    */

    private function cekAksesWaka()
    {
        $user = auth()->user();

        if (!$user || strtolower(trim($user->role ?? '')) !== 'waka') {
            abort(403, 'Akses hanya untuk Wakil Kepala Sekolah.');
        }

        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD WAKA
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $this->cekAksesWaka();

        return view('waka.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | DATA GURU
    |--------------------------------------------------------------------------
    */

    public function guru(Request $request)
    {
        $this->cekAksesWaka();

        $kataKunci = trim($request->input('cari', ''));

        $query = DB::table('guru');

        if ($kataKunci !== '') {
            $query->where(function ($q) use ($kataKunci) {
                $q->where('kodeguru', 'like', "%{$kataKunci}%")
                    ->orWhere('nama', 'like', "%{$kataKunci}%")
                    ->orWhere('nip', 'like', "%{$kataKunci}%");
            });
        }

        $dataGuru = $query
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        foreach ($dataGuru as $guru) {
            $guru->daftar_mapel = DB::table('guru_mapel')
                ->join('mapel', 'guru_mapel.mapelid', '=', 'mapel.id')
                ->where('guru_mapel.guruid', $guru->id)
                ->pluck('mapel.namamapel');
        }

        $totalGuru = DB::table('guru')->count();

        $guruDenganMapel = DB::table('guru_mapel')
            ->distinct()
            ->count('guruid');

        $guruTanpaMapel = max(0, $totalGuru - $guruDenganMapel);

        $totalMapelDiampu = DB::table('guru_mapel')
            ->distinct()
            ->count('mapelid');

        $sebaranMapel = DB::table('guru_mapel')
            ->join('guru', 'guru_mapel.guruid', '=', 'guru.id')
            ->join('mapel', 'guru_mapel.mapelid', '=', 'mapel.id')
            ->select(
                'mapel.id',
                'mapel.namamapel',
                DB::raw('COUNT(DISTINCT guru_mapel.guruid) as total_guru')
            )
            ->groupBy('mapel.id', 'mapel.namamapel')
            ->orderByDesc('total_guru')
            ->orderBy('mapel.namamapel')
            ->get();

        return view('waka.guru', compact(
            'dataGuru',
            'kataKunci',
            'totalGuru',
            'guruDenganMapel',
            'guruTanpaMapel',
            'totalMapelDiampu',
            'sebaranMapel'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | DATA SISWA
    |--------------------------------------------------------------------------
    */

    public function siswa(Request $request)
    {
        $this->cekAksesWaka();

        $search = trim($request->input('search', ''));

        $query = DB::table('siswa')
            ->leftJoin('kelas', 'siswa.kelasid', '=', 'kelas.id')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->select(
                'siswa.*',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.namajurusan'
            );

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('siswa.nama', 'like', "%{$search}%")
                    ->orWhere('siswa.nisn', 'like', "%{$search}%")
                    ->orWhere('kelas.namakelas', 'like', "%{$search}%")
                    ->orWhere('jurusan.namajurusan', 'like', "%{$search}%");
            });
        }

        $dataSiswa = $query
            ->orderBy('siswa.nama')
            ->get();

        $jumlahSiswa = DB::table('siswa')->count();

        $daftarKelas = DB::table('kelas')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->select(
                'kelas.id',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.namajurusan'
            )
            ->orderBy('kelas.tingkat')
            ->orderBy('jurusan.namajurusan')
            ->orderBy('kelas.namakelas')
            ->get();

        return view('waka.siswa', compact(
            'dataSiswa',
            'jumlahSiswa',
            'daftarKelas',
            'search'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | TAMBAH SISWA + AKUN LOGIN
    |--------------------------------------------------------------------------
    */

    public function simpanSiswa(Request $request)
    {
        $this->cekAksesWaka();

        $request->validate([
            'nisn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('siswa', 'nisn'),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'jeniskelamin' => ['required', Rule::in(['L', 'P'])],
            'tempatlahir' => ['nullable', 'string', 'max:100'],
            'tanggallahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'nomortelepon' => ['nullable', 'string', 'max:20'],
            'kelasid' => ['required', 'integer', 'exists:kelas,id'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'nama.required' => 'Nama siswa wajib diisi.',
            'jeniskelamin.required' => 'Jenis kelamin wajib dipilih.',
            'kelasid.required' => 'Kelas wajib dipilih.',
            'kelasid.exists' => 'Kelas yang dipilih tidak ditemukan.',
            'email.unique' => 'Email sudah digunakan akun lain.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $nisn = trim($request->nisn);

        if (
            DB::table('users')
                ->where('nisn', $nisn)
                ->where('role', 'siswa')
                ->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'nisn' => 'Akun dengan NISN tersebut sudah tersedia.'
                ]);
        }

        $kelas = DB::table('kelas')
            ->where('id', $request->kelasid)
            ->first();

        if (!$kelas) {
            return back()
                ->withInput()
                ->withErrors([
                    'kelasid' => 'Kelas tidak ditemukan.'
                ]);
        }

        $email = $request->filled('email')
            ? trim($request->email)
            : $nisn . '@siswa.scp.local';

        if (DB::table('users')->where('email', $email)->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Email akun siswa sudah digunakan.'
                ]);
        }

        $password = $request->filled('password')
            ? $request->password
            : $nisn;

        DB::transaction(function () use (
            $request,
            $nisn,
            $email,
            $password
        ) {
            DB::table('siswa')->insert([
                'nisn' => $nisn,
                'nama' => trim($request->nama),
                'jeniskelamin' => $request->jeniskelamin,
                'tempatlahir' => $request->tempatlahir,
                'tanggallahir' => $request->tanggallahir,
                'alamat' => $request->alamat,
                'nomortelepon' => $request->nomortelepon,
                'kelasid' => $request->kelasid,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->insert([
                'name' => trim($request->nama),
                'email' => $email,
                'nisn' => $nisn,
                'password' => Hash::make($password),
                'role' => 'siswa',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()
            ->route('waka.siswa')
            ->with(
                'success',
                'Data siswa dan akun login berhasil ditambahkan. Password awal: ' . $password
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DATA KELAS
    |--------------------------------------------------------------------------
    */

    public function kelas()
    {
        $this->cekAksesWaka();

        $jumlahKelas = DB::table('kelas')->count();
        $jumlahSiswa = DB::table('siswa')->count();

        $daftarAngkatan = DB::table('kelas')
            ->select('tingkat')
            ->whereNotNull('tingkat')
            ->distinct()
            ->orderBy('tingkat')
            ->get();

        return view('waka.kelas', compact(
            'jumlahKelas',
            'jumlahSiswa',
            'daftarAngkatan'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | KELAS BERDASARKAN ANGKATAN
    |--------------------------------------------------------------------------
    */

    public function kelasAngkatan($tingkat)
    {
        $this->cekAksesWaka();

        $tingkat = (int) $tingkat;

        if (!in_array($tingkat, [10, 11, 12])) {
            abort(404);
        }

        $daftarJurusan = DB::table('jurusan')
            ->leftJoin('kelas', function ($join) use ($tingkat) {
                $join->on('jurusan.id', '=', 'kelas.jurusanid')
                    ->where('kelas.tingkat', '=', $tingkat);
            })
            ->select(
                'jurusan.id',
                'jurusan.kodejurusan',
                'jurusan.namajurusan',

                // Harus sama dengan properti yang dipanggil Blade.
                DB::raw('COUNT(kelas.id) as jumlah_kelas')
            )
            ->groupBy(
                'jurusan.id',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->orderBy('jurusan.namajurusan', 'asc')
            ->get();

        // Alias untuk kompatibilitas dengan Blade lama.
        $dataJurusan = $daftarJurusan;

        return view('waka.kelas-angkatan', compact(
            'daftarJurusan',
            'dataJurusan',
            'tingkat'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | KELAS BERDASARKAN JURUSAN
    |--------------------------------------------------------------------------
    */

    public function kelasJurusan($tingkat, $jurusanId)
    {
        $this->cekAksesWaka();

        $tingkat = (int) $tingkat;

        if (!in_array($tingkat, [10, 11, 12])) {
            abort(404);
        }

        $jurusan = DB::table('jurusan')
            ->where('id', $jurusanId)
            ->first();

        if (!$jurusan) {
            abort(404);
        }

        $dataKelas = DB::table('kelas')
            ->where('tingkat', $tingkat)
            ->where('jurusanid', $jurusanId)
            ->orderBy('namakelas')
            ->get();

        foreach ($dataKelas as $kelas) {
            $kelas->total_siswa = DB::table('siswa')
                ->where('kelasid', $kelas->id)
                ->count();
        }

        return view('waka.kelas-jurusan', compact(
            'dataKelas',
            'jurusan',
            'tingkat'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | DATA SISWA PER KELAS
    |--------------------------------------------------------------------------
    */

    public function siswaKelas(Request $request, $id)
    {
        $this->cekAksesWaka();

        $kelas = DB::table('kelas')
            ->leftJoin('jurusan', 'kelas.jurusanid', '=', 'jurusan.id')
            ->select(
                'kelas.*',
                'jurusan.namajurusan'
            )
            ->where('kelas.id', $id)
            ->first();

        if (!$kelas) {
            abort(404);
        }

        $query = DB::table('siswa')
            ->where('kelasid', $id);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $dataSiswa = $query
            ->orderBy('nama')
            ->get();

        return view('waka.kelas-siswa', compact(
            'kelas',
            'dataSiswa'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | JURNAL MENGAJAR WAKA
    |--------------------------------------------------------------------------
    */

    public function jurnal(Request $request)
    {
        $this->cekAksesWaka();

        $query = DB::table('jurnal_mengajar')
            ->leftJoin(
                'guru',
                'jurnal_mengajar.guruid',
                '=',
                'guru.id'
            )
            ->select(
                'jurnal_mengajar.*',
                'guru.nama as namaguru',
                'guru.kodeguru'
            );

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('guru.nama', 'like', "%{$search}%")
                    ->orWhere('jurnal_mengajar.materi', 'like', "%{$search}%")
                    ->orWhere('jurnal_mengajar.kegiatan', 'like', "%{$search}%")
                    ->orWhere('jurnal_mengajar.catatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate(
                'jurnal_mengajar.tanggal',
                $request->tanggal
            );
        }

        $dataJurnal = $query
            ->orderByDesc('jurnal_mengajar.tanggal')
            ->orderByDesc('jurnal_mengajar.id')
            ->paginate(10)
            ->withQueryString();

        return view('waka.jurnal', compact('dataJurnal'));
    }
}