<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_gedung_area";
    protected $primaryKey = "id_area";
    public $timestamps = false;

    protected $fillable = [
        'id_area',
        'gedung_id',
        'nama_lantai',
        'nama_ruang',
        'nama_sub_bagian',
        'nama_lain',
        'existing',
        'nama_pejabat'
    ];

    public function gedung() {
        return $this->belongsTo(Gedung::class, 'gedung_id');
    }
}
