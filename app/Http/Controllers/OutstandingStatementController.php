<?php

namespace App\Http\Controllers;
use App\Models\OutstandingStatement;
use Illuminate\Http\Request;

class OutstandingStatementController extends Controller
{

    public function index1()
    {
        $outstandingList = OutstandingStatement::
        select(
            'outstanding_statements.outlet_id',
            \DB::raw('SUM(outstanding_statements.total_due_amount) AS total_due_amount'),
            \DB::raw('COUNT(*) AS num_statements'),
            'users.name as user_name',
            \DB::raw('MAX(outstanding_statements.created_at) AS latest_created_at')
        )
        ->join('users', 'outstanding_statements.user_id', '=', 'users.id')
        ->groupBy('outstanding_statements.outlet_id', 'users.name')
        ->orderBy('latest_created_at', 'desc')
        ->where('outstanding_date', '<=', now())->get();


    // dd($outstandingList);


        return view('admin.outstanding_list.index', ['outstandingList' => $outstandingList]);
    }


    //comment on 27-04-26

    // public function index()
    // {
    //     $outstandingList = OutstandingStatement::
    //     select(
    //         'outstanding_statements.outlet_id',
    //         \DB::raw('SUM(outstanding_statements.total_due_amount) AS total_due_amount'),
    //         \DB::raw('COUNT(*) AS num_statements'),
    //         'users.name as user_name',
    //         'users.outlet_name',  // Assuming 'outlet_name' is the column name in the 'users' table
    //         'users.mobile_number', // Include mobile_number here
    //         \DB::raw('MAX(outstanding_statements.created_at) AS latest_created_at')
    //     )
    //     ->join('users', 'outstanding_statements.user_id', '=', 'users.id')
    //     ->groupBy(
    //         'outstanding_statements.outlet_id',
    //         'users.name',
    //         'users.outlet_name',
    //         'users.mobile_number' // Add mobile_number to groupBy
    //     )  
    //     ->orderBy('latest_created_at', 'desc')
    //     // ->where('outstanding_date', '<=', now())
    //     ->get();
    
    //     // dd($outstandingList);
    //     return view('admin.outstanding_list.index', ['outstandingList' => $outstandingList]);
    // }
    

//added on 27-04-26    
public function index()
{
    $paymentsSub = \DB::table('payments')
        ->select('order_id', \DB::raw('SUM(total_paid) as total_paid'))
        ->groupBy('order_id');

    $ordersCountSub = \DB::table('orders')
        ->select('outlet_id', \DB::raw('COUNT(*) as total_orders'))
        ->groupBy('outlet_id');

    $outstandingList = \DB::table('orders')
        ->join('users', 'orders.outlet_id', '=', 'users.id')
        ->join('delivery_management', 'orders.id', '=', 'delivery_management.order_id')

        ->leftJoinSub($paymentsSub, 'payments', function ($join) {
            $join->on('orders.id', '=', 'payments.order_id');
        })

        ->leftJoinSub($ordersCountSub, 'order_counts', function ($join) {
            $join->on('orders.outlet_id', '=', 'order_counts.outlet_id');
        })

        ->whereIn('orders.payment_status', ['unpaid', 'partial'])
        ->where('delivery_management.delivery_status', 'delivered')

        ->select(
            'orders.outlet_id',
            'users.name as user_name',
            'users.outlet_name',
            'users.mobile_number',

            \DB::raw('SUM(orders.total_discount_value - COALESCE(payments.total_paid, 0)) as total_due_amount'),

            \DB::raw('COUNT(orders.id) as num_statements'),

            \DB::raw('MAX(orders.created_at) as latest_created_at')
        )

        ->groupBy(
            'orders.outlet_id',
            'users.name',
            'users.outlet_name',
            'users.mobile_number'
        )
        ->orderByDesc('latest_created_at')
        ->get();

    $totalOutstanding = $outstandingList->sum('total_due_amount');

    return view('admin.outstanding_list.index', compact('outstandingList', 'totalOutstanding'));
}
    


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
