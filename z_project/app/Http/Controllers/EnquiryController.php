<?php

namespace App\Http\Controllers;

use App\Exports\EnquiryExport;
use App\Imports\EnquiryImport;
use App\Models\Enquiry;
use App\Models\AdminNotification;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Holiday;
use App\Models\User;
use App\Models\CustomerPrice;
use App\Services\SmsService;
use App\Notifications\NewQuoteRequest;
use App\Notifications\NewQuoteRequestCustomerNotification;
use App\Notifications\NewEnqueryRequestCustomerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\DatabaseNotification;
use App\Constants\Status;

class EnquiryController extends Controller
{


    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
    {

        $status = $request->status;

        $enquiriesData_ = Enquiry::with('product')
            ->when($request->status, function ($query) use ($status) {
                $query->where('status', 'pending');
            })->get();

        // Create a collection directly from the Eloquent result
        $collection = collect($enquiriesData_);

        // Group the collection by 'enquiry_no'
        // $grouped = $collection->groupBy('user_id');
        $grouped = $collection->groupBy('enquiry_no');


        // Map through each group to get the latest record
        $latestInEachGroup = $grouped->map(function ($group) {
            // Sort the group by the 'created_at' timestamp
            return $group->sortByDesc('created_at')->first();
        });

        // If you want the final result as an array, you can use ->values()->all()
        $enquiriesData = $latestInEachGroup->values()->all();





        $enquiriesDataPanding = Enquiry::with('product')->where('status', 'pending')->latest()->get();
        // dd($enquiriesData);

        return view("admin.enquiries.index", compact('enquiriesData', 'enquiriesDataPanding', 'status'));
    }


        public function store(Request $request)
        {
            $enquiryData = $request->all();
            //   dd($enquiryData);
            $enquiries = [];
            $enquiryModelInstances = []; // To store model instances for notifications
        
            foreach ($enquiryData as $key => $value) {
                if (strpos($key, 'product_id') === 0 && isset($enquiryData['product_types' . substr($key, 10)])) {
                    $product_id = $value;
                    $quantity = $enquiryData['quantity' . substr($key, 10)];
                    $product_types = $enquiryData['product_types' . substr($key, 10)];
                    $monthlyconsumption = $enquiryData['monthlyconsumption' . substr($key, 10)];
                    $requestOfferPrice = $enquiryData['offer_price' . substr($key, 10)];
                    $discount = $enquiryData['discount' . substr($key, 10)];
                    $mrp = $enquiryData['mrp' . substr($key, 10)];
        
                    $user = auth()->user();
                    $product = Product::find($product_id);
                    if (!$product) {
                        continue; // Skip if product is not found
                    }
                    
                    $customerPrice = CustomerPrice::where('customer_id', $user->id)
                        ->where('product_id', $product_id)
                        ->value('product_price');

                    if ($customerPrice !== null) {
                        $offer_price = (float) $customerPrice;
                        $price_source = 'customer';
                    } else {
                        $offer_price = (float) $requestOfferPrice;
                        $price_source = 'product';
                    }
                    
                    $cost_per_item = $product->cost_per_item;
        
                    $enquiryCounts  = Enquiry::get();
                    if ($enquiryCounts->count() == 0) {
                        $enquiry_no = 'Diz-Enq-' . now()->format('y') . '-' . sprintf('%03d', 1);
                    } else {
                        $lastEnquiryNo = $enquiryCounts->last()->enquiry_no ?? null;
                        $next_number = $lastEnquiryNo 
                            ? sprintf('%03d', intval(substr($lastEnquiryNo, -3)) + 1) 
                            : sprintf('%03d', 1);
                        $enquiry_no = 'Diz-Enq-' . now()->format('y') . '-' . $next_number;
                    }
        
                    $enquiries[] = [
                        'enquiry_no' => $enquiry_no,
                        'product_id' => $product_id,
                        'quantity' => $quantity,
                        'product_types' => $product_types,
                        'status' => 'pending',
                        'user_id' => $user->id ?? 1,
                        'monthlyconsumption' => $monthlyconsumption,
                        'offer_price' => $offer_price,
                        'discount' => $discount,
                        'mrp' => $mrp,
                        'cost_per_item' => $cost_per_item,
                        'price_source' => $price_source,
                    ];
                }
            }
        
            if (!empty($enquiries)) {
                foreach ($enquiries as $enquiry) {
                    $enquiryModel = Enquiry::create($enquiry);
                    $enquiryModelInstances[] = $enquiryModel; 
                }
            }
        
            if (!empty($enquiryModelInstances)) {
                $notificationMessage = 'New Enquiry Submitted.';
                $firstEnquiryId = $enquiryModelInstances[0]->id;
            
                // Send one notification
                auth()->user()->notify(new NewEnqueryRequestCustomerNotification($firstEnquiryId, $notificationMessage));
            }
            
        
            $adminNotification            = new AdminNotification();
            $adminNotification->user_id   = $user->id;
            $adminNotification->title = 'New Enquiry ' . $enquiry['enquiry_no'];
            
            // Generate URL with route parameter and query string
            $adminNotification->click_url = route('customer.product.detailss', [
                'user' => $user->id,
            ], false) . '?enquiry_no=' . $enquiry['enquiry_no'];
            '?enquiry_no=' . $enquiry['enquiry_no'];
            
// dd($adminNotification);
            $adminNotification->save();
            

            // Clear user's quotes
            $quotes = Quote::with('product')->where('user_id', auth()->user()->id)->get();
            $quotes->each->delete();
        
            return response()->json(['success' => 'Enquiry updated successfully!', 'data' => []]);
        }
    


