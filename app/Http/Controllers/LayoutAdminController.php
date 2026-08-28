<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\AdminNotification;
use App\Models\DeliveryManagement;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\OrderNotification;
use DB;
use App\Models\Payment;
use App\Models\Order;
use App\Constants\Status;

class LayoutAdminController  extends Controller
{

    public function header()
    {
        $orderListCount = DeliveryManagement::where('delivery_status','pending')->count();
        $offerListCount = Notification::where('admin_read', 'no')->count();

        $NewUsernotifications = UserNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get();
        $NewUserCount = UserNotification::where('is_read', Status::NO)->count();
        
        $oredrnotifications = OrderNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get();
        $orderCount = OrderNotification::where('is_read', Status::NO)->count();
// dd($oredrnotifications);

            $adminNotifications = AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get();
            $adminNotificationCount = AdminNotification::where('is_read', Status::NO)->count();
// dd($adminNotifications);


        return view('admin.includes.headernew', compact( 'orderListCount', 'offerListCount' ,'adminNotificationCount' ,'adminNotifications','NewUserCount',
        'NewUsernotifications','oredrnotifications','orderCount'));
    }


 public function update(Request $request)
    {
        // Update the notifications to mark them as read
        Notification::where('admin_read', 'no')->update(['admin_read' => 'yes']);

        // Return a JSON response
        return response()->json(['success' => true]);
    }

//  public function userupdate(Request $request)
//     {
//         // Update the notifications to mark them as read
//         User::where('new_user', 'false')->update(['new_user' => 'true']);

//         // Return a JSON response
//         return response()->json(['success' => true]);
//     }

public function footer()
{

      $payments = Payment::with([
        'order',
        'histories',
        'user',
        'outlet'
    ])
    ->orderBy('id', 'desc')
    ->paginate(100);

    return view('admin.includes.footernew' , compact('payments'));
}
}







?>
