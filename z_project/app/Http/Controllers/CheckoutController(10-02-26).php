<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use App\Models\Pincode;
use App\Models\OutstandingStatement;

use App\Models\Holiday;
use App\Models\ZoneProcessing;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    
    
    function slotDateLabel(Carbon $date)

{
    if ($date->isToday()) {
        return 'Today';
    }

    if ($date->isTomorrow()) {
        return 'Tomorrow';
    }

    return $date->format('jS M y');
}


    public function index(Request $request, $id)
{
    if (!Auth::check()) {
        return redirect()->route('homepage')->with('error', 'You are not logged in. Please log in to continue.');
    }

    $user = Auth::user();
    // dd($user);
    $datewisecheckout = $request->query('datewisecheckout');

    $query = Cart::with('product')->where('user_id', $user->id);
    $holidays = Holiday::all();

    if ($datewisecheckout == 'today') {
        $query->whereDate('created_at', Carbon::today());
    } elseif ($datewisecheckout == 'yesterday') {
        $query->whereDate('created_at', Carbon::yesterday());
    } elseif ($datewisecheckout == 'p7days') {
        $query->whereDate('created_at', '>=', Carbon::now()->subDays(7));
    }

    $cart = $query->latest()->get();
    $coupn = Cart::where('user_id', $user->id)->orderBy('id', 'asc')->get();

    if ($cart->isEmpty()) {
        return redirect()->route('homepage')->with('error', 'The requested cart is empty. Please add items to continue.');
    }

    $outletID = $id;
    $outstandingData = OutstandingStatement::where('user_id', $id)->get();
    $totalDueAmount = $outstandingData->sum('total_due_amount');

    $outletData = User::find($id);
    // dd($outletData);
    if (!$outletData) {
        abort(404, 'User not found');
    }

    $billingAddress = '';
    $shippingAddress = '';
    $mainshippingAddress = '';
    $mainshippingPincode = '';

    foreach ($outletData->kycdocuments as $kycDocument) {
        if ($kycDocument->user_id == $outletData->id) {
            $billingAddress = $kycDocument->billing_address . ' - ' . $kycDocument->billing_pincode;
            $shippingAddress = $kycDocument->outlet_address . ' - ' . $kycDocument->outlet_pincode;
            $mainshippingAddress = $kycDocument->outlet_address;
            // dd($mainshippingAddress);
            $mainshippingPincode = $kycDocument->outlet_pincode;
            break;
        }
    }

    $pincodeData = Pincode::where('pincode', $mainshippingPincode)->first();
    $zone_id = $pincodeData ? $pincodeData->zone_id : null;

  $deliveryOptions = [];
  
 $delivery_time = 0;
$bulkDeliverycharges = 0;
$singleDeliverycharges = 0;
$packingcharges = 0;
$otherscharges = 0;
$zoneProcessingData = null;


    if ($zone_id) {
        $zoneProcessingData = ZoneProcessing::where('id', $zone_id)->first();
        
        // dd($zoneProcessingData);
        if (!$zoneProcessingData || $zoneProcessingData->status != 'Active') {
            session()->flash('not_servicable', 'Service not available on this location.');
            // return redirect()->back();
        }

$deliveryOptions = [];

$now              = Carbon::now();
$today            = Carbon::today();
$tomorrow         = Carbon::tomorrow();

$morningCutoff    = Carbon::parse($zoneProcessingData->same_day_timing);      
$afternoonCutoff  = Carbon::parse($zoneProcessingData->next_day_timing);      

$slot1Time = $zoneProcessingData->next_day_slot;   
$slot2Time = $zoneProcessingData->same_day_slot; 
$weekDaySlot = $zoneProcessingData->week_day_slot;


// ----------------------------------------------------
//               APPLY NEW DELIVERY LOGIC
// ----------------------------------------------------
if ($now->lt($morningCutoff)) {

    // CASE 1: Before morning cutoff → Both same day
    $deliveryOptions[] = [
        'date' => $today->toDateString(),
        'slot' => "Slot 1 : " . $this->slotDateLabel($today) . " - {$slot1Time}",
        'time_only' => $slot1Time
    ];

    $deliveryOptions[] = [
        'date' => $today->toDateString(),
        'slot' => "Slot 2 : " . $this->slotDateLabel($today) . " - {$slot2Time}",
        'time_only' => $slot2Time
    ];

    $slot1Date = $today->copy();

}
elseif ($now->gte($morningCutoff) && $now->lt($afternoonCutoff)) {

    // CASE 2: After morning cutoff but before afternoon cutoff
    // Slot 1 → tomorrow
    $deliveryOptions[] = [
        'date' => $tomorrow->toDateString(),
        'slot' => "Slot 1 : " . $this->slotDateLabel($tomorrow) . " - {$slot1Time}",
        'time_only' => $slot1Time
    ];

    // Slot 2 → today
    $deliveryOptions[] = [
        'date' => $today->toDateString(),
        'slot' => "Slot 2 : " . $this->slotDateLabel($today) . " - {$slot2Time}",
        'time_only' => $slot2Time
    ];

    $slot1Date = $tomorrow->copy();
}
else {

    // CASE 3: After afternoon cutoff → All next day
    $deliveryOptions[] = [
        'date' => $tomorrow->toDateString(),
        'slot' => "Slot 1 : " . $this->slotDateLabel($tomorrow) . " - {$slot1Time}",
        'time_only' => $slot1Time
    ];

    $deliveryOptions[] = [
        'date' => $tomorrow->toDateString(),
        'slot' => "Slot 2 : " . $this->slotDateLabel($tomorrow) . " - {$slot2Time}",
        'time_only' => $slot2Time
    ];

    $slot1Date = $tomorrow->copy();
}


// ----------------------------------------------------
//                REST OF THE WEEK SLOTS
// ----------------------------------------------------

$daysPrinted = 0;
$startDate   = $slot1Date->copy()->addDay();

while ($daysPrinted < 7) {

    $date = $startDate->toDateString();
    $isHoliday = $holidays->contains('holiday_date', $date);

    if (!$isHoliday) {
        $deliveryOptions[] = [
            'date' => $date,
            'slot' => Carbon::parse($date)->format('jS M y') . " - {$weekDaySlot}",
            'time_only' => $weekDaySlot
        ];
        $daysPrinted++;
    }

    $startDate->addDay();
}





        $bulkDeliverycharges = $zoneProcessingData->bulk_delivery_charges;
        $singleDeliverycharges = $zoneProcessingData->single_delivery_charges;
        $packingcharges = $zoneProcessingData->packing_charge;
        $otherscharges = $zoneProcessingData->others_charges;
    } else {
        session()->flash('not_servicable', 'Service not available on this location.');
        // return redirect()->back();
    }

    return view('web.checkout', compact(
        'totalDueAmount',
        'outletID',
        'holidays',
        'outletData',
        'packingcharges',
        'otherscharges',
        'cart',
        'datewisecheckout',
        'billingAddress',
        'shippingAddress',
        'mainshippingAddress',
        'mainshippingPincode',
        'bulkDeliverycharges',
        'singleDeliverycharges',
        'deliveryOptions',
        'zoneProcessingData',
        'coupn'
    ));
}




}
