<?php

namespace App\Exports;

use App\Models\Luaran;
use App\Models\Dosen;
use App\Models\AnggotaDosen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class LuaranExport implements FromCollection, WithHeadings, WithMapping
{
    protected $jenis_luaran;

    public function __construct($jenis_luaran = null)
    {
        $this->jenis_luaran = $jenis_luaran;
    }

    /**
     * Mengambil data berdasarkan peran pengguna.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Luaran::with(['usulan.ketuaDosen.user'])->get(); // Load relasi dengan User
    }

    /**
     * Header untuk file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Usulan ID',
            'Nama Ketua',
            'Judul',
            'Jenis Luaran',
            'Tipe',
            'URL',
            'Status',
            'File LOA',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * Mapping data ke format yang sesuai untuk ekspor.
     *
     * @param  \App\Models\Luaran  $luaran
     * @return array
     */
    public function map($luaran): array
    {
        return [
            $luaran->id,
            $luaran->usulan_id,
            $luaran->usulan_id,
            $luaran->usulan?->ketuaDosen?->user?->name ?? 'N/A', // Mengambil Nama Ketua Dosen
            $luaran->judul,
            $luaran->jenis_luaran,
            $luaran->type,
            $luaran->url,
            $luaran->status,
            $luaran->file_loa,
            $luaran->created_at,
            $luaran->updated_at,
        ];
    }
}
