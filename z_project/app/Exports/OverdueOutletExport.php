<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class OverdueOutletExport implements FromArray, WithEvents
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $orderInvoice      = $this->data['orderInvoice'];
        $dueDaysLimit      = $this->data['due_days_limit'];
        $hasNewPaymentTerm = $this->data['hasNewPaymentTerm'];
        $paymentTerm       = $this->data['paymentTerm'];
        $companyName       = $this->data['company_name1'];
        $creditLimit       = $this->data['creditLimit'];
        $mobileNumber      = $this->data['mobileNumber'];
        $outletName        = $orderInvoice->first()->user?->outlet_name ?? 'N/A';
        $gstNo             = $this->data['orderss']->first()->gst_no ?? 'N/A';

        if ($hasNewPaymentTerm) {
            $parts = [];
            if (!empty($paymentTerm->from_range)) $parts[] = (int) $paymentTerm->from_range;
            if (!empty($paymentTerm->to_range))   $parts[] = (int) $paymentTerm->to_range;
            if (!empty($paymentTerm->days))       $parts[] = (int) $paymentTerm->days;
            $creditDaysText = implode(' + ', $parts) . ' = ' . array_sum($parts);
        } else {
            $creditDaysText = $dueDaysLimit ?? 0;
        }

        $rows = [];

        // ---- Summary / metadata block ----
        $rows[] = ['Company Name:', $companyName, '', 'Credit Limit:', $creditLimit];
        $rows[] = ['Outlet Name:', $outletName, '', 'Credit Days:', $creditDaysText];
        $rows[] = ['Outlet Contact:', $mobileNumber];
        $rows[] = ['GST No:', $gstNo];
        $rows[] = []; // spacer row

        // ---- Table header ----
        $rows[] = ['Sr.', 'Invoice ID', 'Invoice Date', 'Delivery Date', 'Due Date', 'Days Outstanding', 'Due Amount'];

        $sr               = 0;
        $totalOutstanding = 0;
        $totalOverdue     = 0;
        $maxOverdueDays   = 0;

        foreach ($orderInvoice as $order) {
            $sr++;
            $deliveryDate = Carbon::parse($order->delivery_date);

            if ($order->payment_method === 'special_credit' && $order->custom_due_days) {

                $dueDate = $deliveryDate->copy()->addDays($order->custom_due_days);
                $daysDifference = now()->diffInDays($dueDate, false);

                if ($daysDifference < 0) {
                    $overdue = abs($daysDifference);
                    $daysText = 'Overdue by ' . $overdue . ' days';
                    $maxOverdueDays = max($maxOverdueDays, $overdue);
                } elseif ($daysDifference > 0) {
                    $daysText = 'Due in ' . $daysDifference . ' days';
                } else {
                    $daysText = 'Today';
                }

            } elseif ($hasNewPaymentTerm) {

                $deliveryDateStart = $deliveryDate->copy()->startOfDay();
                $dueDay  = (int) $paymentTerm->days ?: 1;
                $dueDate = $deliveryDateStart->copy()->addMonthNoOverflow()->day($dueDay)->startOfDay();
                $today   = now()->startOfDay();

                if ($today->gt($dueDate)) {
                    $overdue = $today->diffInDays($dueDate);
                    $daysText = 'Overdue by ' . $overdue . ' days';
                    $maxOverdueDays = max($maxOverdueDays, $overdue);
                } elseif ($today->lt($dueDate)) {
                    $daysText = 'Due in ' . $today->diffInDays($dueDate) . ' days';
                } else {
                    $daysText = 'Today';
                }

            } else {

                $dueDate = $deliveryDate->copy()->addDays($dueDaysLimit);
                $daysDifference = now()->diffInDays($dueDate->copy()->addDay(), false);

                if ($daysDifference < 0) {
                    $overdue = abs($daysDifference);
                    $daysText = 'Overdue by ' . $overdue . ' days';
                    $maxOverdueDays = max($maxOverdueDays, $overdue);
                } elseif ($daysDifference > 0) {
                    $daysText = 'Due in ' . $daysDifference . ' days';
                } else {
                    $daysText = 'Today';
                }
            }

            $balance = floatval($order->balance_amount);
            $totalOutstanding += $balance;

            if (str_contains($daysText, 'Overdue')) {
                $totalOverdue += $balance;
            }

            $rows[] = [
                $sr,
                $order->invoice_id,
                $order->created_at->format('Y-m-d'),
                $deliveryDate->format('Y-m-d'),
                $dueDate->format('Y-m-d'),
                $daysText,
                number_format($balance, 2),
            ];
        }

        // ---- Totals ----
        $rows[] = ['', '', '', '', '', 'Total:', number_format($totalOutstanding, 2)];
        $rows[] = ['', '', '', '', '', 'Max Overdue:', $maxOverdueDays > 0 ? $maxOverdueDays . ' days' : '0'];
        $rows[] = ['', '', '', '', '', 'Total Overdue Amount:', number_format($totalOverdue, 2)];

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestCol = $sheet->getHighestColumn(); // e.g. 'G'

                // Bold metadata labels
                $sheet->getStyle('A1:A4')->getFont()->setBold(true);
                $sheet->getStyle('D1:D2')->getFont()->setBold(true);

                // --- Find the header row dynamically by content, not by count ---
                $headerRow = null;
                for ($row = 1; $row <= $highestRow; $row++) {
                    $cellValue = trim((string) $sheet->getCell('A' . $row)->getValue());
                    if ($cellValue === 'Sr.') {
                        $headerRow = $row;
                        break;
                    }
                }

                if ($headerRow) {
                    $range = "A{$headerRow}:G{$headerRow}";
                    $sheet->getStyle($range)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
                    $sheet->getStyle($range)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('333333');
                    $sheet->getStyle($range)->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // --- Style the last 3 rows (Total / Max Overdue / Total Overdue Amount) ---
                for ($i = 0; $i < 3; $i++) {
                    $row = $highestRow - $i;
                    $sheet->getStyle("F{$row}:G{$row}")->getFont()->setBold(true);
                }

                // Auto-size all used columns
                foreach (range('A', $highestCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
