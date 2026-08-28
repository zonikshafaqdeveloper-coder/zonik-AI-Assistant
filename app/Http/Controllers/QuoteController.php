<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Enquiry;
use App\Notifications\NewQuoteRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewQuoteRequestCustomerNotification;
use App\Notifications\NewEnqueryRequestCustomerNotification;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\Product;
use App\Models\user;
use App\Models\CustomerPrice;
use App\Models\VendorPriceList;
use App\Models\Holiday;
use App\Models\Quotelist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    //
    public function index()
    {
        $user = User::find(3);
        $quoteCounts =   Quote::where('user_id', auth()->user()?->id)->get();
        $cartsCount =   Cart::where('user_id', auth()->user()?->id)->get();
        $quoteItems = $user->quoteItems;
        return view('web.quotes.index', compact('quoteItems', 'cartsCount', 'quoteCounts'));
    }


    public function quote()
    {
        $user = User::find(3);
        $quoteItems = $user->quoteItems; // Assuming you have a relationship set up
        return view('web.front.request_quotelist');
    }


    public function quotelist()
{
    $user = Auth::user();
    $holidays = Holiday::all();

    if ($user) {
        $quoteCounts = Quote::where('user_id', $user->id)->count();
        $cartsCount = Cart::where('user_id', $user->id)->count();
        $userData = User::where('priority', auth()->id())->get();

        $enquiriesForAcceptCount = Enquiry::where('user_id', $user->id)->where('status', 'accept')->count();
        $offerListCount = Enquiry::where('user_id', $user->id)->where('status', 'submitted')->count();

        $quoteItems = $user->quoteItems;
        $quote_Items_list = Quote::with('product')->where('user_id', $user->id)->latest()->get();
        
        $enquiriesForOfferList = Enquiry::with('product')
            ->where('user_id', $user->id)
            ->where('status', 'submitted')
            ->latest()->get();

        $enquiriesForAccept = Enquiry::with('product')->where('user_id', $user->id)->where('status', 'accept')
            ->latest()->get();

            // dd($enquiriesForAccept);
            
            
         $productsWithVendorPrices = VendorPriceList::select('product_id', DB::raw('MIN(vendor_price) as lowest_vendor_price'))
            ->whereNotNull('vendor_price')
            ->where('vendor_price', '>', 0)
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');
            
            // dd($productsWithVendorPrices);
        
        // if ($productsWithVendorPrices->isNotEmpty()) {
        //     $productIds = $productsWithVendorPrices->keys()->toArray();
        //     $products = Product::whereIn('id', $productIds)->get();
            
        //     foreach ($products as $product) {
        //         $lowestVendorPrice = $productsWithVendorPrices->get($product->id)?->lowest_vendor_price;
        //         $currentCost = $product->cost_per_item ?? 0;
                

        //         $prices = array_filter([$currentCost, $lowestVendorPrice], function($price) {
        //             return $price !== null && $price > 0;
        //         });
                
        //         if (!empty($prices)) {
        //             $lowestPrice = min($prices);
                    
                    
        //             if ($product->cost_per_item != $lowestPrice) {
        //                 $product->cost_per_item = $lowestPrice;
        //                 $product->save();
        //             }
        //         }
        //     }
        // }    

        // Loop through each enquiry in 'enquiriesForAccept' and check if cost_per_item values differ
        foreach ($enquiriesForAccept as $acceptLits) {
            // Check if the product status is inactive
            if ($acceptLits->product->status == 'inactive') {
                // Delete the enquiry record if product is inactive
                $acceptLits->delete();
                
        $notification_message = 'A product in your cart is inactive and has been removed.';
        $notification = new NewEnqueryRequestCustomerNotification($acceptLits->id, $notification_message);
        
        $acceptLits->user->notify($notification);

        // Update the notification to mark it as read for the admin
        $notificationRecord = $acceptLits->user->notifications()->where('data->enquiry_id', $acceptLits->id)->latest()->first();
        if ($notificationRecord) {
            $notificationRecord->update(['admin_read' => true]);
        }
                continue; 
            }
        


// if ($acceptLits->cost_per_item !== $acceptLits->product->cost_per_item && !$acceptLits->price_notified) {
//     $notification_message = 'The price of ' . $acceptLits->product->product_name . ' has been changed.Please check price section to accept the price.';

   
//     $notification = new NewEnqueryRequestCustomerNotification($acceptLits->id, $notification_message);
//     $acceptLits->user->notify($notification);

//     // ✅ Set price_notified to true so notification isn't sent again on reload
//     $acceptLits->price_notified = true;

//     // ✅ Prevent unnecessary price updates on reload
//     $A = abs($acceptLits->product->cost_per_item - $acceptLits->cost_per_item);

//     if ($A > 0) {
//         if ($acceptLits->product_types == 1) {
//             $acceptLits->product->sale_price_carton += $A;
//         } elseif ($acceptLits->product_types == 2) {
//             $acceptLits->product->sale_price_loose_pcs += $A;
//         }
        
//         // Update offer price
//         $acceptLits->offer_price += $A;
//     }

//     // ❌ DO NOT update $acceptLits->cost_per_item here
//     // Let it remain different from the product cost until user confirms it

//     // ✅ Save changes
//     $acceptLits->save();
//     $acceptLits->product->save();
// }


}
        
        
         $customerPrices = CustomerPrice::where('customer_id', $user->id)
        ->get()
        ->keyBy('product_id');
        
        $enquiriesForRejected = Enquiry::with('product')->where('user_id', $user->id)->where('status', 'rejected')
            ->latest()->get();
            
        $customerPricesc = CustomerPrice::where('customer_id', $user->id)->get();
        $customerPricesCount = $customerPricesc->count();
        $customerPricesKeyed = $customerPricesc->keyBy('product_id');

        $totalPriceListCount = $enquiriesForAcceptCount + $customerPricesCount;      

        $cart = Cart::with('product')->where('user_id', $user->id)->latest()->get();
        $coupon = Coupon::whereDate('end_date', '>=', Carbon::today())->get();
    } else {
        return redirect()->back()->withError('Please log in');
    }

    return view('web.quotes.quote-list', compact(
        'coupon', 'quoteItems', 'userData', 'cartsCount', 'enquiriesForAcceptCount', 'offerListCount',
        'quoteCounts', 'quote_Items_list', 'enquiriesForOfferList', 'enquiriesForAccept', 'enquiriesForRejected', 'cart','customerPrices','totalPriceListCount'
    ));
}


