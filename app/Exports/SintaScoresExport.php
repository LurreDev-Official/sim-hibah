<?php

namespace App\Exports;

use App\Models\SintaScore;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SintaScoresExport implements FromCollection, WithHeadings
{
    /**
     * Mengambil semua data untuk diekspor.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil semua data dari tabel SintaScore
        return SintaScore::select('sintaid', 'nidn', 'sintascorev3', 'tahun')->get();
    }

    /**
     * Menentukan header untuk file ekspor.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'sintaid',
            'nidn',
            'sintascorev3',
            'tahun',
        ];
    }
}
