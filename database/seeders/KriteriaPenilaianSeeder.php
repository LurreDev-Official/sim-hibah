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

        $data2 = [
            // Penelitian - Usulan
            [
                'kriteria' => 'Rumusan Masalah',
                'nama_indikator' => 'Kejelasan perumusan masalah',
                'jumlah_bobot' => 20,
            ],
            [
                'kriteria' => 'Rumusan Masalah',
                'nama_indikator' => 'Relevansi masalah dengan bidang penelitian',
                'jumlah_bobot' => 15,
            ],
            [
                'kriteria' => 'Rumusan Masalah',
                'nama_indikator' => 'Tingkat kebaruan (novelty) masalah yang diangkat',
                'jumlah_bobot' => 20,
            ],
            [
                'kriteria' => 'Kontribusi Hasil Penelitian',
                'nama_indikator' => 'Potensi kontribusi terhadap ilmu pengetahuan',
                'jumlah_bobot' => 25,
            ],
            [
                'kriteria' => 'Kontribusi Hasil Penelitian',
                'nama_indikator' => 'Manfaat praktis bagi masyarakat atau industri',
                'jumlah_bobot' => 20,
            ],
            [
                'kriteria' => 'Kontribusi Hasil Penelitian',
                'nama_indikator' => 'Peluang publikasi di jurnal bereputasi',
                'jumlah_bobot' => 15,
            ],
            [
                'kriteria' => 'Luaran',
                'nama_indikator' => 'Jenis luaran yang ditargetkan (publikasi, paten, prototipe, dll.)',
                'jumlah_bobot' => 30,
            ],
            [
                'kriteria' => 'Luaran',
                'nama_indikator' => 'Kesesuaian luaran dengan tujuan penelitian',
                'jumlah_bobot' => 25,
            ],
            [
                'kriteria' => 'Tinjauan Pustaka',
                'nama_indikator' => 'Kelengkapan dan relevansi literatur yang dikaji',
                'jumlah_bobot' => 15,
            ],
            [
                'kriteria' => 'Tinjauan Pustaka',
                'nama_indikator' => 'Kritisisme dalam menganalisis literatur',
                'jumlah_bobot' => 20,
            ],
            [
                'kriteria' => 'Tinjauan Pustaka',
                'nama_indikator' => 'Identifikasi gap penelitian berdasarkan literatur',
                'jumlah_bobot' => 20,
            ],
            [
                'kriteria' => 'Metode Penelitian',
                'nama_indikator' => 'Kejelasan dan ketepatan metode yang digunakan',
                'jumlah_bobot' => 30,
            ],
            [
                'kriteria' => 'Metode Penelitian',
                'nama_indikator' => 'Kesesuaian metode dengan tujuan penelitian',
                'jumlah_bobot' => 25,
            ],
            [
                'kriteria' => 'Metode Penelitian',
                'nama_indikator' => 'Kelayakan metode untuk mencapai hasil yang diharapkan',
                'jumlah_bobot' => 20,
            ],
            [
                'kriteria' => 'Keterlibatan Mahasiswa',
                'nama_indikator' => 'Jumlah dan peran mahasiswa dalam penelitian',
                'jumlah_bobot' => 10,
            ],
            [
                'kriteria' => 'Keterlibatan Mahasiswa',
                'nama_indikator' => 'Relevansi keterlibatan mahasiswa dengan pengembangan kompetensi mereka',
                'jumlah_bobot' => 15,
            ],
            [
                'kriteria' => 'Jadwal Penelitian',
                'nama_indikator' => 'Kejelasan dan realisme timeline penelitian',
                'jumlah_bobot' => 15,
            ],
            [
                'kriteria' => 'Jadwal Penelitian',
                'nama_indikator' => 'Kesesuaian jadwal dengan sumber daya yang tersedia',
                'jumlah_bobot' => 10,
            ],
            [
                'kriteria' => 'Anggaran Biaya',
                'nama_indikator' => 'Rincian anggaran yang jelas dan terperinci',
                'jumlah_bobot' => 20,
            ],
            [
                'kriteria' => 'Anggaran Biaya',
                'nama_indikator' => 'Efisiensi dan efektivitas penggunaan dana',
                'jumlah_bobot' => 15,
            ],

            // Penelitian - Laporan Kemajuan
            [
                'kriteria' => 'Capaian Luaran Sementara',
                'nama_indikator' => 'Tingkat pencapaian luaran sesuai dengan target sementara',
                'jumlah_bobot' => 25,
            ],
            [
                'kriteria' => 'Kesesuaian dengan Rencana',
                'nama_indikator' => 'Tingkat kesesuaian pelaksanaan dengan proposal awal',
                'jumlah_bobot' => 20,
            ],
            [
                'kriteria' => 'Kendala dan Solusi',
                'nama_indikator' => 'Identifikasi kendala yang dihadapi selama penelitian',
                'jumlah_bobot' => 15,
            ],
            [
                'kriteria' => 'Penggunaan Anggaran',
                'nama_indikator' => 'Laporan realisasi anggaran dibandingkan dengan rencana',
                'jumlah_bobot' => 15,
            ],

            // Penelitian - Laporan Akhir
            [
                'kriteria' => 'Hasil Penelitian',
                'nama_indikator' => 'Kejelasan dan kelengkapan penyajian hasil penelitian',
                'jumlah_bobot' => 30,
            ],
            [
                'kriteria' => 'Analisis dan Pembahasan',
                'nama_indikator' => 'Kedalaman analisis data yang dilakukan',
                'jumlah_bobot' => 25,
            ],
            [
                'kriteria' => 'Kesimpulan dan Saran',
                'nama_indikator' => 'Kejelasan kesimpulan yang diambil berdasarkan hasil',
                'jumlah_bobot' => 20,
            ],
            [
                'kriteria' => 'Luaran Akhir',
                'nama_indikator' => 'Pencapaian luaran sesuai dengan yang ditargetkan',
                'jumlah_bobot' => 30,
            ],
            [
                'kriteria' => 'Dokumentasi Kegiatan',
                'nama_indikator' => 'Kelengkapan dokumentasi selama proses penelitian',
                'jumlah_bobot' => 15,
            ],
        ];

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
