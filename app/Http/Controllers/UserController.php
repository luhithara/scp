<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();

        return view('users.index', compact('users'));
    }


    public function create()
    {
        return view('users.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'nisn' => 'nullable|string|max:20',
            'kodepegawai' => 'nullable|string|max:30',
            'password' => 'required|string|min:6',
            'role' => 'required|in:siswa,orangtua,guru,waka,bk,walikelas',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDASI IDENTITAS LOGIN
        |--------------------------------------------------------------------------
        */

        if ($request->role === 'siswa' && empty($request->nisn)) {
            return back()
                ->withInput()
                ->with('error', 'Akun siswa wajib memiliki NISN.');
        }


        if (
            in_array(
                $request->role,
                ['guru', 'waka', 'bk', 'walikelas']
            )
            && empty($request->kodepegawai)
        ) {
            return back()
                ->withInput()
                ->with('error', 'Akun ini wajib memiliki kode pegawai.');
        }


        /*
        |--------------------------------------------------------------------------
        | CEK NISN SUDAH DIGUNAKAN
        |--------------------------------------------------------------------------
        */

        if ($request->nisn) {

            $cekNisn = User::where(
                'nisn',
                $request->nisn
            )->first();

            if ($cekNisn) {
                return back()
                    ->withInput()
                    ->with('error', 'NISN tersebut sudah digunakan.');
            }

        }


        /*
        |--------------------------------------------------------------------------
        | CEK KODE PEGAWAI
        |--------------------------------------------------------------------------
        */

        if ($request->kodepegawai) {

            $cekKode = User::where(
                'kodepegawai',
                $request->kodepegawai
            )->first();

            if ($cekKode) {
                return back()
                    ->withInput()
                    ->with('error', 'Kode pegawai tersebut sudah digunakan.');
            }

        }


        /*
        |--------------------------------------------------------------------------
        | BUAT USER
        |--------------------------------------------------------------------------
        */

        User::create([
            'name' => $request->name,

            'email' => $request->email
                ?: strtolower(
                    str_replace(
                        ' ',
                        '',
                        $request->name
                    )
                ) . '@scp.local',

            'nisn' => $request->nisn,

            'kodepegawai' => $request->kodepegawai,

            /*
            |--------------------------------------------------------------------------
            | PASSWORD OTOMATIS HASH
            |--------------------------------------------------------------------------
            */

            'password' => Hash::make(
                $request->password
            ),

            'role' => $request->role,

            'status' => 'aktif',
        ]);


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Akun berhasil ditambahkan. Password sudah otomatis diamankan.'
            );
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view(
            'users.edit',
            compact('user')
        );
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'nisn' => 'nullable|string|max:20',
            'kodepegawai' => 'nullable|string|max:30',
            'role' => 'required|in:siswa,orangtua,guru,waka,bk,walikelas',
            'status' => 'required|in:aktif,nonaktif',
        ]);


        $user->name = $request->name;

        $user->email = $request->email;

        $user->nisn = $request->nisn;

        $user->kodepegawai = $request->kodepegawai;

        $user->role = $request->role;

        $user->status = $request->status;


        /*
        |--------------------------------------------------------------------------
        | PASSWORD HANYA DIUBAH JIKA DIISI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('password')) {

            $user->password = Hash::make(
                $request->password
            );

        }


        $user->save();


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Data akun berhasil diperbarui.'
            );
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Akun berhasil dihapus.'
            );
    }
}