<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;
use App\Models\OverdueFollowup;
use App\Models\Payment;
use App\Models\OutletPaymentTerm;
use App\Models\User;
use App\Models\DairyPaymentTerm;
use App\Models\Enquiry;
use App\Models\KYCDocument;
use App\Models\DeliveryManagement;
use App\Models\Admin;
use App\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\UserNotification;


class AdminnewController extends Controller
{
 
 
 
private function buildReorderReport()
{
    $today = Carbon::now();
    $last30Days = Carbon::now()->subDays(30);

    $lssSettings = DB::table('product_lss_settings')
        ->pluck('lss_percent', 'product_id');

    $settings = DB::table('product_reorder_settings')
        ->get()
        ->keyBy('product_id');

    /*
    |-----------------------------------------
    | SALES (COMMON) — matches current production query exactly
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
            DB::raw("COALESCE(ps.total_stock,0) as stock")
        )
        ->get();

    $report = [];

    foreach ($products as $index => $p) {

        $last30 = (float) ($sales[$p->id] ?? 0);
        $setting = $settings[$p->id] ?? null;

      
        $dcr = $last30 / 30;
        $weekly = $dcr * 7.5;
        
     
        $leadTime = (float) ($p->lead_time ?? 0);
        $lss_percent = (float) ($lssSettings[$p->id] ?? 20);
        
       
        $rop = $dcr * $leadTime;
        
       
        $rop_nos = $rop * (1 + ($lss_percent / 100));
        
        $rop_boxes = ($p->carton_size > 0)
            ? floor($rop_nos / $p->carton_size)
            : 0;
        
        
if ($p->stock <= 0 && $dcr > 0) {
    // Zero stock with real demand is always CRITICAL, regardless of
    // what the ROP math produces (e.g. if lead_time is missing/zero
    // and silently zeroes out $rop_nos below).
    $status = 'CRITICAL';

} elseif ($dcr <= 0) {

    $status = 'OK';

} elseif ($rop_nos <= 0.5) {

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

        $spp = $setting->spp ?? 2;
        $oss_percent = $setting->oss_percent ?? 20;

        $roq_wo_ss = $weekly * $spp;
        $roq_nos = $roq_wo_ss + ($roq_wo_ss * ($oss_percent / 100));

        $roq_boxes = ($p->carton_size > 0)
            ? floor($roq_nos / $p->carton_size)
            : 0;

        $investment = $roq_nos * $p->cost_per_item;

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
            'daily_consumption' => round($dcr, 2),
            'rop_nos' => round($rop_nos),
            'rop_boxes' => $rop_boxes,
            'status' => $status,
            'roq_nos' => round($roq_nos),
            'roq_boxes' => $roq_boxes,
            'investment' => round($investment, 2),
            'price' => $p->cost_per_item
        ];
    }

    return $report;
}

// private function buildOverdueSummary()
// {
//     $orders = Order::join('outstanding_statements', 'orders.id', '=', 'outstanding_statements.order_id')
//         ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
//         ->whereIn('orders.payment_status', ['unpaid', 'partial'])
//         ->where('delivery_management.delivery_status', 'delivered')
//         ->select('orders.*')
//         ->get();

//     $today = now()->startOfDay();

//     $overdueByOutlet    = [];
//     $notOverdueByOutlet = [];
//     $dueSoonByOutlet    = [];

//     $overdueDaysByOutlet   = [];
//     $overdueAmountByOutlet = [];

//     foreach ($orders as $order) {

//         $payment = Payment::where('order_id', $order->id)->first();

//         $totalAmount   = $order->total_discount_value;
//         $totalPaid     = $payment->total_paid ?? 0;
//         $balanceAmount = $totalAmount - $totalPaid;

//         if ($balanceAmount <= 0) {
//             continue;
//         }

//         $deliveryDate = Carbon::parse($order->delivery_date);

//         $paymentTerm = OutletPaymentTerm::where('user_id', $order->outlet_id)
//             ->where('is_active', 1)
//             ->first();

//         $hasNewPaymentTerm = $paymentTerm ? true : false;

//         $userData = User::where('id', $order->outlet_id)
//             ->select('due_days_limit')
//             ->first();

//         $due_days_limit = $userData->due_days_limit ?? 0;

//         $isOverdue = false;
//         $daysOverdue = 0;
//         $daysUntilDue = null; // used for due-soon check

//         /*
//         |----------------------------------
//         | PRIORITY 1: special_credit
//         |----------------------------------
//         */
//         if ($order->payment_method === 'special_credit') {

//             $dairyTerm = DairyPaymentTerm::where('user_id', $order->outlet_id)
//                 ->where('is_active', 1)
//                 ->first();

//             $customDueDays = ($dairyTerm && $dairyTerm->due_limit_days !== null)
//                 ? (int) $dairyTerm->due_limit_days
//                 : $due_days_limit;

//             $dueDate = $deliveryDate->copy()->addDays($customDueDays);

//             $daysDifference = $today->diffInDays($dueDate, false);

//             $isOverdue    = $daysDifference < 0;
//             $daysOverdue  = $isOverdue ? abs($daysDifference) : 0;
//             $daysUntilDue = $daysDifference;

//         /*
//         |----------------------------------
//         | PRIORITY 2: outlet payment term
//         |----------------------------------
//         */
//         } elseif ($hasNewPaymentTerm) {

//             $deliveryDateStart = $deliveryDate->copy()->startOfDay();
//             $dueDay = (int) $paymentTerm->days ?: 1;

//             $dueDate = $deliveryDateStart->copy()
//                 ->addMonthNoOverflow()
//                 ->day($dueDay)
//                 ->startOfDay();

//             $isOverdue    = $today->gt($dueDate);
//             $daysOverdue  = $isOverdue ? $today->diffInDays($dueDate) : 0;
//             $daysUntilDue = $isOverdue ? -$daysOverdue : $today->diffInDays($dueDate);

//         /*
//         |----------------------------------
//         | PRIORITY 3: normal credit — WITH +1 day grace, matches invoiceID() exactly
//         |----------------------------------
//         */
//         } else {

//             $dueDate = $deliveryDate->copy()->addDays($due_days_limit);

//             $daysDifference = $today->diffInDays($dueDate->copy()->addDay(), false);

//             $isOverdue    = $daysDifference < 0;
//             $daysOverdue  = $isOverdue ? abs($daysDifference) : 0;
//             $daysUntilDue = $daysDifference;
//         }

//         if ($isOverdue) {

//             $overdueByOutlet[$order->outlet_id] = ($overdueByOutlet[$order->outlet_id] ?? 0) + $balanceAmount;

//             if (!isset($overdueDaysByOutlet[$order->outlet_id]) || $daysOverdue > $overdueDaysByOutlet[$order->outlet_id]) {
//                 $overdueDaysByOutlet[$order->outlet_id] = $daysOverdue;
//             }

//             $overdueAmountByOutlet[$order->outlet_id] = ($overdueAmountByOutlet[$order->outlet_id] ?? 0) + $balanceAmount;

//         } else {

//             $notOverdueByOutlet[$order->outlet_id] = ($notOverdueByOutlet[$order->outlet_id] ?? 0) + $balanceAmount;

//             if ($daysUntilDue !== null && $daysUntilDue >= 0 && $daysUntilDue <= 3) {
//                 $dueSoonByOutlet[$order->outlet_id] = ($dueSoonByOutlet[$order->outlet_id] ?? 0) + $balanceAmount;
//             }
//         }
//     }

//     $bucket30to60Count = 0;
//     $bucket60to90Count = 0;
//     $bucketOver90Count = 0;
//     $bucket0to30Count = 0;


//     $bucket30to60Amount = 0;
//     $bucket60to90Amount = 0;
//     $bucketOver90Amount = 0;
//     $bucket0to30Amount = 0;


//     foreach ($overdueDaysByOutlet as $outletId => $days) {

//         $outletOverdueAmount = $overdueAmountByOutlet[$outletId] ?? 0;

//         if ($days > 90) {
//             $bucketOver90Count++;
//             $bucketOver90Amount += $outletOverdueAmount;
//         } elseif ($days > 60) {
//             $bucket60to90Count++;
//             $bucket60to90Amount += $outletOverdueAmount;
//         } elseif ($days > 30) {
//             $bucket30to60Count++;
//             $bucket30to60Amount += $outletOverdueAmount;
//         }  else {
//           $bucket0to30Count++;
//           $bucket0to30Amount += $outletOverdueAmount;
//       }
//     }

//     return [
//         'overdue_customer_count'     => count($overdueByOutlet),
//         'overdue_total_amount'       => array_sum($overdueByOutlet),

//         'not_overdue_customer_count' => count($notOverdueByOutlet),
//         'not_overdue_total_amount'   => array_sum($notOverdueByOutlet),

//         'due_soon_customer_count'    => count($dueSoonByOutlet),
//         'due_soon_total_amount'      => array_sum($dueSoonByOutlet),
        
//         'overdue_0_30_count'         => $bucket0to30Count,
//         'overdue_0_30_amount'        => $bucket0to30Amount,

//         'overdue_30_60_count'        => $bucket30to60Count,
//         'overdue_30_60_amount'       => $bucket30to60Amount,

//         'overdue_60_90_count'        => $bucket60to90Count,
//         'overdue_60_90_amount'       => $bucket60to90Amount,

//         'overdue_over_90_count'      => $bucketOver90Count,
//         'overdue_over_90_amount'     => $bucketOver90Amount,
//     ];
// }



private function buildOverdueSummary()
{
    $orders = Order::join('outstanding_statements', 'orders.id', '=', 'outstanding_statements.order_id')
        ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
        ->whereIn('orders.payment_status', ['unpaid', 'partial'])
        ->where('delivery_management.delivery_status', 'delivered')
        ->select('orders.*')
        ->get();

    $today = now()->startOfDay();

    $overdueByOutlet    = [];
    $notOverdueByOutlet = [];
    $dueSoonByOutlet    = [];
    $dueSoon7ByOutlet   = [];

    $overdueDaysByOutlet   = [];
    $overdueAmountByOutlet = [];

    foreach ($orders as $order) {

        $payment = Payment::where('order_id', $order->id)->first();

        $totalAmount   = $order->total_discount_value;
        $totalPaid     = $payment->total_paid ?? 0;
        $balanceAmount = $totalAmount - $totalPaid;

        if ($balanceAmount <= 0) {
            continue;
        }

        $deliveryDate = Carbon::parse($order->delivery_date);

        $paymentTerm = OutletPaymentTerm::where('user_id', $order->outlet_id)
            ->where('is_active', 1)
            ->first();

        $hasNewPaymentTerm = $paymentTerm ? true : false;

        $userData = User::where('id', $order->outlet_id)
            ->select('due_days_limit')
            ->first();

        $due_days_limit = $userData->due_days_limit ?? 0;

        $isOverdue = false;
        $daysOverdue = 0;
        $daysUntilDue = null;

        /*
        |----------------------------------
        | PRIORITY 1: special_credit
        |----------------------------------
        */
        if ($order->payment_method === 'special_credit') {

            $dairyTerm = DairyPaymentTerm::where('user_id', $order->outlet_id)
                ->where('is_active', 1)
                ->first();

            $customDueDays = ($dairyTerm && $dairyTerm->due_limit_days !== null)
                ? (int) $dairyTerm->due_limit_days
                : $due_days_limit;

            $dueDate = $deliveryDate->copy()->addDays($customDueDays);

            $daysDifference = $today->diffInDays($dueDate, false);

            // Due today (0) now correctly counts as overdue
            $isOverdue    = $daysDifference <= 0;
            $daysOverdue  = $isOverdue ? abs($daysDifference) : 0;
            $daysUntilDue = $daysDifference;

        /*
        |----------------------------------
        | PRIORITY 2: outlet payment term
        |----------------------------------
        */
        } elseif ($hasNewPaymentTerm) {

            $deliveryDateStart = $deliveryDate->copy()->startOfDay();
            $dueDay = (int) $paymentTerm->days ?: 1;

            $dueDate = $deliveryDateStart->copy()
                ->addMonthNoOverflow()
                ->day($dueDay)
                ->startOfDay();

            // gte instead of gt — today being equal to due date now counts as overdue
            $isOverdue    = $today->gte($dueDate);
            $daysOverdue  = $isOverdue ? $today->diffInDays($dueDate) : 0;
            $daysUntilDue = $isOverdue ? -$daysOverdue : $today->diffInDays($dueDate);

        /*
        |----------------------------------
        | PRIORITY 3: normal credit — WITH +1 day grace, matches invoiceID() exactly
        |----------------------------------
        */
        } else {

            $dueDate = $deliveryDate->copy()->addDays($due_days_limit);

            $daysDifference = $today->diffInDays($dueDate->copy()->addDay(), false);

            // Due today (0) now correctly counts as overdue
            $isOverdue    = $daysDifference <= 0;
            $daysOverdue  = $isOverdue ? abs($daysDifference) : 0;
            $daysUntilDue = $daysDifference;
        }

        if ($isOverdue) {

            $overdueByOutlet[$order->outlet_id] = ($overdueByOutlet[$order->outlet_id] ?? 0) + $balanceAmount;

            if (!isset($overdueDaysByOutlet[$order->outlet_id]) || $daysOverdue > $overdueDaysByOutlet[$order->outlet_id]) {
                $overdueDaysByOutlet[$order->outlet_id] = $daysOverdue;
            }

            $overdueAmountByOutlet[$order->outlet_id] = ($overdueAmountByOutlet[$order->outlet_id] ?? 0) + $balanceAmount;

        } else {

            $notOverdueByOutlet[$order->outlet_id] = ($notOverdueByOutlet[$order->outlet_id] ?? 0) + $balanceAmount;

            if ($daysUntilDue !== null && $daysUntilDue > 0 && $daysUntilDue <= 3) {
                $dueSoonByOutlet[$order->outlet_id] = ($dueSoonByOutlet[$order->outlet_id] ?? 0) + $balanceAmount;
            }
            
              if ($daysUntilDue !== null && $daysUntilDue > 0 && $daysUntilDue <= 7) {
                $dueSoon7ByOutlet[$order->outlet_id] = ($dueSoon7ByOutlet[$order->outlet_id] ?? 0) + $balanceAmount;
            }
        }
    }

    $bucket30to60Count = 0;
    $bucket60to90Count = 0;
    $bucketOver90Count = 0;
    $bucket0to30Count = 0;

    $bucket30to60Amount = 0;
    $bucket60to90Amount = 0;
    $bucketOver90Amount = 0;
    $bucket0to30Amount = 0;

    foreach ($overdueDaysByOutlet as $outletId => $days) {

        $outletOverdueAmount = $overdueAmountByOutlet[$outletId] ?? 0;

        if ($days > 90) {
            $bucketOver90Count++;
            $bucketOver90Amount += $outletOverdueAmount;
        } elseif ($days > 60) {
            $bucket60to90Count++;
            $bucket60to90Amount += $outletOverdueAmount;
        } elseif ($days > 30) {
            $bucket30to60Count++;
            $bucket30to60Amount += $outletOverdueAmount;
        } else {
            $bucket0to30Count++;
            $bucket0to30Amount += $outletOverdueAmount;
        }
    }

    return [
        'overdue_customer_count'     => count($overdueByOutlet),
        'overdue_total_amount'       => array_sum($overdueByOutlet),

        'not_overdue_customer_count' => count($notOverdueByOutlet),
        'not_overdue_total_amount'   => array_sum($notOverdueByOutlet),

        'due_soon_customer_count'    => count($dueSoonByOutlet),
        'due_soon_total_amount'      => array_sum($dueSoonByOutlet),
        
        'due_soon_7_customer_count'  => count($dueSoon7ByOutlet),
        'due_soon_7_total_amount'    => array_sum($dueSoon7ByOutlet),

        'overdue_0_30_count'         => $bucket0to30Count,
        'overdue_0_30_amount'        => $bucket0to30Amount,

        'overdue_30_60_count'        => $bucket30to60Count,
        'overdue_30_60_amount'       => $bucket30to60Amount,

        'overdue_60_90_count'        => $bucket60to90Count,
        'overdue_60_90_amount'       => $bucket60to90Amount,

        'overdue_over_90_count'      => $bucketOver90Count,
        'overdue_over_90_amount'     => $bucketOver90Amount,
    ];
}



public function index(Request $request)
{
    
    $admin = auth('admin')->user();

    if ($admin && $admin->role_id == 1) {
        $allowedSections = DB::table('dashboard_sections')->pluck('key')->toArray();
    } elseif ($admin) {
        $allowedSections = DB::table('role_dashboard_sections')
            ->join('dashboard_sections', 'dashboard_sections.id', '=', 'role_dashboard_sections.dashboard_section_id')
            ->where('role_dashboard_sections.role_id', $admin->role_id)
            ->pluck('dashboard_sections.key')
            ->toArray();
    } else {
        $allowedSections = [];
    }
    
    
    $todayStart = Carbon::today()->startOfDay();
    $todayEnd   = Carbon::today()->endOfDay();
    $monthStart = Carbon::now()->startOfMonth();
    $monthEnd   = Carbon::now()->endOfMonth();

    // ===============================
    // SALES
    // ===============================

    $salesToday = Order::whereBetween('created_at', [$todayStart, $todayEnd])
        ->whereHas('delivery', function ($q) {
            $q->where('delivery_status', 'delivered');
        })
        ->sum('total_discount_value');

    $salesThisMonth = Order::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->whereHas('delivery', function ($q) {
            $q->where('delivery_status', 'delivered');
        })
        ->sum('total_discount_value');

    // ===============================
    // MONTHLY DATA (FOR CHARTS)
    // ===============================

    $orders = Order::with(['items.product', 'user', 'delivery'])
        ->whereHas('delivery', function ($q) {
            $q->where('delivery_status', 'delivered');
        })
        ->get();

    $monthlyTotalPrices = [];
    $monthlyProductTotalPrices = [];

    foreach ($orders as $order) {
        $month = $order->created_at->format('Y-m');

        $monthlyTotalPrices[$month] = $monthlyTotalPrices[$month] ?? 0;
        $monthlyProductTotalPrices[$month] = $monthlyProductTotalPrices[$month] ?? 0;

        $totalPrice = $order->total_discount_value;

        $productPrice = 0;
        foreach ($order->items as $item) {
            if ($item->product) {
                $productPrice += $item->product->cost_per_item * $item->quantity;
            }
        }

        $productPrice = $productPrice - $order->product_discout + $order->cgst_sgst;

        $monthlyTotalPrices[$month] += $totalPrice;
        $monthlyProductTotalPrices[$month] += $productPrice;
    }

    // ===============================
    // ORDER REPORT (FOR LINE CHART)
    // ===============================

    $orderReport = Order::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('month')
        ->get();

    $orderdata = [];
    foreach ($orderReport as $report) {
        $orderdata[$report->month] = $report->total;
    }

    // ===============================
    // SECTION 1 — PRODUCTS
    // ===============================

    $productsCount          = DB::table('products')->count();
    $activeProductsCount    = DB::table('products')->where('status', 'active')->count();
    $inactiveProductsCount  = DB::table('products')->where('status', 'inactive')->count();

    // ===============================
    // SECTION 2 — FRONT END
    // ===============================

    $enquiriesReceivedCount = Enquiry::where('status', 'pending')
        ->select('enquiry_no')
        ->distinct()
        ->count('enquiry_no');

    $enquiriesSubmittedCount = Enquiry::where('status', 'submitted')
        ->select('enquiry_no')
        ->distinct()
        ->count('enquiry_no');

    $totalItemsQuotedCount   = Enquiry::where('status', 'pending')->count();
    
    $customerResponseReceivedCount = DB::table('enquiries')
    ->whereIn('status', ['pending', 'rejected'])
    ->where('offer_check', '=', 1)
    ->distinct()
    ->count('enquiry_no');
    
    $totalItemsApprovedCount = Enquiry::where('status', 'accept')->count();
    $totalItemsRejectedCount = Enquiry::where('status', 'rejected')->count();

    // $totalActiveOutletsCount = DB::table('users')
    //     ->where('type', 'outlet')
    //     ->where('verified_status', 'verified')
    //     ->count();
    
    $totalActiveOutletsCount = DB::table('users')
    ->where('type', 'outlet')
    ->whereIn('id', function ($query) {
        $query->select('outlet_id')
            ->from('orders')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct();
    })
    ->count();
        
 
    // $totalInactiveOutletsCount = DB::table('users')
    // ->where('type', 'outlet')
    // ->where('verified_status', '!=', 'verified') 
    // ->count();
    
    $totalInactiveOutletsCount = DB::table('users')
    ->where('type', 'outlet')
    ->whereNotIn('id', function ($query) {
        $query->select('outlet_id')
            ->from('orders')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct();
    })
    ->count();

    $productsOnSaleCount = DB::table('rack_stocks')
        ->where('is_on_sale', true)
        ->where('quantity', '>', 0)
        ->whereRaw('DATEDIFF(expiry_date, CURDATE()) >= 0')
        ->count();

    // ===============================
    // SECTION 3 — BACK END
    // ===============================

    $salesOrderPendingCount = DB::table('orders')
        ->join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
        ->where('delivery_management.delivery_status', 'pending')
        ->where('orders.created_at', '<=', now()->subDays(3))
        ->count();

    // ===============================
    // SECTION — INVENTORY
    // ===============================

    // 1. No. of Products Expired
    $expiredProductsCount = DB::table('rack_stocks')
        ->whereNotNull('expiry_date')
        ->where('quantity', '>', 0)
        ->whereRaw('expiry_date < CURDATE()')
        ->count();

    // 2. No. of Products Near To Expiry (0-60 days, not on sale)
    $nearExpiryProductsCount = DB::table('rack_stocks')
        ->whereNotNull('expiry_date')
        ->where('quantity', '>', 0)
        ->where('is_on_sale', false)
        ->whereRaw('DATEDIFF(expiry_date, CURDATE()) BETWEEN 0 AND 60')
        ->count();

    // 3. No. of Products Non Moving (30+ days since GRN, no sale in 30+ days)
    $nonMovingProductsCount = 0;

    $cutoffDate = Carbon::now()->subDays(31);

    $rackStocksForNonMoving = DB::table('rack_stocks')
        ->join('stock_receivings', 'stock_receivings.id', '=', 'rack_stocks.stock_receiving_id')
        ->whereIn('stock_receivings.status', ['approved', 'approved_with_changes'])
        ->where('rack_stocks.quantity', '>', 0)
        ->select('rack_stocks.product_id', 'stock_receivings.receipt_date')
        ->get();

    $eligibleForNonMoving = $rackStocksForNonMoving->filter(function ($rs) use ($cutoffDate) {
        return $rs->receipt_date && Carbon::parse($rs->receipt_date)->lte($cutoffDate);
    });

    if ($eligibleForNonMoving->isNotEmpty()) {

        $productIdsForNonMoving = $eligibleForNonMoving->pluck('product_id')->unique()->values();

        $lastSaleDatesForNonMoving = DB::table('stock_movements')
            ->whereIn('product_id', $productIdsForNonMoving)
            ->where('movement_type', 'OUT')
            ->where('reference_type', 'ORDER')
            ->select('product_id', DB::raw('MAX(created_at) as last_sale_date'))
            ->groupBy('product_id')
            ->pluck('last_sale_date', 'product_id');

        foreach ($eligibleForNonMoving as $rs) {

            $lastSale = $lastSaleDatesForNonMoving[$rs->product_id] ?? null;

            $daysSinceLastSale = $lastSale
                ? Carbon::parse($lastSale)->diffInDays(Carbon::now())
                : Carbon::parse($rs->receipt_date)->diffInDays(Carbon::now());

            $isNonRunning = !$lastSale || $daysSinceLastSale > 30;

            if ($isNonRunning) {
                $nonMovingProductsCount++;
            }
        }
    }
    
    
        // 4. Total Value Stock in Hand
        $totalStockValue = DB::table('rack_stocks')
            ->join('stock_receivings', 'stock_receivings.id', '=', 'rack_stocks.stock_receiving_id')
            ->join('products', 'products.id', '=', 'rack_stocks.product_id')
            ->whereIn('stock_receivings.status', ['approved', 'approved_with_changes'])
            ->where('rack_stocks.quantity', '>', 0)
            ->selectRaw('SUM(rack_stocks.quantity * products.cost_per_item) as total_value')
            ->value('total_value');
        
        $totalStockValue = $totalStockValue ?? 0;

    // ===============================
    // SECTION 4 — ORDER PROCESS (Today & This Month)
    // ===============================

    $pendingAcceptanceToday = DB::table('orders')
        ->join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
        ->where('delivery_management.delivery_status', 'pending')
        ->whereBetween('orders.created_at', [$todayStart, $todayEnd])
        ->count();

    $pendingAcceptanceMonth = DB::table('orders')
        ->join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
        ->where('delivery_management.delivery_status', 'pending')
        ->whereBetween('orders.created_at', [$monthStart, $monthEnd])
        ->count();

    $pickListCreatedToday = DB::table('pick_lists')
        ->whereBetween('created_at', [$todayStart, $todayEnd])
        ->distinct('order_id')
        ->count('order_id');

    $pickListCreatedMonth = DB::table('pick_lists')
        ->whereBetween('created_at', [$monthStart, $monthEnd])
        ->distinct('order_id')
        ->count('order_id');

    $markedPickedToday = DB::table('pick_lists')
        ->where('status', 'PICKED')
        ->whereBetween('updated_at', [$todayStart, $todayEnd])
        ->distinct('order_id')
        ->count('order_id');

    $markedPickedMonth = DB::table('pick_lists')
        ->where('status', 'PICKED')
        ->whereBetween('updated_at', [$monthStart, $monthEnd])
        ->distinct('order_id')
        ->count('order_id');

    $deliveryStatusCount = function ($status, $start, $end) {
        return DB::table('delivery_management')
            ->where('delivery_status', $status)
            ->whereBetween('updated_at', [$start, $end])
            ->count();
    };

    $acceptedInProgressToday = $deliveryStatusCount('in_progress', $todayStart, $todayEnd);
    $acceptedInProgressMonth = $deliveryStatusCount('in_progress', $monthStart, $monthEnd);

    $readyForDispatchToday = $deliveryStatusCount('ready_for_dispatch', $todayStart, $todayEnd);
    $readyForDispatchMonth = $deliveryStatusCount('ready_for_dispatch', $monthStart, $monthEnd);

    $finalCheckDoneToday = $deliveryStatusCount('final_check_done', $todayStart, $todayEnd);
    $finalCheckDoneMonth = $deliveryStatusCount('final_check_done', $monthStart, $monthEnd);

    $dispatchedToday = $deliveryStatusCount('dispatched', $todayStart, $todayEnd);
    $dispatchedMonth = $deliveryStatusCount('dispatched', $monthStart, $monthEnd);

    $deliveredToday = $deliveryStatusCount('delivered', $todayStart, $todayEnd);
    $deliveredMonth = $deliveryStatusCount('delivered', $monthStart, $monthEnd);

    $cancelledToday = $deliveryStatusCount('cancelled', $todayStart, $todayEnd);
    $cancelledMonth = $deliveryStatusCount('cancelled', $monthStart, $monthEnd);

    $preShortLogToday = DB::table('pre_material_short_logs')
    ->join('orders', 'orders.id', '=', 'pre_material_short_logs.order_id')
    ->join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
    ->where('delivery_management.delivery_status', 'pending')
    ->whereBetween('pre_material_short_logs.created_at', [$todayStart, $todayEnd])
    ->count();

$preShortLogMonth = DB::table('pre_material_short_logs')
    ->join('orders', 'orders.id', '=', 'pre_material_short_logs.order_id')
    ->join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
    ->where('delivery_management.delivery_status', 'pending')
    ->whereBetween('pre_material_short_logs.created_at', [$monthStart, $monthEnd])
    ->count();

    $postShortLogToday = DB::table('post_material_short_logs')
        ->whereBetween('created_at', [$todayStart, $todayEnd])
        ->count();

    $postShortLogMonth = DB::table('post_material_short_logs')
        ->whereBetween('created_at', [$monthStart, $monthEnd])
        ->count();
        
    $reorderReport = $this->buildReorderReport();
    
    $carefulCount  = collect($reorderReport)->where('status', 'CAREFUL')->count();
    $watchCount    = collect($reorderReport)->where('status', 'WATCH')->count();
    $reorderCount  = collect($reorderReport)->where('status', 'REORDER')->count();
    $criticalCount = collect($reorderReport)->where('status', 'CRITICAL')->count();
        
    

    $overdueSummary = $this->buildOverdueSummary();

    $overdueCustomerCount    = $overdueSummary['overdue_customer_count'];
    $overdueTotalAmount      = $overdueSummary['overdue_total_amount'];

    $notOverdueCustomerCount = $overdueSummary['not_overdue_customer_count'];
    $notOverdueTotalAmount   = $overdueSummary['not_overdue_total_amount'];

    $dueSoonCustomerCount    = $overdueSummary['due_soon_customer_count'];
    $dueSoonTotalAmount      = $overdueSummary['due_soon_total_amount'];
    
    $overdue0to30Count       = $overdueSummary['overdue_0_30_count'];
    $overdue0to30Amount      = $overdueSummary['overdue_0_30_amount'];

    $overdue30to60Count      = $overdueSummary['overdue_30_60_count'];
    $overdue30to60Amount     = $overdueSummary['overdue_30_60_amount'];

    $overdue60to90Count      = $overdueSummary['overdue_60_90_count'];
    $overdue60to90Amount     = $overdueSummary['overdue_60_90_amount'];

    $overdueOver90Count      = $overdueSummary['overdue_over_90_count'];
    $overdueOver90Amount     = $overdueSummary['overdue_over_90_amount'];
    
    $dueSoon7CustomerCount = $overdueSummary['due_soon_7_customer_count'];
    $dueSoon7TotalAmount   = $overdueSummary['due_soon_7_total_amount'];
    
    // ===============================
    // SECTION — SALES (Today, Previous Day, This Month, Financial Year Till Date)
    // ===============================

    $yesterdayStart = Carbon::yesterday()->startOfDay();
    $yesterdayEnd   = Carbon::yesterday()->endOfDay();

    // Financial Year: April 1 to March 31 (India). If current month is Jan-Mar,
    // FY started April 1 of LAST year; otherwise FY started April 1 THIS year.
    $fyStartYear = (Carbon::now()->month >= 4) ? Carbon::now()->year : Carbon::now()->year - 1;
    $fyStart     = Carbon::create($fyStartYear, 4, 1)->startOfDay();
    $fyEnd       = Carbon::now()->endOfDay();

    $salesQuery = function ($start, $end) {
        return Order::join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
            ->where('delivery_management.delivery_status', 'delivered')
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('COUNT(orders.id) as order_count, COALESCE(SUM(orders.total_discount_value), 0) as total_amount')
            ->first();
    };

    $salesTodayData    = $salesQuery($todayStart, $todayEnd);
    $salesYesterdayData = $salesQuery($yesterdayStart, $yesterdayEnd);
    $salesMonthData     = $salesQuery($monthStart, $monthEnd);
    $salesFYData        = $salesQuery($fyStart, $fyEnd);

    $salesTodayCount     = $salesTodayData->order_count ?? 0;
    $salesTodayAmount    = $salesTodayData->total_amount ?? 0;

    $salesYesterdayCount  = $salesYesterdayData->order_count ?? 0;
    $salesYesterdayAmount = $salesYesterdayData->total_amount ?? 0;

    $salesMonthCount  = $salesMonthData->order_count ?? 0;
    $salesMonthAmount = $salesMonthData->total_amount ?? 0;

    $salesFYCount  = $salesFYData->order_count ?? 0;
    $salesFYAmount = $salesFYData->total_amount ?? 0;


    // ===============================
    // RETURN VIEW
    // ===============================

    if (auth('admin')->check()) {
        return view('admin.pages.dashboardnew', compact(
            'salesToday',
            'salesThisMonth',
            'orderdata',
            'monthlyTotalPrices',
            'monthlyProductTotalPrices',
            'allowedSections',

            'productsCount',
            'activeProductsCount',
            'inactiveProductsCount',
            
            'carefulCount',
            'watchCount',
            'reorderCount',
            'criticalCount',
            
            'salesTodayCount', 'salesTodayAmount',
            'salesYesterdayCount', 'salesYesterdayAmount',
            'salesMonthCount', 'salesMonthAmount',
            'salesFYCount', 'salesFYAmount',
            
            'overdueCustomerCount',
            'overdueTotalAmount',
            'notOverdueCustomerCount',
            'notOverdueTotalAmount',
            'dueSoonCustomerCount',
            'dueSoonTotalAmount',
            'overdue0to30Count',
            'overdue0to30Amount',
            'overdue30to60Count',
            'overdue30to60Amount',
            'overdue60to90Count',
            'overdue60to90Amount',
            'overdueOver90Count',
            'overdueOver90Amount',
            'dueSoon7CustomerCount',
            'dueSoon7TotalAmount',

            'enquiriesReceivedCount',
            'enquiriesSubmittedCount',
            'totalItemsQuotedCount',
            'customerResponseReceivedCount',
            'totalItemsApprovedCount',
            'totalItemsRejectedCount',
            'totalActiveOutletsCount',
            'totalInactiveOutletsCount',
            'productsOnSaleCount',

            'salesOrderPendingCount',

            'expiredProductsCount',
            'nearExpiryProductsCount',
            'nonMovingProductsCount',
            'totalStockValue',

            'pendingAcceptanceToday', 'pendingAcceptanceMonth',
            'pickListCreatedToday', 'pickListCreatedMonth',
            'markedPickedToday', 'markedPickedMonth',
            'acceptedInProgressToday', 'acceptedInProgressMonth',
            'readyForDispatchToday', 'readyForDispatchMonth',
            'finalCheckDoneToday', 'finalCheckDoneMonth',
            'dispatchedToday', 'dispatchedMonth',
            'deliveredToday', 'deliveredMonth',
            'cancelledToday', 'cancelledMonth',
            'preShortLogToday', 'preShortLogMonth',
            'postShortLogToday', 'postShortLogMonth'
        ));
    } else {
        return view('admin.login.loginnew');
    }
}


    // public function index(Request $request)
    // {


    //     $orders = Order::with(['items', 'user'])->get();
    //     $monthlyTotalPrices = [];
    //     $monthlyProductTotalPrices = [];

    //     $discout_val = 0;
    //     $product_price = 0;

    //     foreach ($orders as $order) {
    //         $delivery = DeliveryManagement::where('order_id', $order->id)
    //             ->where('delivery_status', 'delivered')
    //             ->first();

    //         if ($delivery) {
    //             $month = $order->created_at->format('Y-m');
    //             if (!isset($monthlyTotalPrices[$month])) {
    //                 $monthlyTotalPrices[$month] = 0;
    //             }

    //             if (!isset($monthlyProductTotalPrices[$month])) {
    //                 $monthlyProductTotalPrices[$month] = 0;
    //             }
    //             $productPrice = 0;
    //             $totalPrice = 0;
    //           foreach ($order->items as $item) {
    //                 $beforediscount = $item->price;
    //                 $totalPrice += $beforediscount;
    //               if ($item->product) {
    //     $productPrice += $item->product->cost_per_item * $item->quantity;
    // } else {
    //     // Handle the case where there is no product associated (e.g., log an error, set a default value)
    //     $productPrice += 0; // or you can skip this item
    // }
    //             }


    //             $productPrice =  $productPrice - $order->product_discout +  $order->cgst_sgst;
    //             $monthlyTotalPrices[$month] += $totalPrice;
    //             $monthlyProductTotalPrices[$month] += $productPrice;

    //         }
    //     }


    //     // foreach ($monthlyTotalPrices as $month => $totalPrice) {
    //     //     $productPrice = isset($monthlyProductTotalPrices[$month]) ? $monthlyProductTotalPrices[$month] : 0;
    //     //     var_dump("Month: $month, Total Price: $totalPrice, Product Expense: $productPrice<br>");
    //     // }
    //     // exit();


    //     $responses = Enquiry::select('status', DB::raw('COUNT(*) as total'))
    //         ->groupBy('status')
    //         ->get();

    //     $deliveryresponses = DeliveryManagement::select('delivery_status', DB::raw('COUNT(*) as total'))
    //         ->groupBy('delivery_status')
    //         ->get();

    //         $orderReport = Order::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as total'))
    //         ->groupBy('month')
    //         ->get();



    //         $orderdata = [];
    //             foreach ($orderReport as $report) {
    //                 $orderdata[$report->month] = $report->total;
    //             }
    //         $deliverydata = [];
    //         foreach ($deliveryresponses as $deliveryresponse) {
    //             $deliverydata[$deliveryresponse->delivery_status] = $deliveryresponse->total;
    //         }


    //     $respondedStatuses = ['accept', 'reject', 'reoffer'];
    //     $notRespondedStatuses = ['submitted'];
    //     $pendingSubmittedStatuses = ['pending'];
    //     $respondedTotal = 0;
    //     $notRespondedTotal = 0;
    //     $pendingSubmittedTotal = 0;

    //     $data = [];
    //     foreach ($responses as $response) {
    //         $data[$response->status] = $response->total;
    //         if (in_array($response->status, $respondedStatuses)) {
    //             $respondedTotal += $response->total;
    //         } elseif (in_array($response->status, $notRespondedStatuses)) {
    //             $notRespondedTotal += $response->total;
    //         } elseif (in_array($response->status, $pendingSubmittedStatuses)) {
    //             $pendingSubmittedTotal += $response->total;
    //         }
    //     }

    //     $responsedata = [
    //         'responded' => $respondedTotal,
    //         'not_responded' => $notRespondedTotal,
    //         'pending_submitted' => $pendingSubmittedTotal,
    //     ];

    //     if ($request->session()->has('ADMIN_LOGIN')) {

    //         return view('admin.pages.dashboardnew', compact('data', 'responsedata','deliverydata','orderdata','monthlyTotalPrices', 'monthlyProductTotalPrices'));
    //     } else {
    //         return view('admin.login.loginnew');
    //     }
    // }



    // public function auth(Request $request)
    // {
    //     $email = $request->post('email');
    //     $password = $request->post('password');

    //     // $result=Admin::where(['email'=>$email,'password'=>$password])->get();
    //     $result = Admin::where(['email' => $email])->first();
    //   //dd($result);
    //     if ($result) {
    //         if ($request->post('password') === $result->password) {
    //             $request->session()->put('ADMIN_LOGIN', true);
    //             $request->session()->put('ADMIN_ID', $result->id);
    //             $request->session()->put('role', $result->role);
    //             return redirect('dashboardd');
    //         } else {

    //             $request->session()->flash('success', 'Please enter correct password');
    //             return redirect('adminnew');
    //         }
    //     } else {
    //         $request->session()->flash('success', 'Please enter valid login details');
    //         return redirect('adminnew');
    //     }
    // }
    
     public function auth(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::guard('admin')->attempt($credentials)) {
        $request->session()->regenerate(); 
        return redirect('dashboardd');
    }

    return redirect('adminnew')->with('error', 'Invalid login details');
}


