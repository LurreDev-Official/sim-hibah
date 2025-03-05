<?php

namespace App\Models;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usulan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'judul_usulan',
        'jenis_skema',
        'tahun_pelaksanaan',
        'ketua_dosen_id',
        'dokumen_usulan',
        'status',
        'rumpun_ilmu',
        'bidang_fokus',
        'tema_penelitian',
        'topik_penelitian',
        'lokasi_penelitian',
        'lama_kegiatan',
        'tingkat_kecukupan_teknologi', // TKT
        'nama_mitra',
        'lokasi_mitra',
        'bidang_mitra',
        'jarak_pt_ke_lokasi_mitra', // dalam km
        'luaran',
    ];

    /**
     * Relasi ke model Dosen.
     * Setiap usulan dimiliki oleh satu dosen sebagai ketua.
     */
    public function ketuaDosen()
    {
        return $this->belongsTo(Dosen::class, 'ketua_dosen_id');
    }

    /**
     * Relasi ke Anggota Dosen.
     * Satu usulan dapat memiliki banyak anggota dosen.
     */
    public function anggotaDosen()
    {
        return $this->hasMany(AnggotaDosen::class, 'usulan_id');
    }

    /**
     * Relasi ke Anggota Mahasiswa.
     * Satu usulan dapat memiliki banyak anggota mahasiswa.
     */
    public function anggotaMahasiswa()
    {
        return $this->hasMany(AnggotaMahasiswa::class, 'usulan_id');
    }

    /**
     * Relasi ke Penilaian Reviewer.
     * Usulan dapat memiliki banyak penilaian dari reviewer.
     */
    public function penilaianReviewers()
    {
        return $this->hasMany(PenilaianReviewer::class, 'usulan_id');
    }

    /**
     * Relasi ke Reviewer (many-to-many melalui tabel PenilaianReviewer).
     * Usulan dapat diulas oleh banyak reviewer.
     */
    public function reviewers()
    {
        return $this->hasManyThrough(
            Reviewer::class,
            PenilaianReviewer::class,
            'usulan_id',   // Foreign key di tabel PenilaianReviewer
            'id',          // Foreign key di tabel Reviewer
            'id',          // Local key di tabel Usulan
            'reviewer_id'  // Local key di tabel PenilaianReviewer
        );
    }

    public function usulanPerbaikans()
{
    return $this->hasMany(UsulanPerbaikan::class);
}

public function laporanKemajuan()
{
    return $this->hasOne(LaporanKemajuan::class, 'usulan_id');
}


}