    function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->route('customer.index');
    }

    public function showEnquiry(Request $request, $id)
    {
        // Mark the notification as read
        if ($request->has('notification_id')) {
            $notification = DatabaseNotification::find($request->notification_id);
            if ($notification) {
                $notification->markAsRead();
            }
        }

        // Retrieve and show the enquiry details
        $enquiry = Enquiry::findOrFail($id);

        return view('web.front.show', compact('enquiry'));
    }

    function addSingleProduct(Request $request)
    {

        if ($request->box) {
            $totalQnt = $request->quantity ? $request->box * $request->quantity : $request->box;
            $product_type = 1;
        } else {
            $totalQnt = $request->quantity ? $request->loose_value * $request->quantity : $request->loose_value;
            $product_type = 2;
        }

        Enquiry::create([
            'user_id' => auth()->user()->id,
            'product_id' => $request->product_id,
            'quantity' => $totalQnt,
            'product_types' => $product_type,
            'status' => 'pending',

        ]);

        return redirect()->back()->with('success', 'Your Enquery was added successful!');
    }

    public function edit(Enquiry $enquiry)
    {
        // dd($enquiry);

        $products = Product::all();

        return view("admin.enquiries.edit", compact('enquiry', 'products'));
    }

    public function update(Request $request, Enquiry $enquiry)
    {

        $enquiry->update($request->all());

        auth()->user()->notify(new NewQuoteRequestCustomerNotification());

        return redirect()->route('enquiry.index')->with('success', 'Product updated successfully.');
    }



  public function offerRequest(Request $request, Enquiry $enquiry)
{
    $enquiry->update($request->all());
    $enquiry->counter_comment = $request->counter_comment;
    $enquiry->save();

    // Calculate discount with GST
    $gstRate = $enquiry->product->cgst + $enquiry->product->sgst;
    $sellingPriceWithGst = $enquiry->offer_price * (1 + ($gstRate / 100));

    $discount = 0;
    if ($enquiry->mrp > 0) {
        $discount = (($enquiry->mrp - $sellingPriceWithGst) / $enquiry->mrp) * 100;
    }
    
    $customerPrice = CustomerPrice::where('customer_id', $enquiry->user_id)
    ->where('product_id', $enquiry->product_id)
    ->value('product_price');

    return response()->json([
        'message' => 'Enquiry updated successfully.',
        'code'    => 200,
        'acceptedProduct' => [
            'id' => $enquiry->id,
            'product_id' => $enquiry->product_id,
            'product_types' => $enquiry->product_types,
            'offer_price' => (float) $enquiry->offer_price,
            'mrp' => $enquiry->mrp,
            'discount' => number_format($discount, 2),
            'expected_price_value' => $enquiry->expected_price_value,
            'monthlyconsumption' => $enquiry->monthlyconsumption,
            'status' => $enquiry->status,
            'cost_per_item' => (float) $enquiry->cost_per_item,
            'price_source' => $enquiry->price_source,
            'customer_price' => $customerPrice,
            'product' => [
                'image' => $enquiry->product->image,
                'product_name' => $enquiry->product->product_name,
                'carton_size' => $enquiry->product->carton_size,
                'cost_per_item' => (float) $enquiry->product->cost_per_item,
            ]
        ]
    ]);
}

    
        
    
    

 // return response()->json(['message' => 'Updated successfully.','code' => '200'], 200);