    public function user()
    {
        return view('admin.login.user');
    }


    public function store(Request $request)
    {
        $user = new Admin();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->role = $request->input('role');

        $user->save();
        return redirect()->route('admin.store')->with('success', 'User added successfully.');
    }



//comment on 30-04-26
    // public function dashboard(Request $request)
    // {

    //     $orders = Order::with(['items', 'user'])->get();
    //     $monthlyTotalPrices = [];
    //     $monthlyProductTotalPrices = [];

    //     $discout_val = 0;
    //     $product_price = 0;
        
        
    //     $today = Carbon::today();
    //     $currentMonth = Carbon::now();
        
    //     $salesToday = 0;
    //     $salesThisMonth = 0;


    //     foreach ($orders as $order) {
    //         $delivery = DeliveryManagement::where('order_id', $order->id)
    //             ->where('delivery_status', 'delivered')
    //             ->first();

    //         if ($delivery) {
    //             $month = $order->created_at->format('Y-m');
    //             if (!isset($monthlyTotalPrices[$month])) {
    //                 $monthlyTotalPrices[$month] = 0;
    //             }

    //             if (!isset($monthlyProductTotalPrices[$month])) {
    //                 $monthlyProductTotalPrices[$month] = 0;
    //             }
    //             $productPrice = 0;
    //             $totalPrice = 0;
    //          foreach ($order->items as $item) {
    //                 $beforediscount = $item->price;
    //                 $totalPrice += $beforediscount;
    //               if ($item->product) {
    //     $productPrice += $item->product->cost_per_item * $item->quantity;
    // } else {
    //     // Handle the case where there is no product associated (e.g., log an error, set a default value)
    //     $productPrice += 0; // or you can skip this item
    // }
    //             }



