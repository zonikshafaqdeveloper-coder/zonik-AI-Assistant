<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OutletCustomersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::with(['outlet', 'outletPaymentTerm', 'dairyPaymentTerm'])
            ->where('type', 'outlet')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Outlet User',
            'Outlet Name',
            'Outlet Number',
            'Outlet Company Name',
            'Email',
            'Credit Status',
            'Credit Limit',
            'Due Max Days',
            'Status',
            'Registered At',
            'Payment Term From Range',
            'Payment Term To Range',
            'Payment Term Days',
            'Payment Term Active',
            'Dairy Due Limit Days',
            'Dairy Payment Term Active',
        ];
    }

    public function map($customer): array
    {
        $status = match ($customer->verified_status) {
            'verified'   => 'Active',
            'unverified' => 'Inactive',
            default      => 'Not Set',
        };

        return [
            $customer->name,
            $customer->outlet_name,
            $customer->mobile_number,
            $customer->outlet?->outlet_name ?? 'N/A',
            $customer->email,
            $customer->credit_status,
            $customer->credit_limit,
            $customer->due_days_limit,
            $status,
            optional($customer->created_at)->format('Y-m-d'),
            $customer->outletPaymentTerm->from_range ?? '',
            $customer->outletPaymentTerm->to_range ?? '',
            $customer->outletPaymentTerm->days ?? '',
            ($customer->outletPaymentTerm->is_active ?? 0) ? 'Yes' : 'No',
            $customer->dairyPaymentTerm->due_limit_days ?? '',
            ($customer->dairyPaymentTerm->is_active ?? 0) ? 'Yes' : 'No',
        ];
    }
}