public function offerReject(Request $request, Enquiry $enquiry)
{
    $user = Auth::user();

    // Count remaining 'submitted' enquiries where reoffer != 'yes' (before updating current one)
    $remainingNoReoffer = Enquiry::where('user_id', $user->id)
                                ->where('status', 'submitted')
                                ->where('reoffer', '!=', 'yes')
                                ->where('id', '!=', $enquiry->id)
                                ->count();

    // Update enquiry details
    $enquiry->update($request->all());
    $enquiry->counter_comment = $request->counter_comment;
    $enquiry->save();

    // **Use the same title format as Reoffer Request**
    $notificationTitle = 'Reoffer Request ' . $enquiry->enquiry_no;

    // **Check if an Admin Notification already exists for this enquiry_no**
    $existingNotification = AdminNotification::where('user_id', $enquiry->user_id)
        ->where('title', $notificationTitle) // ✅ Title is now the same for both methods
        ->first();

    if ($existingNotification) {
        // ✅ **Update existing notification timestamp**
        $existingNotification->updated_at = now();
        $existingNotification->click_url = route('customer.product.detailss', [
            'user' => $enquiry->user_id,
        ], false) . '?enquiry_no=' . $enquiry->enquiry_no;

        $existingNotification->is_read = false; // ✅ **Mark as unread again**
        $existingNotification->save();
    } else {
        // ✅ **Create a new notification only if it doesn't exist**
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $enquiry->user_id;
        $adminNotification->title = $notificationTitle; // ✅ Same as Reoffer Request
        $adminNotification->click_url = route('customer.product.detailss', [
            'user' => $enquiry->user_id,
        ], false) . '?enquiry_no=' . $enquiry->enquiry_no;
        $adminNotification->is_read = false;
        $adminNotification->save();
    }

    // If this was the last remaining enquiry where reoffer != 'yes', redirect to homepage
    // if ($remainingNoReoffer === 0) { 
    //     return redirect('/homepage')->with('success', 'Last product rejected. Redirecting to homepage.');
    // }

   return response()->json([
        'message' => 'Offer rejected successfully.',
        'code' => 200,
    ]);
}



