<?php
namespace App\Models;
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
        'total_nilai',
        'proses_penilaian',    // Menyimpan jenis proses (Usulan, Laporan Kemajuan, Laporan Akhir)
        'urutan_penilaian',    // Menyimpan urutan penilaian
    ];

    /**
     * Relasi ke UsulanPerbaikan (one-to-one)
     * Setiap PenilaianReviewer dapat memiliki satu UsulanPerbaikan.
     */
    public function usulanPerbaikan()
    {
        return $this->hasOne(UsulanPerbaikan::class, 'penilaian_id');
    }

    /**
     * Relasi ke model Usulan (many-to-one)
     * Setiap PenilaianReviewer terkait dengan satu Usulan.
     */
    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    /**
     * Relasi ke model Reviewer (many-to-one)
     * Setiap PenilaianReviewer terkait dengan satu Reviewer.
     */
    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class, 'reviewer_id');
    }

    // PenilaianReviewer Model
public function formPenilaians()
{
    return $this->hasMany(FormPenilaian::class, 'penilaian_reviewers_id');
}

}

