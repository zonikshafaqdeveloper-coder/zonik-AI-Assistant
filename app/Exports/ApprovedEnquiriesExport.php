<?php

namespace App\Exports;

use App\Models\Enquiry;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ApprovedEnquiriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Get all unique user IDs from approved enquiries
        $userIds = Enquiry::with('product', 'user')
            ->where('status', 'accept')
            ->orderBy('id', 'desc')
            ->pluck('user_id')
            ->unique();

        $exportData = collect();
        $serialNo = 1;

        foreach ($userIds as $userId) {
            // Get latest enquiry for each user
            $enquiry = Enquiry::with('product', 'user')
                ->where('status', 'accept')
                ->where('user_id', $userId)
                ->orderBy('id', 'desc')
                ->first();

            $enquiryCount = Enquiry::where('status', 'accept')
                ->where('user_id', $userId)
                ->count();

            // Get all products related to this enquiry
            $products = Enquiry::where('status', 'accept')
                ->where('user_id', $userId)
                ->with('product')
                ->get()
                ->pluck('product.product_name')
                ->toArray();

            if (count($products) > 0) {
                // Add first product with full row details
                $exportData->push([
                    'Sr. No' => $serialNo,
                    'Outlet Name' => optional($enquiry->user)->outlet_name ?? 'N/A',
                    'Customer Name' => optional($enquiry->user)->name ?? 'N/A',
                    'Contact Number' => optional($enquiry->user)->mobile_number ?? 'N/A',
                    'Location' => optional($enquiry->user)->location ?? 'N/A',
                    'No of Items' => $enquiryCount,
                    'Products' => array_shift($products), // Take first product
                ]);

                // Add remaining products in new rows (without duplicating other details)
                foreach ($products as $product) {
                    $exportData->push([
                        'Sr. No' => '',
                        'Outlet Name' => '',
                        'Customer Name' => '',
                        'Contact Number' => '',
                        'Location' => '',
                        'No of Items' => '',
                        'Products' => $product,
                    ]);
                }

                $serialNo++; // Increment Sr. No only after all products of an enquiry
            }
        }

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Sr. No',
            'Outlet Name',
            'Customer Name',
            'Contact Number',
            'Location',
            'No of Items',
            'Products'
        ];
    }
}
