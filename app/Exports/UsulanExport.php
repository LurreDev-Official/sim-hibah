<?php
namespace App\Exports;

use App\Models\Usulan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsulanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $jenis;

    // Constructor to receive 'jenis' parameter
    public function __construct($jenis)
    {
        $this->jenis = $jenis;
    }

    /**
     * Return the collection of Usulan data based on 'jenis'.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Query Usulan model with the 'jenis' field
        return Usulan::where('jenis_skema', $this->jenis)->get();
    }

    /**
     * Define the headers for the exported Excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID', 
            'Judul Usulan', 
            'Jenis Skema', 
            'Tahun Pelaksanaan', 
            'Ketua Dosen', 
            'Dokumen Usulan', 
            'Status', 
            'Rumpun Ilmu', 
            'Bidang Fokus', 
            'Tema Penelitian', 
            'Topik Penelitian', 
            'Lama Kegiatan'
        ];
    }

    /**
     * Map each row's data for export.
     *
     * @param  \App\Models\Usulan  $usulan
     * @return array
     */
    public function map($usulan): array
    {
        return [
            $usulan->id,
            $usulan->judul_usulan,
            $usulan->jenis_skema,
            $usulan->tahun_pelaksanaan,
            $usulan->ketuaDosen->name ?? 'N/A', // Assuming 'name' is a field in Dosen
            $usulan->dokumen_usulan,
            $usulan->status,
            $usulan->rumpun_ilmu,
            $usulan->bidang_fokus,
            $usulan->tema_penelitian,
            $usulan->topik_penelitian,
            $usulan->lama_kegiatan,
        ];
    }
}

