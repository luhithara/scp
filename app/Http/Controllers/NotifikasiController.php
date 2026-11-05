<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan notifikasi milik pengguna yang sedang login.
     */
    public function index()
    {
        $items = DB::table('notifikasi')
            ->where('userid', Auth::id())
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(50)
            ->get();

        $jumlahBelumDibaca = DB::table('notifikasi')
            ->where('userid', Auth::id())
            ->where('sudahdibaca', false)
            ->count();

        return view('notifikasi.index', compact(
            'items',
            'jumlahBelumDibaca'
        ));
    }

    /**
     * Menandai satu notifikasi sebagai sudah dibaca.
     */
    public function baca($id)
    {
        DB::table('notifikasi')
            ->where('id', $id)
            ->where('userid', Auth::id())
            ->update([
                'sudahdibaca' => true,
                'waktudibaca' => now(),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Menandai seluruh notifikasi milik pengguna sebagai sudah dibaca.
     */
    public function bacaSemua()
    {
        DB::table('notifikasi')
            ->where('userid', Auth::id())
            ->where('sudahdibaca', false)
            ->update([
                'sudahdibaca' => true,
                'waktudibaca' => now(),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Semua notifikasi sudah dibaca.');
    }
}