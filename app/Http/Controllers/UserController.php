<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Role;
use App\Models\Pegawai;
use Hash;
use Auth;
use Carbon\Carbon;
use Session;
use DB;

class UserController extends Controller
{

    public function index()
    {
        $user = User::get();
        return view('dashboard.pages.user.show', compact('user'));
    }

    public function time()
    {
        $response = Carbon::now()->isoFormat('HH:mm:ss / DD-MM-Y');
        return response()->json($response);
    }

    public function create()
    {
        $role    = Role::get();
        $pegawai = Pegawai::get();
        return view('dashboard.pages.user.create', compact('role', 'pegawai'));
    }

    public function detail($id)
    {
        $user    = User::where('id', $id)->first();
        $role    = Role::get();
        $pegawai = Pegawai::get();

        return view('dashboard.pages.user.detail', compact('id', 'user', 'role', 'pegawai'));
    }

    public function store(Request $request)
    {
        $user   = User::count();
        $idUser = $user + 1;

        $tambah = new User();
        $tambah->id             = $idUser;
        $tambah->role_id        = $request->role_id;
        $tambah->pegawai_id     = $request->pegawai_id;
        $tambah->username       = $request->username;
        $tambah->password       = Hash::make($request->password);
        $tambah->password_teks  = $request->password;
        $tambah->status         = 'aktif';
        $tambah->save();

        return redirect()->route('user.show')->with('success', 'Berhasil Menambah Baru');
    }

    public function edit($id)
    {
        $user    = User::where('id', $id)->first();
        $role    = Role::get();
        $pegawai = Pegawai::get();

        return view('dashboard.pages.user.edit', compact('id', 'user', 'role', 'pegawai'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        User::where('id', $id)->update([
            'role_id'    => $request->role_id ? $request->role_id : $user->role_id,
            'pegawai_id' => $request->pegawai_id,
            'username'   => $request->username,
            'status'     => $request->status ? $request->status : $user->status
        ]);

        return back()->with('success', 'Berhasil Menyimpan Perubahan');
    }
}
