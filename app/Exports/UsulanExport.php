<?php

namespace App\Exports;

use App\Models\Usulan;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsulanExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Usulan::all();
    }
}
