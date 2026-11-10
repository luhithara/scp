<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | AKSES
    |--------------------------------------------------------------------------
    */

    private function cekAksesGuru()
    {
        $user = Auth::user();

        if (!$user || strtolower($user->role ?? '') !== 'guru') {
            abort(403, 'Halaman ini hanya dapat diakses oleh Guru.');
        }

        return $user;
    }

    private function cekAksesWaka()
    {
        $user = Auth::user();

        if (!$user || strtolower($user->role ?? '') !== 'waka') {
            abort(403, 'Halaman ini hanya dapat diakses oleh Waka.');
        }

        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS KUNCI JADWAL
    |--------------------------------------------------------------------------
    */

    private function jadwalTerkunci(): bool
    {
        return (bool) DB::table('pengaturan_jadwal')
            ->where('id', 1)
            ->value('terkunci');
    }

    /*
    |--------------------------------------------------------------------------
    | PERHITUNGAN JAM PELAJARAN
    |--------------------------------------------------------------------------
    */

    private function daftarJamPelajaran(string $hari): array
    {
        $daftar = [];

        $waktuSekarang = Carbon::createFromTime(7, 15, 0);

        for ($i = 1; $i <= 7; $i++) {
            $mulai = $waktuSekarang->copy();
            $selesai = $mulai->copy()->addMinutes(45);

            $daftar[$i] = [
                'mulai' => $mulai->format('H:i:s'),
                'selesai' => $selesai->format('H:i:s'),
            ];

            $waktuSekarang = $selesai->copy();

            if ($i === 2) {
                $waktuSekarang->addMinutes(15);
            }

            if ($i === 5) {
                $waktuSekarang->addMinutes(60);
            }
        }

        return $daftar;
    }

    /*
    |--------------------------------------------------------------------------
    | GURU - HALAMAN JADWAL
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = $this->cekAksesGuru();

        $guru = Guru::where('kodeguru', $user->kodepegawai)->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        $jadwal = Jadwal::with([
            'guru',
            'mapel',
            'kelas.jurusan',
            'ruangan',
        ])
            ->where('guruid', $guru->id)
            ->orderByRaw("
                CASE LOWER(hari)
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    ELSE 6
                END
            ")
            ->orderBy('jamMulai')
            ->get();

        $kelas = Kelas::with('jurusan')
            ->orderBy('tingkat')
            ->orderBy('namakelas')
            ->get();

        $jurusan = DB::table('jurusan')
            ->orderBy('namajurusan')
            ->get();

        $jadwalTerkunci = $this->jadwalTerkunci();

        return view('guru.jadwal', [
            'guru' => $guru,
            'jadwal' => $jadwal,
            'kelas' => $kelas,
            'jurusan' => $jurusan,
            'jadwalTerkunci' => $jadwalTerkunci,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GURU - SIMPAN JADWAL
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = $this->cekAksesGuru();

        if ($this->jadwalTerkunci()) {
            return back()->with(
                'error',
                'Jadwal sedang dikunci oleh Waka. Kamu belum dapat menambah jadwal.'
            );
        }

        $validated = $request->validate([
            'namamapel' => [
                'required',
                'string',
                'max:100',
            ],

            'kelasid' => [
                'required',
                'exists:kelas,id',
            ],

            'hari' => [
                'required',
                'in:senin,selasa,rabu,kamis,jumat',
            ],

            'jamMulaiKe' => [
                'required',
                'integer',
                'min:1',
                'max:7',
            ],

            'jamSelesaiKe' => [
                'required',
                'integer',
                'min:1',
                'max:7',
            ],

            'blok' => [
                'required',
                'integer',
                'in:1,2',
            ],
        ], [
            'namamapel.required' => 'Nama mata pelajaran wajib diisi.',
            'namamapel.max' => 'Nama mata pelajaran maksimal 100 karakter.',
            'kelasid.required' => 'Kelas wajib dipilih.',
            'kelasid.exists' => 'Kelas yang dipilih tidak tersedia.',
            'hari.required' => 'Hari wajib dipilih.',
            'jamMulaiKe.required' => 'Jam pelajaran mulai wajib dipilih.',
            'jamMulaiKe.max' => 'Jam pelajaran maksimal sampai JP 7.',
            'jamSelesaiKe.required' => 'Jam pelajaran selesai wajib dipilih.',
            'jamSelesaiKe.max' => 'Jam pelajaran maksimal sampai JP 7.',
            'blok.required' => 'Putaran blok wajib dipilih.',
        ]);

        $namaMapel = trim($validated['namamapel']);

        if ($namaMapel === '') {
            return back()
                ->withInput()
                ->with('error', 'Nama mata pelajaran tidak boleh kosong.');
        }

        if (
            (int) $validated['jamSelesaiKe']
            < (int) $validated['jamMulaiKe']
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Jam pelajaran selesai tidak boleh lebih awal dari jam mulai.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | DATA GURU
        |--------------------------------------------------------------------------
        */

        $guru = Guru::where(
            'kodeguru',
            $user->kodepegawai
        )->first();

        if (!$guru) {
            return back()
                ->withInput()
                ->with('error', 'Data guru tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | DATA KELAS
        |--------------------------------------------------------------------------
        */

        $kelas = Kelas::find($validated['kelasid']);

        if (!$kelas) {
            return back()
                ->withInput()
                ->with('error', 'Data kelas tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK PUTARAN BLOK KELAS
        |--------------------------------------------------------------------------
        */

        preg_match(
            '/(\d+)\s*$/',
            $kelas->namakelas,
            $hasilNomor
        );

        if (!empty($hasilNomor[1])) {
            $nomorKelas = (int) $hasilNomor[1];

            $blokSeharusnya = $nomorKelas % 2 === 1 ? 1 : 2;

            if ((int) $validated['blok'] !== $blokSeharusnya) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Kelas ' . $kelas->namakelas .
                        ' harus menggunakan Putaran/Blok ' .
                        $blokSeharusnya . '.'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | KONVERSI JAM PELAJARAN KE JAM SEBENARNYA
        |--------------------------------------------------------------------------
        */

        $daftarJam = $this->daftarJamPelajaran(
            $validated['hari']
        );

        $nomorMulai = (int) $validated['jamMulaiKe'];
        $nomorSelesai = (int) $validated['jamSelesaiKe'];

        $jamMulai = $daftarJam[$nomorMulai]['mulai'];
        $jamSelesai = $daftarJam[$nomorSelesai]['selesai'];

        /*
        |--------------------------------------------------------------------------
        | CEK BENTROK GURU
        |--------------------------------------------------------------------------
        */

        $guruBentrok = Jadwal::where(
            'guruid',
            $guru->id
        )
            ->where('hari', $validated['hari'])
            ->where('blok', $validated['blok'])
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query
                    ->where('jamMulai', '<', $jamSelesai)
                    ->where('jamSelesai', '>', $jamMulai);
            })
            ->exists();

        if ($guruBentrok) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Jadwal guru bentrok dengan jadwal lain pada hari, blok, dan waktu tersebut.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK BENTROK KELAS
        |--------------------------------------------------------------------------
        */

        $kelasBentrok = Jadwal::where(
            'kelasid',
            $validated['kelasid']
        )
            ->where('hari', $validated['hari'])
            ->where('blok', $validated['blok'])
            ->where(function ($query) use ($jamMulai, $jamSelesai) {
                $query
                    ->where('jamMulai', '<', $jamSelesai)
                    ->where('jamSelesai', '>', $jamMulai);
            })
            ->exists();

        if ($kelasBentrok) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Jadwal kelas bentrok dengan mata pelajaran lain.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CARI ATAU BUAT MATA PELAJARAN
        |--------------------------------------------------------------------------
        |
        | Nama mata pelajaran dibandingkan tanpa membedakan huruf besar/kecil.
        | Jika belum ada, buat kode mata pelajaran otomatis dan unik.
        |
        */

        $jadwal = DB::transaction(function () use (
            $namaMapel,
            $guru,
            $validated,
            $jamMulai,
            $jamSelesai
        ) {
            $namaMapelNormal = mb_strtolower($namaMapel);

            $mapel = Mapel::whereRaw(
                'LOWER(TRIM(namamapel)) = ?',
                [$namaMapelNormal]
            )->first();

            if (!$mapel) {
                $mapel = Mapel::create([
                    'kodemapel' => 'MAP' . strtoupper(bin2hex(random_bytes(8))),
                    'namamapel' => $namaMapel,
                ]);
            }

            return Jadwal::create([
                'guruid' => $guru->id,
                'mapelid' => $mapel->id,
                'namamapel' => $namaMapel,
                'kelasid' => $validated['kelasid'],
                'hari' => $validated['hari'],
                'jamke' => $nomorMulai,
                'jamMulai' => $jamMulai,
                'jamSelesai' => $jamSelesai,
                'blok' => $validated['blok'],
            ]);
        });

        return redirect()
            ->route('guru.jadwal')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | GURU - HAPUS JADWAL
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $user = $this->cekAksesGuru();

        if ($this->jadwalTerkunci()) {
            return back()->with(
                'error',
                'Jadwal sedang dikunci oleh Waka. Kamu belum dapat menghapus jadwal.'
            );
        }

        $guru = Guru::where(
            'kodeguru',
            $user->kodepegawai
        )->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        $jadwal = Jadwal::where('id', $id)
            ->where('guruid', $guru->id)
            ->first();

        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan atau bukan milik Anda.');
        }

        $jadwal->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | WAKA - HALAMAN UTAMA JADWAL
    |--------------------------------------------------------------------------
    */

    public function wakaIndex()
    {
        $this->cekAksesWaka();

        $daftarAngkatan = collect([
            (object) ['tingkat' => 10],
            (object) ['tingkat' => 11],
            (object) ['tingkat' => 12],
        ]);

        $jadwalTerkunci = $this->jadwalTerkunci();

        return view(
            'waka.jadwal',
            compact('daftarAngkatan', 'jadwalTerkunci')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WAKA - PILIH ANGKATAN
    |--------------------------------------------------------------------------
    */

    public function wakaAngkatan($tingkat)
    {
        $this->cekAksesWaka();

        $tingkat = (int) $tingkat;

        if (!in_array($tingkat, [10, 11, 12])) {
            abort(404, 'Angkatan tidak ditemukan.');
        }

        $daftarJurusan = DB::table('jurusan')
            ->join('kelas', 'kelas.jurusanid', '=', 'jurusan.id')
            ->where('kelas.tingkat', $tingkat)
            ->select(
                'jurusan.id',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->selectRaw('COUNT(kelas.id) as jumlah_kelas')
            ->groupBy(
                'jurusan.id',
                'jurusan.kodejurusan',
                'jurusan.namajurusan'
            )
            ->orderBy('jurusan.kodejurusan')
            ->get();

        return view(
            'waka.jadwal-angkatan',
            compact('tingkat', 'daftarJurusan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WAKA - PILIH JURUSAN
    |--------------------------------------------------------------------------
    */

    public function wakaJurusan($tingkat, $jurusanId)
    {
        $this->cekAksesWaka();

        $tingkat = (int) $tingkat;

        if (!in_array($tingkat, [10, 11, 12])) {
            abort(404, 'Angkatan tidak ditemukan.');
        }

        $jurusan = DB::table('jurusan')
            ->where('id', $jurusanId)
            ->first();

        if (!$jurusan) {
            abort(404, 'Jurusan tidak ditemukan.');
        }

        $dataKelas = DB::table('kelas')
            ->where('tingkat', $tingkat)
            ->where('jurusanid', $jurusanId)
            ->orderBy('namakelas')
            ->get();

        foreach ($dataKelas as $kelas) {
            $kelas->jumlah_jadwal = Jadwal::where(
                'kelasid',
                $kelas->id
            )->count();
        }

        return view(
            'waka.jadwal-jurusan',
            compact('tingkat', 'jurusan', 'dataKelas')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WAKA - JADWAL PER KELAS
    |--------------------------------------------------------------------------
    */

    public function wakaKelas($tingkat, $jurusanId, $kelasId)
    {
        $this->cekAksesWaka();

        $tingkat = (int) $tingkat;

        if (!in_array($tingkat, [10, 11, 12])) {
            abort(404, 'Angkatan tidak ditemukan.');
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
            ->where('kelas.id', $kelasId)
            ->where('kelas.tingkat', $tingkat)
            ->where('kelas.jurusanid', $jurusanId)
            ->first();

        if (!$kelas) {
            abort(404, 'Kelas tidak ditemukan.');
        }

        $jadwal = Jadwal::with([
            'guru',
            'mapel',
            'ruangan',
        ])
            ->where('kelasid', $kelasId)
            ->orderByRaw("
                CASE LOWER(hari)
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    ELSE 6
                END
            ")
            ->orderBy('jamMulai')
            ->get();

        $ruangan = Ruangan::where('status', 'aktif')
            ->orderBy('namaruangan')
            ->get();

        $jadwalTerkunci = $this->jadwalTerkunci();

        return view(
            'waka.jadwal-kelas',
            compact(
                'kelas',
                'jadwal',
                'ruangan',
                'tingkat',
                'jurusanId',
                'jadwalTerkunci'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WAKA - KUNCI / BUKA JADWAL
    |--------------------------------------------------------------------------
    */

    public function ubahKunciJadwal(Request $request)
    {
        $user = $this->cekAksesWaka();

        $validated = $request->validate([
            'terkunci' => ['required', 'boolean'],
        ]);

        DB::table('pengaturan_jadwal')
            ->where('id', 1)
            ->update([
                'terkunci' => (bool) $validated['terkunci'],
                'diubah_oleh' => $user->id,
                'updated_at' => now(),
            ]);

        return back()->with(
            'success',
            $validated['terkunci']
                ? 'Seluruh jadwal berhasil dikunci. Guru tidak dapat menambah atau menghapus jadwal.'
                : 'Seluruh jadwal berhasil dibuka kembali.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WAKA - ATUR RUANGAN
    |--------------------------------------------------------------------------
    */

    public function aturRuangan(Request $request, $id)
    {
        $this->cekAksesWaka();

        $validated = $request->validate([
            'ruanganid' => [
                'required',
                'exists:ruangan,id',
            ],
        ], [
            'ruanganid.required' => 'Ruangan wajib dipilih.',
            'ruanganid.exists' => 'Ruangan yang dipilih tidak tersedia.',
        ]);

        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan.');
        }

        $ruanganBentrok = Jadwal::where(
            'ruanganid',
            $validated['ruanganid']
        )
            ->where('id', '!=', $jadwal->id)
            ->where('hari', $jadwal->hari)
            ->where('blok', $jadwal->blok)
            ->where(function ($query) use ($jadwal) {
                $query
                    ->where('jamMulai', '<', $jadwal->jamSelesai)
                    ->where('jamSelesai', '>', $jadwal->jamMulai);
            })
            ->exists();

        if ($ruanganBentrok) {
            return back()->with(
                'error',
                'Ruangan tersebut sudah digunakan pada waktu yang sama.'
            );
        }

        $jadwal->update([
            'ruanganid' => $validated['ruanganid'],
        ]);

        return back()->with('success', 'Ruangan berhasil ditetapkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | WAKA - HAPUS JADWAL
    |--------------------------------------------------------------------------
    */

    public function hapusJadwal($id)
    {
        $this->cekAksesWaka();

        $jadwal = Jadwal::find($id);

        if (!$jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan.');
        }

        $jadwal->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}