<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormPenilaian extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari konvensi
    protected $table = 'form_penilaians';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'penilaian_reviewers_id',
        'id_kriteria',
        'id_indikator',
        'catatan',
        'nilai',
        'status',
    ];

    // Relasi ke model KriteriaPenilaian
    public function kriteria()
    {
        return $this->belongsTo(KriteriaPenilaian::class, 'id_kriteria');
    }

    public function indikator()
    {
        return $this->belongsTo(IndikatorPenilaian::class, 'id_indikator');
    }

    // Relasi ke model PenilaianReviewers
    public function reviewer()
    {
        return $this->belongsTo(PenilaianReviewers::class, 'penilaian_reviewers_id');
    }
}