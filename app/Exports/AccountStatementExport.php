<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AccountStatementExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $summary;

    public function __construct(array $summary)
    {
        $this->summary = $summary;
    }

    public function collection()
    {
        return collect($this->summary);
    }

    public function headings(): array
    {
        return ['Month', 'Total Amount (₹)', 'Status', 'Outstanding (₹)'];
    }

    public function map($row): array
    {
        return [
            $row['month_label'],
            number_format($row['total'], 2),
            $row['status'],
            number_format($row['outstanding'], 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
