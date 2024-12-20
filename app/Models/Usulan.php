<?php

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
        'lama_kegiatan',
    ];

    /**
     * Relasi ke model Dosen.
     * Usulan belongs to a Dosen (ketua dosen).
     */
    public function ketuaDosen()
    {
        return $this->belongsTo(Dosen::class, 'ketua_dosen_id');
    }

       
       // Relasi ke Anggota Dosen
       public function anggotaDosen()
       {
           return $this->hasMany(AnggotaDosen::class, 'usulan_id');
       }
   
       // Relasi ke Anggota Mahasiswa
       public function anggotaMahasiswa()
       {
           return $this->hasMany(AnggotaMahasiswa::class, 'usulan_id');
       }
       

    public function reviewers()
    {
        return $this->belongsToMany(Reviewer::class, 'usulan_reviewer', 'usulan_id', 'reviewer_id')
                    ->withTimestamps();
    }
    
}
