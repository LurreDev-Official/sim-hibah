<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    // Tentukan tabel yang akan digunakan oleh model ini
    protected $table = 'dosens';

    // Tentukan kolom yang dapat diisi secara massal (mass assignment)
    protected $fillable = [
        'user_id',
        'sintaid',
        'nidn',
        'status',
        'kuota_proposal',
        'jumlah_proposal',
        'fakultas_id',
        'prodi_id',
        'score_sinta',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
    /**
     * Relasi ke model User (many-to-one)
     * Setiap dosen dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
