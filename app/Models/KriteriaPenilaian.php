<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaPenilaian extends Model
{
    use HasFactory;

    // Tentukan tabel yang akan digunakan oleh model ini
    protected $table = 'kriteria_penilaians';

    // Tentukan kolom yang dapat diisi secara massal (mass assignment)
    protected $fillable = [
        'nama',
        'jenis',
        'proses',
    ];
    public function indikatorPenilaians()
    {
        return $this->hasMany(IndikatorPenilaian::class);
    }
}
