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

class PegawaiController extends Controller
{

    public function index()
    {
        $pegawai = Pegawai::get();
        return view('dashboard.pages.pegawai.show', compact('pegawai'));
    }

    public function time()
    {
        $response = Carbon::now()->isoFormat('HH:mm:ss / DD-MM-Y');
        return response()->json($response);
    }

    public function create()
    {
        return view('dashboard.pages.pegawai.create');
    }

    public function detail($id)
    {
        $pegawai = Pegawai::where('id_pegawai', $id)->first();

        return view('dashboard.pages.pegawai.detail', compact('id', 'pegawai'));
    }

    public function store(Request $request)
    {
        $pegawai   = Pegawai::withTrashed()->count();
        $idPegawai = $pegawai + 1;

        $tambah = new Pegawai();
        $tambah->id_pegawai     = $idPegawai;
        $tambah->unit_kerja_id  = $request->unit_kerja;
        $tambah->nip            = $request->nip;
        $tambah->nama_pegawai   = $request->nama_pegawai;
        $tambah->nama_jabatan   = $request->nama_jabatan;
        $tambah->created_at     = Carbon::now();
        $tambah->save();

        return redirect()->route('pegawai.show')->with('success', 'Berhasil Menambah Baru');
    }

    public function edit($id)
    {
        $pegawai = Pegawai::where('id_pegawai', $id)->first();

        return view('dashboard.pages.pegawai.edit', compact('id', 'pegawai'));
    }

    public function update(Request $request, $id)
    {
        Pegawai::where('id_pegawai', $id)->update([
            'unit_kerja_id' => $request->unit_kerja,
            'nip'           => $request->nip,
            'nama_pegawai'  => $request->nama_pegawai,
            'nama_jabatan'  => $request->nama_jabatan
        ]);

        return back()->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function destroy($id)
    {
        Pegawai::where('id_pegawai', $id)->delete();

        return back()->with('success', 'Berhasil Menghapus Data');
    }
}
