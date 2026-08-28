<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorPriceList;
use App\Models\VendorBill;
use App\Models\CustomerPrice;
use App\Models\VendorPayment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VendorPriceExport;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Exports\VendorBulkExport;
use App\Imports\VendorPriceImport;
use Illuminate\Support\Facades\Response;

class VendorPriceListController extends Controller
{
    public function index()
{
    $vendors = VendorPriceList::with('vendor')
        ->select('vendor_id')
        ->groupBy('vendor_id')
        ->latest()
        ->get();

    return view('admin.vendor_price.index', compact('vendors'));
}

    public function create()
    {
        $vendors = Vendor::select('id', 'name')
            ->orderBy('name')
            ->get();

        
    $products = Product::where('status', 'active')
    ->select('id', 'product_name', 'cost_per_item')
    ->orderBy('product_name')
    ->get();

        return view('admin.vendor_price.create', compact('vendors', 'products'));
    }

        public function getVendorPriceLocks($vendorId)
    {
        $prices = VendorPriceList::where('vendor_id', $vendorId)
            ->pluck('vendor_price', 'product_id');

        return response()->json([
            'prices' => $prices
        ]);
    }


// comment on 23-03-26

//   private function syncLowestVendorCostToProducts()
// {
//     // Log::info('Vendor cost sync started');

//     $vendorPrices = VendorPriceList::select(
//             'product_id',
//             'vendor_id',
//             'vendor_price'
//         )
//         ->whereNotNull('vendor_price')
//         ->where('vendor_price', '>', 0)
//         ->orderBy('vendor_price', 'asc')
//         ->get()
//         ->groupBy('product_id')
//         ->map(fn ($rows) => $rows->first());

//     if ($vendorPrices->isEmpty()) {
//         Log::warning('No vendor prices found');
//         return;
//     }

//     $vendorIds = $vendorPrices->pluck('vendor_id')->unique();

//     $vendors = Vendor::whereIn('id', $vendorIds)
//         ->pluck('name', 'id');

//     $today = Carbon::now()->format('Y-m-d');

//     Product::whereIn('id', $vendorPrices->keys())
//         ->chunkById(200, function ($products) use ($vendorPrices, $vendors, $today) {

//             foreach ($products as $product) {

//                 $row = $vendorPrices[$product->id];

//                 $newCost = $row->vendor_price;
//                 $vendorName = $vendors[$row->vendor_id] ?? null;

//                 $updateData = [
//                     'cost_per_item'     => $newCost,
//                     'supplier_traced'  => $vendorName,
//                     'vendor_id'  => $row->vendor_id,
//                     'last_update_price'=> $today,
//                 ];

//                 $product->update($updateData);

//                 // Log::info('Product updated', [
//                 //     'product_id' => $product->id,
//                 //     'cost' => $newCost,
//                 //     'vendor' => $vendorName,
//                 //     'date' => $today
//                 // ]);
//             }
//         });

//     // Log::info('Vendor cost sync completed');
// }
// cpmment on 03-04-26
// private function syncCustomerPrices($productId, $difference)
// {
//     if ($difference == 0) return;

//     CustomerPrice::where('product_id', $productId)
//         ->chunkById(200, function ($rows) use ($difference) {

//             foreach ($rows as $row) {

//                 $newPrice = max(0, $row->product_price + $difference);

//                 $row->update([
//                     'product_price' => $newPrice
//                 ]);
//             }
//         });
// }

// private function syncLowestVendorCostToProducts()
// {
//     Log::info('Vendor cost sync started');

//     $vendorPrices = VendorPriceList::select(
//             'product_id',
//             'vendor_id',
//             'vendor_price'
//         )
//         ->whereNotNull('vendor_price')
//         ->where('vendor_price', '>', 0)
//         ->orderBy('vendor_price', 'asc')
//         ->get()
//         ->groupBy('product_id')
//         ->map(fn ($rows) => $rows->first());

//     if ($vendorPrices->isEmpty()) {
//         Log::warning('No vendor prices found');
//         return;
//     }

//     $vendorIds = $vendorPrices->pluck('vendor_id')->unique();

//     $vendors = Vendor::whereIn('id', $vendorIds)
//         ->pluck('name', 'id'); 

//     $today = Carbon::now()->format('Y-m-d');

//     Product::whereIn('id', $vendorPrices->keys())
//         ->chunkById(200, function ($products) use ($vendorPrices, $vendors, $today) {

//             foreach ($products as $product) {

//                 $row = $vendorPrices[$product->id];

//                 $newCost = $row->vendor_price;
//                 $oldCost = $product->cost_per_item ?? 0;

                
//                 if ($newCost == $oldCost) {
//                     continue;
//                 }

//                 $difference = $newCost - $oldCost;

//                 $vendorName = $vendors[$row->vendor_id] ?? null;

               
//                 $looseMargin  = $product->sale_price_loose_pcs - $oldCost;
//                 $cartonMargin = $product->sale_price_carton - $oldCost;

//                 $newLoosePrice  = max(0, $newCost + $looseMargin);
//                 $newCartonPrice = max(0, $newCost + $cartonMargin);

//                 $product->update([
//                     'cost_per_item'       => $newCost,
//                     'supplier_traced'     => $vendorName,
//                     'vendor_id'           => $row->vendor_id,
//                     'last_update_price'   => $today,

                    
//                     'sale_price_loose_pcs' => $newLoosePrice,
//                     'sale_price_carton'    => $newCartonPrice,

                  
//                     'sale_price_loose_pcs_old' => $product->sale_price_loose_pcs,
//                     'sale_price_carton_old'    => $product->sale_price_carton,
//                 ]);

              
//                 $this->syncCustomerPrices($product->id, $difference);

//                 Log::info('Product price synced', [
//                     'product_id' => $product->id,
//                     'old_cost' => $oldCost,
//                     'new_cost' => $newCost,
//                     'difference' => $difference
//                 ]);
//             }
//         });

//     Log::info('Vendor cost sync completed');
// }


private function syncCustomerPrices($productId, $difference)
{
    if ($difference <= 0) {
        return;
    }

    CustomerPrice::where('product_id', $productId)
        ->chunkById(200, function ($rows) use ($difference) {

            foreach ($rows as $row) {

                $newPrice = max(0, $row->product_price + $difference);

                $row->update([
                    'product_price' => $newPrice
                ]);
            }
        });
}

private function syncLowestVendorCostToProducts()
{
    Log::info('Vendor cost sync started');

    $vendorPrices = VendorPriceList::select(
            'product_id',
            'vendor_id',
            'vendor_price'
        )
        ->whereNotNull('vendor_price')
        ->where('vendor_price', '>', 0)
        ->orderBy('vendor_price', 'asc')
        ->get()
        ->groupBy('product_id')
        ->map(fn ($rows) => $rows->first());

    if ($vendorPrices->isEmpty()) {
        Log::warning('No vendor prices found');
        return;
    }

    $vendorIds = $vendorPrices->pluck('vendor_id')->unique();

    $vendors = Vendor::whereIn('id', $vendorIds)
        ->pluck('name', 'id'); 

    $today = Carbon::now()->format('Y-m-d');

    Product::whereIn('id', $vendorPrices->keys())
        ->chunkById(200, function ($products) use ($vendorPrices, $vendors, $today) {

            // foreach ($products as $product) {

            //     $row = $vendorPrices[$product->id];

            //     $newCost = (float) $row->vendor_price;
            //     $oldCost = (float) ($product->cost_per_item ?? 0);

              
            //     if ($newCost == $oldCost) {
            //         continue;
            //     }

            //     $difference = $newCost - $oldCost;
            //     $vendorName = $vendors[$row->vendor_id] ?? null;

            //     $looseMargin  = $product->sale_price_loose_pcs - $oldCost;
            //     $cartonMargin = $product->sale_price_carton - $oldCost;

            //     $newLoosePrice  = max(0, $newCost + $looseMargin);
            //     $newCartonPrice = max(0, $newCost + $cartonMargin);

            //     $product->update([
            //         'cost_per_item'       => $newCost,
            //         'supplier_traced'     => $vendorName,
            //         'vendor_id'           => $row->vendor_id,
            //         'last_update_price'   => $today,

            //         'sale_price_loose_pcs' => $newLoosePrice,
            //         'sale_price_carton'    => $newCartonPrice,

            //         'sale_price_loose_pcs_old' => $product->sale_price_loose_pcs,
            //         'sale_price_carton_old'    => $product->sale_price_carton,
            //     ]);

                // if ($difference > 0) {
                //     $this->syncCustomerPrices($product->id, $difference);
                // }

                // Log::info('Product price synced', [
                //     'product_id' => $product->id,
                //     'old_cost'   => $oldCost,
                //     'new_cost'   => $newCost,
                //     'difference' => $difference,
                //     'customer_sync' => $difference > 0 ? 'YES' : 'NO'
                // ]);
            // }
        });

    Log::info('Vendor cost sync completed');
}



