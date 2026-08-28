<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\CustomerPrice;
use App\Models\Notification;
use App\Models\AdminNotification;
use DB;
use App\Constants\Status;

class LayoutController extends Controller
{

public function header()
    {
        $user = auth()->user();
// dd($user);
        $quoteCounts = 0;
        $cart = 0;
        $mypricelist = 0;
        $offerListCount = 0;
        $notification = 0;
        $reofferListCount = 0;

        if ($user) {
            $quoteCounts = Quote::where('user_id', $user->id)->count();
            $cart = Cart::with('product')->where('user_id', $user->id)->count();
            $notification = Notification::where('notifiable_id', $user->id)->where('read','false')->count();
            $customerPricesCount = CustomerPrice::where('customer_id', $user->id)->count();
            $offerListCount =  Enquiry::where('user_id', $user->id)
            ->where('status', 'submitted')
            ->where('reoffer', 'no')
            ->count();
            
            // $mypricelist = Enquiry::where('user_id', $user->id)->where('status', 'accept')->count();
            
            $enquiryCount = Enquiry::where('user_id', $user->id)
            ->where('status', 'accept')
            ->count();

            $customerPricesCount = CustomerPrice::where('customer_id', $user->id)->count();
    
            $mypricelist = $enquiryCount + $customerPricesCount;
            
            $reofferListCount = Enquiry::where('user_id', $user->id)
            ->where('status', 'submitted')
            ->where('reoffer', 'yes')
            ->count();

        }

        // dd($reofferListCount);

        return view('web.front.include.header', compact('quoteCounts', 'cart', 'offerListCount', 'reofferListCount','notification','mypricelist'));
    }


    public function adminheader()
    {
        $user = auth()->user();

        $quoteCounts = 0;
        $cart = 0;
        $offerListCount = 0;

        if ($user) {
            $quoteCounts = Quote::where('user_id', $user->id)->count();
            $cart = Cart::with('product')->where('user_id', $user->id)->count();
            $offerListCount = Enquiry::where('user_id', $user->id)->where('status', 'submitted')->count();
        }

        return view('web.front.include.header', compact('quoteCounts', 'cart', 'offerListCount'));
    }



public function footer()
{
    return view('web.front.include.footer');
}


public function getCounts()
{
    $user = auth()->user();
    if (!$user) {
        return response()->json([
            'quoteCounts'      => 0,
            'cart'             => 0,
            'offerListCount'   => 0,
            'reofferListCount' => 0,
            'notification'     => 0,
            'mypricelist'      => 0,
            'notifications'    => [],
        ]);
    }
    
    $enquiryCount = Enquiry::where('user_id', $user->id)
        ->where('status', 'accept')
        ->count();

    $customerPricesCount = CustomerPrice::where('customer_id', $user->id)->count();

    return response()->json([
        'quoteCounts'      => Quote::where('user_id', $user->id)->count(),
        'cart'             => Cart::where('user_id', $user->id)->count(),
        'offerListCount'   => Enquiry::where('user_id', $user->id)->where('status', 'submitted')->where('reoffer', 'no')->count(),
        'reofferListCount' => Enquiry::where('user_id', $user->id)->where('status', 'submitted')->where('reoffer', 'yes')->count(),
        'notification'     => Notification::where('notifiable_id', $user->id)->where('read','false')->count(),
        'mypricelist'      => $enquiryCount + $customerPricesCount,
        'notifications'    => $user->unreadNotifications()
                                    ->take(10) // latest 10
                                    ->get()
                                    ->map(function ($n) {
                                        return [
                                            'text' => $n->data['data'] ?? '',
                                            'tag'  => $n->data['tag'] ?? '',
                                            'date' => $n->created_at->format('M d, Y'),
                                        ];
                                    }),
    ]);
}



}







?>
