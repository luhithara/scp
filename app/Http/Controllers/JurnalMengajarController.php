<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JurnalMengajarController extends Controller
{
    /**
     * Mengambil data guru yang sedang login.
     */
    private function getGuru()
    {
        $user = Auth::user();

        if (!$user || strtolower(trim($user->role ?? '')) !== 'guru') {
            abort(403, 'Akses hanya untuk guru.');
        }

        $guru = DB::table('guru')
            ->where('kodeguru', $user->kodepegawai)
            ->first();

        if (!$guru) {
            abort(404, 'Data guru tidak ditemukan.');
        }

        return $guru;
    }

    /**
     * Mendapatkan nama hari ini dalam bahasa Indonesia.
     */
    private function getHariIni(): string
    {
        $daftarHari = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'minggu',
        ];

        return $daftarHari[now()->dayOfWeekIso];
    }

    /**
     * Query jadwal beserta nama mapel milik guru.
     */
    private function queryJadwalGuru($guruId)
    {
        return DB::table('jadwal')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->leftJoin('jurusan', 'jadwal.jurusanid', '=', 'jurusan.id')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->where('jadwal.guruid', $guruId)
            ->select(
                'jadwal.*',
                'mapel.namamapel',
                'jurusan.namajurusan',
                'kelas.namakelas'
            );
    }

    /**
     * Query riwayat jurnal milik guru.
     */
    private function queryJurnalGuru($guruId)
    {
        return DB::table('jurnal_mengajar')
            ->leftJoin(
                'jadwal',
                'jurnal_mengajar.jadwalid',
                '=',
                'jadwal.id'
            )
            ->leftJoin(
                'mapel',
                'jadwal.mapelid',
                '=',
                'mapel.id'
            )
            ->leftJoin(
                'jurusan',
                'jadwal.jurusanid',
                '=',
                'jurusan.id'
            )
            ->leftJoin(
                'kelas',
                'jadwal.kelasid',
                '=',
                'kelas.id'
            )
            ->where('jurnal_mengajar.guruid', $guruId)
            ->select(
                'jurnal_mengajar.*',
                'jadwal.hari',
                'jadwal.jamMulai',
                'jadwal.jamSelesai',
                'jadwal.tingkat',
                'mapel.namamapel',
                'jadwal.kelasid',
                'jurusan.namajurusan',
                'kelas.namakelas'
            );
    }

    /**
     * Memastikan jurnal masih boleh diubah.
     */
    private function pastikanJurnalHariIni($jurnal): void
    {
        if (
            !$jurnal ||
            \Carbon\Carbon::parse($jurnal->tanggal)->toDateString()
                !== now()->toDateString()
        ) {
            abort(
                403,
                'Jurnal hanya dapat diedit atau dihapus pada hari yang sama.'
            );
        }
    }

    /**
     * Halaman daftar jurnal guru.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = $this->getGuru();

        $jadwal = $this->queryJadwalGuru($guru->id)
            ->orderByRaw("
                CASE LOWER(jadwal.hari)
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    WHEN 'sabtu' THEN 6
                    WHEN 'minggu' THEN 7
                    ELSE 8
                END
            ")
            ->orderBy('jadwal.jamMulai')
            ->get();

        $jurnalQuery = $this->queryJurnalGuru($guru->id);

        if ($request->filled('tanggal')) {
            $jurnalQuery->where(
                'jurnal_mengajar.tanggal',
                $request->tanggal
            );
        }

        if ($request->filled('jadwalid')) {
            $jurnalQuery->where(
                'jurnal_mengajar.jadwalid',
                $request->jadwalid
            );
        }

        $jurnal = $jurnalQuery
            ->orderByDesc('jurnal_mengajar.tanggal')
            ->orderByDesc('jurnal_mengajar.id')
            ->paginate(10)
            ->withQueryString();

        $jurnalHariIni = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->whereDate('tanggal', now()->toDateString())
            ->count();

        $jumlahJurnal = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->count();

        $tanggalHariIni = now()->toDateString();

        return view(
            'jurnal.guru',
            compact(
                'user',
                'guru',
                'jadwal',
                'jurnal',
                'jurnalHariIni',
                'jumlahJurnal',
                'tanggalHariIni'
            )
        );
    }

    /**
     * Halaman membuat jurnal baru.
     */
    public function create()
    {
        $user = Auth::user();
        $guru = $this->getGuru();
        $hariIni = $this->getHariIni();

        // Hanya jadwal milik guru login pada hari ini.
        // Nama mapel diambil dari tabel mapel.
        $jadwal = $this->queryJadwalGuru($guru->id)
            ->whereRaw('LOWER(TRIM(jadwal.hari)) = ?', [$hariIni])
            ->orderBy('jadwal.jamMulai')
            ->get();

        $jurnal = $this->queryJurnalGuru($guru->id)
            ->orderByDesc('jurnal_mengajar.tanggal')
            ->orderByDesc('jurnal_mengajar.id')
            ->paginate(5, ['*'], 'riwayat_page');

        $jurnalHariIni = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->whereDate('tanggal', now()->toDateString())
            ->count();

        $jumlahJurnal = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->count();

        $tanggalHariIni = now()->toDateString();

        return view(
            'jurnal.create',
            compact(
                'user',
                'guru',
                'jadwal',
                'jurnal',
                'jurnalHariIni',
                'jumlahJurnal',
                'tanggalHariIni'
            )
        );
    }

    /**
     * Menyimpan jurnal mengajar.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $guru = $this->getGuru();

        $file = $request->file('fotobukti');

        if ($file && !$file->isValid()) {
            $uploadError = match ($file->getError()) {
                UPLOAD_ERR_INI_SIZE,
                UPLOAD_ERR_FORM_SIZE =>
                    'Ukuran foto melebihi batas upload server.',
                UPLOAD_ERR_PARTIAL =>
                    'Foto hanya terunggah sebagian. Silakan coba lagi.',
                UPLOAD_ERR_NO_TMP_DIR =>
                    'Folder temporary upload tidak tersedia di server.',
                UPLOAD_ERR_CANT_WRITE =>
                    'Server tidak dapat menulis file foto ke disk.',
                UPLOAD_ERR_EXTENSION =>
                    'Upload foto dihentikan oleh ekstensi PHP.',
                default =>
                    'Foto gagal diunggah. Silakan coba lagi.',
            };

            return back()
                ->withInput()
                ->withErrors(['fotobukti' => $uploadError]);
        }

        if (!$file && $request->has('fotobukti')) {
            return back()
                ->withInput()
                ->withErrors([
                    'fotobukti' => 'Foto gagal diunggah. Silakan ambil foto ulang.',
                ]);
        }

        $validated = $request->validate(
            [
                'jadwalid' => ['required', 'integer'],
                'materi' => ['required', 'string', 'max:500'],
                'kegiatan' => ['required', 'string', 'max:1000'],
                'catatan' => ['nullable', 'string', 'max:1000'],
                'fotobukti' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120',
                ],
            ],
            [
                'jadwalid.required' => 'Jadwal harus dipilih.',
                'materi.required' => 'Materi harus diisi.',
                'kegiatan.required' => 'Kegiatan pembelajaran harus diisi.',
                'fotobukti.required' => 'Foto bukti pembelajaran wajib diambil.',
                'fotobukti.image' => 'Foto bukti harus berupa gambar.',
                'fotobukti.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
                'fotobukti.max' => 'Ukuran foto maksimal 5 MB.',
            ]
        );

        $hariIni = $this->getHariIni();
        $tanggalHariIni = now()->toDateString();

        // Pastikan jadwal milik guru login dan berlangsung hari ini.
        $jadwal = DB::table('jadwal')
            ->leftJoin('mapel', 'jadwal.mapelid', '=', 'mapel.id')
            ->where('jadwal.id', $validated['jadwalid'])
            ->where('jadwal.guruid', $guru->id)
            ->whereRaw('LOWER(TRIM(jadwal.hari)) = ?', [$hariIni])
            ->select(
                'jadwal.*',
                'mapel.namamapel'
            )
            ->first();

        if (!$jadwal) {
            return back()
                ->withInput()
                ->withErrors([
                    'jadwalid' => 'Jadwal tidak valid atau bukan jadwal Anda hari ini.',
                ]);
        }

        $jurnalSudahAda = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->where('jadwalid', $jadwal->id)
            ->whereDate('tanggal', $tanggalHariIni)
            ->exists();

        if ($jurnalSudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'jurnal' => 'Jurnal untuk jadwal ini hari ini sudah dibuat.',
                ]);
        }

        $fotoPath = $request
            ->file('fotobukti')
            ->store('jurnal', 'public');

        if (!$fotoPath) {
            return back()
                ->withInput()
                ->withErrors([
                    'fotobukti' => 'Foto gagal disimpan. Silakan coba lagi.',
                ]);
        }

        try {
            DB::table('jurnal_mengajar')->insert([
                'guruid' => $guru->id,
                'jadwalid' => $jadwal->id,
                'tanggal' => $tanggalHariIni,
                'materi' => $validated['materi'],
                'kegiatan' => $validated['kegiatan'],
                'catatan' => $validated['catatan'] ?? null,
                'fotobukti' => $fotoPath,
                'diubaholeh' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($fotoPath);
            throw $e;
        }

        // Notifikasi untuk semua akun Waka.
        $wakaIds = DB::table('users')
            ->whereRaw('LOWER(role) = ?', ['waka'])
            ->pluck('id');

        foreach ($wakaIds as $wakaId) {
            DB::table('notifikasi')->insert([
                'userid' => $wakaId,
                'judul' => 'Jurnal mengajar baru',
                'pesan' => $guru->nama . ' mengirim jurnal untuk ' .
                    ($jadwal->namamapel ?? 'jadwal mengajar') . '.',
                'jenis' => 'jurnal',
                'url' => route('waka.jurnal'),
                'sudahdibaca' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()
            ->route('jurnal.guru')
            ->with('success', 'Jurnal mengajar berhasil disimpan.');
    }

    /**
     * Menampilkan formulir edit jurnal.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $guru = $this->getGuru();

        $jurnal = DB::table('jurnal_mengajar')
            ->where('id', $id)
            ->where('guruid', $guru->id)
            ->first();

        if (!$jurnal) {
            abort(404, 'Jurnal tidak ditemukan.');
        }

        $this->pastikanJurnalHariIni($jurnal);

        $jadwal = $this->queryJadwalGuru($guru->id)
            ->whereRaw(
                'LOWER(TRIM(jadwal.hari)) = ?',
                [$this->getHariIni()]
            )
            ->orderBy('jadwal.jamMulai')
            ->get();

        return view(
            'jurnal.edit',
            compact('user', 'guru', 'jadwal', 'jurnal')
        );
    }

    /**
     * Memperbarui jurnal hari ini.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $guru = $this->getGuru();

        $jurnal = DB::table('jurnal_mengajar')
            ->where('id', $id)
            ->where('guruid', $guru->id)
            ->first();

        if (!$jurnal) {
            abort(404, 'Jurnal tidak ditemukan.');
        }

        $this->pastikanJurnalHariIni($jurnal);

        $validated = $request->validate(
            [
                'jadwalid' => ['required', 'integer'],
                'materi' => ['required', 'string', 'max:500'],
                'kegiatan' => ['required', 'string', 'max:1000'],
                'catatan' => ['nullable', 'string', 'max:1000'],
                'fotobukti' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120',
                ],
            ],
            [
                'jadwalid.required' => 'Jadwal harus dipilih.',
                'materi.required' => 'Materi harus diisi.',
                'kegiatan.required' => 'Kegiatan pembelajaran harus diisi.',
                'fotobukti.image' => 'File bukti harus berupa gambar.',
                'fotobukti.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
                'fotobukti.max' => 'Ukuran foto maksimal 5 MB.',
            ]
        );

        $jadwal = DB::table('jadwal')
            ->where('id', $validated['jadwalid'])
            ->where('guruid', $guru->id)
            ->whereRaw(
                'LOWER(TRIM(hari)) = ?',
                [$this->getHariIni()]
            )
            ->first();

        if (!$jadwal) {
            return back()
                ->withInput()
                ->withErrors([
                    'jadwalid' => 'Jadwal tidak valid atau bukan jadwal Anda hari ini.',
                ]);
        }

        $duplikat = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->where('jadwalid', $jadwal->id)
            ->whereDate('tanggal', now()->toDateString())
            ->where('id', '<>', $id)
            ->exists();

        if ($duplikat) {
            return back()
                ->withInput()
                ->withErrors([
                    'jurnal' => 'Jurnal untuk jadwal tersebut hari ini sudah ada.',
                ]);
        }

        $fotoBaru = null;

        if ($request->hasFile('fotobukti')) {
            $fotoBaru = $request
                ->file('fotobukti')
                ->store('jurnal', 'public');

            if (!$fotoBaru) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'fotobukti' => 'Foto gagal disimpan.',
                    ]);
            }
        }

        try {
            DB::table('jurnal_mengajar')
                ->where('id', $id)
                ->where('guruid', $guru->id)
                ->update([
                    'jadwalid' => $jadwal->id,
                    'tanggal' => $jurnal->tanggal,
                    'materi' => $validated['materi'],
                    'kegiatan' => $validated['kegiatan'],
                    'catatan' => $validated['catatan'] ?? null,
                    'fotobukti' => $fotoBaru ?: $jurnal->fotobukti,
                    'diubaholeh' => $user->id,
                    'updated_at' => now(),
                ]);
        } catch (\Throwable $e) {
            if ($fotoBaru) {
                Storage::disk('public')->delete($fotoBaru);
            }

            throw $e;
        }

        if ($fotoBaru && $jurnal->fotobukti) {
            Storage::disk('public')->delete($jurnal->fotobukti);
        }

        return redirect()
            ->route('jurnal.guru')
            ->with('success', 'Jurnal berhasil diperbarui.');
    }

    /**
     * Menghapus jurnal.
     */
    public function destroy($id)
    {
        $guru = $this->getGuru();

        $jurnal = DB::table('jurnal_mengajar')
            ->where('id', $id)
            ->where('guruid', $guru->id)
            ->first();

        if (!$jurnal) {
            return back()
                ->withErrors([
                    'jurnal' => 'Jurnal tidak ditemukan.',
                ]);
        }

        $this->pastikanJurnalHariIni($jurnal);

        if ($jurnal->fotobukti) {
            Storage::disk('public')->delete($jurnal->fotobukti);
        }

        DB::table('jurnal_mengajar')
            ->where('id', $id)
            ->where('guruid', $guru->id)
            ->delete();

        return back()
            ->with('success', 'Jurnal berhasil dihapus.');
    }
}