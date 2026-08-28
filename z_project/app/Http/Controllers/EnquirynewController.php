<?php

namespace App\Http\Controllers;

use App\Exports\EnquiryExport;
use App\Exports\ApprovedEnquiriesExport;
use App\Imports\EnquiryImport;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\Quote;
use App\Models\User;
use App\Services\SmsService;
use App\Notifications\NewQuoteRequest;
use App\Notifications\NewQuoteRequestCustomerNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class EnquirynewController extends Controller
{


    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
    {

        $status = $request->status;

        $enquiriesData_ = Enquiry::with('product')
            ->where('status', 'pending')
            ->orderByDesc('id') // Order by ID in descending order
            ->get();


        $collection = collect($enquiriesData_);

        $grouped = $collection->groupBy('enquiry_no');


        $latestInEachGroup = $grouped->map(function ($group) {
            return $group->sortByDesc('created_at')->first();
        });

        $enquiriesData = $latestInEachGroup->values()->all();


        $enquiriesDataPanding = Enquiry::with('product')->where('status', 'pending')->latest()->get();
        
         $notifications = DB::table('admin_notifications')
    ->where('is_read', 0)
    ->get()
    ->keyBy(function($item) {
        return $item->user_id . '_' . $item->click_url;
    });

    // dd($enquiriesData);

        return view("admin.newenquiries.index", compact('enquiriesData', 'enquiriesDataPanding', 'status','notifications'));
    }


    public function approved(Request $request)
    {

        $status = $request->status;

        $userIds = Enquiry::with('product', 'user')
        // ->where('status', 'submitted')
        ->where('status', 'accept')
        ->orderBy('id')
        ->latest()
        ->pluck('user_id')
        ->unique();

    $enquiriesData = collect();
    foreach ($userIds as $userId) {
        $enquiry = Enquiry::with('product', 'user')
            // ->where('status', 'submitted')
             ->where('status', 'accept')
            ->where('user_id', $userId)
            ->orderBy('id')
            ->latest()
            ->first();

        $enquiryCount = Enquiry::where('status', 'accept')
            ->where('user_id', $userId)
            ->count();
        $enquiry->enquiriescount = $enquiryCount;

        $enquiriesData->push($enquiry);
    }

        // $collection = collect($enquiriesData_);
        // dd($enquiriesData);
        // $grouped = $collection->groupBy('enquiry_no');


        // $latestInEachGroup = $grouped->map(function ($group) {
        //     return $group->sortByDesc('created_at')->first();
        // });
        // $enquiriesData = $latestInEachGroup->values()->all();
        $enquiriesData = $enquiriesData->sortByDesc('id');

        $enquiriesDataPanding = Enquiry::with('product')->where('status', 'pending')->latest()->get();
        
        $notifications = DB::table('admin_notifications')
     ->where('is_read', 0)
     ->get()
     ->keyBy(function($item) {
        return $item->user_id . '_' . $item->click_url;
     });
     

        return view("admin.newenquiries.product_approved_list", compact('enquiriesData', 'enquiriesDataPanding', 'status','notifications'));
    }
    
    
    public function approvedExport()
{
    $timestamp = now()->format('Y-m-d_H-i-s');
    $filename = $timestamp . '_Approved_Enquiries.xlsx';
    return Excel::download(new ApprovedEnquiriesExport, $filename);
}



    public function approved_customer(Request $request)
{
    // Fetch all enquiries based on user_id from the request
    $enquiriesData = Enquiry::with('product', 'user')
        ->where('status', 'accept')
        ->where('user_id', $request->user_id)
        ->orderBy('id', 'desc')
        ->get();
        

    // Initialize a collection to store the users with matching priorities
    $usersWithPriority = collect();

    // Loop through each enquiry to get user information based on priority
    foreach ($enquiriesData as $enquiry) {
        // Fetch the user associated with the enquiry
        $user = $enquiry->user;

        // Fetch the user where the priority matches the user's ID
        $userWithPriority = User::where('priority', $user->id)->get();  // or `where('priority', auth()->id())`

        // If a user is found, store it in the collection
        if ($userWithPriority) {
            $usersWithPriority->push($userWithPriority);
        }

        // Optionally, you can add more logic here to attach this information to the enquiry, e.g.:
        $enquiry->user_with_priority = $userWithPriority;
    }
    // dd($userWithPriority);

    // Pass the data to the view
    return view("admin.newenquiries.product_approved_price_list", compact('enquiriesData', 'usersWithPriority'));
}


    public function store(Request $request)
    {


        $enquiryData = $request->all();

        $enquiries = [];

        foreach ($enquiryData as $key => $value) {
            if (strpos($key, 'product_id') === 0 && isset($enquiryData['quantity' . substr($key, 10)]) && isset($enquiryData['product_types' . substr($key, 10)])) {
                $product_id = $value;
                $quantity = $enquiryData['quantity' . substr($key, 10)];
                $product_types = $enquiryData['product_types' . substr($key, 10)];
                $monthlyconsumption = $enquiryData['monthlyconsumption' . substr($key, 10)];
                $offer_price = $enquiryData['offer_price' . substr($key, 10)];
                $discount = $enquiryData['discount' . substr($key, 10)];
                $mrp = $enquiryData['mrp' . substr($key, 10)];

                $user = auth()->user();
                $enquiryCounts  = Enquiry::get();

                if ($enquiryCounts->count() == 0) {
                    $enquiry_no = 'Diz-Enq-' . now()->format('y') . '-' . sprintf('%03d', $enquiryCounts->count() + 1);
                } else {
                    foreach ($enquiryCounts as $key => $value) {
                        $existing_enquiry_no =  $value->enquiry_no;
                        if ($existing_enquiry_no) {
                            $next_number = sprintf('%03d', intval(substr($existing_enquiry_no, -3)) + 1);
                            $enquiry_no = 'Diz-Enq-' . now()->format('y') . '-' . $next_number;
                        }
                    }
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
                ];
            }
        }
        if (!empty($enquiries)) {
            foreach ($enquiries as $enquiry) {
                Enquiry::create($enquiry);
            }
        }
        
        // Add for solving error:
        $user = auth()->user();
        Notification::send($user, new NewQuoteRequest($user->name));

        $quotes = Quote::with('product')->where('user_id', auth()->user()->id)->get();

        $quotes->each->delete();

        return redirect()->back()->with('success', 'Your Enquery was added successful!');
    }


    function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->route('customer.index');
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

    public function editt(Enquiry $enquiry)
    {

        $products = Product::all();

        return view("admin.newenquiries.edit", compact('enquiry', 'products'));
    }

         public function updatestatus(SmsService $smsService, Request $request, Enquiry $enquiry)
        {
            // Retrieve the current offer price from the request
            $newOfferPrice = (int)$request->offer_price;
        
            // Retrieve the previous offer price from the database
            $previousOfferPrice = (int)$enquiry->offer_price;
            
            // Check if the offer price has changed
           if ($newOfferPrice !== $previousOfferPrice) {
                $enquiry->reoffer = 'yes';
                $enquiry->save();
            }

      
            $enquiry->update($request->all());
        
            // Find the user associated with the enquiry
            $user = User::find($enquiry->user_id);
        
            // Notify the user about the updated quote request
            if ($user && $user->mobile_number ) {
             $user->notify(new NewQuoteRequestCustomerNotification());
            // $response = 
           $smsService->sendOrderDetails(
           $user->mobile_number,
            $enquiry->enquiry_no,
           'Enquiry approved' 
            );
            }
            // Redirect back to the index route with a success message
            return redirect()->route('enquiry.indexx')->with('success', 'Pending Enquiry updated successfully.');
        }


    public function offerRequest(Request $request, Enquiry $enquiry)
    {

        $enquiry->update($request->all());

        return redirect()->back()->with('success', 'updated successfully.');
    }


    public function statusUpdate(Request $request, $status)
    {

        if ($request->id) {
            Enquiry::where('status', $status)->where('user_id', $request->id)->update(['status' => 'submitted']);
        } else {
            Enquiry::where('status', $status)->update(['status' => 'submitted']);
        }


         // Add for solving error:
         $user = auth()->user();
         Notification::send($user, new NewQuoteRequest($user->name));


        return redirect()->route('enquiry.index')->with('success', 'Pending Enquiry updated successfully.');
    }

    public function statusChanges($status)
    {
        $enquiriesData = Enquiry::with('product')
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->get();
        return view("admin.newenquiries.view", compact('enquiriesData', 'status'));
    }


 public function submittedview($status)
{
    $enquiriesCount = Enquiry::whereIn('status', ['pending', 'rejected'])
                             ->where('offer_check', '1')                            
                             ->count();

    $enquiriesCountt = Enquiry::where('status', 'pending')
                              ->where('offer_check','!=','1')                            
                              ->count();

    if ($status === 'submitted') {
        $enquiriesData = Enquiry::with('product')
            ->whereIn('status', ['pending', 'rejected'])
            ->where('offer_check', '1')
            ->orderBy('updated_at', 'desc')
            ->get();
    } else {
        $enquiriesData = collect(); // empty if not submitted
    }

 $notifications = DB::table('admin_notifications')
    ->where('is_read', 0)
    ->get()
    ->keyBy(function($item) {
        return $item->user_id . '_' . $item->click_url;
    });


    // dd($enquiriesData);

    return view("admin.newenquiries.submitted_view", compact('enquiriesData', 'status', 'enquiriesCount', 'enquiriesCountt','notifications'));
}

  public function submittedviewAll()
{
    $enquiriesData = Enquiry::with('product')
        ->where('status', 'submitted')   // FIXED
        ->orderBy('id', 'desc')
        ->get();

    // dd($enquiriesData);

    return view("admin.newenquiries.submitted_view1", compact('enquiriesData'));
}



        // public function submittedview($status)
        // {
            
        //     $enquiriesCount = Enquiry::where('status', 'submitted')->count();
        //     $enquiriesCountt = Enquiry::whereIn('status', ['accept', 'rejected', 'reoffer'])->count();

            
            
        //   if ($status === 'submitted') {
        //     $enquiriesData = Enquiry::with('product')
        //         ->when($status, function ($query) use ($status) {
        //             $query->where('status', $status);
        //         })
        //         ->orderBy('updated_at', 'desc') // Order by updated_at in descending order
        //         ->get();
        // } elseif ($status !== 'submitted') {
        //     $enquiriesData = Enquiry::with('product')
        //         ->whereIn('status', ['accept', 'rejected', 'reoffer'])
        //         ->orderBy('updated_at', 'desc') // Order by updated_at in descending order
        //         ->get();
        // }



        //     // dd( $enquiriesData);
        //     return view("admin.newenquiries.submitted_view", compact('enquiriesData', 'status','enquiriesCount','enquiriesCountt'));
        // }

        // public function submittedviewAll()
        // {
        //     $enquiriesData = Enquiry::with('product')
        //     ->whereIn('status', ['accept', 'rejected', 'reoffer'])
        //     ->orderBy('id', 'desc')
        //     ->get();

        //     // dd($enquiriesData);

        //     return view("admin.newenquiries.submitted_view", compact('enquiriesData'));
        // }



    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->back()->with('success', 'updated successfully.');
    }


    function offerPriceUpdate(Request $request)
    {

        $offerPriceValue = $request->all();
        $enquiries_offer_prices = [];

        foreach ($offerPriceValue as $key => $value) {
            if (strpos($key, 'id') === 0 && isset($offerPriceValue['offer_price' . substr($key, 2)])) {
                $id = $value;
                $offer_price = $offerPriceValue['offer_price' . substr($key, 2)];

                $enquiries_offer_prices[] = compact('id', 'offer_price');
            }
        }

        if (!empty($enquiries_offer_prices)) {
            foreach ($enquiries_offer_prices as $enquiries_offer_price) {
                $enquiry = Enquiry::find($enquiries_offer_price['id']);
                $enquiry->update([
                    'offer_price' => $enquiries_offer_price['offer_price'],
                ]);
            }
        }


        return redirect()->back()->with('success', 'Enquiry updated successfully.');
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
   
    // Add export to excel feature: 
public function exportData(Request $request)
{
    return Excel::download(
        new EnquiryExport([
            'enquiry_no' => $request->enquiry_no
        ]),
        'Enquiry.xlsx'
    );
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
}
