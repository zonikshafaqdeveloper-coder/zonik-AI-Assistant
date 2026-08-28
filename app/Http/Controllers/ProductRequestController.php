<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductRequest;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Product;
use App\Models\CustomerPrice;
use Illuminate\Support\Facades\DB;
use App\Exports\CustomerPriceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerPriceBulkExport;
use App\Imports\CustomerPriceImport;

class ProductRequestController extends Controller
{

    public function index()
    {
        $requestedProducts = ProductRequest::with('user')->get();
        // dd($requestedProducts);

        return view('admin.requestedProduct.index', compact('requestedProducts'));
    }

//  public function customer_price()
//         {
//             $customers = CustomerPrice::select(
//                     'customer_prices.customer_id',
//                     DB::raw('MIN(customer_prices.id) as id')
//                 )
//                 ->with('customer:id,name,location,outlet_name')
//                 ->groupBy('customer_prices.customer_id')
//                 ->get();

//             return view('admin.customer_price.index', compact('customers'));
//         }

// public function customer_price()
// {
//     $customers = CustomerPrice::select(
//             'customer_prices.customer_id',
//             DB::raw('MIN(customer_prices.id) as id')
//         )
//         ->with('users:id,priority,outlet_name,location')

//         ->groupBy('customer_prices.customer_id')
//         ->get();

//         // dd($customers);

//     return view('admin.customer_price.index', compact('customers'));
// }

 public function customer_price()
{
    $outlets = CustomerPrice::with([
            'outlet:id,outlet_name,location',
            'customer:id,outlet_name'
        ])
        ->select('outlet_id', 'customer_id')
        ->distinct()
        ->get();

        // dd($outlets);

    return view('admin.customer_price.index', compact('outlets'));
}

public function bulkExport()
{
    return Excel::download(
        new CustomerPriceBulkExport(),
        'customer_price_bulk.xlsx'
    );
}

public function bulkImport(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,csv'
    ]);

    Excel::import(new CustomerPriceImport, $request->file('file'));

    return back()->with('success', 'Customer prices imported successfully');
}

public function downloadSample()
{
    return response()->download(
        public_path('samples/customer_price_minimal.xlsx')
    );
}



//   public function customer_price_create()
//     {
//         $customers = User::where('type', 'group')
//             ->select('id', 'name','outlet_name')
//             ->orderBy('outlet_name')
//             ->get();

//         $products = Product::select(
//             'id',
//             'product_name',
//             'cost_per_item'
//         )->orderBy('product_name')->get();

//         return view('admin.customer_price.create', compact('customers', 'products'));
//     }

    
//     public function customer_price_store(Request $request)
//     {
//         $request->validate([
//             'customer_id' => 'required|exists:users,id',
//             'prices' => 'required|array',
//         ]);

//         foreach ($request->prices as $productId => $price) {
//             if ($price === null || $price === '') {
//                 continue;
//             }

//             CustomerPrice::updateOrCreate(
//                 [
//                     'customer_id' => $request->customer_id,
//                     'product_id' => $productId,
//                 ],
//                 [
//                     'product_price' => $price,
//                 ]
//             );
//         }

//         return redirect()
//             ->route('admin.customer.price')
//             ->with('success', 'Customer prices saved successfully.');
//     }

  public function customer_price_create()
    {
        $outlets = User::where('type', 'outlet')
            ->with('parentCustomer:id,outlet_name')  
            ->select('id', 'outlet_name', 'priority')
            ->orderBy('outlet_name')
            ->get();
            

$products = Product::where('status', 'active')
                    ->select('id', 'product_name', 'cost_per_item')
                    ->orderBy('product_name')
                    ->get();

        return view('admin.customer_price.create', compact('outlets', 'products'));
    }
    
     public function customer_price_store(Request $request)
{
    $request->validate([
        'outlet_id' => 'required|exists:users,id',
        'prices'    => 'required|array',
    ]);

    
    $outlet = User::where('id', $request->outlet_id)
        ->where('type', 'outlet')
        ->firstOrFail();

    
    $customerId = $outlet->priority;

    
    if (!$customerId) {
        return back()->with('error', 'Selected outlet is not linked to any customer group.');
    }

    foreach ($request->prices as $productId => $price) {

        if ($price === null || $price === '') {
            continue;
        }

        CustomerPrice::updateOrCreate(
            [
                'customer_id' => $customerId,
                'outlet_id'   => $outlet->id,
                'product_id'  => $productId,
            ],
            [
                'product_price' => $price,
            ]
        );
    }

    return redirect()
        ->route('admin.customer.price')
        ->with('success', 'Customer prices saved successfully.');
}

    

    public function customer_price_delete($customerId)
{
    CustomerPrice::where('outlet_id', $customerId)->delete();

    return redirect()
        ->route('admin.customer.price')
        ->with('success', 'Customer pricing deleted successfully.');
}

