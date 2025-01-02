<?php

namespace App\Http\Controllers;

use App\Exports\TamuExport;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Tamu;
use App\Models\Gedung;
use App\Models\Instansi;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;

class TamuController extends Controller
{
    public function index()
    {
        //
    }

    public function confirm(Request $request, $gedung, $lobi, $id)
    {
        $success = $request->get('success', null);
        if (!$request->no_visitor && $lobi != '2c') {
            $tamu    = Tamu::where('id_tamu', $id)->first();
            return view('tamu.confirm', compact('gedung', 'id', 'lobi', 'tamu', 'success'));
        } else if ($lobi == '2c') {
            $success = 'true';

            Tamu::where('id_tamu', $id)->update([
                'nomor_visitor' => 0
            ]);

            $tamu    = Tamu::where('id_tamu', $id)->first();
            return view('tamu.confirm', compact('gedung', 'id', 'lobi', 'tamu', 'success'));
        } else {
            $success = 'true';
            Tamu::where('id_tamu', $id)->update([
                'nomor_visitor' => $request->no_visitor
            ]);
            $tamu    = Tamu::where('id_tamu', $id)->first();
            return view('tamu.confirm', compact('gedung', 'id', 'lobi', 'tamu', 'success'))->with('success', 'Selamat Datang!');
        }
    }

    public function create($id, $lobi)
    {
        if ($id == 'adhyatma' && $lobi == 'lobi-a') {
            $gedung = 1;
        } else if ($id == 'adhyatma' && $lobi == 'lobi-c') {
            $gedung = 2;
        } else if ($id == 'adhyatma' && $lobi == '2c') {
            $gedung = 2;
        } else if ($id == 'sujudi' && $lobi == 'lobi') {
            $gedung = 3;
        } else {
            abort(404);
        }

        $instansi = Instansi::orderBy('id_instansi', 'DESC')->get();

        if ($lobi == '2c') {
            $dataArea = Area::where('gedung_id', '!=', 3)->where('status', 'aktif')
                ->where('nama_lantai', 'like', '%lantai 2%')
                ->orderBy('id_area', 'ASC');
        } else {
            $dataArea = Area::where('gedung_id', $gedung)->where('status', 'aktif');
        }

        if ($gedung == 3) {
            $area = $dataArea->orderBy('id_area', 'ASC')->get();
        } else {
            $area = $dataArea->orderBy('nama_lantai', 'ASC')->get();
        }


        return view('tamu.create', compact('area', 'id', 'gedung', 'lobi', 'instansi'));
    }

    public function store(Request $request, $id)
    {
        //dd($request->all());
        if ($request->test != 'true') {
            if (!$request->input('capturedImage')) {
                return back()->with('failed', 'Anda belum mengambil gambar');
            };
        }

        $total   = str_pad(Tamu::withTrashed()->count() + 1, 4, 0, STR_PAD_LEFT);
        $id_tamu = Carbon::now()->format('ymdHis') . $total;
        $lobi    = $request->lokasi_datang;

        $tambah = new Tamu();
        $tambah->id_tamu = $id_tamu;
        $tambah->area_id = $request->area_id;
        $tambah->lokasi_datang  = $lobi;
        $tambah->jam_masuk      = Carbon::now();
        $tambah->nama_tamu      = $request->nama;
        $tambah->nik_nip        = $request->nik_nip;
        $tambah->alamat_tamu    = $request->alamat;
        $tambah->no_telpon      = $request->no_telp;
        $tambah->instansi_id    = $request->instansi;
        $tambah->nama_instansi  = $request->nama_instansi;
        $tambah->nama_tujuan    = $request->nama_tujuan;
        $tambah->keperluan      = $request->keperluan;
        $tambah->created_at     = Carbon::now();
        $tambah->save();

        if ($request->test != 'true') {

            $filePict64 = $request->input('capturedImage');

            list($type, $filePict64) = explode(';', $filePict64);
            list(, $filePict64) = explode(',', $filePict64);
            $fileDecoded = base64_decode($filePict64);

            $fileName = 'file_' . now()->timestamp . '.png';
            $filePath = 'public/foto_tamu/' . $fileName;

            Storage::put($filePath, $fileDecoded);
            //dd($fileName);
        } else {
            $foto         = $request->file('capturedImage');
            $fullFileName = $foto->getClientOriginalName();
            $foto_name    = pathinfo($fullFileName)['filename'];
            $extension    = $foto->getClientOriginalExtension();
            $fileName     = 'file_' . now()->timestamp . '.' . $extension;
            $foto->storeAs('public/foto_tamu', $fileName);
        }

        Tamu::where('id_tamu', $id_tamu)->update([
            'foto_tamu' => $fileName
        ]);


        return redirect()->route('tamu.confirm', ['gedung' => $id, 'lobi' => $lobi, 'id' => $id_tamu])->with('success', 'Berhasil Mengisi Form');
    }

