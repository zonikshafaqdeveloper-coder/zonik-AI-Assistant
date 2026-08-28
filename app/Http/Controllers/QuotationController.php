<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\LeadCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
   public function index()
{
    $quotations = Quotation::with(['leadCustomer', 'items'])->latest()->get();
    return view('admin.quotation.index', compact('quotations'));
}

    public function create()
    {
        $leadCustomers = LeadCustomer::orderBy('outlet_name')->get();
        return view('admin.quotation.create', compact('leadCustomers'));
    }

    
   
    public function getLeadCustomerDetails($id)
    {
        $lead = LeadCustomer::findOrFail($id);

        return response()->json([
            'customer_name' => $lead->customer_name,
            'outlet_name'   => $lead->outlet_name,
            'mobile_number' => $lead->mobile_number,
            'address'       => $lead->address,
            'payment_term'  => $lead->payment_term,
        ]);
    }

    
public function getProductsForQuotation()
{
    $products = DB::table('products')
        ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
        ->select(
            'products.id',
            'products.product_name',
            'products.brands as brand',
            'categories.category_name as category',
            'products.cost_per_item',
            'products.sale_price_loose_pcs'
        )
        ->get();

    
    $lastGrnDates = DB::table('stock_receiving_items')
        ->join('stock_receivings', 'stock_receivings.id', '=', 'stock_receiving_items.stock_receiving_id')
        ->select('stock_receiving_items.product_id', DB::raw('MAX(stock_receivings.receipt_date) as last_grn_date'))
        ->groupBy('stock_receiving_items.product_id')
        ->pluck('last_grn_date', 'product_id');

    $products = $products->map(function ($p) use ($lastGrnDates) {
        $p->last_grn_date = $lastGrnDates[$p->id] ?? null;
        return $p;
    });

    return response()->json($products);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'lead_customer_id'              => 'required|exists:lead_customers,id',
        'quotation_date'                => 'required|date',
        'items'                         => 'required|array|min:1',
        'items.*.product_id'            => 'required|exists:products,id',
        'items.*.brand'                 => 'nullable|string',
        'items.*.category'              => 'nullable|string',
        'items.*.cost_per_item'         => 'required|numeric|min:0',
        'items.*.sale_price_basic'      => 'required|numeric|min:0',
        'items.*.profit_margin'         => 'nullable|numeric',
        'items.*.customer_price'        => 'required|numeric|min:0',
        'items.*.total_saving_percent'  => 'nullable|numeric',
        'items.*.last_grn_date'         => 'nullable|date',
    ]);

    DB::beginTransaction();

    try {
        $quotationNumber = 'QT-' . date('Ymd') . '-' . str_pad((Quotation::max('id') + 1), 4, '0', STR_PAD_LEFT);

        $quotation = Quotation::create([
            'quotation_number' => $quotationNumber,
            'lead_customer_id' => $validated['lead_customer_id'],
            'quotation_date'   => $validated['quotation_date'],
            'created_by'       => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            QuotationItem::create([
                'quotation_id'          => $quotation->id,
                'product_id'            => $item['product_id'],
                'brand'                 => $item['brand'] ?? null,
                'category'              => $item['category'] ?? null,
                'cost_per_item'         => $item['cost_per_item'],
                'sale_price_basic'      => $item['sale_price_basic'],
                'profit_margin'         => $item['profit_margin'] ?? 0,
                'customer_price'        => $item['customer_price'],
                'total_saving_percent'  => $item['total_saving_percent'] ?? 0,
                'last_grn_date'         => $item['last_grn_date'] ?? null,
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Quotation created successfully.',
            'redirect_url' => route('quotations.index'),
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}

   public function edit($id)
{
    $quotation = Quotation::with(['items.product', 'leadCustomer'])->findOrFail($id);
    $leadCustomers = LeadCustomer::orderBy('outlet_name')->get();
    return view('admin.quotation.edit', compact('quotation', 'leadCustomers'));
}

public function update(Request $request, $id)
{
    $quotation = Quotation::findOrFail($id);

    $validated = $request->validate([
        'lead_customer_id'              => 'required|exists:lead_customers,id',
        'quotation_date'                => 'required|date',
        'items'                         => 'required|array|min:1',
        'items.*.product_id'            => 'required|exists:products,id',
        'items.*.brand'                 => 'nullable|string',
        'items.*.category'              => 'nullable|string',
        'items.*.cost_per_item'         => 'required|numeric|min:0',
        'items.*.sale_price_basic'      => 'required|numeric|min:0',
        'items.*.profit_margin'         => 'nullable|numeric',
        'items.*.customer_price'        => 'required|numeric|min:0',
        'items.*.total_saving_percent'  => 'nullable|numeric',
        'items.*.last_grn_date'         => 'nullable|date',
    ]);

    DB::beginTransaction();

    try {
        $quotation->update([
            'lead_customer_id' => $validated['lead_customer_id'],
            'quotation_date'   => $validated['quotation_date'],
        ]);

        $quotation->items()->delete();

        foreach ($validated['items'] as $item) {
            QuotationItem::create([
                'quotation_id'          => $quotation->id,
                'product_id'            => $item['product_id'],
                'brand'                 => $item['brand'] ?? null,
                'category'              => $item['category'] ?? null,
                'cost_per_item'         => $item['cost_per_item'],
                'sale_price_basic'      => $item['sale_price_basic'],
                'profit_margin'         => $item['profit_margin'] ?? 0,
                'customer_price'        => $item['customer_price'],
                'total_saving_percent'  => $item['total_saving_percent'] ?? 0,
                'last_grn_date'         => $item['last_grn_date'] ?? null,
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Quotation updated successfully.',
            'redirect_url' => route('quotations.index'),
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}

    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quotation deleted successfully.',
        ]);
    }

    public function show($id)
    {
        $quotation = Quotation::with(['items.product', 'leadCustomer'])->findOrFail($id);
        return view('admin.quotation.show', compact('quotation'));
    }

    public function invoice($id)
    {
        $quotation = Quotation::with(['items.product', 'leadCustomer'])->findOrFail($id);
        return view('admin.quotation.invoice', compact('quotation'));
    }
    
    
      public function exportExcel($id)
{
    $quotation = Quotation::with(['leadCustomer', 'items.product'])->findOrFail($id);

    $filename = 'quotation_' . $quotation->quotation_number . '.xls';

    $headers = [
        "Content-Type" => "application/vnd.ms-excel",
        "Content-Disposition" => "attachment; filename=\"$filename\"",
    ];

    return response()->view('admin.quotation.excel', compact('quotation'), 200, $headers);
}
    
    
}