    //             $productPrice =  $productPrice - $order->product_discout +  $order->cgst_sgst;
    //             $monthlyTotalPrices[$month] += $totalPrice;
    //             $monthlyProductTotalPrices[$month] += $productPrice;

    //         }
    //     }


    //     // foreach ($monthlyTotalPrices as $month => $totalPrice) {
    //     //     $productPrice = isset($monthlyProductTotalPrices[$month]) ? $monthlyProductTotalPrices[$month] : 0;
    //     //     var_dump("Month: $month, Total Price: $totalPrice, Product Expense: $productPrice<br>");
    //     // }
    //     // exit();


    //     $responses = Enquiry::select('status', DB::raw('COUNT(*) as total'))
    //         ->groupBy('status')
    //         ->get();

    //     $deliveryresponses = DeliveryManagement::select('delivery_status', DB::raw('COUNT(*) as total'))
    //         ->groupBy('delivery_status')
    //         ->get();

    //         $orderReport = Order::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as total'))
    //         ->groupBy('month')
    //         ->get();



    //         $orderdata = [];
    //             foreach ($orderReport as $report) {
    //                 $orderdata[$report->month] = $report->total;
    //             }
    //         $deliverydata = [];
    //         foreach ($deliveryresponses as $deliveryresponse) {
    //             $deliverydata[$deliveryresponse->delivery_status] = $deliveryresponse->total;
    //         }


