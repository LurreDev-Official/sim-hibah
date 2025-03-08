<?php

namespace App\Exports;

use App\Models\LaporanAkhir;
use App\Models\Dosen;
use App\Models\AnggotaDosen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class ReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $jenis;

    /**
     * Constructor untuk menerima parameter jenis laporan.
     *
     * @param string|null $jenis
     */
    public function __construct($jenis = null)
    {
        $this->jenis = $jenis;
    }

    /**
     * Mengembalikan koleksi LaporanAkhir berdasarkan jenis dan role pengguna.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user(); // Dapatkan user yang sedang login
        
        if ($user->hasRole('Kepala LPPM')) {
            // Jika user adalah Kepala LPPM, ambil semua data LaporanAkhir
            return LaporanAkhir::with(['dosen', 'usulan'])
                ->when($this->jenis, function ($query) {
                    return $query->where('jenis', $this->jenis);
                })
                ->get();
        } elseif ($user->hasRole('Dosen')) {
            // Jika user adalah Dosen, ambil data yang terkait dengan Dosen tersebut
            $dosen = Dosen::where('user_id', $user->id)->first();
            if ($dosen) {
                $usulanIds = AnggotaDosen::where('dosen_id', $dosen->id)->pluck('usulan_id');

                return LaporanAkhir::with(['dosen', 'usulan'])
                    ->whereIn('usulan_id', $usulanIds)
                    ->when($this->jenis, function ($query) {
                        return $query->where('jenis', $this->jenis);
                    })
                    ->get();
            }
        }

        // Jika tidak ada role yang sesuai, kembalikan koleksi kosong
        return collect([]);
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
            'Ketua Dosen',
            'Usulan ID',
            'Dokumen Laporan Akhir',
            'Status',
            'Jenis',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * Mapping data setiap baris untuk file Excel.
     *
     * @param  \App\Models\LaporanAkhir  $laporanAkhir
     * @return array
     */
    public function map($laporanAkhir): array
    {
        return [
            $laporanAkhir->id,
            $laporanAkhir->usulan?->ketuaDosen?->user?->name ?? 'N/A', // Mengambil Nama Ketua Dosen
            $laporanAkhir->usulan_id,
            'https://srikandi.unhasy.ac.id/storage/' .$laporanAkhir->dokumen_laporan_akhir ?? 'Tidak Ada',
            $laporanAkhir->status,
            $laporanAkhir->jenis,
            $laporanAkhir->created_at,
            $laporanAkhir->updated_at,
        ];
    }
}