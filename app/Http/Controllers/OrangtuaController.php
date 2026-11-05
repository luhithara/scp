<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class OrangtuaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CEK AKUN ORANGTUA
    |--------------------------------------------------------------------------
    */

    private function cekOrangtua()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role !== 'orangtua') {
            abort(
                403,
                'Halaman ini khusus untuk akun orangtua.'
            );
        }

        return $user;
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL DATA ANAK
    |--------------------------------------------------------------------------
    |
    | Akun orangtua terhubung dengan anak melalui:
    |
    | users.nisn = siswa.nisn
    |
    */

    private function ambilAnak($user)
    {
        return DB::table('siswa')
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
            ->where(
                'siswa.nisn',
                $user->nisn
            )
            ->select(
                'siswa.*',
                'kelas.namakelas as namakelas',
                'kelas.tingkat as tingkat',
                'jurusan.namajurusan as namajurusan'
            )
            ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT JAM
    |--------------------------------------------------------------------------
    */

    private function formatJam($jam)
    {
        if (
            $jam === null ||
            $jam === ''
        ) {
            return '00:00';
        }

        if (
            is_string($jam) &&
            str_contains($jam, ':')
        ) {
            return substr(
                $jam,
                0,
                5
            );
        }

        return str_pad(
            (string) $jam,
            2,
            '0',
            STR_PAD_LEFT
        ) . ':00';
    }


    /*
    |--------------------------------------------------------------------------
    | HARI SEKARANG
    |--------------------------------------------------------------------------
    */

    private function hariSekarang()
    {
        return strtolower(
            Carbon::now()
                ->locale('id')
                ->dayName
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL JADWAL ANAK
    |--------------------------------------------------------------------------
    */

    private function ambilJadwalAnak(
        $anak,
        $hanyaHariIni = false
    ) {
        if (!$anak) {
            return collect();
        }

        $tanggalHariIni =
            Carbon::today()->toDateString();


        $query = DB::table('jadwal')

            ->leftJoin(
                'guru',
                'jadwal.guruid',
                '=',
                'guru.id'
            )

            ->leftJoin(
                'mapel',
                'jadwal.mapelid',
                '=',
                'mapel.id'
            )

            ->leftJoin(
                'ruangan',
                'jadwal.ruanganid',
                '=',
                'ruangan.id'
            )

            ->where(
                'jadwal.kelasid',
                $anak->kelasid
            )

            /*
            |------------------------------------------------------------------
            | HANYA BLOK YANG SEDANG BERJALAN
            |------------------------------------------------------------------
            */

            ->whereDate(
                'jadwal.tanggalMulaiBlok',
                '<=',
                $tanggalHariIni
            )

            ->whereDate(
                'jadwal.tanggalSelesaiBlok',
                '>=',
                $tanggalHariIni
            )

            ->select(
                'jadwal.*',

                'guru.nama as namaguru',

                'mapel.namamapel as namamapelmaster',

                'ruangan.namaruangan as namaruangan'
            );


        if ($hanyaHariIni) {

            $query->where(
                'jadwal.hari',
                $this->hariSekarang()
            );
        }


        $jadwal = $query
            ->orderBy(
                'jadwal.jamMulai'
            )
            ->get();


        /*
        |------------------------------------------------------------------
        | RAPKAN DATA JADWAL
        |------------------------------------------------------------------
        */

        $jadwal->transform(
            function ($item) {

                $item->jamMulai =
                    $this->formatJam(
                        $item->jamMulai
                    );

                $item->jamSelesai =
                    $this->formatJam(
                        $item->jamSelesai
                    );


                /*
                | Jika mapel master kosong,
                | gunakan nama mapel yang diketik guru.
                */

                if (
                    empty(
                        $item->namamapelmaster
                    ) &&
                    !empty(
                        $item->namamapel
                    )
                ) {
                    $item->namamapelmaster =
                        $item->namamapel;
                }


                if (
                    empty(
                        $item->namaguru
                    )
                ) {
                    $item->namaguru = '-';
                }


                if (
                    empty(
                        $item->namaruangan
                    )
                ) {
                    $item->namaruangan = null;
                }


                return $item;
            }
        );


        return $jadwal;
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ORANGTUA
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $user = $this->cekOrangtua();

        if (
            $user instanceof
            \Illuminate\Http\RedirectResponse
        ) {
            return $user;
        }


        /*
        |----------------------------------------------------------------------
        | DATA ANAK
        |----------------------------------------------------------------------
        */

        $anak = $this->ambilAnak(
            $user
        );


        if (!$anak) {
            abort(
                404,
                'Data anak tidak ditemukan.'
            );
        }


        /*
        |----------------------------------------------------------------------
        | HARI SEKARANG
        |----------------------------------------------------------------------
        */

        $hariSekarang =
            $this->hariSekarang();


        /*
        |----------------------------------------------------------------------
        | JADWAL HARI INI
        |----------------------------------------------------------------------
        */

        $jadwalHariIni =
            $this->ambilJadwalAnak(
                $anak,
                true
            );


        /*
        |----------------------------------------------------------------------
        | JUMLAH PRESENSI
        |----------------------------------------------------------------------
        */

        $jumlahHadir = DB::table(
            'presensi'
        )
            ->where(
                'siswaid',
                $anak->id
            )
            ->where(
                'status',
                'hadir'
            )
            ->count();


        $jumlahIzin = DB::table(
            'presensi'
        )
            ->where(
                'siswaid',
                $anak->id
            )
            ->where(
                'status',
                'izin'
            )
            ->count();


        $jumlahSakit = DB::table(
            'presensi'
        )
            ->where(
                'siswaid',
                $anak->id
            )
            ->where(
                'status',
                'sakit'
            )
            ->count();


        $jumlahAlpa = DB::table(
            'presensi'
        )
            ->where(
                'siswaid',
                $anak->id
            )
            ->where(
                'status',
                'alpa'
            )
            ->count();


        /*
        |----------------------------------------------------------------------
        | PRESENSI HARI INI
        |----------------------------------------------------------------------
        |
        | PENTING:
        | Tabel presensi tidak mempunyai kolom "tanggal".
        |
        | Karena itu tanggal pencatatan menggunakan:
        |
        | presensi.created_at
        |
        */

        $presensiHariIni = DB::table(
            'presensi'
        )

            ->leftJoin(
                'jadwal',
                'presensi.jadwalid',
                '=',
                'jadwal.id'
            )

            ->leftJoin(
                'guru',
                'jadwal.guruid',
                '=',
                'guru.id'
            )

            ->leftJoin(
                'mapel',
                'jadwal.mapelid',
                '=',
                'mapel.id'
            )

            ->where(
                'presensi.siswaid',
                $anak->id
            )

            ->whereDate(
                'presensi.created_at',
                Carbon::today()
            )

            ->select(
                'presensi.*',

                'guru.nama as namaguru',

                'mapel.namamapel as namamapelmaster',

                'jadwal.namamapel'
            )

            ->orderBy(
                'presensi.waktu'
            )

            ->get();


        /*
        |----------------------------------------------------------------------
        | RIWAYAT IZIN TERBARU
        |----------------------------------------------------------------------
        */

        $izinTerbaru = DB::table(
            'izin_siswa'
        )
            ->where(
                'siswaid',
                $anak->id
            )
            ->orderByDesc(
                'tanggal'
            )
            ->orderByDesc(
                'waktu'
            )
            ->limit(5)
            ->get();


        /*
        |----------------------------------------------------------------------
        | KIRIM DATA KE DASHBOARD
        |----------------------------------------------------------------------
        */

        return view(
            'orangtua.dashboard',
            compact(
                'user',
                'anak',
                'hariSekarang',
                'jadwalHariIni',
                'presensiHariIni',
                'jumlahHadir',
                'jumlahIzin',
                'jumlahSakit',
                'jumlahAlpa',
                'izinTerbaru'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | JADWAL ANAK
    |--------------------------------------------------------------------------
    */

    public function jadwal()
    {
        $user = $this->cekOrangtua();

        if (
            $user instanceof
            \Illuminate\Http\RedirectResponse
        ) {
            return $user;
        }


        $anak = $this->ambilAnak(
            $user
        );


        if (!$anak) {
            abort(
                404,
                'Data anak tidak ditemukan.'
            );
        }


        $jadwal =
            $this->ambilJadwalAnak(
                $anak
            );


        return view(
            'orangtua.jadwal',
            compact(
                'user',
                'anak',
                'jadwal'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PRESENSI ANAK
    |--------------------------------------------------------------------------
    */

    public function presensi()
    {
        $user = $this->cekOrangtua();

        if (
            $user instanceof
            \Illuminate\Http\RedirectResponse
        ) {
            return $user;
        }


        $anak = $this->ambilAnak(
            $user
        );


        if (!$anak) {
            abort(
                404,
                'Data anak tidak ditemukan.'
            );
        }


        /*
        |----------------------------------------------------------------------
        | AMBIL SELURUH RIWAYAT PRESENSI ANAK
        |----------------------------------------------------------------------
        |
        | Tidak menggunakan presensi.tanggal
        | karena kolom tersebut memang tidak ada.
        |
        */

        $presensi = DB::table(
            'presensi'
        )

            ->leftJoin(
                'jadwal',
                'presensi.jadwalid',
                '=',
                'jadwal.id'
            )

            ->leftJoin(
                'guru',
                'jadwal.guruid',
                '=',
                'guru.id'
            )

            ->leftJoin(
                'mapel',
                'jadwal.mapelid',
                '=',
                'mapel.id'
            )

            ->where(
                'presensi.siswaid',
                $anak->id
            )

            ->select(
                'presensi.*',

                'guru.nama as namaguru',

                'mapel.namamapel as namamapelmaster',

                'jadwal.namamapel'
            )

            /*
            |------------------------------------------------------------------
            | URUTKAN BERDASARKAN CREATED_AT
            |------------------------------------------------------------------
            */

            ->orderByDesc(
                'presensi.created_at'
            )

            ->orderByDesc(
                'presensi.waktu'
            )

            ->get();


        /*
        |----------------------------------------------------------------------
        | HITUNG REKAP PRESENSI
        |----------------------------------------------------------------------
        */

        $jumlahHadir = DB::table(
            'presensi'
        )
            ->where(
                'siswaid',
                $anak->id
            )
            ->where(
                'status',
                'hadir'
            )
            ->count();


        $jumlahIzin = DB::table(
            'presensi'
        )
            ->where(
                'siswaid',
                $anak->id
            )
            ->where(
                'status',
                'izin'
            )
            ->count();


        $jumlahSakit = DB::table(
            'presensi'
        )
            ->where(
                'siswaid',
                $anak->id
            )
            ->where(
                'status',
                'sakit'
            )
            ->count();


        $jumlahAlpa = DB::table(
            'presensi'
        )
            ->where(
                'siswaid',
                $anak->id
            )
            ->where(
                'status',
                'alpa'
            )
            ->count();


        /*
        |----------------------------------------------------------------------
        | TAMPILKAN HALAMAN PRESENSI ORANGTUA
        |----------------------------------------------------------------------
        */

        return view(
            'orangtua.presensi',
            compact(
                'user',
                'anak',
                'presensi',
                'jumlahHadir',
                'jumlahIzin',
                'jumlahSakit',
                'jumlahAlpa'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFIL ORANGTUA
    |--------------------------------------------------------------------------
    |
    | HALAMAN INI KHUSUS PROFIL ORANGTUA.
    |
    | Data anak hanya menjadi informasi anak
    | yang terhubung dengan akun orangtua.
    |
    */

    public function profil()
    {
        $user = $this->cekOrangtua();

        if (
            $user instanceof
            \Illuminate\Http\RedirectResponse
        ) {
            return $user;
        }


        /*
        |----------------------------------------------------------------------
        | AMBIL DATA ANAK
        |----------------------------------------------------------------------
        */

        $anak = $this->ambilAnak(
            $user
        );


        if (!$anak) {
            abort(
                404,
                'Data anak tidak ditemukan.'
            );
        }


        /*
        |----------------------------------------------------------------------
        | AMBIL DATA ORANGTUA
        |----------------------------------------------------------------------
        |
        | Relasi:
        |
        | orangtuas.siswaid = siswa.id
        |
        */

        $orangtua = DB::table(
            'orangtuas'
        )
            ->where(
                'siswaid',
                $anak->id
            )
            ->first();


        /*
        |----------------------------------------------------------------------
        | VIEW PROFIL ORANGTUA
        |----------------------------------------------------------------------
        */

        return view(
            'orangtua.profil',
            compact(
                'user',
                'orangtua',
                'anak'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM EDIT PROFIL ORANGTUA
    |--------------------------------------------------------------------------
    */

    public function editProfil()
    {
        $user = $this->cekOrangtua();

        if (
            $user instanceof
            \Illuminate\Http\RedirectResponse
        ) {
            return $user;
        }


        $anak = $this->ambilAnak(
            $user
        );


        if (!$anak) {
            abort(
                404,
                'Data anak tidak ditemukan.'
            );
        }


        $orangtua = DB::table(
            'orangtuas'
        )
            ->where(
                'siswaid',
                $anak->id
            )
            ->first();


        return view(
            'orangtua.profil-edit',
            compact(
                'user',
                'orangtua',
                'anak'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN PERUBAHAN PROFIL ORANGTUA
    |--------------------------------------------------------------------------
    */

    public function updateProfil(
        Request $request
    ) {
        $user = $this->cekOrangtua();

        if (
            $user instanceof
            \Illuminate\Http\RedirectResponse
        ) {
            return $user;
        }


        $anak = $this->ambilAnak(
            $user
        );


        if (!$anak) {
            abort(
                404,
                'Data anak tidak ditemukan.'
            );
        }


        /*
        |----------------------------------------------------------------------
        | VALIDASI
        |----------------------------------------------------------------------
        */

        $request->validate([

            'nama' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(
                    'users',
                    'email'
                )->ignore(
                    $user->id
                ),
            ],

            'hubungan' => [
                'required',
                Rule::in([
                    'ayah',
                    'ibu',
                    'wali',
                ]),
            ],

            'nomortelepon' => [
                'nullable',
                'string',
                'max:20',
            ],

            'alamat' => [
                'nullable',
                'string',
                'max:500',
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
                'confirmed',
            ],

        ], [

            'nama.required' =>
                'Nama orangtua wajib diisi.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.unique' =>
                'Email tersebut sudah digunakan akun lain.',

            'hubungan.required' =>
                'Hubungan dengan anak wajib dipilih.',

            'hubungan.in' =>
                'Hubungan orangtua tidak valid.',

            'password.min' =>
                'Password minimal 6 karakter.',

            'password.confirmed' =>
                'Konfirmasi password tidak sama.',

        ]);


        /*
        |----------------------------------------------------------------------
        | UPDATE USERS
        |----------------------------------------------------------------------
        */

        $dataUser = [

            'name' =>
                $request->nama,

            'email' =>
                $request->email,

            'updated_at' =>
                now(),

        ];


        if (
            $request->filled(
                'password'
            )
        ) {

            $dataUser['password'] =
                Hash::make(
                    $request->password
                );
        }


        DB::table('users')
            ->where(
                'id',
                $user->id
            )
            ->update(
                $dataUser
            );


        /*
        |----------------------------------------------------------------------
        | UPDATE DATA ORANGTUA
        |----------------------------------------------------------------------
        */

        $dataOrangtua = [

            'nama' =>
                $request->nama,

            'hubungan' =>
                $request->hubungan,

            'nomortelepon' =>
                $request->nomortelepon,

            'alamat' =>
                $request->alamat,

            'updated_at' =>
                now(),

        ];


        $orangtuaAda = DB::table(
            'orangtuas'
        )
            ->where(
                'siswaid',
                $anak->id
            )
            ->exists();


        if ($orangtuaAda) {

            DB::table(
                'orangtuas'
            )
                ->where(
                    'siswaid',
                    $anak->id
                )
                ->update(
                    $dataOrangtua
                );
        } else {

            DB::table(
                'orangtuas'
            )->insert([

                'siswaid' =>
                    $anak->id,

                'nama' =>
                    $request->nama,

                'hubungan' =>
                    $request->hubungan,

                'nomortelepon' =>
                    $request->nomortelepon,

                'alamat' =>
                    $request->alamat,

                'created_at' =>
                    now(),

                'updated_at' =>
                    now(),

            ]);
        }


        /*
        |----------------------------------------------------------------------
        | KEMBALI KE PROFIL
        |----------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'orangtua.profil'
            )
            ->with(
                'success',
                'Profil orangtua berhasil diperbarui.'
            );
    }
}