public function customer_price_export($customerId)
{
    return Excel::download(
        new CustomerPriceExport($customerId),
        'price-list-customer-' . $customerId . '.xlsx'
    );
}

  public function getCustomerPrices($customerId)
        {
            $prices = DB::table('customer_prices')
                ->where('customer_id', $customerId)
                ->pluck('product_price', 'product_id');

            return response()->json($prices);
        }
        
         public function getCustomerPriceLocks($customerId)
{
   
     $lockedProducts = DB::table('enquiries')
        ->where('user_id', $customerId)
        ->where('status', 'accept')
        ->select('product_id', 'offer_price')
        ->get()
        ->keyBy('product_id');

    
    $prices = CustomerPrice::where('customer_id', $customerId)
        ->pluck('product_price', 'product_id');

    return response()->json([
        'lockedProducts' => $lockedProducts,
        'prices' => $prices
    ]);
}

//      public function customer_price_edit($customerId)
// {
//     $customer = User::where('id', $customerId)
//         ->where('type', 'group')
//         ->firstOrFail();

//     $products = Product::select(
//         'id',
//         'product_name',
//         'cost_per_item'
//     )->orderBy('product_name')->get();

//     $customerPrices = CustomerPrice::where('customer_id', $customerId)
//         ->pluck('product_price', 'product_id')
//         ->toArray();

  
//     $lockedProducts = \DB::table('enquiries')
//         ->where('user_id', $customerId)
//         ->where('status', 'accept')
//         ->select('product_id', 'offer_price')
//         ->get()
//         ->keyBy('product_id')
//         ->toArray();

//     return view(
//         'admin.customer_price.edit',
//         compact('customer', 'products', 'customerPrices', 'lockedProducts')
//     );
// }

//     public function customer_price_update(Request $request, $customerId)
// {
//     $request->validate([
//         'prices' => 'required|array',
//     ]);

//     foreach ($request->prices as $productId => $price) {

//         if ($price === null || $price === '') {
//             // Remove price if cleared
//             CustomerPrice::where('customer_id', $customerId)
//                 ->where('product_id', $productId)
//                 ->delete();
//             continue;
//         }

//         CustomerPrice::updateOrCreate(
//             [
//                 'customer_id' => $customerId,
//                 'product_id'  => $productId,
//             ],
//             [
//                 'product_price' => $price,
//             ]
//         );
//     }

//     return redirect()
//         ->route('admin.customer.price')
//         ->with('success', 'Customer pricing updated successfully.');
// }


public function customer_price_edit($outletId)
{
    
    $outlet = User::where('id', $outletId)
        ->where('type', 'outlet')
        ->firstOrFail();

   
    $customerId = $outlet->priority;

    $products = Product::where('status', 'active')
    ->select('id', 'product_name', 'cost_per_item')
    ->orderBy('product_name')
    ->get();

   
    $customerPrices = CustomerPrice::where('outlet_id', $outletId)
        ->pluck('product_price', 'product_id')
        ->toArray();

  
    $lockedProducts = DB::table('enquiries')
        ->where('user_id', $customerId)
        ->where('status', 'accept')
        ->select('product_id', 'offer_price')
        ->get()
        ->keyBy('product_id')
        ->toArray();

    return view(
        'admin.customer_price.edit',
        compact('outlet', 'products', 'customerPrices', 'lockedProducts')
    );
}

public function customer_price_update(Request $request, $outletId)
{
    $request->validate([
        'prices' => 'required|array',
    ]);

    $outlet = User::where('id', $outletId)
        ->where('type', 'outlet')
        ->firstOrFail();

    $customerId = $outlet->priority;

    foreach ($request->prices as $productId => $price) {

        if ($price === null || $price === '') {

            CustomerPrice::where('customer_id', $customerId)
                ->where('outlet_id', $outletId)
                ->where('product_id', $productId)
                ->delete();

            continue;
        }

        CustomerPrice::updateOrCreate(
            [
                'customer_id' => $customerId,
                'outlet_id'   => $outletId,
                'product_id'  => $productId,
            ],
            [
                'product_price' => $price,
            ]
        );
    }

    return redirect()
        ->route('admin.customer.price')
        ->with('success', 'Outlet pricing updated successfully.');
}



    public function statusUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,decline',
        ]);

        $requestedProducts = ProductRequest::findOrFail($id);
        $requestedProducts->status = $request->input('status');
        $requestedProducts->save();

        return Redirect::back();
    }

    public function store(Request $request)
    {
        // dd( $request);
        $users = auth()->user();
       $Userid = $users->id;
        $validatedData = $request->validate([
            'product_name' => 'required',
            'product_details' => 'nullable',
        ]);
        $validatedData['user_id'] = $Userid;

        // dd($validatedData);
        ProductRequest::create($validatedData);

      return redirect()->route('homepage')->with('success', 'Product request submitted successfully.');
    }
}