    //     $respondedStatuses = ['accept', 'reject', 'reoffer'];
    //     $notRespondedStatuses = ['submitted'];
    //     $pendingSubmittedStatuses = ['pending'];
    //     $respondedTotal = 0;
    //     $notRespondedTotal = 0;
    //     $pendingSubmittedTotal = 0;

    //     $data = [];
    //     foreach ($responses as $response) {
    //         $data[$response->status] = $response->total;
    //         if (in_array($response->status, $respondedStatuses)) {
    //             $respondedTotal += $response->total;
    //         } elseif (in_array($response->status, $notRespondedStatuses)) {
    //             $notRespondedTotal += $response->total;
    //         } elseif (in_array($response->status, $pendingSubmittedStatuses)) {
    //             $pendingSubmittedTotal += $response->total;
    //         }
    //     }

    //     $responsedata = [
    //         'responded' => $respondedTotal,
    //         'not_responded' => $notRespondedTotal,
    //         'pending_submitted' => $pendingSubmittedTotal,
    //     ];

    //      if (auth('admin')->check()) {
    //         return view('admin.pages.dashboardnew', compact('data', 'responsedata','deliverydata','orderdata','monthlyTotalPrices', 'monthlyProductTotalPrices'));
    //     } else {
    //         return view('admin.login.login');
    //     }
    // }
    
    
    //added on 30-04-26
    
//   public function dashboard(Request $request)
// {
//     // ===============================
//     // ✅ SALES (CORRECT & OPTIMIZED)
//     // ===============================

//     $salesToday = Order::whereBetween('created_at', [
//             now()->startOfDay(),
//             now()->endOfDay()
//         ])
//         ->whereHas('delivery', function ($q) {
//             $q->where('delivery_status', 'delivered');
//         })
//         ->sum('total_discount_value');

//     $salesThisMonth = Order::whereMonth('created_at', now()->month)
//         ->whereYear('created_at', now()->year)
//         ->whereHas('delivery', function ($q) {
//             $q->where('delivery_status', 'delivered');
//         })
//         ->sum('total_discount_value');


//     // ===============================
//     // MONTHLY DATA (FOR CHARTS)
//     // ===============================

//     $orders = Order::with(['items.product', 'user', 'delivery'])
//         ->whereHas('delivery', function ($q) {
//             $q->where('delivery_status', 'delivered');
//         })
//         ->get();

//     $monthlyTotalPrices = [];
//     $monthlyProductTotalPrices = [];

//     foreach ($orders as $order) {
//         $month = $order->created_at->format('Y-m');

//         $monthlyTotalPrices[$month] = $monthlyTotalPrices[$month] ?? 0;
//         $monthlyProductTotalPrices[$month] = $monthlyProductTotalPrices[$month] ?? 0;

//         // ✅ FINAL SALES VALUE
//         $totalPrice = $order->total_discount_value;

//         // Product cost calculation
//         $productPrice = 0;

//         foreach ($order->items as $item) {
//             if ($item->product) {
//                 $productPrice += $item->product->cost_per_item * $item->quantity;
//             }
//         }

//         $productPrice = $productPrice - $order->product_discout + $order->cgst_sgst;

//         $monthlyTotalPrices[$month] += $totalPrice;
//         $monthlyProductTotalPrices[$month] += $productPrice;
//     }


//     // ===============================
//     // ENQUIRY STATUS
//     // ===============================

//     $responses = Enquiry::select('status', DB::raw('COUNT(*) as total'))
//         ->groupBy('status')
//         ->get();

//     $deliveryresponses = DeliveryManagement::select('delivery_status', DB::raw('COUNT(*) as total'))
//         ->groupBy('delivery_status')
//         ->get();

//     $orderReport = Order::select(
//             DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
//             DB::raw('COUNT(*) as total')
//         )
//         ->groupBy('month')
//         ->get();

//     $orderdata = [];
//     foreach ($orderReport as $report) {
//         $orderdata[$report->month] = $report->total;
//     }

//     $deliverydata = [];
//     foreach ($deliveryresponses as $deliveryresponse) {
//         $deliverydata[$deliveryresponse->delivery_status] = $deliveryresponse->total;
//     }


//     // ===============================
//     // RESPONSE SUMMARY
//     // ===============================

//     $respondedStatuses = ['accept', 'reject', 'reoffer'];
//     $notRespondedStatuses = ['submitted'];
//     $pendingSubmittedStatuses = ['pending'];

//     $respondedTotal = 0;
//     $notRespondedTotal = 0;
//     $pendingSubmittedTotal = 0;

//     $data = [];

//     foreach ($responses as $response) {
//         $data[$response->status] = $response->total;

//         if (in_array($response->status, $respondedStatuses)) {
//             $respondedTotal += $response->total;
//         } elseif (in_array($response->status, $notRespondedStatuses)) {
//             $notRespondedTotal += $response->total;
//         } elseif (in_array($response->status, $pendingSubmittedStatuses)) {
//             $pendingSubmittedTotal += $response->total;
//         }
//     }

//     $responsedata = [
//         'responded' => $respondedTotal,
//         'not_responded' => $notRespondedTotal,
//         'pending_submitted' => $pendingSubmittedTotal,
//     ];


//     // ===============================
//     // RETURN VIEW
//     // ===============================

//     if (auth('admin')->check()) {
//         return view('admin.pages.dashboardnew', compact(
//             'data',
//             'responsedata',
//             'deliverydata',
//             'orderdata',
//             'monthlyTotalPrices',
//             'monthlyProductTotalPrices',
//             'salesToday',
//             'salesThisMonth'
//         ));
//     } else {
//         return view('admin.login.login');
//     }
// }

// Dashboard link with sale order page of Today, Privious and Month:

public function todaySales()
{
        $orders = Order::select('orders.*')
        ->join('delivery_management as dm', 'dm.order_id', '=', 'orders.id')
        ->whereDate('orders.created_at', today())
        ->where('dm.delivery_status', 'Delivered')
        ->distinct()
        ->latest('orders.created_at')
        ->get();

    $users = User::all();

    $notifications = UserNotification::where('is_read', 0)
        ->get()
        ->keyBy(function ($item) {
            return $item->user_id . '_' . $item->click_url;
        });

    return view('admin.order.today', compact(
        'orders',
        'users',
        'notifications'
    ));

}

public function previousDaySales()
{
    $orders = Order::select('orders.*')
        ->join('delivery_management as dm', 'dm.order_id', '=', 'orders.id')
        ->whereDate('orders.created_at', today()->subDay())
        ->where('dm.delivery_status', 'Delivered')
        ->distinct()
        ->latest('orders.created_at')
        ->get();

    $users = User::all();

    $notifications = UserNotification::where('is_read', 0)
        ->get()
        ->keyBy(function ($item) {
            return $item->user_id . '_' . $item->click_url;
        });

    return view('admin.order.previous', compact(
        'orders',
        'users',
        'notifications'
    ));
}


public function thisMonthSales()
{
    $orders = Order::select('orders.*')
        ->join('delivery_management as dm', 'dm.order_id', '=', 'orders.id')
        ->whereMonth('orders.created_at', now()->month)
        ->whereYear('orders.created_at', now()->year)
        ->where('dm.delivery_status', 'Delivered')
        ->distinct()
        ->latest('orders.created_at')
        ->get();

    $users = User::all();

    $notifications = UserNotification::where('is_read', 0)
        ->get()
        ->keyBy(function ($item) {
            return $item->user_id . '_' . $item->click_url;
        });

    return view('admin.order.month', compact(
        'orders',
        'users',
        'notifications'
    ));
}  


public function dashboard(Request $request)
{
    
    $admin = auth('admin')->user();

    if ($admin && $admin->role_id == 1) {
        $allowedSections = DB::table('dashboard_sections')->pluck('key')->toArray();
    } elseif ($admin) {
        $allowedSections = DB::table('role_dashboard_sections')
            ->join('dashboard_sections', 'dashboard_sections.id', '=', 'role_dashboard_sections.dashboard_section_id')
            ->where('role_dashboard_sections.role_id', $admin->role_id)
            ->pluck('dashboard_sections.key')
            ->toArray();
    } else {
        $allowedSections = [];
    }
    
    
    $todayStart = Carbon::today()->startOfDay();
    $todayEnd   = Carbon::today()->endOfDay();
    $monthStart = Carbon::now()->startOfMonth();
    $monthEnd   = Carbon::now()->endOfMonth();

    // ===============================
    // SALES
    // ===============================

    $salesToday = Order::whereBetween('created_at', [$todayStart, $todayEnd])
        ->whereHas('delivery', function ($q) {
            $q->where('delivery_status', 'delivered');
        })
        ->sum('total_discount_value');

    $salesThisMonth = Order::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->whereHas('delivery', function ($q) {
            $q->where('delivery_status', 'delivered');
        })
        ->sum('total_discount_value');

    // ===============================
    // MONTHLY DATA (FOR CHARTS)
    // ===============================

    $orders = Order::with(['items.product', 'user', 'delivery'])
        ->whereHas('delivery', function ($q) {
            $q->where('delivery_status', 'delivered');
        })
        ->get();

    $monthlyTotalPrices = [];
    $monthlyProductTotalPrices = [];

    foreach ($orders as $order) {
        $month = $order->created_at->format('Y-m');

        $monthlyTotalPrices[$month] = $monthlyTotalPrices[$month] ?? 0;
        $monthlyProductTotalPrices[$month] = $monthlyProductTotalPrices[$month] ?? 0;

        $totalPrice = $order->total_discount_value;

        $productPrice = 0;
        foreach ($order->items as $item) {
            if ($item->product) {
                $productPrice += $item->product->cost_per_item * $item->quantity;
            }
        }

        $productPrice = $productPrice - $order->product_discout + $order->cgst_sgst;

        $monthlyTotalPrices[$month] += $totalPrice;
        $monthlyProductTotalPrices[$month] += $productPrice;
    }

    // ===============================
    // ORDER REPORT (FOR LINE CHART)
    // ===============================

    $orderReport = Order::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('month')
        ->get();

    $orderdata = [];
    foreach ($orderReport as $report) {
        $orderdata[$report->month] = $report->total;
    }

    // ===============================
    // SECTION 1 — PRODUCTS
    // ===============================

    $productsCount          = DB::table('products')->count();
    $activeProductsCount    = DB::table('products')->where('status', 'active')->count();
    $inactiveProductsCount  = DB::table('products')->where('status', 'inactive')->count();

    // ===============================
    // SECTION 2 — FRONT END
    // ===============================

    $enquiriesReceivedCount = Enquiry::where('status', 'pending')
        ->select('enquiry_no')
        ->distinct()
        ->count('enquiry_no');

    $enquiriesSubmittedCount = Enquiry::where('status', 'submitted')
        ->select('enquiry_no')
        ->distinct()
        ->count('enquiry_no');

    $totalItemsQuotedCount   = Enquiry::where('status', 'pending')->count();
    
    $customerResponseReceivedCount = DB::table('enquiries')
    ->whereIn('status', ['pending', 'rejected'])
    ->where('offer_check', '=', 1)
    ->distinct()
    ->count('enquiry_no');
    
    
    $totalItemsApprovedCount = Enquiry::where('status', 'accept')->count();
    $totalItemsRejectedCount = Enquiry::where('status', 'rejected')->count();

    $totalActiveOutletsCount = DB::table('users')
        ->where('type', 'outlet')
        ->where('verified_status', 'verified')
        ->count();
 
    $totalInactiveOutletsCount = DB::table('users')
    ->where('type', 'outlet')
    ->where('verified_status', '!=', 'verified') 
    ->count();

    $productsOnSaleCount = DB::table('rack_stocks')
        ->where('is_on_sale', true)
        ->where('quantity', '>', 0)
        ->whereRaw('DATEDIFF(expiry_date, CURDATE()) >= 0')
        ->count();

    // ===============================
    // SECTION 3 — BACK END
    // ===============================

    $salesOrderPendingCount = DB::table('orders')
        ->join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
        ->where('delivery_management.delivery_status', 'pending')
        ->where('orders.created_at', '<=', now()->subDays(3))
        ->count();

    // ===============================
    // SECTION — INVENTORY
    // ===============================

    // 1. No. of Products Expired
    $expiredProductsCount = DB::table('rack_stocks')
        ->whereNotNull('expiry_date')
        ->where('quantity', '>', 0)
        ->whereRaw('expiry_date < CURDATE()')
        ->count();

    // 2. No. of Products Near To Expiry (0-60 days, not on sale)
    $nearExpiryProductsCount = DB::table('rack_stocks')
        ->whereNotNull('expiry_date')
        ->where('quantity', '>', 0)
        ->where('is_on_sale', false)
        ->whereRaw('DATEDIFF(expiry_date, CURDATE()) BETWEEN 0 AND 60')
        ->count();

    // 3. No. of Products Non Moving (30+ days since GRN, no sale in 30+ days)
    $nonMovingProductsCount = 0;

    $cutoffDate = Carbon::now()->subDays(31);

    $rackStocksForNonMoving = DB::table('rack_stocks')
        ->join('stock_receivings', 'stock_receivings.id', '=', 'rack_stocks.stock_receiving_id')
        ->whereIn('stock_receivings.status', ['approved', 'approved_with_changes'])
        ->where('rack_stocks.quantity', '>', 0)
        ->select('rack_stocks.product_id', 'stock_receivings.receipt_date')
        ->get();

    $eligibleForNonMoving = $rackStocksForNonMoving->filter(function ($rs) use ($cutoffDate) {
        return $rs->receipt_date && Carbon::parse($rs->receipt_date)->lte($cutoffDate);
    });

    if ($eligibleForNonMoving->isNotEmpty()) {

        $productIdsForNonMoving = $eligibleForNonMoving->pluck('product_id')->unique()->values();

        $lastSaleDatesForNonMoving = DB::table('stock_movements')
            ->whereIn('product_id', $productIdsForNonMoving)
            ->where('movement_type', 'OUT')
            ->where('reference_type', 'ORDER')
            ->select('product_id', DB::raw('MAX(created_at) as last_sale_date'))
            ->groupBy('product_id')
            ->pluck('last_sale_date', 'product_id');

        foreach ($eligibleForNonMoving as $rs) {

            $lastSale = $lastSaleDatesForNonMoving[$rs->product_id] ?? null;

            $daysSinceLastSale = $lastSale
                ? Carbon::parse($lastSale)->diffInDays(Carbon::now())
                : Carbon::parse($rs->receipt_date)->diffInDays(Carbon::now());

            $isNonRunning = !$lastSale || $daysSinceLastSale > 30;

            if ($isNonRunning) {
                $nonMovingProductsCount++;
            }
        }
    }
    
    // 4. Total Value Stock in Hand
        $totalStockValue = DB::table('rack_stocks')
            ->join('stock_receivings', 'stock_receivings.id', '=', 'rack_stocks.stock_receiving_id')
            ->join('products', 'products.id', '=', 'rack_stocks.product_id')
            ->whereIn('stock_receivings.status', ['approved', 'approved_with_changes'])
            ->where('rack_stocks.quantity', '>', 0)
            ->selectRaw('SUM(rack_stocks.quantity * products.cost_per_item) as total_value')
            ->value('total_value');
        
        $totalStockValue = $totalStockValue ?? 0;

    // ===============================
    // SECTION 4 — ORDER PROCESS (Today & This Month)
    // ===============================

    $pendingAcceptanceToday = DB::table('orders')
        ->join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
        ->where('delivery_management.delivery_status', 'pending')
        ->whereBetween('orders.created_at', [$todayStart, $todayEnd])
        ->count();

    $pendingAcceptanceMonth = DB::table('orders')
        ->join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
        ->where('delivery_management.delivery_status', 'pending')
        ->whereBetween('orders.created_at', [$monthStart, $monthEnd])
        ->count();

    $pickListCreatedToday = DB::table('pick_lists')
        ->whereBetween('created_at', [$todayStart, $todayEnd])
        ->distinct('order_id')
        ->count('order_id');

    $pickListCreatedMonth = DB::table('pick_lists')
        ->whereBetween('created_at', [$monthStart, $monthEnd])
        ->distinct('order_id')
        ->count('order_id');

    $markedPickedToday = DB::table('pick_lists')
        ->where('status', 'PICKED')
        ->whereBetween('updated_at', [$todayStart, $todayEnd])
        ->distinct('order_id')
        ->count('order_id');

    $markedPickedMonth = DB::table('pick_lists')
        ->where('status', 'PICKED')
        ->whereBetween('updated_at', [$monthStart, $monthEnd])
        ->distinct('order_id')
        ->count('order_id');

    $deliveryStatusCount = function ($status, $start, $end) {
        return DB::table('delivery_management')
            ->where('delivery_status', $status)
            ->whereBetween('updated_at', [$start, $end])
            ->count();
    };

    $acceptedInProgressToday = $deliveryStatusCount('in_progress', $todayStart, $todayEnd);
    $acceptedInProgressMonth = $deliveryStatusCount('in_progress', $monthStart, $monthEnd);

    $readyForDispatchToday = $deliveryStatusCount('ready_for_dispatch', $todayStart, $todayEnd);
    $readyForDispatchMonth = $deliveryStatusCount('ready_for_dispatch', $monthStart, $monthEnd);

    $finalCheckDoneToday = $deliveryStatusCount('final_check_done', $todayStart, $todayEnd);
    $finalCheckDoneMonth = $deliveryStatusCount('final_check_done', $monthStart, $monthEnd);

    $dispatchedToday = $deliveryStatusCount('dispatched', $todayStart, $todayEnd);
    $dispatchedMonth = $deliveryStatusCount('dispatched', $monthStart, $monthEnd);

    $deliveredToday = $deliveryStatusCount('delivered', $todayStart, $todayEnd);
    $deliveredMonth = $deliveryStatusCount('delivered', $monthStart, $monthEnd);

    $cancelledToday = $deliveryStatusCount('cancelled', $todayStart, $todayEnd);
    $cancelledMonth = $deliveryStatusCount('cancelled', $monthStart, $monthEnd);

    $preShortLogToday = DB::table('pre_material_short_logs')
    ->join('orders', 'orders.id', '=', 'pre_material_short_logs.order_id')
    ->join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
    ->where('delivery_management.delivery_status', 'pending')
    ->whereBetween('pre_material_short_logs.created_at', [$todayStart, $todayEnd])
    ->count();

$preShortLogMonth = DB::table('pre_material_short_logs')
    ->join('orders', 'orders.id', '=', 'pre_material_short_logs.order_id')
    ->join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
    ->where('delivery_management.delivery_status', 'pending')
    ->whereBetween('pre_material_short_logs.created_at', [$monthStart, $monthEnd])
    ->count();

    $postShortLogToday = DB::table('post_material_short_logs')
        ->whereBetween('created_at', [$todayStart, $todayEnd])
        ->count();

    $postShortLogMonth = DB::table('post_material_short_logs')
        ->whereBetween('created_at', [$monthStart, $monthEnd])
        ->count();
        
        
    $reorderReport = $this->buildReorderReport();
    
    $carefulCount  = collect($reorderReport)->where('status', 'CAREFUL')->count();
    $watchCount    = collect($reorderReport)->where('status', 'WATCH')->count();
    $reorderCount  = collect($reorderReport)->where('status', 'REORDER')->count();
    $criticalCount = collect($reorderReport)->where('status', 'CRITICAL')->count();
    
    

    $overdueSummary = $this->buildOverdueSummary();

    $overdueCustomerCount    = $overdueSummary['overdue_customer_count'];
    $overdueTotalAmount      = $overdueSummary['overdue_total_amount'];

    $notOverdueCustomerCount = $overdueSummary['not_overdue_customer_count'];
    $notOverdueTotalAmount   = $overdueSummary['not_overdue_total_amount'];

    $dueSoonCustomerCount    = $overdueSummary['due_soon_customer_count'];
    $dueSoonTotalAmount      = $overdueSummary['due_soon_total_amount'];
    
    $overdue0to30Count       = $overdueSummary['overdue_0_30_count'];
    $overdue0to30Amount      = $overdueSummary['overdue_0_30_amount'];

    $overdue30to60Count      = $overdueSummary['overdue_30_60_count'];
    $overdue30to60Amount     = $overdueSummary['overdue_30_60_amount'];

    $overdue60to90Count      = $overdueSummary['overdue_60_90_count'];
    $overdue60to90Amount     = $overdueSummary['overdue_60_90_amount'];

    $overdueOver90Count      = $overdueSummary['overdue_over_90_count'];
    $overdueOver90Amount     = $overdueSummary['overdue_over_90_amount'];
    
    $dueSoon7CustomerCount = $overdueSummary['due_soon_7_customer_count'];
    $dueSoon7TotalAmount   = $overdueSummary['due_soon_7_total_amount'];
    
     // ===============================
    // SECTION — SALES (Today, Previous Day, This Month, Financial Year Till Date)
    // ===============================

    $yesterdayStart = Carbon::yesterday()->startOfDay();
    $yesterdayEnd   = Carbon::yesterday()->endOfDay();

    // Financial Year: April 1 to March 31 (India). If current month is Jan-Mar,
    // FY started April 1 of LAST year; otherwise FY started April 1 THIS year.
    $fyStartYear = (Carbon::now()->month >= 4) ? Carbon::now()->year : Carbon::now()->year - 1;
    $fyStart     = Carbon::create($fyStartYear, 4, 1)->startOfDay();
    $fyEnd       = Carbon::now()->endOfDay();

    $salesQuery = function ($start, $end) {
        return Order::join('delivery_management', 'delivery_management.order_id', '=', 'orders.id')
            ->where('delivery_management.delivery_status', 'delivered')
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw('COUNT(orders.id) as order_count, COALESCE(SUM(orders.total_discount_value), 0) as total_amount')
            ->first();
    };

    $salesTodayData    = $salesQuery($todayStart, $todayEnd);
    $salesYesterdayData = $salesQuery($yesterdayStart, $yesterdayEnd);
    $salesMonthData     = $salesQuery($monthStart, $monthEnd);
    $salesFYData        = $salesQuery($fyStart, $fyEnd);

    $salesTodayCount     = $salesTodayData->order_count ?? 0;
    $salesTodayAmount    = $salesTodayData->total_amount ?? 0;

    $salesYesterdayCount  = $salesYesterdayData->order_count ?? 0;
    $salesYesterdayAmount = $salesYesterdayData->total_amount ?? 0;

    $salesMonthCount  = $salesMonthData->order_count ?? 0;
    $salesMonthAmount = $salesMonthData->total_amount ?? 0;

    $salesFYCount  = $salesFYData->order_count ?? 0;
    $salesFYAmount = $salesFYData->total_amount ?? 0;


            

    // ===============================
    // RETURN VIEW
    // ===============================

    if (auth('admin')->check()) {
        return view('admin.pages.dashboardnew', compact(
            'salesToday',
            'salesThisMonth',
            'orderdata',
            'monthlyTotalPrices',
            'monthlyProductTotalPrices',
            'allowedSections',

            'productsCount',
            'activeProductsCount',
            'inactiveProductsCount',
            
            'salesTodayCount', 'salesTodayAmount',
            'salesYesterdayCount', 'salesYesterdayAmount',
            'salesMonthCount', 'salesMonthAmount',
            'salesFYCount', 'salesFYAmount',
            
            'carefulCount',
            'watchCount',
            'reorderCount',
            'criticalCount',
            
            'overdueCustomerCount',
            'overdueTotalAmount',
            'notOverdueCustomerCount',
            'notOverdueTotalAmount',
            'dueSoonCustomerCount',
            'dueSoonTotalAmount',
            'overdue0to30Count',
            'overdue0to30Amount',
            'overdue30to60Count',
            'overdue30to60Amount',
            'overdue60to90Count',
            'overdue60to90Amount',
            'overdueOver90Count',
            'overdueOver90Amount',
            'dueSoon7CustomerCount',
            'dueSoon7TotalAmount',

            'enquiriesReceivedCount',
            'enquiriesSubmittedCount',
            'totalItemsQuotedCount',
            'customerResponseReceivedCount',
            'totalItemsApprovedCount',
            'totalItemsRejectedCount',
            'totalActiveOutletsCount',
            'productsOnSaleCount',

            'salesOrderPendingCount',

            'expiredProductsCount',
            'nearExpiryProductsCount',
            'nonMovingProductsCount',
            'totalStockValue',

            'pendingAcceptanceToday', 'pendingAcceptanceMonth',
            'pickListCreatedToday', 'pickListCreatedMonth',
            'markedPickedToday', 'markedPickedMonth',
            'acceptedInProgressToday', 'acceptedInProgressMonth',
            'readyForDispatchToday', 'readyForDispatchMonth',
            'finalCheckDoneToday', 'finalCheckDoneMonth',
            'dispatchedToday', 'dispatchedMonth',
            'deliveredToday', 'deliveredMonth',
            'cancelledToday', 'cancelledMonth',
            'preShortLogToday', 'preShortLogMonth',
            'postShortLogToday', 'postShortLogMonth'
        ));
    } else {
        return view('admin.login.login');
    }
}

