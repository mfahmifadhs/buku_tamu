<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Tamu;
use Hash;
use Auth;
use Carbon\Carbon;
use Session;
use DB;

class AreaController extends Controller
{

    public function index()
    {
        //
    }

    public function select(Request $request, $id)
    {
        $search = $request->search;

        if ($search == '') {
            $area = Area::orderBy('id_area', 'ASC')->where('gedung_id', 'like', '%' . $id . '%')->get();
        } else {
            $area = Area::orderBy('id_area', 'ASC')->where('nama_lantai', 'like', '%' . $search . '%')
                ->where('gedung_id', 'like', '%' . $id . '%')->get();
        }

        $response = array();

        $response[] = array(
            "id"    => "",
            "text"  => "Seluruh Sub Bagian"
        );

        foreach ($area as $data) {
            $response[] = array(
                "id"    =>  $data->id_area,
                "text"  =>  $id == 2 ? $data->nama_lantai . ' - ' . $data->nama_sub_bagian : $data->nama_lantai . ' (' . $data->nama_ruang . ') - '. $data->nama_sub_bagian
            );
        }

        return response()->json($response);
    }
}
