<?php

namespace App\Models;
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

    /**
     * Relasi ke model PenilaianReviewer (one-to-many)
     * Setiap reviewer dapat memiliki banyak penilaian.
     */
    public function penilaianReviewers()
    {
        return $this->hasMany(PenilaianReviewer::class, 'reviewer_id');
    }

    /**
     * Relasi ke model Usulan (many-to-many melalui tabel PenilaianReviewer)
     * Reviewer dapat berhubungan dengan banyak usulan melalui tabel pivot PenilaianReviewer.
     */
    public function usulans()
    {
        return $this->hasManyThrough(
            Usulan::class,
            PenilaianReviewer::class,
            'reviewer_id',   // Foreign key di tabel PenilaianReviewer
            'id',            // Foreign key di tabel Usulan
            'id',            // Local key di tabel Reviewer
            'usulan_id'      // Local key di tabel PenilaianReviewer
        );
    }
}
