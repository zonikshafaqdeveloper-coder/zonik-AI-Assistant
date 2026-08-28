<?php

namespace App\Http\Controllers;

use App\Models\CustomerPriceChangeLog;
use App\Models\CustomerPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PriceChangeLogController extends Controller
{
    public function index()
{
    $logs = CustomerPriceChangeLog::with('product')
        ->latest()
        ->get();

    return view('admin.price_logs.index', compact('logs'));
}

    /*
    |--------------------------------------------------------------------------
    | FLAT UPDATE (QUEUE BASED)
    |--------------------------------------------------------------------------
    */
    public function approveFlat($id)
    {
        $log = CustomerPriceChangeLog::find($id);

        if (!$log) {
      return response()->json([
        'message' => 'Log not found'
    ], 404);
    }

        dispatch(new \App\Jobs\ApplyCustomerPriceUpdateJob($log));

        $log->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'applied_type' => 'flat'
        ]);

        return response()->json([
            'message' => 'Flat update started successfully'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */
    public function reject($id)
    {
        $log = CustomerPriceChangeLog::findOrFail($id);

        $log->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

       return response()->json([
            'message' => 'Rejected successfully'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT INDIVIDUAL
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $log = CustomerPriceChangeLog::findOrFail($id);

        $customers = CustomerPrice::where('product_id', $log->product_id)
            ->with('outlet')
            ->get();

            //  ADD THIS LINE
              $newCost = $log->new_cost;

            // dd($customers);

        return view('admin.price_logs.edit', compact('log', 'customers','newCost'));
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE INDIVIDUAL CHANGES
    |--------------------------------------------------------------------------
    */
   public function updateIndividual(Request $request, $id)
{
    $log = CustomerPriceChangeLog::findOrFail($id);

     try {

        DB::transaction(function () use ($request, $log) {

            $prices = is_array($request->prices) ? $request->prices : [];
            $costs = is_array($request->costs) ? $request->costs : [];

            foreach ($prices as $rowId => $price) {

                $costPrice = $costs[$rowId] ?? 0;

                $margin = $costPrice > 0
                    ? (($price - $costPrice) / $costPrice) * 100
                    : 0;

                CustomerPrice::where('id', $rowId)->update([
                    'product_price' => $price,
                ]);
            }

            $log->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'applied_type' => 'manual'
            ]);

        });

        return response()->json([
            'message' => 'Prices updated successfully'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'message' => $e->getMessage()
        ], 500);
    }
}
}