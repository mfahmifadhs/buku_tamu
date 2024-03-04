<?php

namespace App\Exports;

use App\Models\Area;
use App\Models\Tamu;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TamuExport implements FromCollection, WithHeadings, WithDrawings
{
    protected $request;
    protected $no = 0;


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
        $cekArea = Area::where('id_area', $this->area)->where('gedung_id', $this->gedung)->first();
        $data = Tamu::join('t_gedung_area', 'id_area', 'area_id')
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
                'foto_tamu'
            );

        if ($this->tanggal) {
            $data->where(DB::raw("DATE_FORMAT(jam_masuk, '%d')"), $this->tanggal);
        }

        if ($this->bulan) {
            $data->where(DB::raw("DATE_FORMAT(jam_masuk, '%m')"), $this->bulan);
        }

        if ($this->tahun) {
            $data->where(DB::raw("DATE_FORMAT(jam_masuk, '%Y')"), $this->tahun);
        }

        if ($this->gedung) {
            $data->where('gedung_id', $this->gedung);
        }

        if ($cekArea) {
            $data->where('area_id', $this->area);
        }

        $tamu = $data->get();
        return $tamu;
    }

    public function drawings()
    {
        $drawings = [];
        $tamu = $this->collection();

        foreach ($tamu as $index => $image) {
            // Periksa apakah nilai foto_tamu null atau tidak
            if ($image->foto_tamu) {
                $imagePath = public_path('storage/foto_tamu/' . $image->foto_tamu);

                $drawing = new Drawing();
                $drawing->setName('Image' . $index)
                    ->setDescription($image->foto_tamu)
                    ->setPath($imagePath)
                    ->setHeight(60)
                    ->setWidth(60)
                    // ->setOffsetX(12)
                    // ->setOffsetY(12)
                    ->setCoordinates('P' . ($index + 2));

                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }

    // public function map($tamu): array
    // {
    //     $gambarPath = public_path('storage/foto_tamu/' . $tamu->foto_tamu);

    //     return [
    //         ++$this->no,
    //         $tamu->id_tamus,
    //         $tamu->jam_masuk,
    //         $tamu->jam_keluar,
    //         $tamu->nama_tamu,
    //         $tamu->nomor_visitor,
    //         $tamu->nik_nip,
    //         $tamu->alamat_tamu,
    //         $tamu->no_telpon,
    //         $tamu->nama_instansi,
    //         $tamu->nama_tujuan,
    //         $tamu->keperluan,
    //         $tamu->nama_gedung,
    //         $tamu->nama_lantai,
    //         $tamu->nama_sub_bagian,
    //         $gambarPath
    //     ];
    // }

    public function headings(): array
    {
        return [
            "NO", "ID", "JAM MASUK", "JAM KELUAR", "NAMA TAMU", "NOMOR VISITOR", "NIK/NIP", "ALAMAT", "NO.TELPON",
            "ASAL INSTANSI", "PEGAWAI/PEJABAT YANG DITUJU", "KEPERLUAN", "GEDUNG", "LANTAI", "NAMA SUB BAGIAN", "FOTO"
        ];
    }
}
