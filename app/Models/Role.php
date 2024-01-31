<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "t_role";
    protected $primaryKey = "id_role";
    public $timestamps = false;

    protected $fillable = [
        'role'
    ];
}