public function offerreoffer(Request $request, Enquiry $enquiry)
{
    // Get the authenticated user
    $user = Auth::user();

    // Count remaining enquiries where reoffer is NOT 'yes' (before updating this one)
    $remainingNoReoffer = Enquiry::where('user_id', $user->id)
                                ->where('status', 'submitted')
                                ->where('reoffer', '!=', 'yes')
                                ->where('id', '!=', $enquiry->id) // Exclude current enquiry
                                ->count();

    // Update the enquiry with the request data
    $enquiry->update($request->all());

    // Set the counter comment
    $enquiry->counter_comment = $request->counter_comment;
    $enquiry->status = 'pending'; // Mark status as pending
     $enquiry->increment('reoffer_count');
    $enquiry->save();

    // Prepare notification message
    // $notification_message = 'Your reoffer request has been submitted.';

    // Send notification to user
    // $enquiry->user->notify(new NewEnqueryRequestCustomerNotification($enquiry->id, $notification_message));

    // ✅ **Check if an Admin Notification already exists**
    $existingNotification = AdminNotification::where('user_id', $enquiry->user_id)
        ->where('title', 'Reoffer Request ' . $enquiry->enquiry_no) // **Exact match**
        ->first();

    if ($existingNotification) {
        // ✅ **Update existing notification instead of duplicating**
        $existingNotification->updated_at = now();
        $existingNotification->click_url = route('customer.product.detailss', [
            'user' => $enquiry->user_id,
        ], false) . '?enquiry_no=' . $enquiry->enquiry_no;
        
        $existingNotification->is_read = false; // Mark as unread again
        $existingNotification->save();
    } else {
        // ✅ **Create a new notification only if it doesn't exist**
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $enquiry->user_id;
        $adminNotification->title = 'Reoffer Request ' . $enquiry->enquiry_no; // **Fixed title**
        $adminNotification->click_url = route('customer.product.detailss', [
            'user' => $enquiry->user_id,
        ], false) . '?enquiry_no=' . $enquiry->enquiry_no;
        $adminNotification->is_read = false;
        $adminNotification->save();
    }

    // If this was the last remaining 'no' reoffer product, redirect to homepage
    // if ($remainingNoReoffer === 0) { 
    //     return redirect('/homepage')->with('success', 'Last reoffer submitted. Redirecting to homepage.');
    // }

    // return redirect()->back()->with('success', 'Reoffer request submitted and customer notified successfully.');
    
    return response()->json([
        'message' => 'Reoffer request submitted successfully.',
        'code' => 200,
    ]);
}


public function sendLastReofferNotification()
{
    $user = Auth::user();
    $notification_message = 'Your reoffer request has been submitted.';

    // Send notification
    $user->notify(new NewEnqueryRequestCustomerNotification(null, $notification_message));

    return response()->json(['message' => 'Notification sent successfully.']);
}




//  public function offerReject(Request $request, Enquiry $enquiry)
//  {
//      // Update the enquiry with the request data
//      $enquiry->update($request->all());
 
//      // Set the counter comment
//      $enquiry->counter_comment = $request->counter_comment;
 
//      // Save the updated enquiry data
//      $enquiry->save();
 
//      // Prepare the rejection message for the notification
//     //  $notification_message = 'Your offer has been rejected.';
 
//      // Send the notification with the rejection message
//     //  $enquiry->user->notify(new NewEnqueryRequestCustomerNotification($enquiry->id, $notification_message));
 
//      // Optionally, find the notification record and update 'admin_read' to true
//     //  $notificationRecord = $enquiry->user->notifications()
//                                         //  ->where('type', NewEnqueryRequestCustomerNotification::class)
//                                         //  ->where('data', 'like', '%' . $notification_message . '%')
//                                         //  ->latest()
//                                         //  ->first();
 
//     //  if ($notificationRecord) {
//         //  $notificationRecord->update(['admin_read' => true]);
//     //  }
 
//      // Return a success message
//      return redirect()->back()->with('success', 'Product rejected and customer notified successfully.');
//  }
 

//  public function offerreoffer(Request $request, Enquiry $enquiry)
// {
    
//     // dd($request->all());
//     // Update the enquiry with the request data
//     $enquiry->update($request->all());

//     // Set the counter comment
//     $enquiry->counter_comment = $request->counter_comment;

//     // Set the status to 'pending' to indicate the reoffer request
//     $enquiry->status = 'pending';


// // dd($enquiry);
//     // Save the updated enquiry data
//     $enquiry->save();

//     // // Prepare the reoffer submission message for the notification
//     $notification_message = 'Your reoffer request has been submitted.';

//     // // Send the notification with the reoffer message
//     $enquiry->user->notify(new NewEnqueryRequestCustomerNotification($enquiry->id, $notification_message));

//     // // Admin Notification
//     $adminNotification = new AdminNotification();
//     $adminNotification->user_id = $enquiry->user_id; // Assuming `user_id` is stored in the enquiry model
//     $adminNotification->title = 'Reoffer Request ' . $enquiry->enquiry_no;

//     // // Generate URL with route parameter and query string
//     $adminNotification->click_url = route('customer.product.detailss', [
//         'user' => $enquiry->user_id,
//     ], false) . '?enquiry_no=' . $enquiry->enquiry_no;

//     // // Save the admin notification
//     $adminNotification->save();

