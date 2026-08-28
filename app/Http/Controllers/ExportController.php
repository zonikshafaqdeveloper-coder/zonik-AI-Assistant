<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EnquiryExport;

class ExportController extends Controller
{
    public function exportEnquiry()
    {
        return Excel::download(new EnquiryExport, 'enquiries.xlsx');
    }
}