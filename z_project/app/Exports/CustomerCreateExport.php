<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerCreateExport implements FromCollection, WithHeadings  
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select(
            'name',
            'outlet_name',
            'designation',
            'mobile_number',
            'email',
            'location',
            'pincode'
        )->get();
    }

    /**
    * Excel Column Headings
    */
    public function headings(): array
    {
        return [
            'Customer Name',
            'Company Name',
            'Designation',
            'Mobile Number',
            'Email',
            'Location',
            'Pincode'
        ];
    }
}