   public function store(Request $request)
{
    // Log::info('Vendor price store called');

    $request->validate([
        'vendor_id' => 'required|exists:vendors,id',
        'prices'    => 'required|array',
    ]);

    foreach ($request->prices as $productId => $price) {

        if ($price === null || $price === '') {
            continue;
        }

        VendorPriceList::updateOrCreate(
            [
                'vendor_id'  => $request->vendor_id,
                'product_id' => $productId,
            ],
            [
                'vendor_price' => $price,
            ]
        );

        // Log::info('Vendor price saved', [
        //     'product_id' => $productId,
        //     'price' => $price
        // ]);
    }

  
    $this->syncLowestVendorCostToProducts();

    return redirect()
        ->route('vendor.price.index')
        ->with('success', 'Vendor prices saved successfully.');
}

//   public function store(Request $request)
// {
//     $request->validate([
//         'vendor_id' => 'required|exists:vendors,id',
//         'prices'    => 'required|array',
//     ]);

//     foreach ($request->prices as $productId => $price) {

//         if ($price === null || $price === '') {
//             continue;
//         }

//         VendorPriceList::updateOrCreate(
//             [
//                 'vendor_id'  => $request->vendor_id,
//                 'product_id' => $productId,
//             ],
//             [
//                 'vendor_price' => $price,
//             ]
//         );
//     }

//     return redirect()
//         ->route('vendor.price.index')
//         ->with('success', 'Vendor prices saved successfully.');
// }

