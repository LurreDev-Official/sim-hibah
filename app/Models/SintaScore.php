<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SintaScore extends Model
{
    use HasFactory;
    // Tentukan nama tabel jika tidak sesuai dengan konvensi Laravel
    protected $table = 'sinta_scores';
    // Tentukan kolom yang dapat diisi massal
    protected $fillable = [
        'sintaid',
        'nidn',
        'sintascorev3',
        'tahun',
    ];
}