    public function show(Request $request)
    {
        $tanggal  = $request->get('tanggal');
        $bulan    = $request->get('bulan');
        $tahun    = $request->get('tahun');
        $area     = $request->get('area');
        $instansi = $request->get('instansi');
        $dataArea = [];
        $gedung   = '';
        $area     = '';

        $query    = Tamu::orderBy('id_tamu', 'DESC')
            ->where(DB::raw("DATE_FORMAT(jam_masuk, '%d')"), $tanggal)
            ->where(DB::raw("DATE_FORMAT(jam_masuk, '%m')"), $bulan)->where(DB::raw("DATE_FORMAT(jam_masuk, '%Y')"), $tahun);

        if (Auth::user()->id == 3) {
            $tamu = $query->where('lokasi_datang', 'lobi')->get();
        } elseif (Auth::user()->id == 4) {
            $tamu = $query->where('lokasi_datang', 'lobi-a')->get();
        } elseif (Auth::user()->id == 5) {
            $tamu = $query->whereIn('lokasi_datang', ['lobi-c', '2c'])->get();
        } else {
            $tamu = $query->get();
        }

        return view('dashboard.pages.tamu.show', compact('tanggal', 'bulan', 'tahun', 'tamu', 'gedung', 'area', 'instansi', 'dataArea'));
    }

    public function filter(Request $request)
    {
        $dataArea = [];
        $tanggal  = $request->get('tanggal');
        $bulan    = $request->get('bulan');
        $tahun    = $request->get('tahun');
        $gedung   = $request->get('gedung');
        $area     = $request->get('area');
        $data     = Tamu::orderBy('id_tamu', 'DESC')->join('t_gedung_area', 'id_area', 'area_id');
        $cekArea  = Area::where('id_area', $area)->where('gedung_id', $gedung)->first();

        if ($tanggal || $bulan || $tahun || $gedung || $cekArea) {
            if ($tanggal) {
                $res  = $data->where(DB::raw("DATE_FORMAT(jam_masuk, '%d')"), $tanggal);
            }

            if ($bulan) {
                $res  = $data->where(DB::raw("DATE_FORMAT(jam_masuk, '%m')"), $bulan);
            }

            if ($tahun) {
                $res  = $data->where(DB::raw("DATE_FORMAT(jam_masuk, '%Y')"), $tahun);
            }

            if ($gedung) {
                $res  = $data->where('gedung_id', $gedung);
                $dataArea = Area::where('gedung_id', $gedung)->get();
            }

            if ($cekArea) {
                $res = $data->where('area_id', $area);
            } else {
                $area = '';
            }
        } else {
            $res    = $data;
        }

        // $tamu = $res->get();

        if (Auth::user()->id == 3) {
            $tamu = $res->where('lokasi_datang', 'lobi')->get();
        } elseif (Auth::user()->id == 4) {
            $tamu = $res->where('lokasi_datang', 'lobi-a')->get();
        } elseif (Auth::user()->id == 5) {
            $tamu = $res->whereIn('lokasi_datang', ['lobi-c', '2c'])->get();
        } else {
            $tamu = $res->get();
        }

        if ($request->downloadFile == 'pdf') {
            return view('dashboard.pages.tamu.pdf', compact('tamu'));
        } elseif ($request->downloadFile == 'excel') {
            return Excel::download(new TamuExport($request->all()), 'tamu.xlsx');
        }

        return view('dashboard.pages.tamu.show', compact('tanggal', 'bulan', 'tahun', 'gedung', 'area', 'dataArea', 'tamu'));
    }

    public function edit($id)
    {
        $gedung   = Gedung::get();
        $tamu     = Tamu::where('id_tamu', $id)->first();
        $instansi = Instansi::orderBy('id_instansi', 'DESC')->get();
        return view('dashboard.pages.tamu.edit', compact('id', 'gedung', 'tamu', 'instansi'));
    }