public function quotelist_demo()
{
    $user = Auth::user();
    $holidays = Holiday::all();

    

    if ($user) {
        $quoteCounts = Quote::where('user_id', $user->id)->count();
        $cartsCount = Cart::where('user_id', $user->id)->count();
        $userData = User::where('priority', auth()->id())->get();

        $enquiriesForAcceptCount = Enquiry::where('user_id', $user->id)->where('status', 'accept')->count();
        $offerListCount = Enquiry::where('user_id', $user->id)->where('status', 'submitted')->count();

        $quoteItems = $user->quoteItems;
        $quote_Items_list = Quote::with('product')->where('user_id', $user->id)->latest()->get();
        
        $enquiriesForOfferList = Enquiry::with('product')
            ->where('user_id', $user->id)
            ->where('status', 'submitted')
            ->latest()->get();

        $enquiriesForAccept = Enquiry::with('product')->where('user_id', $user->id)->where('status', 'accept')
            ->latest()->get();

         $productsWithVendorPrices = VendorPriceList::select('product_id', DB::raw('MIN(vendor_price) as lowest_vendor_price'))
            ->whereNotNull('vendor_price')
            ->where('vendor_price', '>', 0)
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');
        
        if ($productsWithVendorPrices->isNotEmpty()) {
            $productIds = $productsWithVendorPrices->keys()->toArray();
            $products = Product::whereIn('id', $productIds)->get();
            
            foreach ($products as $product) {
                $lowestVendorPrice = $productsWithVendorPrices->get($product->id)?->lowest_vendor_price;
                $currentCost = $product->cost_per_item ?? 0;
                

                $prices = array_filter([$currentCost, $lowestVendorPrice], function($price) {
                    return $price !== null && $price > 0;
                });
                
                if (!empty($prices)) {
                    $lowestPrice = min($prices);
                    
                    
                    if ($product->cost_per_item != $lowestPrice) {
                        $product->cost_per_item = $lowestPrice;
                        $product->save();
                    }
                }
            }
        }
        

            // dd($enquiriesForAccept);

        // Loop through each enquiry in 'enquiriesForAccept' and check if cost_per_item values differ
        foreach ($enquiriesForAccept as $acceptLits) {
            // Check if the product status is inactive
            if ($acceptLits->product->status == 'inactive') {
                // Delete the enquiry record if product is inactive
                $acceptLits->delete();
                
        $notification_message = 'A product in your cart is inactive and has been removed.';
        $notification = new NewEnqueryRequestCustomerNotification($acceptLits->id, $notification_message);
        
        $acceptLits->user->notify($notification);

        // Update the notification to mark it as read for the admin
        $notificationRecord = $acceptLits->user->notifications()->where('data->enquiry_id', $acceptLits->id)->latest()->first();
        if ($notificationRecord) {
            $notificationRecord->update(['admin_read' => true]);
        }
                continue; 
            }
        


// if ($acceptLits->cost_per_item !== $acceptLits->product->cost_per_item && !$acceptLits->price_notified) {
//     $notification_message = 'The price of ' . $acceptLits->product->product_name . ' has been changed.Please check price section to accept the price.';

   
//     $notification = new NewEnqueryRequestCustomerNotification($acceptLits->id, $notification_message);
//     $acceptLits->user->notify($notification);

//     // ✅ Set price_notified to true so notification isn't sent again on reload
//     $acceptLits->price_notified = true;

//     // ✅ Prevent unnecessary price updates on reload
//     $A = abs($acceptLits->product->cost_per_item - $acceptLits->cost_per_item);

//     if ($A > 0) {
//         if ($acceptLits->product_types == 1) {
//             $acceptLits->product->sale_price_carton += $A;
//         } elseif ($acceptLits->product_types == 2) {
//             $acceptLits->product->sale_price_loose_pcs += $A;
//         }
        
//         // Update offer price
//         $acceptLits->offer_price += $A;
//     }

//     // ❌ DO NOT update $acceptLits->cost_per_item here
//     // Let it remain different from the product cost until user confirms it

//     // ✅ Save changes
//     $acceptLits->save();
//     $acceptLits->product->save();
// }


}
        
        
         $customerPrices = CustomerPrice::where('customer_id', $user->id)
        ->get()
        ->keyBy('product_id');

        $customerPricesproduct = CustomerPrice::with('product')
    ->where('customer_id', $user->id)
    ->get();
        
        $enquiriesForRejected = Enquiry::with('product')->where('user_id', $user->id)->where('status', 'rejected')
            ->latest()->get();

        $customerPricesc = CustomerPrice::where('customer_id', $user->id)->get();
        $customerPricesCount = $customerPricesc->count();
        $customerPricesKeyed = $customerPricesc->keyBy('product_id');

        $totalPriceListCount = $enquiriesForAcceptCount + $customerPricesCount;    

        $cart = Cart::with('product')->where('user_id', $user->id)->latest()->get();
        $coupon = Coupon::whereDate('end_date', '>=', Carbon::today())->get();


    } else {
        return redirect()->back()->withError('Please log in');
    }

    return view('web.quotes.quote-list-demo', compact(
        'coupon', 'quoteItems', 'userData', 'cartsCount', 'enquiriesForAcceptCount', 'offerListCount',
        'quoteCounts', 'quote_Items_list', 'enquiriesForOfferList', 'enquiriesForAccept', 'enquiriesForRejected', 'cart','customerPrices', 'customerPricesproduct','totalPriceListCount'
    ));
}




