<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaMahasiswa extends Model
{
    use HasFactory;
     // Field yang dapat diisi (fillable)
     protected $fillable = [
        'usulan_id',
        'nim',
        'nama_lengkap',
        'fakultas',
        'prodi',
    ];

    // Definisikan relasi antara AnggotaMahasiswa dan Usulan
    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }
}
