<?php

namespace App\Exports;

use App\Models\Area;
use App\Models\Tamu;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TamuExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $request;


    function __construct($request)
    {
        $this->tanggal = $request['tanggal'];
        $this->bulan   = $request['bulan'];
        $this->tahun   = $request['tahun'];
        $this->gedung  = $request['gedung'];
        $this->area    = $request['area'];
    }


    public function collection()
    {
        $cekArea  = Area::where('id_area', $this->area)->where('gedung_id', $this->gedung)->first();
        $data     = Tamu::join('t_gedung_area', 'id_area', 'area_id')
            ->join('t_gedung', 'id_gedung', 'gedung_id')
            ->select(
                DB::raw('ROW_NUMBER() OVER (ORDER BY id_tamu) as no'),
                DB::raw('CONCAT("`", id_tamu)'),
                'jam_masuk',
                'jam_keluar',
                'nama_tamu',
                'nomor_visitor',
                DB::raw('CONCAT("`", nik_nip)'),
                'alamat_tamu',
                'no_telpon',
                'nama_instansi',
                'nama_tujuan',
                'keperluan',
                'nama_gedung',
                'nama_lantai',
                'nama_sub_bagian',
            );

        if ($this->tanggal || $this->bulan || $this->tahun || $this->gedung || $cekArea) {
            if ($this->tanggal) {
                $res  = $data->where(DB::raw("DATE_FORMAT(jam_masuk, '%d')"), $this->tanggal);
            }

            if ($this->bulan) {
                $res  = $data->where(DB::raw("DATE_FORMAT(jam_masuk, '%m')"), $this->bulan);
            }

            if ($this->tahun) {
                $res  = $data->where(DB::raw("DATE_FORMAT(jam_masuk, '%Y')"), $this->tahun);
            }

            if ($this->gedung) {
                $res  = $data->where('gedung_id', $this->gedung);
            }

            if ($cekArea) {
                $res = $data->where('area_id', $this->area);
            } else {
                $area = '';
            }
        } else {
            $res    = $data;
        }

        $tamu = $res->get();
        return $tamu;
    }

    public function headings(): array
    {
        return ["NO", "ID", "JAM MASUK", "JAM KELUAR", "NAMA TAMU", "NOMOR VISITOR", "NIK/NIP", "ALAMAT", "NO.TELPON",
                "ASAL INSTANSI", "PEGAWAI/PEJABAT YANG DITUJU", "KEPERLUAN", "GEDUNG", "LANTAI", "NAMA SUB BAGIAN"];
    }
}
