<?php

namespace App\Exports;

use App\Models\LaporanKemajuan;
use App\Models\Dosen;
use App\Models\AnggotaDosen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class LaporanKemajuanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $jenis;

    // Constructor to receive 'jenis' parameter
    public function __construct($jenis)
    {
        $this->jenis = $jenis;
    }

    /**
     * Return the collection of LaporanKemajuan data based on 'jenis' and role.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user(); // Get the logged-in user
        
        if ($user->hasRole('Kepala LPPM')) {
            // If the user is Kepala LPPM, fetch all LaporanKemajuan
            return LaporanKemajuan::when($this->jenis, function ($query) {
                return $query->where('jenis', $this->jenis);
            })->get();
        } elseif ($user->hasRole('Dosen')) {
            // If the user is Dosen, fetch only those associated with the logged-in Dosen
            $dosen = Dosen::where('user_id', $user->id)->first();
            if ($dosen) {
                $usulanIds = AnggotaDosen::where('dosen_id', $dosen->id)->pluck('usulan_id');
                
                return LaporanKemajuan::whereIn('usulan_id', $usulanIds)
                    ->when($this->jenis, function ($query) {
                        return $query->where('jenis', $this->jenis);
                    })
                    ->get();
            }
        }

        // If no role matches, return an empty collection
        return collect([]);
    }

    /**
     * Define the headings for the exported Excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Ketua Dosen',
            'Usulan ID',
            'Dokumen Laporan Kemajuan',
            'Status',
            'Jenis'
        ];
    }

    /**
     * Map each row's data for export.
     *
     * @param  \App\Models\LaporanKemajuan  $laporanKemajuan
     * @return array
     */
    public function map($laporanKemajuan): array
    {
        return [
            $laporanKemajuan->id,
            $laporanKemajuan->dosen->name ?? 'N/A', // Ketua Dosen
            $laporanKemajuan->usulan_id,
            $laporanKemajuan->dokumen_laporan_kemajuan,
            $laporanKemajuan->status,
            $laporanKemajuan->jenis,
        ];
    }
}
