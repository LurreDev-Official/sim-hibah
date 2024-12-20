<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
    use HasFactory;

    // Tentukan tabel yang akan digunakan oleh model ini
    protected $table = 'reviewers';

    // Tentukan kolom yang dapat diisi secara massal (mass assignment)
    protected $fillable = [
        'user_id',
        'nidn',
        'fakultas',
        'prodi',
    ];

    /**
     * Relasi ke model User (many-to-one)
     * Setiap reviewer dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    
}
