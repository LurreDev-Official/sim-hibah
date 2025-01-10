<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaDosen extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan oleh model ini (opsional jika nama tabel mengikuti konvensi)
    protected $table = 'anggota_dosens';

    // Tentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'usulan_id',
        'dosen_id',
        'jenis_skema',
        'status_anggota',
        'status',

    ];

    /**
     * Relasi ke model Proposal (many-to-one)
     * Setiap anggota dosen berhubungan dengan satu proposal.
     */
    public function proposal()
    {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }

    /**
     * Relasi ke model Dosen (many-to-one)
     * Setiap anggota dosen berhubungan dengan satu dosen.
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'usulan_id');
    }
}
