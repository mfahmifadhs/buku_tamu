<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instansi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_instansi";
    protected $primaryKey = "id_instansi";
    public $timestamps = false;

    protected $fillable = [
        'instansi'
    ];
}
