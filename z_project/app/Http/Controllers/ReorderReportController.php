<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ProductLssSetting;
use App\Models\ProductReorderSetting;

class ReorderReportController extends Controller
{
    
    public function saveLss(Request $request)
{
    $request->validate([
        'product_id' => 'required',
        'vendor_id' => 'required',
        'lss_percent' => 'required|numeric'
    ]);

    ProductLssSetting::updateOrCreate(
        ['product_id' => $request->product_id],
        [
            'vendor_id' => $request->vendor_id,
            'lss_percent' => $request->lss_percent
        ]
    );

    return response()->json([
        'status' => true,
        'message' => 'LSS saved successfully'
    ]);
}

public function saveReorderSetting(Request $request)
{
    $request->validate([
        'product_id' => 'required',
        'spp' => 'required|numeric',
        'oss_percent' => 'required|numeric'
    ]);

    ProductReorderSetting::updateOrCreate(
        ['product_id' => $request->product_id],
        [
            'spp' => $request->spp,
            'oss_percent' => $request->oss_percent
        ]
    );

    return response()->json([
        'status' => true,
        'message' => 'Saved successfully'
    ]);
}




// public function index()
// {
//     $currentDate  = Carbon::now();
//     $startDate   = Carbon::now()->subDays(30);

//     // $startOfMonth = Carbon::now()->startOfMonth();

//     /*
//     |-----------------------------------------
//     | Total Days of Consumption (TDC)
//     |-----------------------------------------
//     */

//     // $tdc = max(1, $startOfMonth->diffInDays($currentDate));
//     $tdc = 30;

//     /*
//     |-----------------------------------------
//     | Product Consumption (CQ)
//     |-----------------------------------------
//     */

//     $consumption = DB::table('order_items')
//         ->join('orders', 'orders.id', '=', 'order_items.order_id')
//         ->select(
//             'order_items.product_id',
//             DB::raw("SUM(order_items.quantity) as cq")
//         )
//         ->whereBetween('orders.created_at', [$startDate, $currentDate])
//         ->groupBy('order_items.product_id')
//         ->pluck('cq', 'product_id');

//     /*
//     |-----------------------------------------
//     | Product Master Data
//     |-----------------------------------------
//     */

//     $products = DB::table('products')
//         ->leftJoin('product_stocks', 'products.id', '=', 'product_stocks.product_id')
//         ->leftJoin('vendors', 'vendors.id', '=', 'products.vendor_id')
//         ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
//         ->select(
//             'products.id',
//             'products.vendor_id',
//             'products.unique_reference_id as item_code',
//             'products.product_name',
//             'products.unit',
//             'products.carton_size',
//             'products.brands as brand',
//             'categories.category_name as category',
//             'vendors.name as supplier',
//             'vendors.lead_time',
//             DB::raw("COALESCE(product_stocks.total_stock,0) as stock")
//         )
//         ->get();

//     $report = [];

//     foreach ($products as $p) {

//         /*
//         |-----------------------------------------
//         | Consumption Quantity
//         |-----------------------------------------
//         */

//         $cq = (float) ($consumption[$p->id] ?? 0);

//         /*
//         |-----------------------------------------
//         | Average Daily Consumption (ADC)
//         |-----------------------------------------
//         */

//         $adc = ($tdc > 0) ? $cq / $tdc : 0;

//         /*
//         |-----------------------------------------
//         | Supplier Lead Time (SLT)
//         |-----------------------------------------
//         */

//         $slt = (int) ($p->lead_time ?? 0);

//         /*
//         |-----------------------------------------
//         | Lead Safety Stock %
//         |-----------------------------------------
//         */

//         $lss_percent = 20;

//         /*
//         |-----------------------------------------
//         | Lead Safety Stock Qty
//         | (ADC × SLT) × LSS %
//         |-----------------------------------------
//         */

//         $lss_qty = ($adc * $slt) * ($lss_percent / 100);

//         /*
//         |-----------------------------------------
//         | Reorder Point
//         | (SLT × ADC) + LSS
//         |-----------------------------------------
//         */

//         $rop = ($slt * $adc) + $lss_qty;

//         /*
//         |-----------------------------------------
//         | Boxes Calculation
//         |-----------------------------------------
//         */

//         $rop_boxes = ($p->carton_size > 0)
//             ? ceil($rop / $p->carton_size)
//             : 0;

//         $stock = (float) $p->stock;

//         /*
//         |-----------------------------------------
//         | Stock Status
//         |-----------------------------------------
//         */

//         $status = 'OK';

//         if ($stock <= $rop) {

//             $status = 'REORDER';

//         } elseif ($stock > $rop && $stock <= ($rop * 1.10)) {

//             $status = 'WATCH';

//         }

//         /*
//         |-----------------------------------------
//         | Final Report Array
//         |-----------------------------------------
//         */

//         $report[] = [

//             'id' => $p->id,
//             'vendor_id' => $p->vendor_id,

//             'item_code' => $p->item_code,
//             'product'   => $p->product_name,
//             'unit'      => $p->unit,
//             'brand'     => $p->brand,
//             'category'  => $p->category,
//             'supplier'  => $p->supplier ?? 'N/A',

//             'carton_size' => $p->carton_size,

//             'tdc' => $tdc,
//             'cq'  => round($cq),

//             'slt' => $slt,

//             // display rounded but calculation used decimal
//             'adc' => round($adc,2),

//             'lss_percent' => $lss_percent,
//             'lss_qty'     => round($lss_qty),

//             'rop'   => round($rop),

//             'boxes' => $rop_boxes,

//             'scheme' => "BUY 6 BOX 1 FREE",

//             'stock' => $stock,

//             'status' => $status
//         ];
//     }

//     /*
//     |-----------------------------------------
//     | Red Alert Count
//     |-----------------------------------------
//     */

//     $redCount = collect($report)
//         ->where('status', 'REORDER')
//         ->count();

//     return view('admin.reports.reorder_report', compact('report','redCount'));
// }

// public function reorderQty()
// {

//     $today = Carbon::now();
//     $last7Days = Carbon::now()->subDays(7);

//     /*
//     |-----------------------------------------
//     | WEEKLY CONSUMPTION (WCQ)
//     |-----------------------------------------
//     */

//     $consumption = DB::table('order_items')
//         ->join('orders','orders.id','=','order_items.order_id')
//         ->select(
//             'order_items.product_id',
//             DB::raw("SUM(order_items.quantity) as wcq")
//         )
//         ->whereBetween('orders.created_at', [$last7Days, $today])
//         ->groupBy('order_items.product_id')
//         ->pluck('wcq','product_id');

//     /*
//     |-----------------------------------------
//     | PRODUCT DATA
//     |-----------------------------------------
//     */

//     $products = DB::table('products')
//         ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
//         ->leftJoin('vendors','vendors.id','=','products.vendor_id')
//         ->select(
//             'products.id',
//             'products.product_name',
//             'products.carton_size',
//             'products.vendor_id',
//             'vendors.name as supplier',
//             'vendors.moq_type as supplier_moq',
//             DB::raw("COALESCE(product_stocks.total_stock,0) as stock")
//         )
//         ->get();

//     $report = [];

//     foreach ($products as $p) {

//         $wcq = (float) ($consumption[$p->id] ?? 0);

//         /*
//         |-----------------------------------------
//         | DEFAULT VALUES
//         |-----------------------------------------
//         */

//         $spp = 2; // weeks
//         $oss_percent = 20;

//         /*
//         |-----------------------------------------
//         | ORDER SAFETY STOCK
//         |-----------------------------------------
//         */

//         $oss_qty = $wcq * ($oss_percent / 100);

//         /*
//         |-----------------------------------------
//         | REORDER QTY
//         |-----------------------------------------
//         */

//         $roq = ($wcq * $spp) + $oss_qty;

//         /*
//         |-----------------------------------------
//         | BOX CALCULATION
//         |-----------------------------------------
//         */

//         $boxes = 0;

//         if($p->carton_size > 0){
//             $boxes = ceil($roq / $p->carton_size);
//         }

//         $report[] = [

//             'id' => $p->id,
//             'product' => $p->product_name,
//             'supplier' => $p->supplier,
//             'wcq' => round($wcq),

//             'spp' => $spp,

//             'oss_percent' => $oss_percent,

//             'oss_qty' => round($oss_qty),

//             'roq' => round($roq),

//             'boxes' => $boxes,

//             'moq' => $p->supplier_moq
//         ];
//     }

//     return view('admin.reports.reorder_qty_report', compact('report'));
// }

// above both method comment on 10-04-26
// above both method comment on 16-07-26

// public function index()
// {
//     $currentDate = Carbon::now();
//     $startDate   = Carbon::now()->subDays(30);
    
//     $lssSettings = DB::table('product_lss_settings')
//     ->pluck('lss_percent', 'product_id');

//     $tdc = 30;

//     $consumption = DB::table('original_items')
//         ->join('orders', 'orders.id', '=', 'original_items.order_id')
//         ->join('delivery_management as dm', 'dm.order_id', '=', 'orders.id')
//         ->whereNotIn('dm.delivery_status', ['pending', 'cancelled'])
//         ->whereBetween('orders.created_at', [$startDate, $currentDate])
//         ->select(
//             'original_items.product_id',
//             DB::raw("SUM(original_items.quantity) as cq")
//         )
//         ->groupBy('original_items.product_id')
//         ->pluck('cq', 'product_id');
        

//     $products = DB::table('products')
//         ->leftJoin('product_stocks', 'products.id', '=', 'product_stocks.product_id')
//         ->leftJoin('vendors', 'vendors.id', '=', 'products.vendor_id')
//         ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
//         ->select(
//             'products.id',
//             'products.vendor_id',
//             'products.product_name',
//             'products.brands as brand',
//             'products.carton_size',
//             'products.cost_per_item',
//             'categories.category_name as category',
//             'vendors.name as vendor_name',
//             'vendors.lead_time',
//             DB::raw("COALESCE(product_stocks.total_stock,0) as stock")
//         )
//         ->get();

//     $report = [];

//     foreach ($products as $p) {

//         $cq = (float) ($consumption[$p->id] ?? 0);
//         // $adc = ($tdc > 0) ? round($cq / $tdc) : 0;
//         $adc = ($tdc > 0) ? ($cq / $tdc) : 0;

//         $slt = (int) ($p->lead_time ?? 0);

//         $rop_without = round($slt * $adc);

//         $lss_percent = $lssSettings[$p->id] ?? 20;

//         // $nos = ($lss_percent / 100) * $rop_without + $rop_without;
//         $nos = round(($lss_percent / 100) * $rop_without + $rop_without);

//         $boxes = ($p->carton_size > 0)
//             ? floor($nos / $p->carton_size)
//             : 0;

//         $stock = (float) $p->stock;

//         // STATUS
//       $watchLimit = $nos * 1.20;

//       // STATUS
// // STATUS
// if ($adc <= 0) {
//     $status = 'OK';
// } else {
//     $diff_percent = ($nos > 0)
//         ? (($stock - $nos) / $nos) * 100
//         : 0;

//     if ($diff_percent > 50) {
//         $status = 'OK';
//     } elseif ($diff_percent > 20) {
//         $status = 'CAREFUL';
//     } elseif ($diff_percent >= 0) {
//         $status = 'WATCH';
//     } elseif ($diff_percent >= -20) {
//         $status = 'REORDER';
//     } else {
//         $status = 'CRITICAL';
//     }
// }

//         $report[] = [

//             'id' => $p->id,
//             'vendor_id' => $p->vendor_id,

//             'product' => $p->product_name,
//             'brand' => $p->brand,
//             'category' => $p->category,
            

//             'vendor_name' => $p->vendor_name ?? 'N/A', 
//             'carton_size' => $p->carton_size,
//             'purchase_price' => $p->cost_per_item ?? 0,

//             // RAW (for JS)
//             'rop_without_raw' => $rop_without,
//             'nos_raw' => $nos,
//             'stock' => $stock,

//             // DISPLAY
//             'cq' => number_format($cq, 2),
//             // 'adc' => number_format($adc, 2),
//             'adc' => number_format($adc, 2),
//             'slt' => $slt,
//             'rop_without' => number_format($rop_without, 2),
//             'nos' => number_format($nos, 0),
//             'boxes' => $boxes,
//             'lss_percent' => $lss_percent,

//             'status' => $status
//         ];
//     }

//     return view('admin.reports.reorder_report', compact('report'));
// }


public function index()
{
    $currentDate = Carbon::now();
    $startDate   = Carbon::now()->subDays(30);

    $lssSettings = DB::table('product_lss_settings')->pluck('lss_percent', 'product_id');
    $tdc = 30;

    $consumption = DB::table('original_items')
        ->join('orders', 'orders.id', '=', 'original_items.order_id')
        ->join('delivery_management as dm', 'dm.order_id', '=', 'orders.id')
        ->whereNotIn('dm.delivery_status', ['pending', 'cancelled'])
        ->whereBetween('orders.created_at', [$startDate, $currentDate])
        ->select('original_items.product_id', DB::raw("SUM(original_items.quantity) as cq"))
        ->groupBy('original_items.product_id')
        ->pluck('cq', 'product_id');

    $products = DB::table('products')
        ->leftJoin('product_stocks', 'products.id', '=', 'product_stocks.product_id')
        ->leftJoin('vendors', 'vendors.id', '=', 'products.vendor_id')
        ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
        ->where('products.status', '!=', 'inactive') 
        ->select(
            'products.id',
            'products.vendor_id',
            'products.product_name',
            'products.brands as brand',
            'products.carton_size',
            'products.cost_per_item',
            'categories.category_name as category',
            'vendors.name as vendor_name',
            'vendors.lead_time',
            DB::raw("COALESCE(product_stocks.total_stock,0) as stock")
        )
        ->get();

    $report = [];

    foreach ($products as $p) {

    $cq = (float) ($consumption[$p->id] ?? 0);
    $adc = ($tdc > 0) ? ($cq / $tdc) : 0;
    $slt = (int) ($p->lead_time ?? 0);
    
 
    $rop_without_raw = $slt * $adc;
    
    $lss_percent = $lssSettings[$p->id] ?? 20;
    $nos_raw = ($lss_percent / 100) * $rop_without_raw + $rop_without_raw;
    
    $nos = round($nos_raw); 
    $rop_without = round($rop_without_raw); 
    
    $boxes = ($p->carton_size > 0)
        ? floor($nos_raw / $p->carton_size)
        : 0;

        $stock = (float) $p->stock;

        /*
        |-----------------------------------------
        | STATUS
        |-----------------------------------------
        | > 50%        : OK
        | 20% - 50%     : CAREFUL
        | 0% - 20%      : WATCH
        | -20% - 0%     : REORDER
        | < -20%        : CRITICAL
        */
        if ($stock <= 0 && $adc > 0) {
        $status = 'CRITICAL';
    } elseif ($adc <= 0) {
        $status = 'OK';
    } else {
        $diff_percent = ($nos_raw > 0)
            ? (($stock - $nos_raw) / $nos_raw) * 100
            : 0;
    
        if ($diff_percent > 50) {
            $status = 'OK';
        } elseif ($diff_percent > 20) {
            $status = 'CAREFUL';
        } elseif ($diff_percent >= 0) {
            $status = 'WATCH';
        } elseif ($diff_percent >= -20) {
            $status = 'REORDER';
        } else {
            $status = 'CRITICAL';
        }
    }

        $report[] = [
            'id' => $p->id,
            'vendor_id' => $p->vendor_id,
            'product' => $p->product_name,
            'brand' => $p->brand,
            'category' => $p->category,
            'vendor_name' => $p->vendor_name ?? 'N/A',
            'carton_size' => $p->carton_size,
            'purchase_price' => $p->cost_per_item ?? 0,

            // RAW (for JS recalculation)
            'rop_without_raw' => $rop_without,
            'nos_raw' => $nos_raw,
            'adc_raw' => $adc,
            'stock' => $stock,

            // DISPLAY
            'cq' => number_format($cq, 2),
            'adc' => number_format($adc, 2),
            'slt' => $slt,
            'rop_without' => number_format($rop_without, 2),
            'nos' => number_format($nos, 0),
            'boxes' => $boxes,
            'lss_percent' => $lss_percent,
            'status' => $status,
        ];
    }

    return view('admin.reports.reorder_report', compact('report'));
}






public function nonRunningProductsReport()
{
    $currentDate = Carbon::now();
    $cutoffDate  = Carbon::now()->subDays(30);

    // 1. Current stock on hand, tied back to the GRN it came from
    $rackStocks = DB::table('rack_stocks')
        ->join('stock_receivings', 'stock_receivings.id', '=', 'rack_stocks.stock_receiving_id')
        ->join('products', 'products.id', '=', 'rack_stocks.product_id')
        ->leftJoin('vendors', 'vendors.id', '=', 'products.vendor_id')
        ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
        ->whereIn('stock_receivings.status', ['approved', 'approved_with_changes'])
        ->where('rack_stocks.quantity', '>', 0)
        ->select(
            'rack_stocks.id as rack_stock_id',
            'rack_stocks.product_id',
            'rack_stocks.batch_no',
            'rack_stocks.expiry_date',
            'rack_stocks.quantity',
            'rack_stocks.rack_no',
            'rack_stocks.level_no',
            'rack_stocks.slot_no',
            'stock_receivings.id as grn_id',
            'stock_receivings.receipt_date',
            'stock_receivings.bill_no',
            'products.product_name',
            'products.brands as brand',
            'products.cost_per_item',
            'categories.category_name as category',
            'vendors.name as vendor_name'
        )
        ->get();

    // 2. Only GRNs that are 30+ days old
    $eligible = $rackStocks->filter(function ($rs) use ($cutoffDate) {
        return $rs->receipt_date && Carbon::parse($rs->receipt_date)->lte($cutoffDate);
    });

    if ($eligible->isEmpty()) {
        return view('admin.reports.non_running_products', ['report' => []]);
    }

    $productIds = $eligible->pluck('product_id')->unique()->values();

    // 3. Last time each product actually sold (OUT movement written in acceptOrder)
    $lastSaleDates = DB::table('stock_movements')
        ->whereIn('product_id', $productIds)
        ->where('movement_type', 'OUT')
        ->where('reference_type', 'ORDER')
        ->select('product_id', DB::raw('MAX(created_at) as last_sale_date'))
        ->groupBy('product_id')
        ->pluck('last_sale_date', 'product_id');

    $report = [];

    foreach ($eligible as $rs) {

        $receiptDate   = Carbon::parse($rs->receipt_date);
        $daysSinceGrn  = $receiptDate->diffInDays($currentDate);
        $lastSale      = $lastSaleDates[$rs->product_id] ?? null;

        $daysSinceLastSale = $lastSale
            ? Carbon::parse($lastSale)->diffInDays($currentDate)
            : $daysSinceGrn; // never sold -> age it by GRN date

        $isNonRunning = !$lastSale || $daysSinceLastSale > 30;

        if (!$isNonRunning) continue;

        $report[] = [
            'rack_stock_id'        => $rs->rack_stock_id,
            'product_id'           => $rs->product_id,
            'product'              => $rs->product_name,
            'brand'                => $rs->brand,
            'category'             => $rs->category,
            'vendor_name'          => $rs->vendor_name ?? 'N/A',
            'batch_no'             => $rs->batch_no,
            'expiry_date'          => $rs->expiry_date,
            'grn_id'               => $rs->grn_id,
            'bill_no'              => $rs->bill_no,
            'receipt_date'         => $receiptDate->format('Y-m-d'),
            'days_since_grn'       => $daysSinceGrn,
            'quantity'             => $rs->quantity,
            'rack_location'        => "{$rs->rack_no}/{$rs->level_no}/{$rs->slot_no}",
            'purchase_price'       => $rs->cost_per_item ?? 0,
            'stock_value'          => round(($rs->cost_per_item ?? 0) * $rs->quantity, 2),
            'last_sale_date'       => $lastSale ? Carbon::parse($lastSale)->format('Y-m-d') : 'Never Sold',
            'days_since_last_sale' => $daysSinceLastSale,
        ];
    }

   
    usort($report, fn($a, $b) => $b['days_since_last_sale'] <=> $a['days_since_last_sale']);

    return view('admin.reports.non_running_products', compact('report'));
}


public function reorderQty()
{
    $today = Carbon::now();
    $last30Days = Carbon::now()->subDays(30);
    
    $settings = DB::table('product_reorder_settings')
    ->get()
    ->keyBy('product_id');

    /*
    |-----------------------------------------
    | LAST 30 DAYS SALES
    |-----------------------------------------
    */

    $sales = DB::table('original_items')
        ->join('orders', 'orders.id', '=', 'original_items.order_id')
        ->join('delivery_management as dm', 'dm.order_id', '=', 'orders.id')
        ->whereNotIn('dm.delivery_status', ['pending', 'cancelled'])
        ->whereBetween('orders.created_at', [$last30Days, $today])
        ->select(
            'original_items.product_id',
            DB::raw("SUM(original_items.quantity) as total_qty")
        )
        ->groupBy('original_items.product_id')
        ->pluck('total_qty', 'product_id');

    /*
    |-----------------------------------------
    | PRODUCT DATA
    |-----------------------------------------
    */

 $products = DB::table('products as p')
    ->leftJoin('vendors as v','v.id','=','p.vendor_id')
    ->leftJoin('categories as c', 'c.id', '=', 'p.category_id') // FIXED
    ->where('p.status', '!=', 'inactive') 
    ->select(
        'p.id',
        'p.product_name',
        'p.brands as brand_name',
        'v.name as vendor_name',
        'c.category_name as category',
        'p.carton_size',
        'p.cost_per_item',
        'v.name as supplier'
    )
    ->get();

    $report = [];

    foreach ($products as $index => $p) {

        $last30 = (float) ($sales[$p->id] ?? 0);
         $setting = $settings[$p->id] ?? null;

        // Default values
        $spp = $setting->spp ?? 2;
        $oss_percent = $setting->oss_percent ?? 20;

        // Calculations
        $dcr = $last30 / 30;
        $weekly = $dcr * 7.5;
        $roq_wo_ss = $weekly * $spp;
        $nos = $roq_wo_ss + ($roq_wo_ss * ($oss_percent / 100));

        // $boxes = ($p->carton_size > 0)
        //     ? ceil($nos / $p->carton_size)
        //     : 0;
        
        $boxes = ($p->carton_size > 0)
        ? floor($nos / $p->carton_size)
        : 0;

        $investment = $nos * $p->cost_per_item;

        $report[] = [
            'index' => $index + 1,
            'product' => $p->product_name,
            'brand' => $p->brand_name,
            'category' => $p->category,
            // Add vendor name:
            'vendor_name' => $p->vendor_name ?? 'N/A', 
            'carton_size' => $p->carton_size,
            'supplier' => $p->supplier,

            'last30' => round($last30),
            'dcr' => round($dcr),
            'weekly' => round($weekly),
            
            
            'product_id' => $p->id,
            'spp' => $spp,
            'oss_percent' => $oss_percent,

            'roq_wo_ss' => round($roq_wo_ss),
            'nos' => round($nos),
            'boxes' => $boxes,

            'price' => $p->cost_per_item,
            'investment' => round($investment,2),
        ];
    }

    return view('admin.reports.reorder_qty_report', compact('report'));
}


// added on 16-07-26
// public function ReorderQtyPoint()
// {
//     $today = Carbon::now();
//     $last30Days = Carbon::now()->subDays(30);
    
//     $lssSettings = DB::table('product_lss_settings')
//     ->pluck('lss_percent', 'product_id');
    
//     $settings = DB::table('product_reorder_settings')
//     ->get()
//     ->keyBy('product_id');

//     /*
//     |-----------------------------------------
//     | SALES (COMMON)
//     |-----------------------------------------
//     */
//     $sales = DB::table('original_items')
//         ->join('orders', 'orders.id', '=', 'original_items.order_id')
//         ->join('delivery_management as dm', 'dm.order_id', '=', 'orders.id')
//         ->whereNotIn('dm.delivery_status', ['pending', 'cancelled'])
//         ->whereBetween('orders.created_at', [$last30Days, $today])
//         ->select(
//             'original_items.product_id',
//             DB::raw("SUM(original_items.quantity) as total_qty")
//         )
//         ->groupBy('original_items.product_id')
//         ->pluck('total_qty', 'product_id');

//     /*
//     |-----------------------------------------
//     | PRODUCTS + STOCK + VENDOR
//     |-----------------------------------------
//     */
//     $products = DB::table('products as p')
//         ->leftJoin('product_stocks as ps','ps.product_id','=','p.id')
//         ->leftJoin('vendors as v','v.id','=','p.vendor_id')
//         ->leftJoin('categories as c','c.id','=','p.category_id')
//         ->leftJoin('reorder_schemes as rs', function($join){
//             $join->on('rs.product_id','=','p.id')
//                 ->on('rs.vendor_id','=','p.vendor_id');
//         })
//         ->select(
//             'p.id',
//             'p.product_name',
//             'p.brands as brand',
//             'p.carton_size',
//             'p.cost_per_item',
//             'p.vendor_id',
//             //  Add for vendor name
//             'v.name as vendor_name',
//             'v.lead_time',
//             'c.category_name as category',
//             'rs.scheme',
//             DB::raw("COALESCE(ps.total_stock,0) as stock")
//         )
//         ->get();

//     $report = [];

//     foreach ($products as $index => $p) {

//         $last30 = (float) ($sales[$p->id] ?? 0);
//          $setting = $settings[$p->id] ?? null;

//         /*
//         |-----------------------------------------
//         | COMMON CALCULATIONS
//         |-----------------------------------------
//         */
//         $dcr = $last30 / 30;
//         // dd($dcr);
//         $weekly = $dcr * 7.5;

//         /*
//         |-----------------------------------------
//         | ROP LOGIC
//         |-----------------------------------------
//         */
//         $adc = $dcr;
//         $slt = (int) ($p->lead_time ?? 0);

//         $rop = round($adc * $slt);

//         $lss_percent = $lssSettings[$p->id] ?? 20;
        
//         // $rop_nos = $rop + ($rop * ($lss_percent / 100));
//         $rop_nos = $rop + ($rop * ($lss_percent / 100));

//         $rop_boxes = ($p->carton_size > 0)
//                 ? floor($rop_nos / $p->carton_size)
//                 : 0;

//         /*
//         | STATUS
//         */
//         // $status = 'OK';
//         // if ($p->stock <= $rop_nos) {
//         //     $status = 'REORDER';
//         // } elseif ($p->stock <= ($rop_nos * 1.10)) {
//         //     $status = 'WATCH';
//         // }
        
// if ($dcr <= 0) {
//     $status = 'OK';
// } else {
//     $diff_percent = ($rop_nos > 0)
//         ? (($p->stock - $rop_nos) / $rop_nos) * 100
//         : 0;

//     if ($diff_percent > 50) {
//         $status = 'OK';
//     } elseif ($diff_percent > 20) {
//         $status = 'CAREFUL';
//     } elseif ($diff_percent >= 0) {
//         $status = 'WATCH';
//     } elseif ($diff_percent >= -20) {
//         $status = 'REORDER';
//     } else {
//         $status = 'CRITICAL';
//     }
// }


//         /*
//         |-----------------------------------------
//         | ROQ LOGIC
//         |-----------------------------------------
//         */
//         $spp = $setting->spp ?? 2;
//         $oss_percent = $setting->oss_percent ?? 20;

//         $roq_wo_ss = $weekly * $spp;

//         $roq_nos = $roq_wo_ss + ($roq_wo_ss * ($oss_percent / 100));

//         $roq_boxes = ($p->carton_size > 0)
//             ? floor($roq_nos / $p->carton_size)
//             : 0;

//         $investment = $roq_nos * $p->cost_per_item;

//         /*
//         |-----------------------------------------
//         | FINAL ARRAY
//         |-----------------------------------------
//         */
//         $report[] = [

//             'id' => $index + 1,
//             'product_id' => $p->id,
//             'vendor_id' => $p->vendor_id,

//             'product' => $p->product_name,
//             'brand' => $p->brand,
//             'category' => $p->category,
//             //  Add for vendor name
//           'vendor_name' => $p->vendor_name ?? 'N/A',
//             'carton_size' => $p->carton_size,
//             'stock' => $p->stock,
//             'scheme' => $p->scheme ?? '',

//             'last_30_days' => round($last30),
//             'daily_consumption' => round($dcr, 2),

//             // ROP
//           'rop_nos' => round($rop_nos),
//             'rop_boxes' => $rop_boxes,
//             'status' => $status,

//             // ROQ
//             'roq_nos' => round($roq_nos),
//             'roq_boxes' => $roq_boxes,
//             'investment' => round($investment,2),

//             'price' => $p->cost_per_item
//         ];
//     }

//     return view('admin.reports.reorder_report_qty_point', compact('report'));
// }


// public function ReorderQtyPoint()
// {
//     $today = Carbon::now();
//     $last30Days = Carbon::now()->subDays(30);

//     $lssSettings = DB::table('product_lss_settings')->pluck('lss_percent', 'product_id');
//     $settings = DB::table('product_reorder_settings')->get()->keyBy('product_id');

//     /*
//     |-----------------------------------------
//     | SALES (COMMON)
//     |-----------------------------------------
//     */
//     $sales = DB::table('original_items')
//         ->join('orders', 'orders.id', '=', 'original_items.order_id')
//         ->join('delivery_management as dm', 'dm.order_id', '=', 'orders.id')
//         ->whereNotIn('dm.delivery_status', ['pending', 'cancelled'])
//         ->whereBetween('orders.created_at', [$last30Days, $today])
//         ->select('original_items.product_id', DB::raw("SUM(original_items.quantity) as total_qty"))
//         ->groupBy('original_items.product_id')
//         ->pluck('total_qty', 'product_id');

//     /*
//     |-----------------------------------------
//     | PRODUCTS + STOCK + VENDOR
//     |-----------------------------------------
//     */
//     $products = DB::table('products as p')
//         ->leftJoin('product_stocks as ps', 'ps.product_id', '=', 'p.id')
//         ->leftJoin('vendors as v', 'v.id', '=', 'p.vendor_id')
//         ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
//         ->leftJoin('reorder_schemes as rs', function ($join) {
//             $join->on('rs.product_id', '=', 'p.id')->on('rs.vendor_id', '=', 'p.vendor_id');
//         })
//           ->where('p.status', '!=', 'inactive') 
//         ->select(
//             'p.id',
//             'p.product_name',
//             'p.brands as brand',
//             'p.carton_size',
//             'p.cost_per_item',
//             'p.vendor_id',
//             'v.name as vendor_name',
//             'v.lead_time',
//             'c.category_name as category',
//             'rs.scheme',
//             DB::raw("COALESCE(ps.total_stock,0) as stock")
//         )
//         ->get();

//     $report = [];

//     foreach ($products as $index => $p) {

//         $last30 = (float) ($sales[$p->id] ?? 0);
//         $setting = $settings[$p->id] ?? null;

//         /*
//         |-----------------------------------------
//         | COMMON CALCULATIONS
//         |-----------------------------------------
//         */
//         $dcr = $last30 / 30;
//         $weekly = $dcr * 7.5;

//         /*
//         |-----------------------------------------
//         | ROP LOGIC
//         |-----------------------------------------
//         */
//         $adc = $dcr;
//         $slt = (int) ($p->lead_time ?? 0);

//         $rop = round($adc * $slt);

//         $lss_percent = $lssSettings[$p->id] ?? 20;

        
//         $rop_nos = $rop + ($rop * ($lss_percent / 100));

//         $rop_boxes = ($p->carton_size > 0)
//             ? floor($rop_nos / $p->carton_size)
//             : 0;

//         /*
//         |-----------------------------------------
//         | STATUS
//         |-----------------------------------------
//         | > 50%        : OK
//         | 20% - 50%     : CAREFUL
//         | 0% - 20%      : WATCH
//         | -20% - 0%     : REORDER
//         | < -20%        : CRITICAL
//         */
//         if ($dcr <= 0) {
//             $status = 'OK';
//         } else {
//             $diff_percent = ($rop_nos > 0)
//                 ? (($p->stock - $rop_nos) / $rop_nos) * 100
//                 : 0;

//             if ($diff_percent > 50) {
//                 $status = 'OK';
//             } elseif ($diff_percent > 20) {
//                 $status = 'CAREFUL';
//             } elseif ($diff_percent >= 0) {
//                 $status = 'WATCH';
//             } elseif ($diff_percent >= -20) {
//                 $status = 'REORDER';
//             } else {
//                 $status = 'CRITICAL';
//             }
//         }

//         /*
//         |-----------------------------------------
//         | ROQ LOGIC
//         |-----------------------------------------
//         */
//         $spp = $setting->spp ?? 2;
//         $oss_percent = $setting->oss_percent ?? 20;

//         $roq_wo_ss = $weekly * $spp;
//         $roq_nos = $roq_wo_ss + ($roq_wo_ss * ($oss_percent / 100));

//         $roq_boxes = ($p->carton_size > 0)
//             ? floor($roq_nos / $p->carton_size)
//             : 0;

//         $investment = $roq_nos * $p->cost_per_item;

//         /*
//         |-----------------------------------------
//         | FINAL ARRAY
//         |-----------------------------------------
//         */
//         $report[] = [
//             'id' => $index + 1,
//             'product_id' => $p->id,
//             'vendor_id' => $p->vendor_id,

//             'product' => $p->product_name,
//             'brand' => $p->brand,
//             'category' => $p->category,
//             'vendor_name' => $p->vendor_name ?? 'N/A',
//             'carton_size' => $p->carton_size,
//             'stock' => $p->stock,
//             'scheme' => $p->scheme ?? '',

//             'last_30_days' => round($last30),
//             'daily_consumption' => number_format($dcr, 2),

//             // ROP
//             'rop_nos' => round($rop_nos),
//             'rop_boxes' => $rop_boxes,
//             'status' => $status,

//             // ROQ
//             'roq_nos' => round($roq_nos),
//             'roq_boxes' => $roq_boxes,
//             'investment' => round($investment, 2),

//             'price' => $p->cost_per_item,
//         ];
//     }

//     return view('admin.reports.reorder_report_qty_point', compact('report'));
// }



public function ReorderQtyPoint()
{
    $today = Carbon::now();
    $last30Days = Carbon::now()->subDays(30);

    $lssSettings = DB::table('product_lss_settings')->pluck('lss_percent', 'product_id');
    $settings = DB::table('product_reorder_settings')->get()->keyBy('product_id');

    /*
    |-----------------------------------------
    | SALES (LAST 30 DAYS)
    |-----------------------------------------
    */
    $sales = DB::table('original_items')
        ->join('orders', 'orders.id', '=', 'original_items.order_id')
        ->join('delivery_management as dm', 'dm.order_id', '=', 'orders.id')
        ->whereNotIn('dm.delivery_status', ['pending', 'cancelled'])
        ->whereBetween('orders.created_at', [$last30Days, $today])
        ->select(
            'original_items.product_id',
            DB::raw('SUM(original_items.quantity) as total_qty')
        )
        ->groupBy('original_items.product_id')
        ->pluck('total_qty', 'product_id');

    /*
    |-----------------------------------------
    | PRODUCTS
    |-----------------------------------------
    */
    $products = DB::table('products as p')
        ->leftJoin('product_stocks as ps', 'ps.product_id', '=', 'p.id')
        ->leftJoin('vendors as v', 'v.id', '=', 'p.vendor_id')
        ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
        ->leftJoin('reorder_schemes as rs', function ($join) {
            $join->on('rs.product_id', '=', 'p.id')
                 ->on('rs.vendor_id', '=', 'p.vendor_id');
        })
        ->where('p.status', '!=', 'inactive')
        ->select(
            'p.id',
            'p.product_name',
            'p.brands as brand',
            'p.carton_size',
            'p.cost_per_item',
            'p.vendor_id',
            'v.name as vendor_name',
            'v.lead_time',
            'c.category_name as category',
            'rs.scheme',
            DB::raw('COALESCE(ps.total_stock,0) as stock')
        )
        ->get();

    $report = [];

    foreach ($products as $index => $p) {

        $last30 = (float)($sales[$p->id] ?? 0);
        $setting = $settings[$p->id] ?? null;

        /*
        |-----------------------------------------
        | DAILY CONSUMPTION
        |-----------------------------------------
        */
        $dcr = $last30 / 30;
        $weekly = $dcr * 7.5;

        /*
        |-----------------------------------------
        | REORDER POINT
        |-----------------------------------------
        */
        $leadTime = (float)($p->lead_time ?? 0);
        $lss_percent = (float)($lssSettings[$p->id] ?? 20);

        // DON'T ROUND HERE
        $rop = $dcr * $leadTime;

        // Safety Stock
        $rop_nos = $rop * (1 + ($lss_percent / 100));

        $rop_boxes = ($p->carton_size > 0)
            ? floor($rop_nos / $p->carton_size)
            : 0;

        /*
        |-----------------------------------------
        | STATUS
        |-----------------------------------------
        */
        if ($p->stock <= 0 && $dcr > 0) {
            // Zero stock with any real demand is never "OK", no matter what
            // the ROP calculation says (including cases where lead_time is
            // missing/zero and silently zeroes out $rop_nos below).
            $status = 'CRITICAL';
        } elseif ($dcr <= 0) {
            // No sales
            $status = 'OK';
        } elseif ($rop_nos <= 0.5) {
            // Very slow moving product
            $status = 'OK';
        } else {
            $diff_percent = (($p->stock - $rop_nos) / $rop_nos) * 100;
            if ($diff_percent > 50) {
                $status = 'OK';
            } elseif ($diff_percent > 20) {
                $status = 'CAREFUL';
            } elseif ($diff_percent >= 0) {
                $status = 'WATCH';
            } elseif ($diff_percent >= -20) {
                $status = 'REORDER';
            } else {
                $status = 'CRITICAL';
            }
        }

        /*
        |-----------------------------------------
        | REORDER QUANTITY
        |-----------------------------------------
        */
        $spp = $setting->spp ?? 2;
        $oss_percent = $setting->oss_percent ?? 20;

        $roq_wo_ss = $weekly * $spp;
        $roq_nos = $roq_wo_ss * (1 + ($oss_percent / 100));

        $roq_boxes = ($p->carton_size > 0)
            ? floor($roq_nos / $p->carton_size)
            : 0;

        $investment = $roq_nos * $p->cost_per_item;

        /*
        |-----------------------------------------
        | REPORT
        |-----------------------------------------
        */
        $report[] = [
            'id' => $index + 1,
            'product_id' => $p->id,
            'vendor_id' => $p->vendor_id,

            'product' => $p->product_name,
            'brand' => $p->brand,
            'category' => $p->category,
            'vendor_name' => $p->vendor_name ?? 'N/A',
            'carton_size' => $p->carton_size,
            'stock' => $p->stock,
            'scheme' => $p->scheme ?? '',

            'last_30_days' => round($last30),
            'daily_consumption' => number_format($dcr, 2),

            'rop_nos' => round($rop_nos, 2),
            'rop_boxes' => $rop_boxes,
            'status' => $status,

            'roq_nos' => round($roq_nos),
            'roq_boxes' => $roq_boxes,
            'investment' => round($investment, 2),

            'price' => $p->cost_per_item,
        ];
    }

    return view('admin.reports.reorder_report_qty_point', compact('report'));
}

public function saveScheme(Request $request)
{
    // Validation
    $request->validate([
        'product_id' => 'required|integer',
        'vendor_id'  => 'required|integer',
        'scheme'     => 'nullable|string'
    ]);

    DB::table('reorder_schemes')->updateOrInsert(
        [
            'product_id' => $request->product_id,
            'vendor_id'  => $request->vendor_id,
        ],
        [
            'scheme' => $request->scheme,
            'updated_at' => now(),
            'created_at' => now()
        ]
    );

    return response()->json([
        'status' => true,
        'message' => 'Scheme saved'
    ]);
}



}