        public function edit($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);

    $products = Product::where('status', 'active')
    ->select('id', 'product_name', 'cost_per_item')
    ->orderBy('product_name')
    ->get();

        $vendorPrices = VendorPriceList::where('vendor_id', $vendorId)
            ->pluck('vendor_price', 'product_id')
            ->toArray();

        return view(
            'admin.vendor_price.edit',
            compact('vendor', 'products', 'vendorPrices')
        );
    }

    public function update(Request $request, $vendorId)
    {
        $request->validate([
            'prices' => 'required|array',
        ]);

        foreach ($request->prices as $productId => $price) {

            if ($price === null || $price === '') {
                // Remove price if cleared
                VendorPriceList::where('vendor_id', $vendorId)
                    ->where('product_id', $productId)
                    ->delete();
                continue;
            }

            VendorPriceList::updateOrCreate(
                [
                    'vendor_id'  => $vendorId,
                    'product_id' => $productId,
                ],
                [
                    'vendor_price' => $price,
                ]
            );
        }
        
         $this->syncLowestVendorCostToProducts();

        return redirect()
            ->route('vendor.price.index')
            ->with('success', 'Vendor pricing updated successfully.');
    }

        public function destroy($vendorId)
        {
            VendorPriceList::where('vendor_id', $vendorId)->delete();

            return redirect()
                ->route('vendor.price.index')
                ->with('success', 'Vendor price list deleted successfully.');
        }

        public function vendor_price_export($vendorId)
    {
        return Excel::download(
            new VendorPriceExport($vendorId),
            'price-list-vendor-' . $vendorId . '.xlsx'
        );
    }
    
    
    public function vendor_payments_index()
    {
        $bills = VendorBill::with([
                'vendor',
                'payments',
                'stockReceiving'
            ])
              ->whereHas('stockReceiving', function ($q) {
            $q->whereIn('status', ['approved', 'approved_with_changes']);
        })
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.vendor-payments.index', compact('bills'));
    }

    public function vendor_payments_create(VendorBill $bill)
    {
        if (!in_array($bill->stockReceiving->status, ['approved', 'approved_with_changes'])) {
    abort(403, 'Stock not approved yet');
}

        $paid = $bill->payments()->sum('amount');

        return view('admin.vendor-payments.create', compact('bill', 'paid'));
    }

        public function vendor_payments_store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'vendor_bill_id'   => 'required|exists:vendor_bills,id',
            'payment_date'     => 'required|date',
            'amount'           => 'required|numeric|min:0.01',
            'payment_mode'     => 'required|string',
            'reference_no'     => 'nullable|string',
            'payment_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'remarks'          => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            $bill = VendorBill::lockForUpdate()->findOrFail($request->vendor_bill_id);

            
           if (!in_array($bill->stockReceiving->status, ['approved', 'approved_with_changes'])) {
                abort(403, 'Payment allowed only after stock approval');
            }
            
            $totalPaid = $bill->payments()->sum('amount');
            $pending   = $bill->grand_total - $totalPaid;

            if ($request->amount > $pending) {
                abort(422, 'Payment amount exceeds pending balance');
            }



            $docName = null;
            if ($request->hasFile('payment_document')) {
                $file = $request->file('payment_document');
                $docName = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/vendor_payments'), $docName);
            }

            
            VendorPayment::create([
                'vendor_bill_id'  => $bill->id,
                'vendor_id'       => $bill->vendor_id,
                'payment_date'    => $request->payment_date,
                'amount'          => $request->amount,
                'payment_mode'    => $request->payment_mode,
                'reference_no'    => $request->reference_no,
                'payment_document'=> $docName,
                'remarks'         => $request->remarks,
            ]);

            
            $newTotalPaid = $bill->payments()->sum('amount');

            
            if ($newTotalPaid >= $bill->grand_total) {
                $bill->update(['status' => 'paid']);
            } elseif ($newTotalPaid > 0) {
                $bill->update(['status' => 'partial']);
            } else {
                $bill->update(['status' => 'unpaid']);
            }
        });

        return redirect()
            ->route('admin.vendor-payments.index')
            ->with('success', 'Payment recorded successfully');
    }

    public function vendor_payments_show(VendorBill $bill)
{
    
    if (!in_array($bill->stockReceiving->status, ['approved', 'approved_with_changes'])) {
        abort(403, 'Payments can be viewed only after stock approval');
    }

    $bill->load([
        'vendor',
        'payments'
    ]);

    $totalPaid = $bill->payments->sum('amount');
    $pending   = $bill->grand_total - $totalPaid;

    return view('admin.vendor-payments.show', compact(
        'bill',
        'totalPaid',
        'pending'
    ));
}


    



    public function vendor_payments_byBill(VendorBill $vendorBill)
    {
        return response()->json(
            $vendorBill->payments()
                ->orderBy('payment_date', 'asc')
                ->get([
                    'payment_date',
                    'amount',
                    'payment_mode',
                    'payment_document'
                ])
                ->map(function ($p) {
                    return [
                        'payment_date'     => \Carbon\Carbon::parse($p->payment_date)->format('d-m-Y'),
                        'amount'           => number_format($p->amount, 2),
                        'payment_mode'     => $p->payment_mode,
                        'payment_document' => $p->payment_document,
                    ];
                })
        );
    }
    
       public function bulkExport()
{
    return Excel::download(
        new VendorBulkExport(),
        'vendor_price_list_' . now()->format('Y_m_d_H_i_s') . '.xlsx'
    );
}

public function bulkImport(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv',
    ]);

    Excel::import(new VendorPriceImport, $request->file('file'));

   
    $this->syncLowestVendorCostToProducts();

    return back()->with('success', 'Vendor prices imported successfully.');
}

public function downloadSample()
{
    $filePath = public_path('samples/vendor_import_sample.xlsx');

    if (!file_exists($filePath)) {
        abort(404, 'Sample file not found.');
    }

    return response()->download($filePath, 'vendor_import_sample.xlsx');
}

}
