<?php

namespace App\Exports;

use App\Models\SintaScore;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SintaScoresExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return SintaScore::all(); // Mengambil semua data Sinta Score
    }

    public function headings(): array
    {
        return [
            'id',
            'sintaid',
            'nidn',
            'sintascorev3',
            'tahun',
        ];
    }
}