<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;
    protected $table = 'periodes';
    protected $fillable = [
        'tahun',
        'tanggal_awal',
        'tanggal_akhir',
        'nominal',
        'is_active'
    ];
    protected $casts = [
        'tanggal_awal' => 'date',
        'tanggal_akhir' => 'date',
        'is_active' => 'boolean',
    ];
}
