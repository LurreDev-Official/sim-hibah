<?php
namespace App\Exports;

use App\Models\LaporanAkhir;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanAkhirExport implements FromCollection, WithHeadings, WithMapping
{
    protected $jenis;

    public function __construct($jenis)
    {
        $this->jenis = $jenis;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Fetch LaporanAkhir data filtered by jenis
        return LaporanAkhir::with(['dosen', 'usulan'])
            ->where('jenis', $this->jenis)  // Filter by jenis
            ->get();
    }

    /**
     * Define the headings for the export file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Ketua Dosen',
            'Usulan',
            'Dokumen Laporan Akhir',
            'Jenis',
            'Status',
            'Dosen Name',
            'Usulan Name'
        ];
    }

    /**
     * Map the data into an array for each row.
     *
     * @param mixed $laporanAkhir
     * @return array
     */
    public function map($laporanAkhir): array
    {
        return [
            $laporanAkhir->id,
            $laporanAkhir->ketua_dosen_id,
            $laporanAkhir->usulan_id,
            $laporanAkhir->dokumen_laporan_akhir,
            $laporanAkhir->jenis,
            $laporanAkhir->status,
            $laporanAkhir->dosen ? $laporanAkhir->dosen->name : 'N/A',
            $laporanAkhir->usulan ? $laporanAkhir->usulan->name : 'N/A',
        ];
    }
}
