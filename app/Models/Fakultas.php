<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'initial',
    ];

    /**
     * Relasi satu ke banyak dengan Prodi
     */
    public function prodis()
    {
        return $this->hasMany(Prodi::class);
    }

}
