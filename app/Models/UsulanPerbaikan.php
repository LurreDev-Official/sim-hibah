<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanPerbaikan extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika berbeda dari konvensi penamaan
    protected $table = 'usulan_perbaikans';

    // Menentukan kolom yang dapat diisi massal
    protected $fillable = [
        'dokumen_usulan',
        'penilaian_id',
        'status',
        'usulan_id',
    ];

    // Definisikan relasi dengan model lain jika diperlukan
    public function penilaianReviewer()
    {
        return $this->belongsTo(PenilaianReviewer::class, 'penilaian_id');
    }

    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }
}