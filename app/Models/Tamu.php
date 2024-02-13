<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tamu extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_tamu";
    protected $primaryKey = "id_tamu";
    public $timestamps = false;

    protected $fillable = [
        'id_tamu',
        'area_id',
        'lokasi_datang',
        'jam_masuk',
        'jam_keluar',
        'nama_lengkap',
        'nik_nip',
        'alamat_tamu',
        'no_telpon',
        'instansi_id',
        'nama_instansi',
        'nama_tujuan',
        'keperluan',
        'nomor_visitor',
        'survei',
        'foto_ktp'
    ];

    public function instansi() {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function area() {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