public function updateAcceptCost(Request $request)
{
    $enquiry = Enquiry::with(['product', 'user'])->find($request->enquiry_id);

    if (!$enquiry) {
        return response()->json([
            'status' => 'error',
            'message' => 'Enquiry not found'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT FLOW
    |--------------------------------------------------------------------------
    */
    if ($request->action === 'reject') {

        $enquiry->status = 'rejected';
        $enquiry->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Price rejected successfully'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCEPT FLOW
    |--------------------------------------------------------------------------
    */

    $product = $enquiry->product;
    $oldCost = (float) $enquiry->cost_per_item;

    /*
    |--------------------------------------------------------------------------
    | CASE 2 — CUSTOMER PRICE (highest priority)
    |--------------------------------------------------------------------------
    */
    if ($request->type === 'customer') {

        if (!$request->filled('new_cost')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer price not provided'
            ]);
        }

        $newCost = (float) $request->new_cost;

        // Update enquiry prices
        $enquiry->cost_per_item = $newCost;
        $enquiry->offer_price  = $newCost;
        $enquiry->price_source = 'customer';
        $enquiry->price_notified = false;
        $enquiry->save();

        // Notify customer
        $notification_message =
            'The price of ' . $product->product_name .
            ' has been changed. Please check price section to accept the price.';

        $notification = new NewEnqueryRequestCustomerNotification(
            $enquiry->id,
            $notification_message
        );

        $enquiry->user->notify($notification);

        return response()->json([
            'status'  => 'success',
            'message' => 'Customer price accepted successfully'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CASE 1 — PRODUCT MASTER PRICE
    |--------------------------------------------------------------------------
    */

    $newCost = (float) $product->cost_per_item;


    // Save enquiry changes
    $enquiry->cost_per_item = $newCost;
    $enquiry->offer_price = $newCost;
    $enquiry->price_source = 'product';
    $enquiry->price_notified = false;
    $enquiry->save();

    // Notify customer
    $notification_message =
        'The price of ' . $product->product_name .
        ' has been changed. Please check price section to accept the price.';

    $notification = new NewEnqueryRequestCustomerNotification(
        $enquiry->id,
        $notification_message
    );

    $enquiry->user->notify($notification);

    return response()->json([
        'status'  => 'success',
        'message' => 'Product price accepted successfully'
    ]);
}



    // public function updateAcceptCost(Request $request)
    // {
    //     // Find the enquiry with the related product
    //     $enquiry = Enquiry::with('product')->find($request->enquiry_id);

    //     if (!$enquiry) {
    //         return response()->json(['status' => 'error', 'message' => 'Enquiry not found.']);
    //     }

    //     $oldCost = $enquiry->cost_per_item;
    //     $newCost = $enquiry->product->cost_per_item;

    //     // Calculate the absolute difference to ensure a positive value
    //     // $difference = abs($newCost - $oldCost);

        

    //     // Update the enquiry's cost_per_item to the product's current cost
    //     $enquiry->cost_per_item = $newCost;
    //     // $enquiry->status = 'submitted';
    //     // $enquiry->reoffer = 'no';
    //     // $enquiry->alert = 'active';
    //     $enquiry->price_notified = false;

    //     // Save the updated enquiry data
    //     $enquiry->save();

    //     // Send notification to the customer about the product price update
    //     // $notification_message = 'Product price updated successfully.';
    //     // $notification = new NewEnqueryRequestCustomerNotification($enquiry->id, $notification_message);

    //     $productName = $enquiry->product->product_name; // Get product name
    //     $notification_message = "The price of \"{$productName}\" has been changed. Please check offer section to accept the price again.";
    //     // Notify the customer
    //     // $enquiry->user->notify($notification);

    //     // Update the notification to mark it as read for the admin (admin_read = true)
    //     // $notificationRecord = $enquiry->user->notifications()->where('data->enquiry_id', $enquiry->id)->latest()->first();
    //     // if ($notificationRecord) {
    //         // $notificationRecord->update(['admin_read' => true]);
    //     // }

    //     return response()->json(['status' => 'success', 'message' => $notification_message]);
    // }

//    $acceptLits->cost_per_item = $acceptLits->product->cost_per_item;

    public function addToQuote(Request $request)
{
    $userId = auth()->user()->id;

    // Check if the product is already in the Enquiry table with a status other than "rejected"
    
    $existingCustomerPrice = CustomerPrice::where('customer_id', $userId)
    ->where('product_id', $request->product_id)
    ->first();

    if ($existingCustomerPrice) {
        return response()->json([
            'error' => 'Item already exists in the price list'
        ], 400);
    }
    
    $duplicateEnquiry = Enquiry::where('product_id', $request->product_id)
        ->where('user_id', $userId)
        ->where('product_types', $request->productType === 'BOX' ? 1 : 2)
        ->where('status', '!=', 'rejected')
        ->first();

    if ($duplicateEnquiry) {
        return response()->json(['error' => 'Item already exists in the price list'], 400);
    }

    // Check if the product is already in the Quote table
    $existingQuoteItem = Quote::where('user_id', $userId)
        ->where('product_id', $request->product_id)
        ->where('product_type', $request->productType === 'BOX' ? 1 : 2)
        ->first();

    if ($existingQuoteItem) {
        return response()->json(['error' => 'Item already exists in the Enquiry List'], 400);
    }

    // Add the product to the Quote table
    Quote::create([
        'user_id' => $userId,
        'product_id' => $request->product_id,
        'product_type' => $request->productType === 'BOX' ? 1 : 2,
    ]);

    return response()->json(['success' => 'Enquiry Submitted successfully'], 200);
}



    public function submitQuote12(Request $request)
    {
        // Create a new quote record in the database
        // Your logic here - Instantiate Quote model, set attributes, and save to database

        // Notify the admin
        $admin = User::find(3); // Replace 3 with the actual admin's ID
        // Notification::send($admin, new NewQuoteRequest());

        $quoteId = session('quote_id', []);

        $quote = Quote::find($quoteId);

        // Notify the customer who submitted the quote
        // $customer = Auth::user(); // Assuming the customer is logged in
        $customer = User::find(3);

        // Notification::send($customer, new NewQuoteRequestCustomerNotification());

        // Create a new admin notification
        $quotelist = new Quotelist();
        $quotelist->user_id = $admin->id; // Use the admin's ID
        // $quotelist->quote_id = $quote->id; // Adjust this based on your quote creation logic
        // $quotelist->quote_id = $quote->id;
        $quotelist->quote_id = implode(',', $quoteId);

        $quotelist->save();

        return redirect()->route('quotes.index')->with('success', 'Quote request submitted successfully.');
    }



    public function submitQuote(Request $request)
    {
        $admin = User::find(3); // Replace 3 with the actual admin's ID
        $quoteIds = session('quote_id', []);

        $customer = User::find(3);

        foreach ($quoteIds as $quoteId) {
            $quotelist = new Quotelist();
            $quotelist->user_id = $admin->id;
            $quotelist->quote_id = $quoteId;

            $quotelist->save();
        }
        return redirect()->route('quotes.index')->with('success', 'Quote request submitted successfully.');
    }


    public function getQuoteCount()
    {
        $quoteCount = Quote::where('user_id', auth()->user()->id)->count();
        return response()->json(['count' => $quoteCount]);
    }






    public function removequote($id)
    {
        $quote = Quote::find($id);
        $quote->delete();

        return redirect()->back()->with('success', 'Quote Deleted Successfully.');
    }




    public function showNotifications()
    {
        $notifications = AdminNotification::where('user_id', auth()->user()->id)
            ->where('read', false)
            ->get();

        return view('web.admin.notifications', compact('notifications'));
    }


    public function markNotificationAsRead($notificationId)
    {
        $notification = AdminNotification::findOrFail($notificationId);
        $notification->read = true;
        $notification->save();

        return redirect()->route('web.admin.notifications')->with('success', 'Notification marked as read.');
    }
}
