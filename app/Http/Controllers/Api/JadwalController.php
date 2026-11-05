<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class JadwalController extends Controller
{
    /**
     * Senin pertama Putaran 1.
     * Harus sama dengan ApiConfig.blokAnchor di Flutter.
     */
    private const BLOK_ANCHOR = '2026-10-26';

    /**
     * Ambil role pengguna yang sedang login.
     */
    private function role(): string
    {
        return strtolower(Auth::user()->role ?? '');
    }

    /**
     * Pastikan pengguna adalah guru.
     */
    private function guruLogin()
    {
        abort_unless($this->role() === 'guru', 403, 'Akses khusus guru.');

        $user = Auth::user();

        $guru = DB::table('guru')
            ->where('kodeguru', $user->kodepegawai)
            ->first();

        abort_unless($guru, 404, 'Data guru tidak ditemukan.');

        return $guru;
    }

    /**
     * Pastikan pengguna adalah Waka.
     */
    private function cekWaka(): void
    {
        abort_unless($this->role() === 'waka', 403, 'Akses khusus Waka.');
    }

    /**
     * Status kunci jadwal.
     */
    private function statusKunci(): bool
    {
        return (bool) DB::table('pengaturan_jadwal')
            ->where('id', 1)
            ->value('terkunci');
    }

    /**
     * Nama hari Flutter:
     * 1 Senin, 2 Selasa, 3 Rabu, 4 Kamis, 5 Jumat.
     */
    private function namaHari(int $hari): string
    {
        return [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
        ][$hari] ?? 'senin';
    }

    /**
     * Ubah nama hari database menjadi angka Flutter.
     */
    private function angkaHari(string $hari): int
    {
        return [
            'senin' => 1,
            'selasa' => 2,
            'rabu' => 3,
            'kamis' => 4,
            'jumat' => 5,
        ][strtolower($hari)] ?? 1;
    }

    /**
     * Tentukan putaran kelas dari angka di belakang nama kelas.
     * Kelas ganjil = Putaran 1.
     * Kelas genap = Putaran 2.
     */
    private function putaranKelas(string $namaKelas): int
    {
        preg_match('/(\d+)\s*$/', trim($namaKelas), $hasil);

        if (!isset($hasil[1])) {
            return 1;
        }

        return ((int) $hasil[1]) % 2 === 0 ? 2 : 1;
    }

    /**
     * Buat kode mapel otomatis, misalnya MP001.
     */
    private function kodeMapelBaru(): string
    {
        $kodeTerakhir = DB::table('mapel')
            ->where('kodemapel', 'like', 'MP%')
            ->lockForUpdate()
            ->pluck('kodemapel');

        $nomorTerbesar = 0;

        foreach ($kodeTerakhir as $kode) {
            if (preg_match('/^MP(\d+)$/i', $kode, $hasil)) {
                $nomorTerbesar = max(
                    $nomorTerbesar,
                    (int) $hasil[1]
                );
            }
        }

        do {
            $nomorTerbesar++;
            $kode = 'MP' . str_pad($nomorTerbesar, 3, '0', STR_PAD_LEFT);

            $sudahAda = DB::table('mapel')
                ->where('kodemapel', $kode)
                ->exists();
        } while ($sudahAda);

        return $kode;
    }

    /**
     * Cari mapel berdasarkan nama.
     * Jika belum ada, buat mapel baru.
     * Hubungkan mapel dengan guru.
     */
    private function cariAtauBuatMapel(string $namaMapel, int $guruId): int
    {
        $namaMapel = trim($namaMapel);

        $mapel = DB::table('mapel')
            ->whereRaw('LOWER(namamapel) = ?', [mb_strtolower($namaMapel)])
            ->first();

        if (!$mapel) {
            $mapelId = DB::table('mapel')->insertGetId([
                'kodemapel' => $this->kodeMapelBaru(),
                'namamapel' => $namaMapel,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $mapelId = (int) $mapel->id;
        }

        $sudahTerhubung = DB::table('guru_mapel')
            ->where('guruid', $guruId)
            ->where('mapelid', $mapelId)
            ->exists();

        if (!$sudahTerhubung) {
            DB::table('guru_mapel')->insert([
                'guruid' => $guruId,
                'mapelid' => $mapelId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $mapelId;
    }

    /**
     * Format data jadwal untuk Flutter.
     */
    private function formatJadwal($jadwal): array
    {
        return [
            'id' => (string) $jadwal->id,
            'kelas' => $jadwal->namakelas ?? '',
            'hari' => $this->angkaHari($jadwal->hari ?? 'senin'),
            'jamMulai' => $jadwal->jamMulai
                ? substr((string) $jadwal->jamMulai, 0, 5)
                : '00:00',
            'jamSelesai' => $jadwal->jamSelesai
                ? substr((string) $jadwal->jamSelesai, 0, 5)
                : '00:00',
            'mapel' => $jadwal->namamapel ?? '',
            'guruId' => (string) $jadwal->guruid,
            'guruNama' => $jadwal->namaguru ?? '',
            'ruangan' => $jadwal->namaruangan,
        ];
    }

    /**
     * Query dasar jadwal beserta relasinya.
     */
    private function queryJadwal()
    {
        return DB::table('jadwal')
            ->join('kelas', 'kelas.id', '=', 'jadwal.kelasid')
            ->join('guru', 'guru.id', '=', 'jadwal.guruid')
            ->leftJoin('mapel', 'mapel.id', '=', 'jadwal.mapelid')
            ->leftJoin('ruangan', 'ruangan.id', '=', 'jadwal.ruanganid')
            ->select(
                'jadwal.*',
                'kelas.namakelas',
                'guru.nama as namaguru',
                DB::raw('COALESCE(jadwal.namamapel, mapel.namamapel) as namamapel'),
                'ruangan.namaruangan'
            );
    }

    /**
     * GET /api/jadwal
     *
     * Guru: hanya melihat jadwal miliknya.
     * Waka: dapat melihat seluruh jadwal.
     */
    public function index(Request $request)
    {
        $role = $this->role();

        abort_unless(
            in_array($role, ['guru', 'waka'], true),
            403,
            'Tidak memiliki akses melihat jadwal.'
        );

        $query = $this->queryJadwal();

        if ($role === 'guru') {
            $guru = $this->guruLogin();
            $query->where('jadwal.guruid', $guru->id);
        }

        if ($request->filled('kelas')) {
            $query->where('kelas.namakelas', $request->kelas);
        }

        if ($request->filled('hari')) {
            $hari = (int) $request->hari;

            if ($hari >= 1 && $hari <= 5) {
                $query->where('jadwal.hari', $this->namaHari($hari));
            }
        }

        $jadwal = $query
            ->orderByRaw(
                "FIELD(jadwal.hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat')"
            )
            ->orderBy('jadwal.jamMulai')
            ->get()
            ->map(fn ($item) => $this->formatJadwal($item));

        return response()->json($jadwal);
    }

    /**
     * GET /api/jadwal/kunci
     */
    public function kunci()
    {
        abort_unless(
            in_array($this->role(), ['guru', 'waka'], true),
            403
        );

        return response()->json([
            'terkunci' => $this->statusKunci(),
        ]);
    }

    /**
     * POST /api/jadwal
     * Guru menambahkan jadwal miliknya.
     */
    public function store(Request $request)
    {
        $guru = $this->guruLogin();

        abort_if(
            $this->statusKunci(),
            403,
            'Jadwal sedang dikunci oleh Waka.'
        );

        $data = $this->validasiJadwal($request);

        $jadwalId = DB::transaction(function () use ($data, $guru) {
            $kelas = DB::table('kelas')
                ->where('namakelas', $data['kelas'])
                ->first();

            if (!$kelas) {
                throw ValidationException::withMessages([
                    'kelas' => ['Kelas tidak ditemukan.'],
                ]);
            }

            $putaran = $this->putaranKelas($kelas->namakelas);

            $mapelId = $this->cariAtauBuatMapel(
                $data['mapel'],
                (int) $guru->id
            );

            $this->cekBentrok(
                (int) $guru->id,
                (int) $kelas->id,
                $putaran,
                $data['hari'],
                $data['jamMulai'],
                $data['jamSelesai']
            );

            return DB::table('jadwal')->insertGetId([
                'guruid' => $guru->id,
                'mapelid' => $mapelId,
                'namamapel' => trim($data['mapel']),
                'kelasid' => $kelas->id,
                'hari' => $this->namaHari((int) $data['hari']),
                'jamMulai' => $data['jamMulai'],
                'jamSelesai' => $data['jamSelesai'],
                'blok' => $putaran,
                'putaranblok' => $putaran,

                // Jadwal berulang, tidak berakhir setelah dua minggu.
                'tanggalMulaiBlok' => self::BLOK_ANCHOR,
                'tanggalSelesaiBlok' => '2099-12-31',

                'ruanganid' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        $jadwal = $this->queryJadwal()
            ->where('jadwal.id', $jadwalId)
            ->first();

        return response()->json(
            $this->formatJadwal($jadwal),
            201
        );
    }

    /**
     * PUT /api/jadwal/{id}
     * Guru mengubah jadwal miliknya.
     */
    public function update(Request $request, string $id)
    {
        $guru = $this->guruLogin();

        abort_if(
            $this->statusKunci(),
            403,
            'Jadwal sedang dikunci oleh Waka.'
        );

        $jadwalLama = DB::table('jadwal')
            ->where('id', $id)
            ->first();

        abort_unless($jadwalLama, 404, 'Jadwal tidak ditemukan.');

        abort_unless(
            (int) $jadwalLama->guruid === (int) $guru->id,
            403,
            'Tidak dapat mengubah jadwal guru lain.'
        );

        $data = $this->validasiJadwal($request);

        DB::transaction(function () use ($data, $guru, $id) {
            $kelas = DB::table('kelas')
                ->where('namakelas', $data['kelas'])
                ->first();

            if (!$kelas) {
                throw ValidationException::withMessages([
                    'kelas' => ['Kelas tidak ditemukan.'],
                ]);
            }

            $putaran = $this->putaranKelas($kelas->namakelas);

            $mapelId = $this->cariAtauBuatMapel(
                $data['mapel'],
                (int) $guru->id
            );

            $this->cekBentrok(
                (int) $guru->id,
                (int) $kelas->id,
                $putaran,
                $data['hari'],
                $data['jamMulai'],
                $data['jamSelesai'],
                (int) $id
            );

            DB::table('jadwal')
                ->where('id', $id)
                ->update([
                    'mapelid' => $mapelId,
                    'namamapel' => trim($data['mapel']),
                    'kelasid' => $kelas->id,
                    'hari' => $this->namaHari((int) $data['hari']),
                    'jamMulai' => $data['jamMulai'],
                    'jamSelesai' => $data['jamSelesai'],
                    'blok' => $putaran,
                    'putaranblok' => $putaran,
                    'tanggalMulaiBlok' => self::BLOK_ANCHOR,
                    'tanggalSelesaiBlok' => '2099-12-31',
                    'updated_at' => now(),
                ]);
        });

        $jadwal = $this->queryJadwal()
            ->where('jadwal.id', $id)
            ->first();

        return response()->json($this->formatJadwal($jadwal));
    }

    /**
     * Cek bentrok jadwal guru atau kelas.
     */
    private function cekBentrok(
        int $guruId,
        int $kelasId,
        int $putaran,
        int $hari,
        string $jamMulai,
        string $jamSelesai,
        ?int $kecualiId = null
    ): void {
        $cek = function ($query) use ($kecualiId, $hari, $putaran, $jamMulai, $jamSelesai) {
            $query
                ->where('hari', $this->namaHari($hari))
                ->where(function ($q) use ($putaran) {
                    $q->where('putaranblok', $putaran)
                        ->orWhere(function ($q2) use ($putaran) {
                            $q2->whereNull('putaranblok')
                                ->where('blok', $putaran);
                        });
                })
                ->where('jamMulai', '<', $jamSelesai)
                ->where('jamSelesai', '>', $jamMulai);

            if ($kecualiId !== null) {
                $query->where('id', '!=', $kecualiId);
            }
        };

        $bentrokGuru = DB::table('jadwal')
            ->where('guruid', $guruId);

        $cek($bentrokGuru);

        if ($bentrokGuru->exists()) {
            throw ValidationException::withMessages([
                'jamMulai' => ['Jadwal guru bentrok dengan jadwal lain pada hari dan putaran tersebut.'],
            ]);
        }

        $bentrokKelas = DB::table('jadwal')
            ->where('kelasid', $kelasId);

        $cek($bentrokKelas);

        if ($bentrokKelas->exists()) {
            throw ValidationException::withMessages([
                'jamMulai' => ['Kelas sudah memiliki jadwal lain pada waktu tersebut.'],
            ]);
        }
    }

    /**
     * Validasi input jadwal dari Flutter.
     */
    private function validasiJadwal(Request $request): array
    {
        return $request->validate([
            'kelas' => ['required', 'string', 'max:100'],
            'hari' => ['required', 'integer', 'between:1,5'],
            'jamMulai' => ['required', 'date_format:H:i'],
            'jamSelesai' => ['required', 'date_format:H:i', 'after:jamMulai'],
            'mapel' => ['required', 'string', 'max:100'],
        ]);
    }

    /**
     * DELETE /api/jadwal/{id}
     *
     * Guru hanya boleh menghapus jadwal sendiri.
     * Waka boleh menghapus jadwal mana pun.
     */
    public function destroy(string $id)
    {
        $role = $this->role();

        abort_unless(
            in_array($role, ['guru', 'waka'], true),
            403
        );

        $jadwal = DB::table('jadwal')
            ->where('id', $id)
            ->first();

        abort_unless($jadwal, 404, 'Jadwal tidak ditemukan.');

        if ($role === 'guru') {
            $guru = $this->guruLogin();

            abort_if(
                $this->statusKunci(),
                403,
                'Jadwal sedang dikunci oleh Waka.'
            );

            abort_unless(
                (int) $jadwal->guruid === (int) $guru->id,
                403,
                'Tidak dapat menghapus jadwal guru lain.'
            );
        }

        DB::table('jadwal')->where('id', $id)->delete();

        return response()->json([
            'message' => 'Jadwal berhasil dihapus.',
        ]);
    }

    /**
     * PUT /api/jadwal/kunci
     * Waka mengunci atau membuka jadwal.
     */
    public function setKunci(Request $request)
    {
        $this->cekWaka();

        $data = $request->validate([
            'terkunci' => ['required'],
        ]);

        $nilai = filter_var(
            $data['terkunci'],
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE
        );

        if ($nilai === null) {
            throw ValidationException::withMessages([
                'terkunci' => ['Nilai kunci harus berupa true atau false.'],
            ]);
        }

        DB::table('pengaturan_jadwal')->updateOrInsert(
            ['id' => 1],
            [
                'terkunci' => $nilai,
                'diubah_oleh' => Auth::id(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json([
            'terkunci' => $nilai,
            'message' => $nilai
                ? 'Jadwal berhasil dikunci.'
                : 'Jadwal berhasil dibuka.',
        ]);
    }

    /**
     * PUT /api/jadwal/{id}/ruangan
     * Waka mengatur ruangan jadwal.
     */
    public function setRuangan(Request $request, string $id)
    {
        $this->cekWaka();

        $data = $request->validate([
            'ruangan' => ['required', 'string', 'max:100'],
        ]);

        $jadwal = DB::table('jadwal')
            ->where('id', $id)
            ->first();

        abort_unless($jadwal, 404, 'Jadwal tidak ditemukan.');

        $ruangan = DB::table('ruangan')
            ->where('namaruangan', $data['ruangan'])
            ->first();

        abort_unless($ruangan, 404, 'Ruangan tidak ditemukan.');

        $bentrok = DB::table('jadwal')
            ->where('ruanganid', $ruangan->id)
            ->where('hari', $jadwal->hari)
            ->where(function ($q) use ($jadwal) {
                $q->where('putaranblok', $jadwal->putaranblok ?? $jadwal->blok)
                    ->orWhere('blok', $jadwal->blok);
            })
            ->where('id', '!=', $id)
            ->where('jamMulai', '<', $jadwal->jamSelesai)
            ->where('jamSelesai', '>', $jadwal->jamMulai)
            ->exists();

        abort_if($bentrok, 422, 'Ruangan sudah digunakan pada waktu tersebut.');

        DB::table('jadwal')
            ->where('id', $id)
            ->update([
                'ruanganid' => $ruangan->id,
                'updated_at' => now(),
            ]);

        return response()->json([
            'message' => 'Ruangan berhasil diatur.',
            'ruangan' => $ruangan->namaruangan,
        ]);
    }
}