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

    if (!$zoneProcessingData || $zoneProcessingData->status != 'Active') {
        session()->flash('not_servicable', 'Service not available on this location.');
    }

    $deliveryOptions = [];

    /*
    |--------------------------------------------------------------------------
    | REGULAR DAYS LOGIC
    |--------------------------------------------------------------------------
    */

    if ($zoneProcessingData->regular_days) {

        $weekDaySlot = $zoneProcessingData->week_day_slot;
        $deliveryDays = $zoneProcessingData->delivery_days;

        $dayMap = [
            'sunday'    => 0,
            'monday'    => 1,
            'tuesday'   => 2,
            'wednesday' => 3,
            'thursday'  => 4,
            'friday'    => 5,
            'saturday'  => 6,
        ];

        $tomorrow = Carbon::tomorrow();
        $daysPrinted = 0;
        $startDate = $tomorrow->copy();

        if (!empty($deliveryDays)) {

            $allowedDayNumbers = array_map(
                fn($d) => $dayMap[strtolower($d)],
                $deliveryDays
            );

            while ($daysPrinted < count($deliveryDays)) {

                $date = $startDate->toDateString();
                $isHoliday = $holidays->contains('holiday_date', $date);
                $dayOfWeek = $startDate->dayOfWeek;

                if (!$isHoliday && in_array($dayOfWeek, $allowedDayNumbers)) {

                    $deliveryOptions[] = [
                        'date' => $date,
                        'slot' => Carbon::parse($date)->format('jS M y')
                                    . " - {$weekDaySlot} ("
                                    . Carbon::parse($date)->format('l')
                                    . ")",
                        'time_only' => $weekDaySlot
                    ];

                    $daysPrinted++;
                }

                $startDate->addDay();

                if ($startDate->diffInDays($tomorrow) > 14) {
                    break;
                }
            }

        } else {

            $startDate = Carbon::today();

            while ($daysPrinted < 7) {

                $date = $startDate->toDateString();
                $isHoliday = $holidays->contains('holiday_date', $date);

                if (!$isHoliday) {

                    $deliveryOptions[] = [
                        'date' => $date,
                        'slot' => Carbon::parse($date)->format('jS M y')
                                    . " - {$weekDaySlot} ("
                                    . Carbon::parse($date)->format('l')
                                    . ")",
                        'time_only' => $weekDaySlot
                    ];

                    $daysPrinted++;
                }

                $startDate->addDay();
            }
        }

    } else {

        /*
        |--------------------------------------------------------------------------
        | SLOT 1 / SLOT 2 LOGIC
        |--------------------------------------------------------------------------
        */

        $now = Carbon::now();
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $morningCutoff = Carbon::parse($zoneProcessingData->same_day_timing);
        $afternoonCutoff = Carbon::parse($zoneProcessingData->next_day_timing);

        $slot1Time = $zoneProcessingData->next_day_slot;
        $slot2Time = $zoneProcessingData->same_day_slot;
        $weekDaySlot = $zoneProcessingData->week_day_slot;

        if ($now->lt($morningCutoff)) {

            $deliveryOptions[] = [
                'date' => $today->toDateString(),
                'slot' => "Slot 1 : "
                            . $this->slotDateLabel($today)
                            . " - {$slot1Time}",
                'time_only' => $slot1Time
            ];

            $deliveryOptions[] = [
                'date' => $today->toDateString(),
                'slot' => "Slot 2 : "
                            . $this->slotDateLabel($today)
                            . " - {$slot2Time}",
                'time_only' => $slot2Time
            ];

            $slot1Date = $today->copy();

        } elseif (
            $now->gte($morningCutoff)
            && $now->lt($afternoonCutoff)
        ) {

            $deliveryOptions[] = [
                'date' => $tomorrow->toDateString(),
                'slot' => "Slot 1 : "
                            . $this->slotDateLabel($tomorrow)
                            . " - {$slot1Time}",
                'time_only' => $slot1Time
            ];

            $deliveryOptions[] = [
                'date' => $today->toDateString(),
                'slot' => "Slot 2 : "
                            . $this->slotDateLabel($today)
                            . " - {$slot2Time}",
                'time_only' => $slot2Time
            ];

            $slot1Date = $tomorrow->copy();

        } else {

            $deliveryOptions[] = [
                'date' => $tomorrow->toDateString(),
                'slot' => "Slot 1 : "
                            . $this->slotDateLabel($tomorrow)
                            . " - {$slot1Time}",
                'time_only' => $slot1Time
            ];

            $deliveryOptions[] = [
                'date' => $tomorrow->toDateString(),
                'slot' => "Slot 2 : "
                            . $this->slotDateLabel($tomorrow)
                            . " - {$slot2Time}",
                'time_only' => $slot2Time
            ];

            $slot1Date = $tomorrow->copy();
        }

        /*
        |--------------------------------------------------------------------------
        | REST OF WEEK SLOTS
        |--------------------------------------------------------------------------
        */

        $daysPrinted = 0;
        $startDate = $slot1Date->copy()->addDay();

        while ($daysPrinted < 7) {

            $date = $startDate->toDateString();
            $isHoliday = $holidays->contains('holiday_date', $date);

            if (!$isHoliday) {

                $deliveryOptions[] = [
                    'date' => $date,
                    'slot' => Carbon::parse($date)->format('jS M y')
                                . " - {$weekDaySlot} ("
                                . Carbon::parse($date)->format('l')
                                . ")",
                    'time_only' => $weekDaySlot
                ];

                $daysPrinted++;
            }

            $startDate->addDay();
        }
    }

    $bulkDeliverycharges = $zoneProcessingData->bulk_delivery_charges;
    $singleDeliverycharges = $zoneProcessingData->single_delivery_charges;
    $packingcharges = $zoneProcessingData->packing_charge;
    $otherscharges = $zoneProcessingData->others_charges;

} else {

    session()->flash(
        'not_servicable',
        'Service not available on this location.'
    );
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
