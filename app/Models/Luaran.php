<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Luaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'usulan_id',
        'laporankemajuan_id',
        'laporanakhir_id',
        'judul',
        'jenis_luaran', // Tambahkan ini jika kolom enum diperlukan
        'type',
        'url',
        'status',
        'file_loa',
    ];

    public function usulan()
    {
        return $this->hasMany(Usulan::class, 'id', 'usulan_id');
    }
}
