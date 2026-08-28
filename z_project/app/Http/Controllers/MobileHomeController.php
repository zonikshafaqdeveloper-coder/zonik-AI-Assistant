<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pincode;
use App\Models\ZoneProcessing;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MobileHomeController extends Controller
{
    public function home(Request $request)
    {
        $user = Auth::user();

      
        $outlets = User::where('priority', $user->id)
            ->where('type', 'outlet')
            ->where('verified_status', 'verified')
            ->get();

        $currentOutletId = $user->selected_outlet_id;
        $currentOutlet   = $currentOutletId
            ? ($outlets->firstWhere('id', $currentOutletId) ?? $outlets->first())
            : $outlets->first();

     
        $deliveryLabel = 'tomorrow'; 

        if ($currentOutlet) {

            $mainshippingPincode = null;

            foreach ($currentOutlet->kycdocuments as $kycDocument) {
                if ($kycDocument->user_id == $currentOutlet->id) {
                    $mainshippingPincode = $kycDocument->outlet_pincode;
                    break;
                }
            }

            if ($mainshippingPincode) {

                $pincodeData = Pincode::where('pincode', $mainshippingPincode)->first();
                $zoneId = $pincodeData ? $pincodeData->zone_id : null;

                if ($zoneId) {

                    $zoneProcessingData = ZoneProcessing::where('id', $zoneId)->first();

                    if ($zoneProcessingData && $zoneProcessingData->status == 'Active' && !$zoneProcessingData->regular_days) {

                        $now = Carbon::now();
                        $morningCutoff = Carbon::parse($zoneProcessingData->same_day_timing);

                        // Before the same-day cutoff → next available slot is today
                        $deliveryLabel = $now->lt($morningCutoff) ? 'today' : 'tomorrow';
                    }
                }
            }
        }

      
        $showWeatherAlert = false;

        return view('mobile.home', compact(
            'outlets', 'currentOutlet', 'deliveryLabel', 'showWeatherAlert'
        ));
    }
}