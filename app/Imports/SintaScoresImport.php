<?php

namespace App\Imports;

use App\Models\SintaScore;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class SintaScoresImport implements ToModel, WithHeadingRow
{
    protected $failures = [];

    public function model(array $row)
    {
        // Validasi data sebelum menyimpannya
        $validator = Validator::make($row, [
            'sintaid' => 'required',
            'nidn' => 'required',
            'sintascorev3' => 'required', // Pastikan ini ada
            'tahun' => 'required',
        ]);

        if ($validator->fails()) {
            // Jika validasi gagal, simpan kesalahan
            $this->failures[] = $validator->errors()->all();
            return null; // Kembalikan null agar tidak menyimpan data yang tidak valid
        }

        // Cek apakah SintaScore dengan sintaid dan nidn sudah ada
        $sintaScore = SintaScore::where('sintaid', $row['sintaid'])
            ->where('nidn', $row['nidn'])
            ->first();

        if ($sintaScore) {
            // Jika sudah ada, update sintascorev3
            $sintaScore->sintascorev3 = $row['sintascorev3'];
            $sintaScore->tahun = $row['tahun']; // Update tahun jika diperlukan
            $sintaScore->save(); // Simpan perubahan
        } else {
            // Jika tidak ada, buat entri baru
            return new SintaScore([
                'sintaid' => $row['sintaid'],
                'nidn' => $row['nidn'],
                'sintascorev3' => $row['sintascorev3'],
                'tahun' => $row['tahun'],
            ]);
        }
    }

    public function getFailures()
    {
        return $this->failures;
    }
}