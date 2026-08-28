<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PopupTrack;

class PopupController extends Controller
{
    // public function track(Request $request)
    // {
    //     $ip = $request->ip();

    //     // Find or create record for this IP
    //     $record = PopupTrack::firstOrCreate(
    //         ['ip_address' => $ip],
    //         ['views' => 0]
    //     );

    //     // Allow popup only if views < 1
    //     if ($record->views < 1) {
    //         $record->increment('views');
    //         return response()->json(['show' => true]);
    //     }

    //     return response()->json(['show' => false]);
    // }
    
    
     public function track(Request $request)
{
    
    if ($request->cookie('popup_seen')) {
        return response()->json(['show' => false]);
    }

    
    return response()
        ->json(['show' => true])
        ->cookie(
            'popup_seen',   
            1,             
            60 * 24 * 365 * 10 
        );
}
}