//     // Return a success message
//     return redirect()->back()->with('success', 'Reoffer request submitted and customer notified successfully.');
// }



//  public function offerreoffer1(Request $request, Enquiry $enquiry)
//     {
    
//         $enquiry->update($request->all());
//       return redirect()->back()->with('success', 'Product updated successfully.');
//     }


public function offerreoffer1(Request $request, $id)
    {
    
        // $enquiry->update($request->all());
        
        $enquiry = Enquiry::findOrFail($id);

//   dd($request);

         $enquiry->update([
        'expected_price_value' => $request->expected_price_value,
    ]);
    
    
    
       return redirect()->back()->with('success', 'Product updated successfully.');
    }







    // public function offerRequest(Request $request, Enquiry $enquiry)
    // {
    //     $holidays = Holiday::all();

    //     // $updateDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $request->input('updated_at'));
    //     $updateDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $request->input('updated_at'), 'Asia/Kolkata');
    //     $currentDate = \Carbon\Carbon::now('Asia/Kolkata');
    //     $remainingDays = 0;
    //     $workingDaysToWait = 2;
    //     $count = 0;
    //     $maxIterations = 10;

    //     while ($count < $workingDaysToWait && $maxIterations > 0) {
    //         $currentDate->subDay();
    //         if (!$currentDate->isWeekend() && !$this->isHoliday($currentDate, $holidays)) {
    //             $count++;
    //         }
    //         $maxIterations--;
    //     }

    //     // dd($currentDate);
    //     if ($updateDate->lessThan($currentDate)) {
    //         $enquiry->update($request->all());
    //   auth()->user()->notify(new NewEnqueryRequestCustomerNotification());
    //         return response()->json(['message' => 'Updated successfully.', 'code' => '200'], 200);
    //     } else {
    //         while ($updateDate->greaterThan($currentDate)) {
    //             $currentDate->addDay();
    //             if (!$currentDate->isWeekend() && !$this->isHoliday($currentDate, $holidays)) {
    //                 $remainingDays++;
    //             }
    //         }
    //         return response()->json(['message' => 'Please wait for '.$remainingDays.' days before updating.', 'code' => '300'], 200);
    //     }
    // }


    // private function isHoliday($date, $holidays)
    // {

    //     foreach ($holidays as $holiday) {
    //         $holidayDate = \Carbon\Carbon::createFromFormat('Y-m-d', $holiday->holiday_date);
    //         if ($holidayDate->equalTo($date)) {
    //             return true;
    //         }
    //     }
    //     return false;
    // }


    public function statusUpdate(Request $request, $status)
    {

        if ($request->id) {
        Enquiry::where('status', $status)->where('user_id', $request->id)->update(['status' => 'submitted']);
        } else {
            Enquiry::where('status', $status)->update(['status' => 'submitted']);
        }


        auth()->user()->notify(new NewQuoteRequestCustomerNotification());

        return redirect()->back()->with('success', 'Pending Enquiry updated successfully.');
    }

    public function statusChanges($status)
    {
        $enquiriesData = Enquiry::with('product')
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->get();
        return view("admin.enquiries.view", compact('enquiriesData', 'status'));
    }


   public function destroy(Enquiry $enquiry)
{
    $enquiry->delete();

    return response()->json([
        'message' => 'Removed successfully'
    ]);
}

public function destroyCustomerPrice(CustomerPrice $customerPrice)
{
    $customerPrice->delete();

    return response()->json([
        'message' => 'Removed successfully'
    ]);
}

//     public function destroy(Enquiry $enquiry){
//      $enquiry->delete();

//         // Return a JSON response for the AJAX request
//         return response()->json(['success' => true, 'message' => 'Enquiry deleted successfully.']);
// }


// comment on 8-12-25
// public function offerPriceUpdate(Request $request, SmsService $smsService)
// {
//     $offerPriceValue = $request->all();
//     $enquiries_offer_prices = [];
//     $firstNotificationSent = false;

//     foreach ($offerPriceValue as $key => $value) {
//         if (strpos($key, 'id') === 0 && isset($offerPriceValue['offer_price' . substr($key, 2)])) {
//             $id = $value;
//             $offer_price = $offerPriceValue['offer_price' . substr($key, 2)];

