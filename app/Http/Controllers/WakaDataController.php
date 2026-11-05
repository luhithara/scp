<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WakaDataController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CEK AKSES WAKA
    |--------------------------------------------------------------------------
    */

    private function cekAksesWaka()
    {
        if (!Auth::check()) {
            abort(403, 'Anda belum login.');
        }

        if (strtolower(Auth::user()->role ?? '') !== 'waka') {
            abort(403, 'Akses hanya untuk Waka.');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | MEMBUAT KODE LOGIN KELAS
    |--------------------------------------------------------------------------
    |
    | Contoh:
    | XI RPL 1 menjadi XI-RPL-1
    |
    */

    private function buatKodeKelas(string $namaKelas): string
    {
        $kode = strtoupper(
            preg_replace('/[^A-Za-z0-9]+/', '-', trim($namaKelas))
        );

        return trim($kode, '-');
    }


    /*
    |--------------------------------------------------------------------------
    | DATA SEKOLAH
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $this->cekAksesWaka();

        $jurusan = DB::table('jurusan')
            ->orderBy('kodejurusan')
            ->get();

        $kelas = DB::table('kelas')
            ->leftJoin(
                'jurusan',
                'kelas.jurusanid',
                '=',
                'jurusan.id'
            )
            ->select(
                'kelas.*',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->orderBy('kelas.tingkat')
            ->orderBy('kelas.namakelas')
            ->get();

        $siswa = DB::table('siswa')
            ->leftJoin(
                'kelas',
                'siswa.kelasid',
                '=',
                'kelas.id'
            )
            ->select(
                'siswa.*',
                'kelas.namakelas',
                'kelas.tingkat'
            )
            ->orderBy('siswa.nama')
            ->get();

        $guru = DB::table('guru')
            ->orderBy('nama')
            ->get();

        $orangtua = DB::table('orangtuas')
            ->leftJoin(
                'siswa',
                'orangtuas.siswaid',
                '=',
                'siswa.id'
            )
            ->select(
                'orangtuas.*',
                'siswa.nama as nama_siswa'
            )
            ->orderBy('orangtuas.nama')
            ->get();

        return view(
            'waka.data',
            compact(
                'jurusan',
                'kelas',
                'siswa',
                'guru',
                'orangtua'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATA ORANGTUA
    |--------------------------------------------------------------------------
    */

    public function orangtua(Request $request)
    {
        $this->cekAksesWaka();

        $kataKunci = trim(
            $request->input('cari', '')
        );

        $dataSiswa = DB::table('siswa')
            ->leftJoin(
                'kelas',
                'siswa.kelasid',
                '=',
                'kelas.id'
            )
            ->leftJoin(
                'jurusan',
                'kelas.jurusanid',
                '=',
                'jurusan.id'
            )
            ->select(
                'siswa.id',
                'siswa.nisn',
                'siswa.nama',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->orderBy('siswa.nama')
            ->get();

        $queryOrangtua = DB::table('orangtuas')
            ->leftJoin(
                'siswa',
                'orangtuas.siswaid',
                '=',
                'siswa.id'
            )
            ->leftJoin(
                'kelas',
                'siswa.kelasid',
                '=',
                'kelas.id'
            )
            ->leftJoin(
                'jurusan',
                'kelas.jurusanid',
                '=',
                'jurusan.id'
            )
            ->select(
                'orangtuas.*',
                'siswa.nama as namasiswa',
                'siswa.nisn as nisnanak',
                'kelas.namakelas',
                'kelas.tingkat',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            );

        if ($kataKunci !== '') {
            $queryOrangtua->where(function ($query) use ($kataKunci) {
                $query
                    ->where(
                        'orangtuas.nama',
                        'like',
                        '%' . $kataKunci . '%'
                    )
                    ->orWhere(
                        'orangtuas.nomortelepon',
                        'like',
                        '%' . $kataKunci . '%'
                    )
                    ->orWhere(
                        'siswa.nama',
                        'like',
                        '%' . $kataKunci . '%'
                    )
                    ->orWhere(
                        'siswa.nisn',
                        'like',
                        '%' . $kataKunci . '%'
                    )
                    ->orWhere(
                        'kelas.namakelas',
                        'like',
                        '%' . $kataKunci . '%'
                    )
                    ->orWhere(
                        'jurusan.kodejurusan',
                        'like',
                        '%' . $kataKunci . '%'
                    );
            });
        }

        $dataOrangtua = $queryOrangtua
            ->orderBy('orangtuas.nama')
            ->get();

        $jumlahOrangtua = DB::table('orangtuas')->count();

        return view(
            'waka.orangtua',
            compact(
                'dataOrangtua',
                'dataSiswa',
                'jumlahOrangtua',
                'kataKunci'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN SISWA
    |--------------------------------------------------------------------------
    */

    public function simpanSiswa(Request $request)
    {
        $this->cekAksesWaka();

        $validated = $request->validate([
            'nisn' => 'required|string|max:50|unique:siswa,nisn',
            'nama' => 'required|string|max:255',
            'jeniskelamin' => 'required|string|max:20',
            'tanggallahir' => 'nullable|date',
            'tempatlahir' => 'nullable|string|max:100',
            'nomortelepon' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'kelasid' => 'required|exists:kelas,id',
        ]);

        DB::transaction(function () use ($validated) {
            DB::table('siswa')->insertGetId([
                'nisn' => $validated['nisn'],
                'nama' => $validated['nama'],
                'jeniskelamin' => $validated['jeniskelamin'],
                'tanggallahir' => $validated['tanggallahir'] ?? null,
                'tempatlahir' => $validated['tempatlahir'] ?? null,
                'nomortelepon' => $validated['nomortelepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'kelasid' => $validated['kelasid'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->insert([
                'name' => $validated['nama'],
                'email' => $validated['nisn'] . '@siswa.scp.local',
                'nisn' => $validated['nisn'],
                'kodepegawai' => null,
                'role' => 'siswa',
                'status' => 'aktif',
                'password' => Hash::make($validated['nisn']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()
            ->route('waka.siswa')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT SISWA
    |--------------------------------------------------------------------------
    */

    public function editSiswa($id)
    {
        $this->cekAksesWaka();

        $siswa = DB::table('siswa')
            ->where('id', $id)
            ->first();

        if (!$siswa) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        $kelas = DB::table('kelas')
            ->leftJoin(
                'jurusan',
                'kelas.jurusanid',
                '=',
                'jurusan.id'
            )
            ->select(
                'kelas.*',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->orderBy('kelas.tingkat')
            ->orderBy('kelas.namakelas')
            ->get();

        return view('waka.siswa-edit', compact('siswa', 'kelas'));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SISWA
    |--------------------------------------------------------------------------
    */

    public function updateSiswa(Request $request, $id)
    {
        $this->cekAksesWaka();

        $siswa = DB::table('siswa')
            ->where('id', $id)
            ->first();

        if (!$siswa) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        $validated = $request->validate([
            'nisn' => 'required|string|max:50|unique:siswa,nisn,' . $id,
            'nama' => 'required|string|max:255',
            'jeniskelamin' => 'required|string|max:20',
            'tanggallahir' => 'nullable|date',
            'tempatlahir' => 'nullable|string|max:100',
            'nomortelepon' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'kelasid' => 'required|exists:kelas,id',
        ]);

        DB::transaction(function () use ($validated, $id, $siswa) {
            DB::table('siswa')
                ->where('id', $id)
                ->update([
                    'nisn' => $validated['nisn'],
                    'nama' => $validated['nama'],
                    'jeniskelamin' => $validated['jeniskelamin'],
                    'tanggallahir' => $validated['tanggallahir'] ?? null,
                    'tempatlahir' => $validated['tempatlahir'] ?? null,
                    'nomortelepon' => $validated['nomortelepon'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'kelasid' => $validated['kelasid'],
                    'updated_at' => now(),
                ]);

            DB::table('users')
                ->where('nisn', $siswa->nisn)
                ->update([
                    'name' => $validated['nama'],
                    'nisn' => $validated['nisn'],
                    'email' => $validated['nisn'] . '@siswa.scp.local',
                    'updated_at' => now(),
                ]);
        });

        return redirect()
            ->route('waka.siswa')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS SISWA
    |--------------------------------------------------------------------------
    */

    public function hapusSiswa($id)
    {
        $this->cekAksesWaka();

        $siswa = DB::table('siswa')
            ->where('id', $id)
            ->first();

        if (!$siswa) {
            return redirect()
                ->route('waka.siswa')
                ->with('error', 'Data siswa tidak ditemukan.');
        }

        DB::transaction(function () use ($id, $siswa) {
            DB::table('orangtuas')
                ->where('siswaid', $id)
                ->delete();

            DB::table('presensi')
                ->where('siswaid', $id)
                ->delete();

            DB::table('users')
                ->where('nisn', $siswa->nisn)
                ->delete();

            DB::table('siswa')
                ->where('id', $id)
                ->delete();
        });

        return redirect()
            ->route('waka.siswa')
            ->with('success', 'Data siswa berhasil dihapus.');
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN GURU
    |--------------------------------------------------------------------------
    */

    public function simpanGuru(Request $request)
    {
        $this->cekAksesWaka();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:100',
            'nomortelepon' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
        ], [
            'nama.required' => 'Nama guru wajib diisi.',
            'nip.required' => 'NIP wajib diisi karena digunakan sebagai kode guru, kode pegawai, dan password awal akun guru.',
        ]);

        $nip = trim($validated['nip']);

        $guruSudahAda = DB::table('guru')
            ->where('nip', $nip)
            ->orWhere('kodeguru', $nip)
            ->exists();

        if ($guruSudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'nip' => 'NIP tersebut sudah terdaftar sebagai guru.',
                ]);
        }

        $akunSudahAda = DB::table('users')
            ->where('kodepegawai', $nip)
            ->exists();

        if ($akunSudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'nip' => 'NIP tersebut sudah digunakan sebagai kode pegawai.',
                ]);
        }

        DB::transaction(function () use ($validated, $nip) {
            DB::table('guru')->insert([
                'kodeguru' => $nip,
                'nama' => $validated['nama'],
                'nip' => $nip,
                'nomortelepon' => $validated['nomortelepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->insert([
                'name' => $validated['nama'],
                'email' => $nip . '@guru.scp.local',
                'nisn' => null,
                'kodepegawai' => $nip,
                'role' => 'guru',
                'status' => 'aktif',
                'password' => Hash::make($nip),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()
            ->route('waka.guru')
            ->with(
                'success',
                'Data guru berhasil ditambahkan. Kode guru dan kode pegawai menggunakan NIP. Password awal akun guru adalah NIP.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT GURU
    |--------------------------------------------------------------------------
    */

    public function editGuru($id)
    {
        $this->cekAksesWaka();

        $guru = DB::table('guru')
            ->where('id', $id)
            ->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        return view('waka.guru-edit', compact('guru'));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE GURU
    |--------------------------------------------------------------------------
    */

    public function updateGuru(Request $request, $id)
    {
        $this->cekAksesWaka();

        $guru = DB::table('guru')
            ->where('id', $id)
            ->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        $validated = $request->validate([
            'kodeguru' => 'required|string|max:50|unique:guru,kodeguru,' . $id,
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:100',
            'nomortelepon' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $guru, $id) {
            DB::table('guru')
                ->where('id', $id)
                ->update([
                    'kodeguru' => $validated['kodeguru'],
                    'nama' => $validated['nama'],
                    'nip' => $validated['nip'] ?? null,
                    'nomortelepon' => $validated['nomortelepon'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'updated_at' => now(),
                ]);

            DB::table('users')
                ->where('kodepegawai', $guru->kodeguru)
                ->update([
                    'name' => $validated['nama'],
                    'kodepegawai' => $validated['kodeguru'],
                    'email' => $validated['kodeguru'] . '@guru.scp.local',
                    'updated_at' => now(),
                ]);
        });

        return redirect()
            ->route('waka.guru')
            ->with('success', 'Data guru berhasil diperbarui.');
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS GURU
    |--------------------------------------------------------------------------
    */

    public function hapusGuru($id)
    {
        $this->cekAksesWaka();

        $guru = DB::table('guru')
            ->where('id', $id)
            ->first();

        if (!$guru) {
            return redirect()
                ->route('waka.guru')
                ->with('error', 'Data guru tidak ditemukan.');
        }

        $adaJadwal = DB::table('jadwal')
            ->where('guruid', $id)
            ->exists();

        if ($adaJadwal) {
            return redirect()
                ->route('waka.guru')
                ->with(
                    'error',
                    'Guru tidak dapat dihapus karena masih memiliki jadwal mengajar.'
                );
        }

        DB::transaction(function () use ($id, $guru) {
            DB::table('guru_mapel')
                ->where('guruid', $id)
                ->delete();

            DB::table('jurnal_mengajar')
                ->where('guruid', $id)
                ->delete();

            DB::table('users')
                ->where('kodepegawai', $guru->kodeguru)
                ->delete();

            DB::table('guru')
                ->where('id', $id)
                ->delete();
        });

        return redirect()
            ->route('waka.guru')
            ->with('success', 'Data guru berhasil dihapus.');
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN KELAS + AKUN LOGIN OTOMATIS
    |--------------------------------------------------------------------------
    */

    public function simpanKelas(Request $request)
    {
        $this->cekAksesWaka();

        $validated = $request->validate([
            'namakelas' => 'required|string|max:100',
            'tingkat' => 'required|in:10,11,12',
            'jurusanid' => 'required|exists:jurusan,id',
        ]);

        $namaKelas = trim($validated['namakelas']);

        $sudahAda = DB::table('kelas')
            ->where('namakelas', $namaKelas)
            ->where('jurusanid', $validated['jurusanid'])
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'namakelas' =>
                        'Nama kelas tersebut sudah ada pada jurusan yang dipilih.',
                ]);
        }

        $kodeKelas = $this->buatKodeKelas($namaKelas);

        if ($kodeKelas === '') {
            return back()
                ->withInput()
                ->withErrors([
                    'namakelas' => 'Nama kelas tidak dapat dijadikan kode akun.',
                ]);
        }

        $kodeSudahAda = DB::table('users')
            ->where('kodekelas', $kodeKelas)
            ->exists();

        if ($kodeSudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'namakelas' =>
                        'Kode login kelas ' . $kodeKelas .
                        ' sudah digunakan. Gunakan nama kelas yang berbeda.',
                ]);
        }

        $emailKelas = strtolower($kodeKelas) . '@kelas.scp.local';

        $emailSudahAda = DB::table('users')
            ->where('email', $emailKelas)
            ->exists();

        if ($emailSudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'namakelas' =>
                        'Email akun kelas sudah digunakan. Hubungi administrator.',
                ]);
        }

        DB::transaction(function () use (
            $validated,
            $namaKelas,
            $kodeKelas,
            $emailKelas
        ) {
            $kelasId = DB::table('kelas')->insertGetId([
                'namakelas' => $namaKelas,
                'tingkat' => $validated['tingkat'],
                'jurusanid' => $validated['jurusanid'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->insert([
                'name' => $namaKelas,
                'email' => $emailKelas,
                'nisn' => null,
                'kodepegawai' => null,
                'kodekelas' => $kodeKelas,
                'kelasid' => $kelasId,
                'role' => 'kelas',
                'status' => 'aktif',
                'password' => Hash::make($kodeKelas),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()
            ->route('waka.kelas')
            ->with(
                'success',
                'Data kelas dan akun login berhasil dibuat. Kode login dan password awal: ' .
                $kodeKelas
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT KELAS
    |--------------------------------------------------------------------------
    */

    public function editKelas(Request $request, $id)
    {
        $this->cekAksesWaka();

        $kelas = DB::table('kelas')
            ->leftJoin(
                'jurusan',
                'kelas.jurusanid',
                '=',
                'jurusan.id'
            )
            ->where('kelas.id', $id)
            ->select(
                'kelas.id',
                'kelas.namakelas',
                'kelas.tingkat',
                'kelas.jurusanid',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->first();

        if (!$kelas) {
            abort(404, 'Data kelas tidak ditemukan.');
        }

        $daftarJurusan = DB::table('jurusan')
            ->select('id', 'kodejurusan', 'namajurusan')
            ->orderBy('kodejurusan')
            ->get();

        $kataKunci = trim($request->input('cari', ''));

        $querySiswa = DB::table('siswa')
            ->where('siswa.kelasid', $id)
            ->select(
                'siswa.id',
                'siswa.nisn',
                'siswa.nama',
                'siswa.jeniskelamin',
                'siswa.nomortelepon'
            );

        if ($kataKunci !== '') {
            $querySiswa->where(function ($q) use ($kataKunci) {
                $q->where(
                    'siswa.nisn',
                    'like',
                    '%' . $kataKunci . '%'
                )
                    ->orWhere(
                        'siswa.nama',
                        'like',
                        '%' . $kataKunci . '%'
                    )
                    ->orWhere(
                        'siswa.nomortelepon',
                        'like',
                        '%' . $kataKunci . '%'
                    );
            });
        }

        $dataSiswa = $querySiswa
            ->orderBy('siswa.nama')
            ->get();

        $jumlahSiswa = DB::table('siswa')
            ->where('kelasid', $id)
            ->count();

        return view(
            'waka.kelas-edit',
            compact(
                'kelas',
                'daftarJurusan',
                'dataSiswa',
                'jumlahSiswa',
                'kataKunci'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE KELAS + AKUN LOGIN
    |--------------------------------------------------------------------------
    */

    public function updateKelas(Request $request, $id)
    {
        $this->cekAksesWaka();

        $kelas = DB::table('kelas')
            ->where('id', $id)
            ->first();

        if (!$kelas) {
            abort(404, 'Data kelas tidak ditemukan.');
        }

        $validated = $request->validate([
            'namakelas' => 'required|string|max:100',
            'tingkat' => 'required|in:10,11,12',
            'jurusanid' => 'required|exists:jurusan,id',
        ]);

        $namaKelas = trim($validated['namakelas']);

        $sudahAda = DB::table('kelas')
            ->where('namakelas', $namaKelas)
            ->where('jurusanid', $validated['jurusanid'])
            ->where('id', '!=', $id)
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'namakelas' => 'Nama kelas tersebut sudah digunakan.',
                ]);
        }

        $akunKelas = DB::table('users')
            ->where('kelasid', $id)
            ->where('role', 'kelas')
            ->first();

        $kodeKelasBaru = $this->buatKodeKelas($namaKelas);

        if ($kodeKelasBaru === '') {
            return back()
                ->withInput()
                ->withErrors([
                    'namakelas' => 'Nama kelas tidak dapat dijadikan kode akun.',
                ]);
        }

        if ($akunKelas && $kodeKelasBaru !== $akunKelas->kodekelas) {
            $kodeSudahAda = DB::table('users')
                ->where('kodekelas', $kodeKelasBaru)
                ->where('id', '!=', $akunKelas->id)
                ->exists();

            if ($kodeSudahAda) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'namakelas' => 'Kode login kelas tersebut sudah digunakan.',
                    ]);
            }

            $emailBaru = strtolower($kodeKelasBaru) . '@kelas.scp.local';

            $emailSudahAda = DB::table('users')
                ->where('email', $emailBaru)
                ->where('id', '!=', $akunKelas->id)
                ->exists();

            if ($emailSudahAda) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'namakelas' => 'Email akun kelas tersebut sudah digunakan.',
                    ]);
            }
        }

        DB::transaction(function () use (
            $validated,
            $id,
            $namaKelas,
            $akunKelas,
            $kodeKelasBaru
        ) {
            DB::table('kelas')
                ->where('id', $id)
                ->update([
                    'namakelas' => $namaKelas,
                    'tingkat' => $validated['tingkat'],
                    'jurusanid' => $validated['jurusanid'],
                    'updated_at' => now(),
                ]);

            if ($akunKelas) {
                DB::table('users')
                    ->where('id', $akunKelas->id)
                    ->update([
                        'name' => $namaKelas,
                        'kodekelas' => $kodeKelasBaru,
                        'email' => strtolower($kodeKelasBaru) .
                            '@kelas.scp.local',
                        'updated_at' => now(),
                    ]);
            }
        });

        return redirect()
            ->route('waka.kelas.edit', $id)
            ->with('success', 'Data kelas berhasil diperbarui.');
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS KELAS + AKUN LOGIN
    |--------------------------------------------------------------------------
    */

    public function hapusKelas($id)
    {
        $this->cekAksesWaka();

        $kelas = DB::table('kelas')
            ->where('id', $id)
            ->first();

        if (!$kelas) {
            return redirect()
                ->route('waka.kelas')
                ->with('error', 'Data kelas tidak ditemukan.');
        }

        $adaSiswa = DB::table('siswa')
            ->where('kelasid', $id)
            ->exists();

        if ($adaSiswa) {
            return back()
                ->with(
                    'error',
                    'Kelas tidak dapat dihapus karena masih memiliki siswa.'
                );
        }

        $adaJadwal = DB::table('jadwal')
            ->where('kelasid', $id)
            ->exists();

        if ($adaJadwal) {
            return back()
                ->with(
                    'error',
                    'Kelas tidak dapat dihapus karena masih memiliki jadwal.'
                );
        }

        DB::transaction(function () use ($id) {
            DB::table('users')
                ->where('kelasid', $id)
                ->where('role', 'kelas')
                ->delete();

            DB::table('kelas')
                ->where('id', $id)
                ->delete();
        });

        return redirect()
            ->route('waka.kelas')
            ->with(
                'success',
                'Data kelas dan akun login kelas berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN ORANGTUA
    |--------------------------------------------------------------------------
    */

    public function simpanOrangtua(Request $request)
    {
        $this->cekAksesWaka();

        $validated = $request->validate([
            'siswaid' => 'required|exists:siswa,id',
            'nama' => 'required|string|max:255',
            'hubungan' => 'required|string|max:30',
            'nomortelepon' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
        ]);

        DB::table('orangtuas')->insert([
            'siswaid' => $validated['siswaid'],
            'nama' => $validated['nama'],
            'hubungan' => $validated['hubungan'],
            'nomortelepon' => $validated['nomortelepon'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('waka.orangtua')
            ->with('success', 'Data orang tua berhasil ditambahkan.');
    }
}