    public function products()
    {
        return view('admin.pages.product');
    }


      public function logout(Request $request)
    {
  
    Auth::guard('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

       

        return redirect('adminnew')->with('success', 'Logout successfully.');
    }
  
    // public function logout()
    // {
    //     Session::forget('ADMIN_LOGIN');
    //     // Session::forget('ADMIN_ID');

    //     // return view('admin.login.login');

    //     return redirect('adminnew')->with('success', 'Logout successfully.');
    // }

    /**
     * Show the my users page.
     *
     * @return \Illuminate\Http\Response
     */
    // public func()
    // {
    //     return view('users');
    // }
    
    
//  private function buildOverdueDetails(string $type)
// {
//     $orders = Order::join('outstanding_statements', 'orders.id', '=', 'outstanding_statements.order_id')
//         ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
//         ->join('users', 'users.id', '=', 'orders.outlet_id')
//         ->whereIn('orders.payment_status', ['unpaid', 'partial'])
//         ->where('delivery_management.delivery_status', 'delivered')
//         ->select('orders.*', 'users.outlet_name', 'users.name as customer_name')
//         ->get();

//     $today = now()->startOfDay();

//     $outletData = [];

//     foreach ($orders as $order) {

//         $payment = Payment::where('order_id', $order->id)->first();

//         $totalAmount   = $order->total_discount_value;
//         $totalPaid     = $payment->total_paid ?? 0;
//         $balanceAmount = $totalAmount - $totalPaid;

//         if ($balanceAmount <= 0) {
//             continue;
//         }

//         $deliveryDate = Carbon::parse($order->delivery_date);

//         $paymentTerm = OutletPaymentTerm::where('user_id', $order->outlet_id)
//             ->where('is_active', 1)
//             ->first();

//         $hasNewPaymentTerm = $paymentTerm ? true : false;

//         $userData = User::where('id', $order->outlet_id)
//             ->select('due_days_limit')
//             ->first();

//         $due_days_limit = $userData->due_days_limit ?? 0;

//         $isOverdue = false;
//         $daysOverdue = 0;
//         $daysUntilDue = null;

//         /*
//         |----------------------------------
//         | PRIORITY 1: special_credit
//         |----------------------------------
//         */
//         if ($order->payment_method === 'special_credit') {

//             $dairyTerm = DairyPaymentTerm::where('user_id', $order->outlet_id)
//                 ->where('is_active', 1)
//                 ->first();

//             $customDueDays = ($dairyTerm && $dairyTerm->due_limit_days !== null)
//                 ? (int) $dairyTerm->due_limit_days
//                 : $due_days_limit;

//             $dueDate = $deliveryDate->copy()->addDays($customDueDays);

//             $daysDifference = $today->diffInDays($dueDate, false);

//             $isOverdue    = $daysDifference < 0;
//             $daysOverdue  = $isOverdue ? abs($daysDifference) : 0;
//             $daysUntilDue = $daysDifference;

//         /*
//         |----------------------------------
//         | PRIORITY 2: outlet payment term
//         |----------------------------------
//         */
//         } elseif ($hasNewPaymentTerm) {

//             $deliveryDateStart = $deliveryDate->copy()->startOfDay();
//             $dueDay = (int) $paymentTerm->days ?: 1;

//             $dueDate = $deliveryDateStart->copy()
//                 ->addMonthNoOverflow()
//                 ->day($dueDay)
//                 ->startOfDay();

//             $isOverdue    = $today->gt($dueDate);
//             $daysOverdue  = $isOverdue ? $today->diffInDays($dueDate) : 0;
//             $daysUntilDue = $isOverdue ? -$daysOverdue : $today->diffInDays($dueDate);

//         /*
//         |----------------------------------
//         | PRIORITY 3: normal credit — WITH +1 day grace, matches invoiceID() exactly
//         |----------------------------------
//         */
//         } else {

//             $dueDate = $deliveryDate->copy()->addDays($due_days_limit);

//             $daysDifference = $today->diffInDays($dueDate->copy()->addDay(), false);

//             $isOverdue    = $daysDifference < 0;
//             $daysOverdue  = $isOverdue ? abs($daysDifference) : 0;
//             $daysUntilDue = $daysDifference;
//         }

//         $isDueSoon = !$isOverdue && $daysUntilDue !== null && $daysUntilDue >= 0 && $daysUntilDue <= 3;

//         $outletId = $order->outlet_id;

//         if (!isset($outletData[$outletId])) {
//             $outletData[$outletId] = [
//                 'outlet_id'              => $outletId,
//                 'outlet_name'            => $order->outlet_name,
//                 'customer_name'          => $order->customer_name,

//                 'overdue_amount'         => 0,
//                 'not_overdue_amount'     => 0,
//                 'due_soon_amount'        => 0,
//                 'max_days_overdue'       => 0,

//                 'is_overdue'             => false,
//                 'is_not_overdue'         => false,
//                 'is_due_soon'            => false,

//                 'last_paid_invoice_date' => null,
//                 'last_paid_invoice_no'   => null,
//             ];
//         }

//         if ($isOverdue) {
//             $outletData[$outletId]['overdue_amount'] += $balanceAmount;
//             $outletData[$outletId]['max_days_overdue'] = max($outletData[$outletId]['max_days_overdue'], $daysOverdue);
//             $outletData[$outletId]['is_overdue'] = true;
//         } else {
//             $outletData[$outletId]['not_overdue_amount'] += $balanceAmount;
//             $outletData[$outletId]['is_not_overdue'] = true;

//             if ($isDueSoon) {
//                 $outletData[$outletId]['due_soon_amount'] += $balanceAmount;
//                 $outletData[$outletId]['is_due_soon'] = true;
//             }
//         }
//     }

//     $details = [];

//     foreach ($outletData as $outletId => $row) {

//         $days = $row['max_days_overdue'];

//         $belongsToBucket = match ($type) {
//             'overdue_till_date'     => $row['is_overdue'],
//             'not_overdue_till_date' => $row['is_not_overdue'],
//             'due_soon'              => $row['is_due_soon'],
//             'overdue_0_30'          => $row['is_overdue'] && $days <= 30,
//             'overdue_90_plus'       => $row['is_overdue'] && $days > 90,
//             'overdue_60_90'         => $row['is_overdue'] && $days > 60 && $days <= 90,
//             'overdue_30_60'         => $row['is_overdue'] && $days > 30 && $days <= 60,
//             default                 => false,
//         };

//         if (!$belongsToBucket) {
//             continue;
//         }

//         $value = match ($type) {
//             'overdue_till_date', 'overdue_0_30', 'overdue_90_plus', 'overdue_60_90', 'overdue_30_60' => $row['overdue_amount'],
//             'not_overdue_till_date' => $row['not_overdue_amount'],
//             'due_soon'              => $row['due_soon_amount'],
//             default                 => 0,
//         };

//         $details[] = [
//             'outlet_id'              => $row['outlet_id'],
//             'outlet_name'            => $row['outlet_name'],
//             'customer_name'          => $row['customer_name'],
//             'value'                  => $value,
//             'last_paid_invoice_date' => null,
//             'last_paid_invoice_no'   => null,
//             'max_days_overdue'       => $days,
//         ];
//     }

//     $outletIds = array_column($details, 'outlet_id');

//     if (!empty($outletIds)) {

//         $lastPaidInvoices = Payment::whereIn('payments.outlet_id', $outletIds)
//             ->where('payments.payment_status', 'paid')
//             ->join('orders', 'orders.id', '=', 'payments.order_id')
//             ->select(
//                 'payments.outlet_id',
//                 'orders.invoice_id',
//                 'orders.invoice_date',
//                 DB::raw('MAX(orders.invoice_date) as latest_invoice_date')
//             )
//             ->groupBy('payments.outlet_id', 'orders.invoice_id', 'orders.invoice_date')
//             ->orderByDesc('orders.invoice_date')
//             ->get()
//             ->groupBy('outlet_id')
//             ->map(fn ($rows) => $rows->first());

//         foreach ($details as &$row) {
//             $lastPaid = $lastPaidInvoices[$row['outlet_id']] ?? null;
//             if ($lastPaid) {
//                 $row['last_paid_invoice_date'] = $lastPaid->invoice_date;
//                 $row['last_paid_invoice_no']   = $lastPaid->invoice_id;
//             }
//         }
//         unset($row);
//     }

//     usort($details, fn($a, $b) => $b['value'] <=> $a['value']);

//     return $details;
// }


private function buildOverdueDetails(string $type)
{
    $orders = Order::join('outstanding_statements', 'orders.id', '=', 'outstanding_statements.order_id')
        ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
        ->join('users', 'users.id', '=', 'orders.outlet_id')
        ->whereIn('orders.payment_status', ['unpaid', 'partial'])
        ->where('delivery_management.delivery_status', 'delivered')
        ->select('orders.*', 'users.outlet_name', 'users.name as customer_name')
        ->get();

    $today = now()->startOfDay();
    $outletData = [];

    foreach ($orders as $order) {

        $payment = Payment::where('order_id', $order->id)->first();

        $totalAmount   = $order->total_discount_value;
        $totalPaid     = $payment->total_paid ?? 0;
        $balanceAmount = $totalAmount - $totalPaid;

        if ($balanceAmount <= 0) {
            continue;
        }

        $deliveryDate = Carbon::parse($order->delivery_date);

        $paymentTerm = OutletPaymentTerm::where('user_id', $order->outlet_id)
            ->where('is_active', 1)
            ->first();

        $hasNewPaymentTerm = $paymentTerm ? true : false;

        $userData = User::where('id', $order->outlet_id)
            ->select('due_days_limit')
            ->first();

        $due_days_limit = $userData->due_days_limit ?? 0;

        $dueDate = null;

        if ($order->payment_method === 'special_credit') {

            $dairyTerm = DairyPaymentTerm::where('user_id', $order->outlet_id)
                ->where('is_active', 1)
                ->first();

            $customDueDays = ($dairyTerm && $dairyTerm->due_limit_days !== null)
                ? (int) $dairyTerm->due_limit_days
                : $due_days_limit;

            $dueDate = $deliveryDate->copy()->addDays($customDueDays)->startOfDay();

        } elseif ($hasNewPaymentTerm) {

            $deliveryDateStart = $deliveryDate->copy()->startOfDay();
            $dueDay = (int) $paymentTerm->days ?: 1;

            $dueDate = $deliveryDateStart->copy()
                ->addMonthNoOverflow()
                ->day($dueDay)
                ->startOfDay();

        } else {
             $dueDate = $deliveryDate->copy()->addDays($due_days_limit)->addDay()->startOfDay();
        }

        // ===== Explicit directional comparison =====
        $isOverdue = $dueDate->lt($today) || $dueDate->eq($today);

        $daysOverdue = 0;
        $isDueSoon = false;
        $isDueSoon7 = false;

        if ($isOverdue) {
           
            $daysOverdue = $dueDate->diffInDays($today);
        } else {
            $daysUntilDueFuture = $today->diffInDays($dueDate);
            $isDueSoon = $daysUntilDueFuture <= 3;
            $isDueSoon7 = $daysUntilDueFuture <= 7;
        }

        $belongsToBucket = match ($type) {
            'overdue_till_date'     => $isOverdue,
            'not_overdue_till_date' => !$isOverdue,
            'due_soon'              => $isDueSoon,
            'due_soon_7'            => $isDueSoon7,
            'overdue_90_plus'       => $isOverdue && $daysOverdue > 90,
            'overdue_60_90'         => $isOverdue && $daysOverdue > 60 && $daysOverdue <= 90,
            'overdue_30_60'         => $isOverdue && $daysOverdue > 30 && $daysOverdue <= 60,
            default                 => false,
        };

        if (!$belongsToBucket) {
            continue;
        }

        $outletId = $order->outlet_id;

        if (!isset($outletData[$outletId])) {
            $outletData[$outletId] = [
                'outlet_id'              => $outletId,
                'outlet_name'            => $order->outlet_name,
                'customer_name'          => $order->customer_name,
                'value'                  => 0,
                'last_paid_invoice_date' => null,
                'last_paid_invoice_no'   => null,
                'max_days_overdue'       => 0,
            ];
        }

        $outletData[$outletId]['value'] += $balanceAmount;
        $outletData[$outletId]['max_days_overdue'] = max($outletData[$outletId]['max_days_overdue'], $daysOverdue);
    }

    $outletIds = array_keys($outletData);

    if (!empty($outletIds)) {

        $lastPaidInvoices = Payment::whereIn('payments.outlet_id', $outletIds)
            ->where('payments.payment_status', 'paid')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->select(
                'payments.outlet_id',
                'orders.invoice_id',
                'orders.invoice_date',
                DB::raw('MAX(orders.invoice_date) as latest_invoice_date')
            )
            ->groupBy('payments.outlet_id', 'orders.invoice_id', 'orders.invoice_date')
            ->orderByDesc('orders.invoice_date')
            ->get()
            ->groupBy('outlet_id')
            ->map(fn ($rows) => $rows->first());

        foreach ($outletData as $outletId => &$row) {
            $lastPaid = $lastPaidInvoices[$outletId] ?? null;
            if ($lastPaid) {
                $row['last_paid_invoice_date'] = $lastPaid->invoice_date;
                $row['last_paid_invoice_no']   = $lastPaid->invoice_id;
            }
        }
        unset($row);
    }

    $details = array_values($outletData);
    usort($details, fn($a, $b) => $b['value'] <=> $a['value']);

    return $details;
}

public function overdueDetailsReport($type)
{
    $validTypes = [
        'overdue_till_date',
        'not_overdue_till_date',
        'due_soon',
        'due_soon_7',
        'overdue_0_30',
        'overdue_90_plus',
        'overdue_60_90',
        'overdue_30_60',
    ];

    if (!in_array($type, $validTypes)) {
        abort(404);
    }

    $titles = [
        'overdue_till_date'     => 'Total Overdue Customers (Till Date)',
        'not_overdue_till_date' => 'Total Billed but Not Overdue (Till Date)',
        'due_soon'              => 'To Be Overdue After 3 Days',
        'due_soon_7'            => 'To Be Overdue in the Next 7 Days',
        'overdue_0_30'          => 'Total Overdue Customers 0 to 30 Days',
        'overdue_90_plus'       => 'Total Overdue Customers More Than 90 Days',
        'overdue_60_90'         => 'Total Overdue Customers 60 to 90 Days',
        'overdue_30_60'         => 'Total Overdue Customers 30 to 60 Days',
    ];

    $details = $this->buildOverdueDetails($type);
    $pageTitle = $titles[$type];
    $totalAmount = array_sum(array_column($details, 'value'));
    
    $followups = OverdueFollowup::whereIn('outlet_id', array_column($details, 'outlet_id'))
    ->get()
    ->keyBy('outlet_id');

    return view('admin.reports.overdue_details', compact('details', 'pageTitle', 'totalAmount', 'type', 'followups'));
}


public function saveOverdueFollowup(Request $request)
{
    $request->validate([
        'outlet_id' => 'required|integer',
        'payment_date_committed' => 'nullable|date',
        'followup_feedback' => 'nullable|string',
        'followup_date' => 'nullable|date',
    ]);

    OverdueFollowup::updateOrCreate(
        ['outlet_id' => $request->outlet_id],
        [
            'payment_date_committed' => $request->payment_date_committed,
            'followup_feedback' => $request->followup_feedback,
            'followup_date' => $request->followup_date,
        ]
    );

    return response()->json(['success' => true]);
}


public function overdueOutletDetail($id)
{
    $orderInvoice = Order::join('outstanding_statements', 'orders.id', '=', 'outstanding_statements.order_id')
        ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')
        ->where('orders.outlet_id', $id)
        ->whereIn('orders.payment_status', ['unpaid', 'partial'])
        ->where('delivery_management.delivery_status', 'delivered')
        ->orderBy('orders.created_at', 'asc')
        ->select('orders.*')
        ->get();

    $orderInvoice->transform(function ($order) {

        $payment = Payment::where('order_id', $order->id)->first();

        $order->total_amount   = $order->total_discount_value;
        $order->total_paid     = $payment->total_paid ?? 0;
        $order->balance_amount = $order->total_amount - $order->total_paid;

        if ($order->payment_method === 'special_credit') {

            $dairyTerm = DairyPaymentTerm::where('user_id', $order->outlet_id)
                ->where('is_active', 1)
                ->first();

            if ($dairyTerm && $dairyTerm->due_limit_days !== null) {
                $order->custom_due_days = (int) $dairyTerm->due_limit_days;
            }
        }

        return $order;
    });

    $userData = User::where('id', $id)
        ->select('credit_limit', 'location', 'mobile_number', 'due_days_limit', 'name', 'outlet_name', 'priority')
        ->first();

    $company_name1 = 'N/A';

    if ($userData && $userData->priority) {
        $company = User::where('id', $userData->priority)->select('outlet_name')->first();
        $company_name1 = $company->outlet_name ?? 'N/A';
    }

    $creditLimit    = $userData->credit_limit ?? 0;
    $location       = $userData->location ?? 'N/A';
    $mobileNumber   = $userData->mobile_number ?? 'N/A';
    $due_days_limit = $userData->due_days_limit ?? 0;

    $orderss = KYCDocument::where('user_id', $id)->get();

    $paymentTerm = OutletPaymentTerm::where('user_id', $id)
        ->where('is_active', 1)
        ->first();

    $hasNewPaymentTerm = $paymentTerm ? true : false;

    return view('admin.reports.overdue_outlet_detail', compact(
        'orderInvoice', 'orderss', 'creditLimit', 'location', 'mobileNumber',
        'due_days_limit', 'paymentTerm', 'hasNewPaymentTerm', 'company_name1'
    ));
}


private function buildExpiredProductsDetails()
{
    return DB::table('rack_stocks')
        ->join('products', 'products.id', '=', 'rack_stocks.product_id')
        ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
        ->whereNotNull('rack_stocks.expiry_date')
        ->where('rack_stocks.quantity', '>', 0)
        ->whereRaw('rack_stocks.expiry_date < CURDATE()')
        ->select(
            'products.product_name',
            'products.brands as brand',
            'categories.category_name as category',
            'rack_stocks.batch_no',
            'rack_stocks.quantity',
            'rack_stocks.expiry_date',
            'rack_stocks.rack_no', 'rack_stocks.level_no', 'rack_stocks.slot_no',
            DB::raw('DATEDIFF(CURDATE(), rack_stocks.expiry_date) as days_expired')
        )
        ->orderByDesc('days_expired')
        ->get();
}

private function buildNearExpiryDetails()
{
    return DB::table('rack_stocks')
        ->join('products', 'products.id', '=', 'rack_stocks.product_id')
        ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
        ->whereNotNull('rack_stocks.expiry_date')
        ->where('rack_stocks.quantity', '>', 0)
        ->where('rack_stocks.is_on_sale', false)
        ->whereRaw('DATEDIFF(rack_stocks.expiry_date, CURDATE()) BETWEEN 0 AND 60')
        ->select(
            'products.product_name',
            'products.brands as brand',
            'categories.category_name as category',
            'rack_stocks.batch_no',
            'rack_stocks.quantity',
            'rack_stocks.expiry_date',
            'rack_stocks.rack_no', 'rack_stocks.level_no', 'rack_stocks.slot_no',
            DB::raw('DATEDIFF(rack_stocks.expiry_date, CURDATE()) as days_to_expiry')
        )
        ->orderBy('days_to_expiry')
        ->get();
}

private function buildNonMovingDetails()
{
    $cutoffDate = Carbon::now()->subDays(31);

    $rackStocks = DB::table('rack_stocks')
        ->join('stock_receivings', 'stock_receivings.id', '=', 'rack_stocks.stock_receiving_id')
        ->join('products', 'products.id', '=', 'rack_stocks.product_id')
        ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
        ->whereIn('stock_receivings.status', ['approved', 'approved_with_changes'])
        ->where('rack_stocks.quantity', '>', 0)
        ->select(
            'rack_stocks.product_id',
            'products.product_name',
            'products.brands as brand',
            'categories.category_name as category',
            'rack_stocks.batch_no',
            'rack_stocks.quantity',
            'stock_receivings.receipt_date',
            'rack_stocks.rack_no', 'rack_stocks.level_no', 'rack_stocks.slot_no'
        )
        ->get();

    $eligible = $rackStocks->filter(function ($rs) use ($cutoffDate) {
        return $rs->receipt_date && Carbon::parse($rs->receipt_date)->lte($cutoffDate);
    });

    if ($eligible->isEmpty()) {
        return collect();
    }

    $productIds = $eligible->pluck('product_id')->unique()->values();

    $lastSaleDates = DB::table('stock_movements')
        ->whereIn('product_id', $productIds)
        ->where('movement_type', 'OUT')
        ->where('reference_type', 'ORDER')
        ->select('product_id', DB::raw('MAX(created_at) as last_sale_date'))
        ->groupBy('product_id')
        ->pluck('last_sale_date', 'product_id');

    $result = collect();

    foreach ($eligible as $rs) {
        $lastSale = $lastSaleDates[$rs->product_id] ?? null;

        $daysSinceLastSale = $lastSale
            ? Carbon::parse($lastSale)->diffInDays(Carbon::now())
            : Carbon::parse($rs->receipt_date)->diffInDays(Carbon::now());

        $isNonRunning = !$lastSale || $daysSinceLastSale > 30;

        if ($isNonRunning) {
            $rs->last_sale_date = $lastSale ? Carbon::parse($lastSale)->format('Y-m-d') : 'Never Sold';
            $rs->days_since_last_sale = $daysSinceLastSale;
            $result->push($rs);
        }
    }

    return $result->sortByDesc('days_since_last_sale')->values();
}

// public function inventoryDetailsReport($type)
// {
//     $validTypes = ['expired', 'near_expiry', 'non_moving', 'careful', 'watch', 'reorder', 'critical'];

//     if (!in_array($type, $validTypes)) {
//         abort(404);
//     }

//     $titles = [
//         'expired'     => 'Products Expired',
//         'near_expiry' => 'Products Near To Expiry',
//         'non_moving'  => 'Products Non Moving',
//         'careful'     => 'Products in Careful Status',
//         'watch'       => 'Products in Watch Status',
//         'reorder'     => 'Products in Reorder Status',
//         'critical'    => 'Products in Critical Status',
//     ];

//     $reportType = 'stock'; 

//     switch ($type) {
//         case 'expired':
//             $details = $this->buildExpiredProductsDetails();
//             break;
//         case 'near_expiry':
//             $details = $this->buildNearExpiryDetails();
//             break;
//         case 'non_moving':
//             $details = $this->buildNonMovingDetails();
//             break;
//         case 'careful':
//         case 'watch':
//         case 'reorder':
//         case 'critical':
//             $statusMap = ['careful' => 'CAREFUL', 'watch' => 'WATCH', 'reorder' => 'REORDER', 'critical' => 'CRITICAL'];
//             $reportType = 'reorder';
//             $details = collect($this->buildReorderReport())
//                 ->where('status', $statusMap[$type])
//                 ->values();
//             break;
//     }

//     $pageTitle = $titles[$type];

//     return view('admin.reports.inventory_details', compact('details', 'pageTitle', 'type', 'reportType'));
// }



public function inventoryDetailsReport($type)
{
    [$details, $pageTitle, $reportType] = $this->buildInventoryDetailsData($type);

    return view('admin.reports.inventory_details', compact('details', 'pageTitle', 'type', 'reportType'));
}

public function inventoryDetailsExport($type)
{
    [$details, $pageTitle, $reportType] = $this->buildInventoryDetailsData($type);

    $filename = 'inventory-' . $type . '-' . now()->format('Y-m-d') . '.xlsx';

    return \Maatwebsite\Excel\Facades\Excel::download(
        new \App\Exports\InventoryDetailsExport($details, $reportType, $pageTitle),
        $filename
    );
}

private function buildInventoryDetailsData($type)
{
    $validTypes = ['expired', 'near_expiry', 'non_moving', 'careful', 'watch', 'reorder', 'critical'];

    if (!in_array($type, $validTypes)) {
        abort(404);
    }

    $titles = [
        'expired'     => 'Products Expired',
        'near_expiry' => 'Products Near To Expiry',
        'non_moving'  => 'Products Non Moving',
        'careful'     => 'Products in Careful Status',
        'watch'       => 'Products in Watch Status',
        'reorder'     => 'Products in Reorder Status',
        'critical'    => 'Products in Critical Status',
    ];

    $reportType = 'stock';

    switch ($type) {
        case 'expired':
            $details = $this->buildExpiredProductsDetails();
            break;
        case 'near_expiry':
            $details = $this->buildNearExpiryDetails();
            break;
        case 'non_moving':
            $details = $this->buildNonMovingDetails();
            break;
        case 'careful':
        case 'watch':
        case 'reorder':
        case 'critical':
            $statusMap = ['careful' => 'CAREFUL', 'watch' => 'WATCH', 'reorder' => 'REORDER', 'critical' => 'CRITICAL'];
            $reportType = 'reorder';
            $details = collect($this->buildReorderReport())
                ->where('status', $statusMap[$type])
                ->values();
            break;
    }

    $pageTitle = $titles[$type];

    return [$details, $pageTitle, $reportType];
}



}