//             $enquiries_offer_prices[] = compact('id', 'offer_price');
//         }
//     }

//     if (!empty($enquiries_offer_prices)) {
//         foreach ($enquiries_offer_prices as $enquiries_offer_price) {
//             $enquiry = Enquiry::find($enquiries_offer_price['id']);
//             $previous_offer_price = round($enquiry->offer_price, 2);
//             $new_offer_price = round($enquiries_offer_price['offer_price'], 2);

//             Log::debug("Rounded Previous Offer Price: {$previous_offer_price} - Rounded New Offer Price: {$new_offer_price}");

//             if ($enquiry->offer_check == 0) {
//                 $reoffer = 'no';
//                 $enquiry->offer_check = 1;
//                 $notification_message = 'Zonik Team has given you an Offer, Please review in offer section.';
//             } else {
//                 if ($enquiry->offer_check == 1) {
//                     $reoffer = 'yes';
//                     $notification_message = 'Zonik Team has given you an Offer, Please review in reoffer section.';
//                 } else {
//                     $reoffer = 'no';
//                     $notification_message = 'Your offer price update has been processed.';
//                 }
//             }

//             $enquiry->offer_price = $new_offer_price;
//             $enquiry->status = 'submitted';
//             $enquiry->reoffer = $reoffer;
//             $enquiry->offer_check = $enquiry->offer_check;
//             $enquiry->save();

//             if (!$firstNotificationSent) {
//                 $user = auth()->user();
//                 if ($user) {
//                     // Send notification
//                     $user->notify(new NewEnqueryRequestCustomerNotification($enquiry->id, $notification_message));

//                     $notificationRecord = $user->notifications()
//                         ->where('type', NewEnqueryRequestCustomerNotification::class)
//                         ->where('data', 'like', '%' . $notification_message . '%')
//                         ->latest()
//                         ->first();

//                     if ($notificationRecord) {
//                         $notificationRecord->update(['admin_read' => true]);
//                     }

//                     $firstNotificationSent = true;
//                 }

//                 // Send SMS message
//                 $mobile = $enquiry->user->mobile_number ?? null;
//                 $order_id = $enquiry->id;
//                 $status = 'submitted';

//                 if ($mobile) {
//                     $smsService->sendOrderDetails($mobile, $order_id, $status);
//                 }
//             }
//         }
//     }
    
//     //  return redirect()->back()->with('success', 'Enquiry updated successfully.');
//      return redirect()->route('enquiry.indexx')->with('success', 'Enquiry updated successfully.');
// }

// public function offerPriceUpdate(Request $request, SmsService $smsService)
// {
//     $data = $request->all();
//     $entries = [];

//     // Collect id + price pairs from form
//     foreach ($data as $key => $value) {
//         if (strpos($key, 'id') === 0) {

//             $index = substr($key, 2);
//             $id = $value;

//             if (isset($data['offer_price' . $index])) {
//                 $entries[] = [
//                     'id' => $id,
//                     'offer_price' => $data['offer_price' . $index]
//                 ];
//             }
//         }
//     }

//     if (empty($entries)) {
//         return back()->with('error', 'No data provided.');
//     }

//     // Get enquiry_no from first row
//     $firstEnquiry = Enquiry::find($entries[0]['id']);
//     $enquiry_no = $firstEnquiry->enquiry_no;

//     // Update all rows with same enquiry_no
//     foreach ($entries as $entry) {
//         $enquiry = Enquiry::find($entry['id']);
//         if (!$enquiry) continue;

//         $new_price = round($entry['offer_price'], 2);

//         // logic
//         $reoffer = $enquiry->offer_check == 0 ? 'no' : 'yes';
//         $message = $reoffer == 'no'
//             ? 'Zonik Team has given you an Offer, Please review in My Price List section.'
//             : 'Zonik Team has given you an Offer, Please review in My Price List section.';

//         $enquiry->offer_check = 1;
//         $enquiry->offer_price = $new_price;
//         $enquiry->status = 'accept';
//         $enquiry->reoffer = $reoffer;
//         $enquiry->save();
//     }

