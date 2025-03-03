<?php

namespace App\Exports;

use App\Models\Luaran;
use Maatwebsite\Excel\Concerns\FromCollection;

class LuaranExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Luaran::with();
    }
}
