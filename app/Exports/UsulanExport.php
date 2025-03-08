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
            'Dokumen Usulan Perbaikan', 
            'Status', 
            'Rumpun Ilmu', 
            'Bidang Fokus', 
            'Tema Penelitian', 
            'Topik Penelitian', 
            'Lama Kegiatan',
            'created_at',
            'updated_at',
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
    // Ambil dokumen usulan perbaikan terbaru (jika ada)
    $dokumenUsulanPerbaikan = $usulan->usulanPerbaikans->last()?->dokumen_usulan ?? null;

    return [
        $usulan->id,
        $usulan->judul_usulan,
        $usulan->jenis_skema,
        $usulan->tahun_pelaksanaan,
        $usulan->ketuaDosen?->user?->name ?? 'N/A', // Nama Ketua Dosen
        'https://srikandi.unhasy.ac.id/storage/' . $usulan->dokumen_usulan, // Dokumen Usulan
        $dokumenUsulanPerbaikan ? 'https://srikandi.unhasy.ac.id/storage/' . $dokumenUsulanPerbaikan : 'N/A', // Dokumen Usulan Perbaikan
        $usulan->status,
        $usulan->rumpun_ilmu,
        $usulan->bidang_fokus,
        $usulan->tema_penelitian,
        $usulan->topik_penelitian,
        $usulan->lama_kegiatan,
        $usulan->created_at,
        $usulan->updated_at,
    ];
}
}