//     // ----------------------------
//     // SEND ONLY ONE NOTIFICATION
//     // ----------------------------

//     $user = auth()->user();

//     if ($user) {
//         $user->notify(
//             new NewEnqueryRequestCustomerNotification(
//                 $firstEnquiry->id,
//                 $message
//             )
//         );
//     }

//     // ----------------------------
//     // SEND ONLY ONE SMS
//     // ----------------------------

//     $mobile = $firstEnquiry->user->mobile_number ?? null;
//     // if ($mobile) {
//     //     $smsService->sendOrderDetails($mobile, $firstEnquiry->id, 'submitted');
//     // }

//     return redirect()->route('enquiry.indexx')->with('success', 'Enquiry updated successfully.');
// }



public function offerPriceUpdate(Request $request, SmsService $smsService)
{
    $data = $request->all();
    $entries = [];

    // Collect id + price pairs from form
    foreach ($data as $key => $value) {
        if (strpos($key, 'id') === 0) {

            $index = substr($key, 2);
            $id = $value;

            if (isset($data['offer_price' . $index])) {
                $entries[] = [
                    'id' => $id,
                    'offer_price' => $data['offer_price' . $index]
                ];
            }
        }
    }

    if (empty($entries)) {
        return back()->with('error', 'No data provided.');
    }

    // Get enquiry_no from first row
    $firstEnquiry = Enquiry::find($entries[0]['id']);
    $enquiry_no = $firstEnquiry->enquiry_no;

    // Update all rows with same enquiry_no
    foreach ($entries as $entry) {
        $enquiry = Enquiry::find($entry['id']);
        if (!$enquiry) continue;

        $new_price = round($entry['offer_price'], 2);

        // logic
        $reoffer = $enquiry->offer_check == 0 ? 'no' : 'yes';
        $message = $reoffer == 'no'
            ? 'Zonik Team has given you an Offer, Please review in offer section.'
            : 'Zonik Team has given you an Offer, Please review in reoffer section.';

        $enquiry->offer_check = 1;
        $enquiry->offer_price = $new_price;
        $enquiry->status = 'submitted';
        $enquiry->reoffer = $reoffer;
        $enquiry->save();
    }

    // ----------------------------
    // SEND ONLY ONE NOTIFICATION
    // ----------------------------

    $user = auth()->user();

    if ($user) {
        $user->notify(
            new NewEnqueryRequestCustomerNotification(
                $firstEnquiry->id,
                $message
            )
        );
    }

    // ----------------------------
    // SEND ONLY ONE SMS
    // ----------------------------

    $mobile = $firstEnquiry->user->mobile_number ?? null;
    if ($mobile) {
        $smsService->sendOrderDetails($mobile, $firstEnquiry->id, 'submitted');
    }

    return redirect()->route('enquiry.indexx')->with('success', 'Enquiry updated successfully.');
}

    function comment(Request $request)
    {

        $enquiry = Enquiry::find($request->id);
        $enquiry->update([
            'rejected' => $request->rejected,
            'rejected_customer_comment' => $request->rejected_customer_comment,
        ]);

        return redirect()->back();
    }

    public function exportData()
    {
        return Excel::download(new EnquiryExport, 'Enquiry_' . date('Y-m-d_H-i-s') . '-' . '.xlsx');

        return redirect()->route('enquiry.index')->with('success', 'Enquiry Export To Excel successfully.');
    }

    public function importData(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        Storage::disk('public')->put($request->file->getClientOriginalName(), file_get_contents($request->file));

        Excel::import(new EnquiryImport, $request->file->getClientOriginalName(), 'public');

        File::delete(storage_path('app/public/' . $request->file->getClientOriginalName()));

        return redirect()->route('enquiry.index')->with('success', 'Enquiry Multiple added successfully.');
    }
    
 public function destroy_enquiry($id)
{
    $enquiry = Enquiry::find($id);
    // dd($enquiry);
    if (!$enquiry) {
        return response()->json(['success' => false, 'message' => 'Enquiry not found.'], 404);
    }

    $enquiry->delete();
    return response()->json(['success' => true, 'message' => 'Enquiry deleted successfully.']);
}

}
