<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Tamu;
use Hash;
use Auth;
use Carbon\Carbon;
use Session;
use DB;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        if (!Auth::user()) {
            return redirect('/');
        }

        $role = Auth::user()->role_id;
        $user = $role == 2 ? 'admin' : 'master';
        $tahun = $request->get('tahun', Carbon::now()->format('Y'));
        $bulan = $request->get('bulan', Carbon::now()->format('m'));
        $tahunBulan = $request->get('tahunBulan', Carbon::now()->format('Y'));
        $totalInstansi = Tamu::select('instansi_id', DB::raw('COUNT(id_tamu) as total'))->groupBy('instansi_id')->get();
        $totalLobi     = Tamu::select('lokasi_datang', DB::raw('COUNT(id_tamu) as total'))->groupBy('lokasi_datang')->get();


        if ($role == 2) {
            $query    = Tamu::where('jam_keluar', null)->orderBy('id_tamu', 'DESC');
            $name     = 'Admin';
            $position = 'Receptionist';

            if (Auth::user()->id == 3) {
                $tamu = $query->where('lokasi_datang', 'lobi')->get();
            } elseif (Auth::user()->id == 4) {
                $tamu = $query->where('lokasi_datang', 'lobi-a')->get();
            }  elseif (Auth::user()->id == 5) {
                $tamu = $query->whereIn('lokasi_datang', ['lobi-c', '2c'])->get();
            }

        } else {
            $tamu     = Tamu::select(DB::raw("DATE_FORMAT(jam_masuk, '%m') as bulan"), DB::raw("DATE_FORMAT(jam_masuk, '%Y') as tahun"), 'id_tamu')->get();
            $name     = Auth::user()->role->role;
            $position = '';
        }

        return view('dashboard.' . $user, compact('name', 'position', 'tamu', 'bulan', 'tahun', 'tahunBulan', 'totalInstansi', 'totalLobi'));

    }

    public function time()
    {
        $response = Carbon::now()->isoFormat('DD MMMM Y HH:mm:ss');
        return response()->json($response);
    }
}
