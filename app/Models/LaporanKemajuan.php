<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKemajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'ketua_dosen_id',
        'usulan_id',
        'dokumen_laporan_kemajuan',
        'status',
        'jenis',
    ];

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'ketua_dosen_id');
    }

    // Relasi ke Usulan
    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }
}
