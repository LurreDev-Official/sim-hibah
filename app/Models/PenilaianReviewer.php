<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianReviewer extends Model
{
    use HasFactory;

    protected $table = 'penilaian_reviewers';

    // Kolom yang bisa diisi secara massal (mass-assignable)
    protected $fillable = [
        'usulan_id',
        'status_penilaian',
        'reviewer_id',
        'total_nilai'
    ];

     // Relasi ke UsulanPerbaikan (one-to-one)
     public function usulanPerbaikan()
     {
         return $this->hasOne(UsulanPerbaikan::class, 'penilaian_id');
     }

    // Relasi ke model Usulan
    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    // Relasi ke model Reviewer
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class, 'reviewer_id');
    }
 
}
