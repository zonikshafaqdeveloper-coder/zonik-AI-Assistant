<?php

namespace App\Exports;

use App\Models\Holiday;
use Maatwebsite\Excel\Concerns\FromCollection;

class HolidaysExport implements FromCollection
{
    public function collection()
    {
        return Holiday::all();
    }
}
