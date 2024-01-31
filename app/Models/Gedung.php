<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gedung   extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_gedung";
    protected $primaryKey = "id_gedung";
    public $timestamps = false;

    protected $fillable = [
        'id_gedung',
        'nama_gedung'
    ];

    public function area() {
        return $this->hasMany(Area::class, 'gedung_id');
    }
}
