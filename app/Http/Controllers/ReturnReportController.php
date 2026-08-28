<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\StockReceivingItem;
use PDF;
use Illuminate\Support\Facades\DB;

class ReturnReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type;

        $query = StockMovement::with([
                'receiving.vendor',
                'receiving.vendorBill', 
                'product'
            ])
            ->whereIn('movement_type', ['RETURN', 'PENDING_RETURN'])
            ->whereNotNull('reference_id');

       
        if (!empty($type)) {
            $query->where('movement_type', $type);
        }

        $movements = $query->latest()->get();

        return view('admin.return_report.index', compact('movements', 'type'));
    }

   public function downloadSingle($id)
{
    // 1. Get movement with required relations
    $move = StockMovement::with([
        'receiving.vendor',
        'product'
    ])->findOrFail($id);

    // 2. सुरक्षा: Only allow RETURN
    if ($move->movement_type !== 'RETURN') {
        abort(404, 'Invalid movement type');
    }

    // 3. Get rate from StockReceivingItem (IMPORTANT 🔥)
    $rate = StockReceivingItem::where('stock_receiving_id', $move->reference_id)
        ->where('product_id', $move->product_id)
        ->value('purchase_rate') ?? 0;

    // 4. Attach rate to movement (so blade stays clean)
    $move->rate = $rate;

    // 5. Prepare note object
    $note = (object)[
        'debit_note_no' => 'DN-' . str_pad($move->id, 5, '0', STR_PAD_LEFT),
        'created_at' => now(),
        'vendor' => $move->receiving->vendor ?? null,
        'receiving' => $move->receiving ?? null,
        'items' => collect([$move]) // 👈 single item
    ];

    // dd($note);

    // 6. Load PDF view
    $pdf = PDF::loadView('admin.debitnote.single_pdf', compact('note'));

    // 7. Stream PDF
    return $pdf->stream('debit_note_' . $note->debit_note_no . '.pdf');
}
}
