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

        if (!$user || $user->role !== 'guru') {
            abort(403);
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
     * Halaman daftar jurnal guru.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'guru') {
            abort(403);
        }

        $guru = $this->getGuru();

        $jadwal = DB::table('jadwal')
            ->leftJoin('jurusan', 'jadwal.jurusanid', '=', 'jurusan.id')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->where('jadwal.guruid', $guru->id)
            ->select(
                'jadwal.*',
                'jurusan.namajurusan',
                'kelas.namakelas'
            )
            ->orderByRaw("
                CASE LOWER(jadwal.hari)
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    WHEN 'sabtu' THEN 6
                    ELSE 7
                END
            ")
            ->orderBy('jadwal.jamMulai')
            ->get();

        $jurnalQuery = DB::table('jurnal_mengajar')
            ->leftJoin(
                'jadwal',
                'jurnal_mengajar.jadwalid',
                '=',
                'jadwal.id'
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
            ->where('jurnal_mengajar.guruid', $guru->id);

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
            ->select(
                'jurnal_mengajar.*',
                'jadwal.hari',
                'jadwal.jamMulai',
                'jadwal.jamSelesai',
                'jadwal.tingkat',
                'jadwal.namamapel',
                'jadwal.kelasid',
                'jurusan.namajurusan',
                'kelas.namakelas'
            )
            ->orderByDesc('jurnal_mengajar.tanggal')
            ->orderByDesc('jurnal_mengajar.id')
            ->get();

        $jurnalHariIni = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->whereDate('tanggal', now()->toDateString())
            ->count();

        $jumlahJurnal = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->count();

        return view(
            'jurnal.guru',
            compact(
                'user',
                'guru',
                'jadwal',
                'jurnal',
                'jurnalHariIni',
                'jumlahJurnal'
            )
        );
    }

    /**
     * Halaman membuat jurnal baru.
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'guru') {
            abort(403);
        }

        $guru = $this->getGuru();

        $jadwal = DB::table('jadwal')
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
            ->where('jadwal.guruid', $guru->id)
            ->select(
                'jadwal.*',
                'jurusan.namajurusan',
                'kelas.namakelas'
            )
            ->orderByRaw("
                CASE LOWER(jadwal.hari)
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    WHEN 'sabtu' THEN 6
                    ELSE 7
                END
            ")
            ->orderBy('jadwal.jamMulai')
            ->get();

        $jurnal = DB::table('jurnal_mengajar')
            ->leftJoin(
                'jadwal',
                'jurnal_mengajar.jadwalid',
                '=',
                'jadwal.id'
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
            ->where('jurnal_mengajar.guruid', $guru->id)
            ->select(
                'jurnal_mengajar.*',
                'jadwal.hari',
                'jadwal.jamMulai',
                'jadwal.jamSelesai',
                'jadwal.tingkat',
                'jadwal.namamapel',
                'jadwal.kelasid',
                'jurusan.namajurusan',
                'kelas.namakelas'
            )
            ->orderByDesc('jurnal_mengajar.tanggal')
            ->orderByDesc('jurnal_mengajar.id')
            ->get();

        $jurnalHariIni = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->whereDate('tanggal', now()->toDateString())
            ->count();

        $jumlahJurnal = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->count();

        return view(
            'jurnal.create',
            compact(
                'user',
                'guru',
                'jadwal',
                'jurnal',
                'jurnalHariIni',
                'jumlahJurnal'
            )
        );
    }

    /**
     * Menyimpan jurnal mengajar.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'guru') {
            abort(403);
        }

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
                    'Foto gagal diunggah. Silakan pilih file lain dan coba lagi.',
            };

            return back()
                ->withInput()
                ->withErrors(['fotobukti' => $uploadError]);
        }

        if (!$file && $request->has('fotobukti')) {
            return back()
                ->withInput()
                ->withErrors([
                    'fotobukti' =>
                        'Foto gagal diunggah. Periksa ukuran file dan konfigurasi upload server.',
                ]);
        }

        $validated = $request->validate(
            [
                'jadwalid' => ['required', 'integer', 'exists:jadwal,id'],
                'tanggal' => ['required', 'date'],
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
                'jadwalid.exists' => 'Jadwal tidak ditemukan.',
                'tanggal.required' => 'Tanggal harus diisi.',
                'materi.required' => 'Materi harus diisi.',
                'kegiatan.required' => 'Kegiatan pembelajaran harus diisi.',
                'fotobukti.required' => 'Foto bukti pembelajaran wajib diunggah.',
                'fotobukti.image' => 'File foto harus berupa gambar.',
                'fotobukti.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
                'fotobukti.max' => 'Ukuran foto maksimal 5 MB.',
            ]
        );

        $jadwal = DB::table('jadwal')
            ->where('id', $validated['jadwalid'])
            ->where('guruid', $guru->id)
            ->first();

        if (!$jadwal) {
            return back()
                ->withInput()
                ->withErrors([
                    'jadwalid' => 'Jadwal tersebut bukan milik Anda.',
                ]);
        }

        $jurnalSudahAda = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->where('jadwalid', $jadwal->id)
            ->whereDate('tanggal', $validated['tanggal'])
            ->exists();

        if ($jurnalSudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'jurnal' => 'Jurnal untuk jadwal dan tanggal tersebut sudah dibuat.',
                ]);
        }

        $fotoPath = null;

        if ($request->hasFile('fotobukti')) {
            $fotoPath = $request
                ->file('fotobukti')
                ->store('jurnal', 'public');

            if (!$fotoPath) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'fotobukti' =>
                            'Foto gagal disimpan ke penyimpanan server. Silakan coba lagi.',
                    ]);
            }
        }

        try {
            DB::table('jurnal_mengajar')->insert([
                'guruid' => $guru->id,
                'jadwalid' => $jadwal->id,
                'tanggal' => $validated['tanggal'],
                'materi' => $validated['materi'],
                'kegiatan' => $validated['kegiatan'],
                'catatan' => $validated['catatan'] ?? null,
                'fotobukti' => $fotoPath,
                'diubaholeh' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }

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

        if (!$user || $user->role !== 'guru') {
            abort(403);
        }

        $guru = $this->getGuru();

        $jurnal = DB::table('jurnal_mengajar')
            ->where('id', $id)
            ->where('guruid', $guru->id)
            ->first();

        if (!$jurnal) {
            abort(404, 'Jurnal tidak ditemukan.');
        }

        $jadwal = DB::table('jadwal')
            ->leftJoin('jurusan', 'jadwal.jurusanid', '=', 'jurusan.id')
            ->leftJoin('kelas', 'jadwal.kelasid', '=', 'kelas.id')
            ->where('jadwal.guruid', $guru->id)
            ->select(
                'jadwal.*',
                'jurusan.namajurusan',
                'kelas.namakelas'
            )
            ->orderByRaw("
                CASE LOWER(jadwal.hari)
                    WHEN 'senin' THEN 1
                    WHEN 'selasa' THEN 2
                    WHEN 'rabu' THEN 3
                    WHEN 'kamis' THEN 4
                    WHEN 'jumat' THEN 5
                    WHEN 'sabtu' THEN 6
                    ELSE 7
                END
            ")
            ->orderBy('jadwal.jamMulai')
            ->get();

        return view(
            'jurnal.edit',
            compact('user', 'guru', 'jadwal', 'jurnal')
        );
    }

    /**
     * Memperbarui jurnal yang sudah tersimpan.
     * Foto lama tetap digunakan jika tidak ada foto baru.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'guru') {
            abort(403);
        }

        $guru = $this->getGuru();

        $jurnal = DB::table('jurnal_mengajar')
            ->where('id', $id)
            ->where('guruid', $guru->id)
            ->first();

        if (!$jurnal) {
            abort(404, 'Jurnal tidak ditemukan.');
        }

        $validated = $request->validate(
            [
                'jadwalid' => ['required', 'integer', 'exists:jadwal,id'],
                'tanggal' => ['required', 'date'],
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
                'jadwalid.exists' => 'Jadwal tidak ditemukan.',
                'tanggal.required' => 'Tanggal harus diisi.',
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
            ->first();

        if (!$jadwal) {
            return back()
                ->withInput()
                ->withErrors([
                    'jadwalid' => 'Jadwal tersebut bukan milik Anda.',
                ]);
        }

        $duplikat = DB::table('jurnal_mengajar')
            ->where('guruid', $guru->id)
            ->where('jadwalid', $validated['jadwalid'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->where('id', '<>', $id)
            ->exists();

        if ($duplikat) {
            return back()
                ->withInput()
                ->withErrors([
                    'jurnal' => 'Jurnal untuk jadwal dan tanggal tersebut sudah ada.',
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
                    'jadwalid' => $validated['jadwalid'],
                    'tanggal' => $validated['tanggal'],
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