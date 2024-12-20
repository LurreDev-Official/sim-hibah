<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorPenilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'kriteria_id', 'nama_indikator', 'jumlah_bobot'
    ];

    public function kriteriaPenilaian()
    {
        return $this->belongsTo(KriteriaPenilaian::class, 'kriteria_id');
    }
}
