<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabangIlmu extends Model
{
    use HasFactory;
    protected $table = 'cabang_ilmu';
    protected $fillable = ['id_rumpun', 'nama_cabang'];

}
