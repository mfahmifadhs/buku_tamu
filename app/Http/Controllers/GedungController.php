<?php

namespace App\Http\Controllers;

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

class GedungController extends Controller
{

    public function index()
    {
        //
    }

    public function select(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $area = Gedung::orderBy('nama_gedung', 'ASC')->get();
        } else {
            $area = Gedung::orderBy('nama_gedung', 'ASC')->where('nama_gedung', 'like', '%' . $search . '%')->get();
        }

        $response = array();

        $response[] = array(
            "id"    => "",
            "text"  => "Seluruh Gedung"
        );

        foreach ($area as $data) {
            $response[] = array(
                "id"    =>  $data->id_gedung,
                "text"  =>  $data->nama_gedung
            );
        }

        return response()->json($response);
    }
}
