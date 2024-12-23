<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\KriteriaPenilaian;
use App\Models\IndikatorPenilaian;
class KriteriaPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            ['nama' => 'Rumusan Masalah', 'jenis' => 'Penelitian', 'proses' => 'Usulan'],
            ['nama' => 'Kontribusi Hasil Penelitian', 'jenis' => 'Penelitian', 'proses' => 'Usulan'],
            ['nama' => 'Luaran', 'jenis' => 'Penelitian', 'proses' => 'Usulan'],
            ['nama' => 'Tinjauan Pustaka', 'jenis' => 'Penelitian', 'proses' => 'Usulan'],
            ['nama' => 'Metode Penelitian', 'jenis' => 'Penelitian', 'proses' => 'Usulan'],
            ['nama' => 'Keterlibatan Mahasiswa', 'jenis' => 'Penelitian', 'proses' => 'Usulan'],
            ['nama' => 'Jadwal Penelitian', 'jenis' => 'Penelitian', 'proses' => 'Usulan'],
            ['nama' => 'Anggaran Biaya', 'jenis' => 'Penelitian', 'proses' => 'Usulan'],
            ['nama' => 'Capaian Luaran Sementara', 'jenis' => 'Penelitian', 'proses' => 'Laporan Kemajuan'],
            ['nama' => 'Kesesuaian dengan Rencana', 'jenis' => 'Penelitian', 'proses' => 'Laporan Kemajuan'],
            ['nama' => 'Kendala dan Solusi', 'jenis' => 'Penelitian', 'proses' => 'Laporan Kemajuan'],
            ['nama' => 'Penggunaan Anggaran', 'jenis' => 'Penelitian', 'proses' => 'Laporan Kemajuan'],
            ['nama' => 'Hasil Penelitian', 'jenis' => 'Penelitian', 'proses' => 'Laporan Akhir'],
            ['nama' => 'Analisis dan Pembahasan', 'jenis' => 'Penelitian', 'proses' => 'Laporan Akhir'],
            ['nama' => 'Kesimpulan dan Saran', 'jenis' => 'Penelitian', 'proses' => 'Laporan Akhir'],
            ['nama' => 'Luaran Akhir', 'jenis' => 'Penelitian', 'proses' => 'Laporan Akhir'],
            ['nama' => 'Dokumentasi Kegiatan', 'jenis' => 'Penelitian', 'proses' => 'Laporan Akhir'],
            ['nama' => 'Rumusan Masalah', 'jenis' => 'Pengabdian', 'proses' => 'Usulan'],
            ['nama' => 'Tujuan dan Manfaat', 'jenis' => 'Pengabdian', 'proses' => 'Usulan'],
            ['nama' => 'Metode Pelaksanaan', 'jenis' => 'Pengabdian', 'proses' => 'Usulan'],
            ['nama' => 'Keterlibatan Mahasiswa', 'jenis' => 'Pengabdian', 'proses' => 'Usulan'],
            ['nama' => 'Jadwal Kegiatan', 'jenis' => 'Pengabdian', 'proses' => 'Usulan'],
            ['nama' => 'Anggaran Biaya', 'jenis' => 'Pengabdian', 'proses' => 'Usulan'],
            ['nama' => 'Capaian Sementara', 'jenis' => 'Pengabdian', 'proses' => 'Laporan Kemajuan'],
            ['nama' => 'Kesesuaian dengan Rencana', 'jenis' => 'Pengabdian', 'proses' => 'Laporan Kemajuan'],
            ['nama' => 'Kendala dan Solusi', 'jenis' => 'Pengabdian', 'proses' => 'Laporan Kemajuan'],
            ['nama' => 'Penggunaan Anggaran', 'jenis' => 'Pengabdian', 'proses' => 'Laporan Kemajuan'],
            ['nama' => 'Hasil Kegiatan', 'jenis' => 'Pengabdian', 'proses' => 'Laporan Akhir'],
            ['nama' => 'Dampak pada Masyarakat', 'jenis' => 'Pengabdian', 'proses' => 'Laporan Akhir'],
            ['nama' => 'Kesimpulan dan Rekomendasi', 'jenis' => 'Pengabdian', 'proses' => 'Laporan Akhir'],
            ['nama' => 'Luaran Akhir', 'jenis' => 'Pengabdian', 'proses' => 'Laporan Akhir'],
            ['nama' => 'Dokumentasi Kegiatan', 'jenis' => 'Pengabdian', 'proses' => 'Laporan Akhir'],
        ];

        foreach ($data as $item) {
            KriteriaPenilaian::create($item);
        }


      

        foreach ($data2 as $item2) {
            $kriteria = KriteriaPenilaian::where('nama', $item2['kriteria'])->first();

            if ($kriteria) {
                IndikatorPenilaian::create([
                    'kriteria_id' => $kriteria->id,
                    'nama_indikator' => $item2['nama_indikator'],
                    'jumlah_bobot' => $item2['jumlah_bobot'],
                ]);
            }
        }
    }
}