    public function update(Request $request, $id)
    {
        $jamKeluar = !$request->jam_keluar ? null : Carbon::parse($request->jam_keluar)->format('Y-m-d H:i:s');
        $tamu = Tamu::where('id_tamu', $id)->first();
        Tamu::where('id_tamu', $id)->update([
            'area_id'        => $request->area_id,
            'jam_masuk'      => $request->jam_masuk,
            'jam_keluar'     => $jamKeluar,
            'nama_tamu'      => $request->nama_tamu,
            'nik_nip'        => $request->nik_nip,
            'alamat_tamu'    => $request->alamat,
            'no_telpon'      => $request->no_telepon,
            'instansi_id'    => $request->instansi,
            'nama_instansi'  => $request->nama_instansi,
            'nama_tujuan'    => $request->nama_tujuan,
            'keperluan'      => $request->keperluan,
            'nomor_visitor'  => $request->nomor_visitor,
            'foto_tamu'      => $tamu->foto_tamu
        ]);

        return back()->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function createByAdmin()
    {
        $lobi = '';
        $dataGedung = Gedung::orderBy('nama_gedung', 'ASC');
        $dataArea   = Area::orderBy('id_area', 'ASC');
        $instansi   = Instansi::orderBy('id_instansi', 'DESC')->get();

        if (Auth::user()->id == 3) {
            $lobi   = 'lobi';
            $gedung = $dataGedung->where('id_gedung', 2)->get();
            $area   = $dataArea->where('gedung_id', 2)->get();
        } elseif (Auth::user()->id == 4) {
            $lobi   = 'lobi-a';
            $gedung = $dataGedung->where('id_gedung', 1)->get();
            $area   = $dataArea->where('gedung_id', 1)->get();
        } elseif (Auth::user()->id == 5) {
            $lobi   = 'lobi-c';
            $gedung = $dataGedung->where('id_gedung', 1)->get();
            $area   = $dataArea->where('id_gedung', 1)->get();
        }

        return view('dashboard.pages.tamu.create', compact('gedung', 'area', 'lobi', 'instansi'));
    }

    public function storeByAdmin(Request $request)
    {
        $total   = str_pad(Tamu::withTrashed()->count() + 1, 4, 0, STR_PAD_LEFT);
        $id_tamu = Carbon::now()->format('ymdHis') . $total;
        $jamKeluar = !$request->jam_keluar ? null : Carbon::parse($request->jam_keluar)->format('Y-m-d H:i:s');

        $tambah = new Tamu();
        $tambah->id_tamu = $id_tamu;
        $tambah->area_id = $request->area_id;
        $tambah->lokasi_datang  = $request->lobi;
        $tambah->jam_masuk      = Carbon::parse($request->jam_masuk)->format('Y-m-d H:i:s');
        $tambah->jam_keluar     = $jamKeluar;
        $tambah->nama_tamu      = $request->nama_tamu;
        $tambah->nik_nip        = $request->nik_nip;
        $tambah->alamat_tamu    = $request->alamat;
        $tambah->no_telpon      = $request->no_telepon;
        $tambah->instansi_id    = $request->instansi;
        $tambah->nama_instansi  = $request->nama_instansi;
        $tambah->nama_tujuan    = $request->nama_tujuan;
        $tambah->keperluan      = $request->keperluan;
        $tambah->nomor_visitor  = $request->nomor_visitor;
        $tambah->created_at     = Carbon::now();
        $tambah->save();

        return redirect()->route('dashboard')->with('success', 'Berhasil Menambah Tamu');
    }

    public function destroy($id)
    {
        Tamu::where('id_tamu', $id)->delete();
        return back()->with('success', 'Berhasil Menghapus');
    }

    public function leave($id)
    {
        Tamu::where('id_tamu', $id)->update([
            'jam_keluar' => Carbon::now()
        ]);

        return back()->with('success', 'Tamu Sudah Keluar');
    }

    public function grafik(Request $request, $id, $bulan, $tahun)
    {
        if ($id == 'bulan') {
            $dataTahun = $tahun ? $tahun : Carbon::now()->format('Y');
            $result = Tamu::select(DB::raw("(DATE_FORMAT(jam_masuk, '%M %Y')) as month"), DB::raw("count(id_tamu) as total_tamu "))
                ->groupBy('month')
                ->where(DB::raw("DATE_FORMAT(jam_masuk, '%Y')"), $dataTahun)
                ->get();
        } else if ($id == 'hari') {
            $dataBulan = $bulan ? $bulan : Carbon::now()->format('m');
            $dataTahun = $tahun ? $tahun : Carbon::now()->format('Y');
            $result = Tamu::select(
                DB::raw("DATE_FORMAT(jam_masuk, '%d/%m/%y') as date"),
                DB::raw("COUNT(id_tamu) as total_tamu"),
                DB::raw("SUM(IF(lokasi_datang = 'lobi', 1, 0)) as gd_sujudi"),
                DB::raw("SUM(IF(lokasi_datang = 'lobi-a', 1, 0)) as gd_adhyatma_lobi_a"),
                DB::raw("SUM(IF(lokasi_datang = 'lobi-c', 1, 0)) as gd_adhyatma_lobi_c"),
                DB::raw("SUM(IF(instansi_id   = '1', 1, 0)) as hotel"),
                DB::raw("SUM(IF(instansi_id   = '2', 1, 0)) as kl_pusat"),
                DB::raw("SUM(IF(instansi_id   = '3', 1, 0)) as kl_daerah"),
                DB::raw("SUM(IF(instansi_id   = '4', 1, 0)) as lsm"),
                DB::raw("SUM(IF(instansi_id   = '5', 1, 0)) as pribadi"),
                DB::raw("SUM(IF(instansi_id   = '0', 1, 0)) as lainnya"),
            )
                ->whereMonth('jam_masuk', $dataBulan)
                ->whereYear('jam_masuk', $dataTahun)
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->get();
        }

        return response()->json($result);
    }

    public function formCheckout($gedung, $id)
    {
        if ($gedung == 'adhyatma' && $id == 'lobi-a') {
            $idGedung = 1;
        } else if ($gedung == 'adhyatma' && $id == 'lobi-c') {
            $idGedung = 2;
        } else if ($gedung == 'sujudi' && $id == 'lobi') {
            $idGedung = 3;
        } else {
            abort(404);
        }

        $gedung   = Gedung::where('id_gedung', $idGedung)->first();

        return view('checkout', compact('gedung', 'id'));
    }

    public function survei(Request $request)
    {
        $today = Carbon::now()->format('Y-m-d');
        $tamu  = Tamu::where('nomor_visitor', $request->no_visitor)
            ->where('lokasi_datang', $request->lobi)
            // ->where(DB::raw("DATE_FORMAT(jam_masuk, '%Y-%m-%d')"), $today)
            ->where('jam_keluar', null)
            ->get();

        if ($tamu->count() == 0) {
            return back()->with('failed', 'Tamu dengan no. visitor ' . $request->no_visitor . ' tidak ditemukan');
        }

        return view('survey', compact('tamu'));
    }

    public function checkoutStore(Request $request, $survei, $id)
    {
        $tamuIds = explode(',', $request->tamu);

        $tamu   = Tamu::where('id_tamu', $tamuIds)->first();
        $gedung = $tamu->area->gedung_id;
        $lobi   = $tamu->lokasi_datang;

        if ($gedung == 1) {
            $gedung = 'adhyatma';
            $lobi   = 'lobi-a';
        } else if ($gedung == 2) {
            $gedung = 'adhyatma';
            $lobi   = 'lobi-c';
        } else if ($gedung == 3) {
            $gedung = 'sujudi';
            $lobi   = 'lobi';
        } else {
            abort(404);
        }

        foreach ($tamuIds as $id_tamu) {
            Tamu::where('id_tamu', $id_tamu)->update([
                'survei' => $request->feedback,
                'jam_keluar' => Carbon::now()
            ]);
        }

        return redirect()->route('checkout', ['gedung' => $gedung, 'lobi' => $lobi])->with('success', 'Terima kasih');
    }


    public function surveyGrafik(Request $request)
    {
        $result = Tamu::select('survei', DB::raw("count(id_tamu) as total_tamu "))
            ->groupBy('survei')
            ->where('survei', '!=', null)
            ->get();

        return response()->json($result);
    }

    public function testCreate($id, $lobi)
    {
        if ($id == 'adhyatma' && $lobi == 'lobi-a') {
            $gedung = 1;
        } else if ($id == 'adhyatma' && $lobi == 'lobi-c') {
            $gedung = 2;
        } else if ($id == 'adhyatma' && $lobi == '2c') {
            $gedung = 2;
        } else if ($id == 'sujudi' && $lobi == 'lobi') {
            $gedung = 3;
        } else {
            abort(404);
        }

        $instansi = Instansi::orderBy('id_instansi', 'DESC')->get();

        if ($lobi == '2c') {
            $dataArea = Area::where('gedung_id', '!=', 3)->where('status', 'aktif')
                ->where('nama_lantai', 'like', '%lantai 2%');
        } else {
            $dataArea = Area::where('gedung_id', $gedung)->where('status', 'aktif');
        }

        if ($gedung == 3) {
            $area = $dataArea->orderBy('id_area', 'ASC')->get();
        } else {
            $area = $dataArea->orderBy('nama_lantai', 'ASC')->get();
        }


        return view('tamu.test-create', compact('area', 'id', 'gedung', 'lobi', 'instansi'));
    }

    public function select(Request $request)
    {
        $aksi    = $request->aksi;
        $id      = $request->id;
        $data    = Tamu::with('instansi', 'area', 'area.gedung')->orderBy('jam_masuk', 'DESC');

        if ($request->area || $request->gedung || $request->tanggal || $request->bulan || $request->tahun) {
            if ($request->area) {
                $res = $data->where('area_id', $request->area);
            }

            if ($request->gedung) {
                $res = $data->whereHas('area', function ($query) use ($request) {
                    $query->where('gedung_id', $request->gedung);
                });
            }

            if ($request->tanggal) {
                $res = $data->where(DB::raw("DATE_FORMAT(jam_masuk, '%d')"), $request->tanggal);
            }

            if ($request->bulan) {
                $res = $data->where(DB::raw("DATE_FORMAT(jam_masuk, '%m')"), $request->bulan);
            }

            if ($request->tahun) {
                $res = $data->where(DB::raw("DATE_FORMAT(jam_masuk, '%Y')"), $request->tahun);
            }

            $result = $res->get();
        } else {
            $result = $data->get();
        }

        $no = 1;
        $response = [];
        foreach ($result as $row) {

            if ($row->status_pengajuan_id == 1) {
                $status = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> setuju</span>';
            } else if ($row->status_pengajuan_id == 2) {
                $status = '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> tolak</span>';
            } else {
                $status = '<span class="badge badge-warning"><i class="fas fa-clock"></i> pending</span>';
            }

            if ($row->status_proses_id >= 2 && $row->status_proses_id < 5) {
                $proses = '<span class="badge badge-warning"><i class="fas fa-clock"></i> proses</span>';
            } else if ($row->status_proses_id == 5) {
                $proses = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> selesai</span>';
            } else {
                $proses = '';
            }

            if (Auth::user()->role_id == 1) {
                $aksi = '<a href="" onclick="confirmRemove(event, ' . route('tamu.delete', $row->id_tamu) . ')">
                    <i class="fas fa-trash-alt"></i>
                </a> ';
            }

            $response[] = [
                'no'        => $no,
                'role'      => Auth::user()->role_id,
                'id'        => $row->id_tamu,
                'aksi'      => $aksi,
                'lokasi'    => $row->area?->gedung->nama_gedung,
                'masuk'     => Carbon::parse($row->jam_masuk)->isoFormat('HH:mm | DD MMM Y'),
                'keluar'    => $row->jam_keluar ? Carbon::parse($row->jam_keluar)->isoFormat('HH:mm | DD MMM Y') : '',
                'novisit'   => $row->nomor_visitor,
                'nama'      => $row->nama_tamu,
                'asal'      => $row->nama_instansi,
                'tujuan'    => $row->nama_tujuan.', '.$row->area->nama_lantai.' - '.$row->area->nama_sub_bagian,
                'keperluan' => $row->keperluan,
                'foto'      => '<img src="' . asset('storage/foto_tamu/' . $row->foto_tamu) . '" alt="Foto Tamu" class="img-fluid">'
            ];

            $no++;
        }

        return response()->json($response);
    }

    public function detail($id)
    {
        $tamu = Tamu::with('instansi', 'area')->find($id);

        if ($tamu) {
            return response()->json($tamu);
        }

        return response()->json(['error' => 'Tamu tidak ditemukan'], 404);
    }
}
