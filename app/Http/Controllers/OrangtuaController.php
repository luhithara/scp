<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

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

        if (strtolower(trim($user->role ?? '')) !== 'orangtua') {
            abort(403, 'Halaman ini khusus untuk akun orangtua.');
        }

        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL DATA ANAK BERDASARKAN NIS
    |--------------------------------------------------------------------------
    |
    | SCP menggunakan NIS sebagai identitas siswa.
    | Tidak menggunakan NISN.
    |
    */

    private function ambilAnak($user)
    {
        if (empty($user->nis)) {
            return null;
        }

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
            ->where('siswa.nis', trim($user->nis))
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
        if ($jam === null || $jam === '') {
            return '--:--';
        }

        if (is_string($jam) && str_contains($jam, ':')) {
            return substr($jam, 0, 5);
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

        $tanggalHariIni = Carbon::today()->toDateString();

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

            // Filter periode blok.
            ->where(function ($q) use ($tanggalHariIni) {

                // Jadwal dengan periode aktif.
                $q->where(function ($sub) use ($tanggalHariIni) {

                    $sub->whereNotNull(
                        'jadwal.tanggalMulaiBlok'
                    )
                    ->whereNotNull(
                        'jadwal.tanggalSelesaiBlok'
                    )
                    ->whereDate(
                        'jadwal.tanggalMulaiBlok',
                        '<=',
                        $tanggalHariIni
                    )
                    ->whereDate(
                        'jadwal.tanggalSelesaiBlok',
                        '>=',
                        $tanggalHariIni
                    );

                })

                // Jadwal lama tanpa periode blok.
                ->orWhere(function ($sub) {

                    $sub->whereNull(
                        'jadwal.tanggalMulaiBlok'
                    )
                    ->whereNull(
                        'jadwal.tanggalSelesaiBlok'
                    );

                });

            })

            ->select(
                'jadwal.*',
                'guru.nama as namaguru',
                'mapel.namamapel as namamapelmaster',
                'ruangan.namaruangan as namaruangan'
            );

        /*
        |--------------------------------------------------------------------------
        | FILTER HARI
        |--------------------------------------------------------------------------
        */

        if ($hanyaHariIni) {
            $query->whereRaw(
                'LOWER(TRIM(jadwal.hari)) = ?',
                [$this->hariSekarang()]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL JADWAL
        |--------------------------------------------------------------------------
        */

        $jadwal = $query
            ->orderBy('jadwal.jamMulai')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RAPIIKAN DATA
        |--------------------------------------------------------------------------
        */

        $jadwal->transform(function ($item) {

            $item->jamMulai = $this->formatJam(
                $item->jamMulai
            );

            $item->jamSelesai = $this->formatJam(
                $item->jamSelesai
            );

            // Gunakan nama mapel dari input jadwal
            // jika nama mapel master tidak tersedia.
            if (
                empty($item->namamapelmaster) &&
                !empty($item->namamapel)
            ) {
                $item->namamapelmaster = $item->namamapel;
            }

            if (empty($item->namamapelmaster)) {
                $item->namamapelmaster = 'Mata Pelajaran';
            }

            if (empty($item->namaguru)) {
                $item->namaguru = '-';
            }

            if (empty($item->namaruangan)) {
                $item->namaruangan = null;
            }

            return $item;
        });

        return $jadwal;
    }

    /*
    |--------------------------------------------------------------------------
    | HITUNG STATISTIK PRESENSI
    |--------------------------------------------------------------------------
    */

    private function hitungPresensi($siswaId)
    {
        $rekap = DB::table('presensi')
            ->where('siswaid', $siswaId)
            ->selectRaw("
                SUM(
                    CASE
                        WHEN LOWER(TRIM(status)) = 'hadir'
                        THEN 1 ELSE 0
                    END
                ) AS jumlahHadir
            ")
            ->selectRaw("
                SUM(
                    CASE
                        WHEN LOWER(TRIM(status)) = 'izin'
                        THEN 1 ELSE 0
                    END
                ) AS jumlahIzin
            ")
            ->selectRaw("
                SUM(
                    CASE
                        WHEN LOWER(TRIM(status)) = 'sakit'
                        THEN 1 ELSE 0
                    END
                ) AS jumlahSakit
            ")
            ->selectRaw("
                SUM(
                    CASE
                        WHEN LOWER(TRIM(status)) = 'alpa'
                        THEN 1 ELSE 0
                    END
                ) AS jumlahAlpa
            ")
            ->first();

        return [
            'jumlahHadir' => (int) ($rekap->jumlahHadir ?? 0),
            'jumlahIzin' => (int) ($rekap->jumlahIzin ?? 0),
            'jumlahSakit' => (int) ($rekap->jumlahSakit ?? 0),
            'jumlahAlpa' => (int) ($rekap->jumlahAlpa ?? 0),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL PRESENSI HARI INI
    |--------------------------------------------------------------------------
    |
    | Mengikuti struktur yang digunakan pada controller sebelumnya:
    | presensi.created_at dan presensi.waktu.
    |
    */

    private function ambilPresensiHariIni($siswaId)
    {
        return DB::table('presensi')
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
                $siswaId
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
            ->orderBy('presensi.waktu')
            ->get()
            ->map(function ($item) {

                if (
                    empty($item->namamapelmaster) &&
                    !empty($item->namamapel)
                ) {
                    $item->namamapelmaster = $item->namamapel;
                }

                if (empty($item->namamapelmaster)) {
                    $item->namamapelmaster = 'Mata Pelajaran';
                }

                if (empty($item->namaguru)) {
                    $item->namaguru = '-';
                }

                $item->status = strtolower(
                    trim((string) $item->status)
                );

                return $item;
            });
    }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ORANGTUA
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $user = $this->cekOrangtua();

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        // Data anak berdasarkan NIS.
        $anak = $this->ambilAnak($user);

        if (!$anak) {
            abort(
                404,
                'Data anak belum ditemukan. Periksa NIS pada akun Orangtua dan data siswa.'
            );
        }

        // Hari sekarang.
        $hariSekarang = $this->hariSekarang();

        // Jadwal hari ini.
        $jadwalHariIni = $this->ambilJadwalAnak(
            $anak,
            true
        );

        // Statistik presensi seluruh riwayat.
        $statistik = $this->hitungPresensi($anak->id);

        $jumlahHadir = $statistik['jumlahHadir'];
        $jumlahIzin = $statistik['jumlahIzin'];
        $jumlahSakit = $statistik['jumlahSakit'];
        $jumlahAlpa = $statistik['jumlahAlpa'];

        // Presensi hari ini.
        $presensiHariIni = $this->ambilPresensiHariIni(
            $anak->id
        );

        // Riwayat izin terbaru.
        $izinTerbaru = DB::table('izin_siswa')
            ->where('siswaid', $anak->id)
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->limit(5)
            ->get();

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

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $anak = $this->ambilAnak($user);

        if (!$anak) {
            abort(404, 'Data anak tidak ditemukan.');
        }

        $jadwal = $this->ambilJadwalAnak($anak);

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

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $anak = $this->ambilAnak($user);

        if (!$anak) {
            abort(404, 'Data anak tidak ditemukan.');
        }

        // Seluruh riwayat presensi anak.
        $presensi = DB::table('presensi')
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
            ->orderByDesc('presensi.created_at')
            ->orderByDesc('presensi.waktu')
            ->get()
            ->map(function ($item) {

                if (
                    empty($item->namamapelmaster) &&
                    !empty($item->namamapel)
                ) {
                    $item->namamapelmaster = $item->namamapel;
                }

                if (empty($item->namamapelmaster)) {
                    $item->namamapelmaster = 'Mata Pelajaran';
                }

                if (empty($item->namaguru)) {
                    $item->namaguru = '-';
                }

                $item->status = strtolower(
                    trim((string) $item->status)
                );

                return $item;
            });

        // Statistik presensi.
        $statistik = $this->hitungPresensi($anak->id);

        $jumlahHadir = $statistik['jumlahHadir'];
        $jumlahIzin = $statistik['jumlahIzin'];
        $jumlahSakit = $statistik['jumlahSakit'];
        $jumlahAlpa = $statistik['jumlahAlpa'];

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
    */

    public function profil()
    {
        $user = $this->cekOrangtua();

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $anak = $this->ambilAnak($user);

        if (!$anak) {
            abort(404, 'Data anak tidak ditemukan.');
        }

        $orangtua = DB::table('orangtuas')
            ->where('siswaid', $anak->id)
            ->first();

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

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $anak = $this->ambilAnak($user);

        if (!$anak) {
            abort(404, 'Data anak tidak ditemukan.');
        }

        $orangtua = DB::table('orangtuas')
            ->where('siswaid', $anak->id)
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

    public function updateProfil(Request $request)
    {
        $user = $this->cekOrangtua();

        if ($user instanceof RedirectResponse) {
            return $user;
        }

        $anak = $this->ambilAnak($user);

        if (!$anak) {
            abort(404, 'Data anak tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
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
                Rule::unique('users', 'email')->ignore($user->id),
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
        |--------------------------------------------------------------------------
        | SIMPAN DALAM TRANSAKSI
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($request, $user, $anak) {

            // Update akun login.
            $dataUser = [
                'name' => $request->nama,
                'email' => $request->email,
                'updated_at' => now(),
            ];

            if ($request->filled('password')) {
                $dataUser['password'] = Hash::make(
                    $request->password
                );
            }

            DB::table('users')
                ->where('id', $user->id)
                ->update($dataUser);

            // Update data orangtua.
            $dataOrangtua = [
                'nama' => $request->nama,
                'hubungan' => $request->hubungan,
                'nomortelepon' => $request->nomortelepon,
                'alamat' => $request->alamat,
                'updated_at' => now(),
            ];

            $orangtuaAda = DB::table('orangtuas')
                ->where('siswaid', $anak->id)
                ->exists();

            if ($orangtuaAda) {

                DB::table('orangtuas')
                    ->where('siswaid', $anak->id)
                    ->update($dataOrangtua);

            } else {

                $dataOrangtua['siswaid'] = $anak->id;
                $dataOrangtua['created_at'] = now();

                DB::table('orangtuas')
                    ->insert($dataOrangtua);

            }
        });

        return redirect()
            ->route('orangtua.profil')
            ->with(
                'success',
                'Profil orangtua berhasil diperbarui.'
            );
    }
}