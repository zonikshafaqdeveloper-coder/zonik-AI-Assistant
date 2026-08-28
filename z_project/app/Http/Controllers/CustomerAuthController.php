<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\KYCDocument;
use App\Models\OutletPaymentTerm;
use App\Models\DairyPaymentTerm;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;
// use App\Services\SmsService;
// use Illuminate\Support\Facades\Notification;
use App\Notifications\NewEnqueryRequestCustomerNotification;
use App\Models\UserNotification;

// Add for excel import function :
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerCreateExport;
use App\Exports\OutletCustomersExport;

class CustomerAuthController extends Controller
{


   //  ADD THIS FUNCTION HERE FOR EXPORT TO EXCEL: 
   public function exportCustomerCreate()
    {
        return Excel::download(new CustomerCreateExport, 'create_customer.xlsx');
    }

    public function sendOtp(SmsService $smsService, Request $request)
    {

        $response = $smsService->sendOtp($request->mobile);

        return response()->json($response);
    }
    
     private function otpWasVerified(array $response): bool
    {
        if (array_key_exists('success', $response)) {
            return (bool) $response['success'];
        }
        if (array_key_exists('status', $response)) {
            return (bool) $response['status'];
        }

        $message = $response['message'] ?? '';
        return stripos($message, 'fail') === false
            && stripos($message, 'wrong') === false
            && stripos($message, 'success') !== false;
    }

    function verifyOtp(SmsService $smsService, Request $request)
    {
        $response = $smsService->verifyOtp($request->mobile, $request->otp);

        \Log::info('OTP verify attempt', [
            'mobile' => $request->mobile,
            'response' => $response,
        ]);

        if (!$this->otpWasVerified($response)) {
            return response()->json([
                'success' => false,
                'message' => 'Entered OTP is wrong.',
            ], 422);
        }

        $userExists = User::where('mobile_number', $request->mobile)->first();

        if ($userExists) {
            Auth::login($userExists);
            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully.',
            ]);
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already registered. Please use a different email or log in instead.',
            ], 422);
        }

        try {
            $CustomerNumber = rand(100000, 999999);

            $user = User::firstOrCreate(
                ['mobile_number' => $request->mobile],
                [
                    'name' => $request->name,
                    'customer_id' => $CustomerNumber,
                    'outlet_name' => $request->outlet_name,
                    'designation' => $request->designation,
                    'pincode' => $request->pincode,
                    'location' => $request->location,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]
            );

            Auth::login($user);

            $url = route('edit-customer', ['id' => $user->id], false);

            $adminNotification = new UserNotification();
            $adminNotification->user_id = $user->id;
            $adminNotification->title = 'New User Registered: ' . $user->name;
            $adminNotification->click_url = $url;
            $adminNotification->save();

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully.',
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Signup failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'This account could not be created. Please check your details and try again.',
            ], 422);
        }
    }

    function verifyloginOtp(SmsService $smsService, Request $request)
    {
        $response = $smsService->verifyOtp($request->mobile, $request->otp);

        \Log::info('Login OTP verify attempt', [
            'mobile' => $request->mobile,
            'response' => $response,
        ]);

        if (!$this->otpWasVerified($response)) {
            return response()->json([
                'success' => false,
                'message' => 'Entered OTP is wrong.',
            ], 422);
        }

        $userExists = User::where('mobile_number', $request->mobile)->first();

        if (!$userExists) {
            return response()->json([
                'success' => false,
                'message' => 'No account found for this number.',
            ], 404);
        }

        Auth::login($userExists);

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully.',
        ]);
    }


    // function verifyOtp(SmsService $smsService, Request $request)
    // {
    //     $response = $smsService->verifyOtp($request->mobile, $request->otp);
    //     $CustomerNumber = rand(100000, 999999);
    //     if ($response['message']) {
    //         $userExists = User::where('mobile_number', $request->mobile)->first();
    //         if($userExists){
    //             Auth::login($userExists);
    //         }else{
    //               $user = User::firstOrCreate([
    //                 'mobile_number' => $request->mobile,
    //                 'name' => $request->name,
    //                 'customer_id' => $CustomerNumber,
    //                 'outlet_name' => $request->outlet_name,
    //                 'designation' =>  $request->designation,
    //                 'pincode' =>  $request->pincode,
    //                 'location' =>  $request->location,
    //                  'email' => $request->email,
    //                 'password' => bcrypt($request->password),
    //                 ]);
    //                 Auth::login($user);

    //                 $url = route('edit-customer', [
    //                     'id' => $user->id,
    //                 ], false);
                    
    //                 $adminNotification = new UserNotification();
    //                 $adminNotification->user_id = $user->id;
    //                 $adminNotification->title = 'New User Registered: ' . $user->name;
    //                 $adminNotification->click_url = $url;  
    //                 $adminNotification->save();
    //         }


    //         return response()->json($response);
    //         // return Redirect::to('/homepage');

    //     }
    //     return redirect()->back()->withErrors(['otp' => 'Entered OTP is wrong.']);
    // }
    
    
    public function login(Request $request)
    {

        // dd($request->all());
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
    
        $customer = User::where('email', $request->email)->first();
    
        // Check if the user exists and if the password is correct
        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials are incorrect.',
            ]);
        }
    
        // Log the customer in
        Auth::login($customer);
    
        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
        ]);
    }

    public function validateemailmobile(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'mobile' => 'required|string|min:10', 
    ]);

    // Find the user based on email and mobile
    $user = User::where('email', $request->email)
                ->where('mobile_number', $request->mobile)
                ->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'The provided email and mobile number are invalid.',
        ]);
    }

    // Return success along with the user ID for further password update
    return response()->json([
        'success' => true,
        'message' => 'Valid email and mobile number found.',
        'user_id' => $user->id, // Pass user_id
    ]);
}



public function resetPassword(Request $request)
{

    $user = User::where('id', $request->user_id)
                ->first();


    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User is not authenticated. Please log in.']);
    }

    $validator = Validator::make($request->all(), [
        'newPassword' => 'required|min:6', 
        'confirmPassword' => 'required|min:6|same:newPassword',
    ]);

    
    if ($validator->fails()) {
        return response()->json(['success' => false, 'message' => 'Password is invalid or confirmation does not match.']);
    }


    $user->password = Hash::make($request->newPassword);

    
    $user->save();

    
    Auth::login($user);

    
    return response()->json(['success' => true, 'message' => 'Password updated successfully.']);
}



    // function verifyloginOtp(SmsService $smsService, Request $request)
    // {
    //     $response = $smsService->verifyOtp($request->mobile, $request->otp);
    //     $CustomerNumber = rand(100000, 999999);
    //     if ($response['message']) {
    //         $userExists = User::where('mobile_number', $request->mobile)->first();
    //         if($userExists){
    //             Auth::login($userExists);
    //         }


    //         return response()->json($response);
    //         // return Redirect::to('/homepage');

    //     }
    //     return redirect()->back()->withErrors(['otp' => 'Entered OTP is wrong.']);
    // }

    public function outletstore(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name'  => 'required|string|unique:users,name',
            'outlet_name'   => 'required|string|unique:users,outlet_name',
            'mobile_number' => 'required|string',
            'email'  => 'required|email|unique:users,email',
            'location' => 'required|string',
            'pincode' => 'required|string',
        ]);
    
        // Check if email already exists
        $existingUser = User::where('email', $validatedData['email'])->first();
    
        if ($existingUser) {
            return response()->json(['error' => 'Email already exists'], 400);
        }
    
        // Generate customer number
        $customerNumber = rand(100000, 999999);
    
        // Create new outlet
        $outlet = new User();
        $outlet->fill($validatedData);
        $outlet->verified_status = 'unverified';
        $outlet->type = 'outlet';
        $outlet->customer_id = $customerNumber;
        $outlet->priority = auth()->id();
        $outlet->save();
    
        $userId = $outlet->id;
    
        // Insert KYC document
        DB::table('k_y_c_documents')->insert([
            'user_id' => $userId,
            'group_id' => auth()->id(),
        ]);
    
        // Custom notification message with outlet_name
        $customMessage = "The outlet '{$validatedData['outlet_name']}' had been created successfully please click verify to submit document and place orders!";
        auth()->user()->notify(new NewEnqueryRequestCustomerNotification($outlet->id, $customMessage));
    
        // Retrieve the notification from the database and update `admin_read`
        $notificationRecord = auth()->user()->notifications()->latest()->first(); // Get the most recent notification
        if ($notificationRecord) {
            $notificationRecord->update(['admin_read' => true]);
        }
    
        return response()->json([
    'message' => $customMessage,
    'redirect_url' => url("verify-outlet/{$userId}")
], 200);
    }
    


    function checkEmail(Request $request)
{
    $exists = User::where('email', $request->email)->exists();

    return response()->json(['exists' => $exists]);
}


    function checkName($number)
    {
        $userExists = User::where('mobile_number', $number)->first();
        return response()->json($userExists);
    }

    function destroy()
    {
        Auth::logout();

        return Redirect::to('/');
    }

    // dont tuch upper code

    public function index()
    {
        $customers =  User::latest()->get();

        return view('admin.customers.index', compact('customers'));
    }



public function indexx(Request $request)
{
    $type = $request->get('type', 'group'); 
    $query = User::with('outlet');
    if ($type !== 'all') {
        $query->where('type', $type);
    }
    $customers = $query->latest()->get();
    return view('admin.customers.indexx', compact('customers'));
}

// public function indexx()
//     {
//         $customers =  User::latest()->get();
//         // $customers_outlet = User::where('type', 'Outlet')->latest()->get();
//         // $customers_group = User::where('type', 'Group')->latest()->get();

//         return view('admin.customers.indexx', compact('customers'));
//     }


// public function indexx1()
//     {

//         $customers_group = User::where('type', 'Group')->latest()->get();

//         return view('admin.customers.indexx1', compact('customers_group'));
//     }

   
    
     public function create()
     
     {

        return view('admin.back_customers.create_customer');

    }
    
        public function customer_edit($id)
{
    $customer = User::findOrFail($id);

    return view('admin.back_customers.edit_customer', compact('customer'));
}



    public function Savecustomer(Request $request)
{
    \Log::info("customer Save Request Started", $request->all());

    $validated = $request->validate([
        'name'          => 'required|string',
        'outlet_name'   => 'required|string',
        'email'         => 'required|email',
        'mobile_number' => 'required|string',
        'location'      => 'required|string',
        'pincode'       => 'required|string',
        'designation'  => 'nullable|string|max:255',
    ],
    
    [],
    
     [
    
      'outlet_name' => 'company name',

    ]
    
    );

   
    try {

        $customerNumber = rand(100000, 999999);

        $user = User::create([
            'name'        => $validated['name'],
            'outlet_name' => $validated['outlet_name'],
            'designation' => $validated['designation'],
            'email'       => $validated['email'],
            'mobile_number' => $validated['mobile_number'],
            'location'    => $validated['location'],
            'pincode'     => $validated['pincode'],
            'customer_id'  => $customerNumber,
        ]);

        return redirect()->route('customer.indexx')->with('success', 'Customer added successfully!');

    } catch (\Throwable $e) {
        \Log::error("Customer Save Failed", [
            'error' => $e->getMessage(),
            'line'  => $e->getLine(),
            'file'  => $e->getFile(),
        ]);

        return back()->with('error', $e->getMessage())->withInput();
    }
}

public function customer_update(Request $request, $id)
{
    $validated = $request->validate([
        'name'          => ['required','string', Rule::unique('users')->ignore($id)],
        'outlet_name'   => ['required','string', Rule::unique('users')->ignore($id)],
        'designation'   => 'nullable|string|max:255',
        'mobile_number' => 'required|string',
        'email'         => ['required','email', Rule::unique('users')->ignore($id)],
        'location'      => 'required|string',
        'pincode'       => 'required|numeric',
    ]);

    User::where('id', $id)->update($validated);

    return redirect()->route('customer.indexx')
            ->with('success', 'Customer updated successfully!');
}


   public function edit($id)
{
    $customer = User::findOrFail($id);

    $kyc = DB::table('k_y_c_documents')
        ->where('user_id', $id)
        ->first();

    $paymentTerm = DB::table('outlet_payment_terms')
        ->where('user_id', $id)
        ->first();

    $selectedCreditTermType = null;

    if (!empty($paymentTerm) && $paymentTerm->is_active) {
        $selectedCreditTermType = 'outlet';
    } elseif (!empty($customer->due_days_limit) && $customer->due_days_limit > 0) {
        $selectedCreditTermType = 'due_days';
    }

    return view('admin.customers.edit-customer', compact('customer', 'kyc', 'paymentTerm', 'selectedCreditTermType'));
}
    
     public function edit_outlet($id)
    {
        $user = User::where('id', $id)
            ->where('type', 'outlet')
            ->firstOrFail();

        $kyc = KYCDocument::where('user_id', $user->id)->first();

        return view('admin.customers.outletedit-customer', compact('user', 'kyc'));
    }
    
    
     public function outletadd($id)
    {
        $user_id = $id; 
        
        return view('admin.customers.outletadd-customer', compact('user_id'));
    }
    

// public function update(SmsService $smsService, Request $request, $id)
// {
//     // VALIDATION
//     $request->validate([
//         'name'            => 'required|string|max:255',
//         'credit_status'   => 'required|in:Active,Inactive',
//         'credit_limit'    => 'required|numeric',
//         'due_days_limit'  => 'required|integer',
//         'status'          => 'required|in:Active,Inactive',
//         'verified_status' => 'nullable|in:verified,unverified',
        
//         'from_range'      => 'nullable|numeric',
//         'to_range'        => 'nullable|numeric',
//         'days'            => 'nullable|integer',
//         'is_active' => 'nullable|boolean',
//     ]);

//     $customer = User::findOrFail($id);
//     $originalVerifiedStatus = $customer->verified_status;

//     // UPDATE FIELDS FIRST
//     $customer->update([
//         'name'           => $request->name,
//         'credit_status'  => $request->credit_status,
//         'credit_limit'   => $request->credit_limit,
//         'due_days_limit' => $request->due_days_limit,
//         'status'         => $request->status,
//     ]);

//     // HANDLE VERIFIED STATUS
//     if ($request->filled('verified_status')) {

//         $newVerifiedStatus = $request->verified_status;

//         if ($newVerifiedStatus !== $originalVerifiedStatus) {

//             $customer->verified_status = $newVerifiedStatus;
//             $customer->user_verified = $newVerifiedStatus === 'verified' ? 'true' : 'false';
//             $customer->save();

//             if ($newVerifiedStatus === 'verified') {

//                 // RUN SMS + NOTIFICATIONS AFTER REQUEST FINISHES
//                 register_shutdown_function(function () use ($smsService, $customer) {

//                     // Send SMS
//                     try {
//                         $smsService->sendVerificationSMS([
//                             'mobile_number' => $customer->mobile_number,
//                             'outlet_name'   => $customer->outlet_name,
//                         ]);
//                     } catch (\Exception $e) {
//                         \Log::error("SMS sending failed: ".$e->getMessage());
//                     }

//                     // Send Notification
//                     if ($customer->priority) {
//                         $originalUser = User::find($customer->priority);
//                         if ($originalUser) {
//                             try {
//                                 $originalUser->notify(new NewEnqueryRequestCustomerNotification(
//                                     $customer->id,
//                                     "Your outlet has been Verified, you can now Place orders at Zonik!"
//                                 ));
//                             } catch (\Exception $e) {
//                                 \Log::error("Notification failed: ".$e->getMessage());
//                             }
//                         }
//                     }

//                 });
//             }
//         }
//     }
    
//      OutletPaymentTerm::updateOrCreate(
//         ['user_id' => $customer->id],
//         [
//             'from_range' => $request->from_range,
//             'to_range'   => $request->to_range,
//             'days'       => $request->days,
//             'is_active'  => $request->is_active ? 1 : 0,
//         ]
//     );
    
//       if ($request->filled('due_limit_days')) {

//     DairyPaymentTerm::updateOrCreate(
//         ['user_id' => $customer->id],
//         [
//             'due_limit_days' => $request->due_limit_days,
//             'is_active'      => $request->dairy_is_active ? 1 : 0,
//         ]
//     );
// }

//     return redirect()->route('customer.indexx')->with('success', 'Customer updated successfully');
// }




public function update(SmsService $smsService, Request $request, $id)
{
    $customer = User::findOrFail($id);
    $originalVerifiedStatus = $customer->verified_status;

    $request->validate([
        'name'            => 'required|string|max:255',
        'credit_status'   => 'required|in:Active,Inactive',
        'credit_limit'    => 'required_if:credit_status,Active|nullable|numeric|min:1',
        'status'          => 'required|in:Active,Inactive',
        'verified_status' => 'nullable|in:verified,unverified',

        'credit_term_type' => 'required_if:credit_status,Active|nullable|in:due_days,outlet',

        'due_days_limit'  => 'required_if:credit_term_type,due_days|nullable|integer|min:1',

        'from_range'      => 'required_if:credit_term_type,outlet|nullable|numeric|min:1',
        'to_range'        => 'required_if:credit_term_type,outlet|nullable|numeric|min:1',
        'days'            => 'required_if:credit_term_type,outlet|nullable|integer|min:1',
        'is_active'       => 'nullable|boolean',

        'dairy_is_active' => 'nullable|boolean',
        'due_limit_days'  => 'required_if:dairy_is_active,1|nullable|integer|min:1',
    ], [
        'credit_limit.required_if'   => 'Credit Limit is required when Credit Status is Active.',
        'credit_limit.min'           => 'Credit Limit must be greater than 0.',
        'credit_term_type.required_if' => 'Please select either Due Limit Days or Outlet Payment Term.',

        'due_days_limit.required_if' => 'Due Limit Days is required for this credit term.',
        'due_days_limit.min'         => 'Due Limit Days must be greater than 0.',

        'from_range.required_if'     => 'From Range is required for Outlet Payment Term.',
        'from_range.min'             => 'From Range must be greater than 0.',
        'to_range.required_if'       => 'To Range is required for Outlet Payment Term.',
        'to_range.min'               => 'To Range must be greater than 0.',
        'days.required_if'           => 'Days is required for Outlet Payment Term.',
        'days.min'                   => 'Days must be greater than 0.',

        'due_limit_days.required_if' => 'Dairy Due Limit Days is required when Dairy Payment Term is enabled.',
        'due_limit_days.min'         => 'Dairy Due Limit Days must be greater than 0.',
    ]);

    if ($request->credit_status !== 'Active') {
        if ($request->filled('credit_term_type') || $request->boolean('dairy_is_active')) {
            return back()->withInput()->with('swal_error', 'Credit Status should be Active to select a Credit Term or enable Dairy Payment Term.');
        }
    }


    $termType = $request->credit_term_type;
    $dairyEnabled = $request->boolean('dairy_is_active');

    $creditLimit = $request->credit_status === 'Active' ? $request->credit_limit : 0;

    $customer->update([
        'name'           => $request->name,
        'credit_status'  => $request->credit_status,
        'credit_limit'   => $creditLimit,
        'due_days_limit' => $termType === 'due_days' ? $request->due_days_limit : 0,
        'status'         => $request->status,
    ]);

    
    if ($request->filled('verified_status')) {
        $newVerifiedStatus = $request->verified_status;

        if ($newVerifiedStatus !== $originalVerifiedStatus) {
            $customer->verified_status = $newVerifiedStatus;
            $customer->user_verified = $newVerifiedStatus === 'verified' ? 'true' : 'false';
            $customer->save();

            if ($newVerifiedStatus === 'verified') {
                register_shutdown_function(function () use ($smsService, $customer) {
                    try {
                        $smsService->sendVerificationSMS([
                            'mobile_number' => $customer->mobile_number,
                            'outlet_name'   => $customer->outlet_name,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error("SMS sending failed: " . $e->getMessage());
                    }

                    if ($customer->priority) {
                        $originalUser = User::find($customer->priority);
                        if ($originalUser) {
                            try {
                                $originalUser->notify(new NewEnqueryRequestCustomerNotification(
                                    $customer->id,
                                    "Your outlet has been Verified, you can now Place orders at Zonik!"
                                ));
                            } catch (\Exception $e) {
                                \Log::error("Notification failed: " . $e->getMessage());
                            }
                        }
                    }
                });
            }
        }
    }

    OutletPaymentTerm::updateOrCreate(
        ['user_id' => $customer->id],
        [
            'from_range' => $termType === 'outlet' ? $request->from_range : 0,
            'to_range'   => $termType === 'outlet' ? $request->to_range : 0,
            'days'       => $termType === 'outlet' ? $request->days : 0,
            'is_active'  => $termType === 'outlet' ? 1 : 0,
        ]
    );

    DairyPaymentTerm::updateOrCreate(
        ['user_id' => $customer->id],
        [
            'due_limit_days' => $dairyEnabled ? $request->due_limit_days : 0,
            'is_active'      => $dairyEnabled ? 1 : 0,
        ]
    );

       return redirect()->route('customer.indexx', ['type' => 'outlet'])->with('success', 'Customer updated successfully');

}
    

    
    
    public function exportOutlets()
{
    return Excel::download(new OutletCustomersExport, 'outlet-customers-' . now()->format('Y-m-d') . '.xlsx');
}


    public function productShow(Request $request, User $user)
    {

        if ($request->query('read_at') == '1') {
            $user->unreadNotifications->markAsRead();
        }

        if ($request->query('enquiry_no')) {
            $enquiriesData  = Enquiry::with('product')->where('user_id', $user->id)->where('enquiry_no',$request->query('enquiry_no'))->latest()->get();
            $enquirie = Enquiry::with('product')->where('user_id', $user->id)->where('enquiry_no',$request->query('enquiry_no'))->latest()->first();
            $enquiriesDataPanding = Enquiry::with('product')->where('status', '=', 'pending')->where('user_id', $user->id)->where('enquiry_no',$request->query('enquiry_no'))->latest()->get();
        } else {
            $enquiriesData  = Enquiry::with('product')->where('user_id', $user->id)->latest()->get();
            $enquirie = Enquiry::with('product')->where('user_id', $user->id)->latest()->first();
            $enquiriesDataPanding = Enquiry::with('product')->where('status', '=', 'pending')->where('user_id', $user->id)->latest()->get();
        }



        return view('admin.customers.product-details', compact('enquiriesData', 'enquiriesDataPanding', 'enquirie'));
    }



public function productShowdata(Request $request, User $user)
    {
        $status = $request->status;
        $enq_status = $request->enq_status;
        
        if ($request->notification_id) {
    DB::table('admin_notifications')
        ->where('id', $request->notification_id)
        ->update([
            'is_read' => 1,
            'updated_at' => now(),
        ]);
}



        if ($request->query('read_at') == '1') {
            $user->unreadNotifications->markAsRead();
        }
if( $enq_status !== 'submitted'){

     if ($request->query('enquiry_no')) {
            $enquiriesData  = Enquiry::with('product')->where('user_id', $user->id)->where('enquiry_no',$request->query('enquiry_no'))
            ->when($request->status, function ($query) use ($status) {
                $query->where('status', $status);
            })->where('status', '!=', 'submitted')->latest()->get();
            $enquirie = Enquiry::with('product')->where('user_id', $user->id)->where('enquiry_no',$request->query('enquiry_no'))->latest()->first();
            $enquiriesDataPanding = Enquiry::with('product')->where('status', '=', 'pending')->where('status', '!=', 'submitted')->where('user_id', $user->id)->where('enquiry_no',$request->query('enquiry_no'))->latest()->get();
        } else {
            $enquiriesData  = Enquiry::with('product')->where('user_id', $user->id)->when($request->status, function ($query) use ($status) {
                $query->where('status', $status);
            })->where('status', '!=', 'submitted')->latest()->get();
            $enquirie = Enquiry::with('product')->where('user_id', $user->id)->latest()->first();
            $enquiriesDataPanding = Enquiry::with('product')->where('status', '=', 'pending')->where('status', '!=', 'submitted')->where('user_id', $user->id)->latest()->get();
        }
}else{

     if ($request->query('enquiry_no')) {

        if($status === 'submitted'){
            $enquiriesData  = Enquiry::with('product')->where('user_id', $user->id)->when($request->status, function ($query) use ($status) {
            $query->where('status', $status);
            })
            ->where('enquiry_no',$request->query('enquiry_no'))
            ->where('status', '!=', 'pending')->latest()->get();
        }else{
            $enquiriesData  = Enquiry::with('product')->where('user_id', $user->id)
            ->where('enquiry_no',$request->query('enquiry_no'))
            ->where('status', '!=', 'pending')
            ->where('status', '!=', 'submitted')
            ->latest()->get();
        }

            $enquirie = Enquiry::with('product')->where('user_id', $user->id)->where('enquiry_no',$request->query('enquiry_no'))->latest()->first();
            $enquiriesDataPanding = Enquiry::with('product')->where('status', '=', 'pending')->where('user_id', $user->id)->where('enquiry_no',$request->query('enquiry_no'))->latest()->get();
        } else {
            if($status === 'submitted'){
                $enquiriesData  = Enquiry::with('product')->where('user_id', $user->id)->when($request->status, function ($query) use ($status) {
                $query->where('status', $status);
                })->where('status', '!=', 'pending')
                ->where('status', '!=', 'submitted')->latest()->get();
            }else{

                $enquiriesData  = Enquiry::with('product')->where('user_id', $user->id)
                ->where('status', '!=', 'pending')
                ->where('status', '!=', 'submitted')
                ->where('status', '!=', 'IsadminApproved')
                ->latest()->get();

                // dd($enquiriesData);
            }

            $enquirie = Enquiry::with('product')->where('user_id', $user->id)->latest()->first();
            $enquiriesDataPanding = Enquiry::with('product')->where('status', '=', 'pending')->where('user_id', $user->id)->latest()->get();
        }

}
if(count($enquiriesData) > 0){
    return view('admin.customers.product-detailss', compact('enquiriesData', 'enquiriesDataPanding', 'enquirie','status'));
}else{
    return redirect()->route('enquiry.indexx');
}

      }
      
      
      public function productShowdatanew(Request $request, User $user)
{
    $status     = $request->status;          // requested status
    $enq_status = $request->enq_status;      // filter from route
    $enquiry_no = $request->query('enquiry_no');

    if ($request->query('read_at') == '1') {
        $user->unreadNotifications->markAsRead();
    }

    // ---------------------------------------------
    // 🔥 BASE QUERY (Product + belongs to user)
    // ---------------------------------------------
    $query = Enquiry::with('product')
        ->where('user_id', $user->id);

    // ---------------------------------------------
    // 🔥 FILTER BY ENQUIRY NUMBER (if provided)
    // ---------------------------------------------
    if ($enquiry_no) {
        $query->where('enquiry_no', $enquiry_no);
    }

    // ---------------------------------------------
    // 🔥 MAIN LOGIC — Include Submitted Records Correctly
    // ---------------------------------------------
    if ($enq_status === 'submitted') {

        // if requested status = submitted → show only submitted
        if ($status === 'submitted') {
            $query->where('status', 'submitted');

        } else {
            // when user selects "Offer List"
            $query->where('status', 'submitted'); // include all submitted
        }

    } else {

        // default mode
        if ($status) {
            $query->where('status', $status);
        }

        // EXCLUDE ONLY pending, not submitted
        $query->where('status', '!=', 'pending');
    }

    // ---------------------------------------------
    // 🔥 FETCH RESULTS
    // ---------------------------------------------
    $enquiriesData = $query->latest()->get();

    // For single enquiry header info
    $enquirie = Enquiry::with('product')
        ->where('user_id', $user->id)
        ->when($enquiry_no, fn($q) => $q->where('enquiry_no', $enquiry_no))
        ->latest()
        ->first();

    // pending list
    $enquiriesDataPanding = Enquiry::with('product')
        ->where('user_id', $user->id)
        ->where('status', 'pending')
        ->when($enquiry_no, fn($q) => $q->where('enquiry_no', $enquiry_no))
        ->latest()
        ->get();

    // ---------------------------------------------
    // 🔥 RETURN VIEW
    // ---------------------------------------------
    if ($enquiriesData->count() > 0) {
        return view('admin.customers.product-detailssnew',
            compact('enquiriesData', 'enquiriesDataPanding', 'enquirie', 'status')
        );
    }

    // dd($enquiriesData);

    return redirect()->route('enquiry.indexx');
}



        public function BestCustomer(Request $request)
      {
          $selectedMonth = $request->input('month');
      
          $bestCustomers = \DB::table('orders')
              ->join('users', 'orders.user_id', '=', 'users.id') 
              ->select(
                  'orders.user_id', 
                  'orders.outlet_id', 
                  'users.outlet_name', 
                  'users.name', 
                  'users.mobile_number', 
                  \DB::raw('SUM(orders.total_discount_value) as total_amount'),
                  \DB::raw('COUNT(orders.id) as order_count') // Count number of orders
              )
              ->when($selectedMonth, function($query) use ($selectedMonth) {
                  $query->whereMonth('orders.created_at', $selectedMonth);
              }) // This will apply only if $selectedMonth is not empty
              ->groupBy('orders.user_id', 'orders.outlet_id', 'users.outlet_name', 'users.name', 'users.mobile_number') 
              ->orderBy('total_amount', 'desc') 
              ->get();
      
          $months = [
              1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 
              5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 
              9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
          ];
      
          return view('admin.customers.best_customer', compact('bestCustomers', 'months', 'selectedMonth'));
      }
      
      




    public function customerNotifications()
    {
        $customers_notifications = DB::table('notifications')->get();
        return view('admin.notifications.customer_list', compact('customers_notifications'));
    }

    public function orderNotifications()
    {
        $customers_notifications = DB::table('notifications')->get();

        return view('admin.notifications.order_list', compact('customers_notifications'));
    }


    public function adminNotifications()
    {
        $customers_notifications = DB::table('notifications')->get();
        //   $notifications = Notification::all();
        //   dd($customers_notifications);

        return view('admin.notifications.admin_list', compact('customers_notifications'));
    }

    // function delete($id)
    // {
    //     $user = User::find($id);
    //     $user->delete();
    //     return redirect()->back()->with(['customer delete successfully!.']);
    // }
    
    public function delete($id)
{
    DB::beginTransaction();

    try {
        $user = User::findOrFail($id);

      
        if ($user->type === 'outlet') {
            
            KYCDocument::where('user_id', $user->id)->delete();
        }

        
        $user->delete();

        DB::commit();

        return redirect()->back()->with('success', 'Customer deleted successfully!');

    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->back()->with('error', $e->getMessage());
    }
}



public function deleteEnquiry($id)
{
    $enquiry = Enquiry::find($id);


    if (!$enquiry) {
        return redirect()->back()->with('error', 'Enquiry not found.');
    }

    $enquiry->delete();

    return redirect()->back()->with('success', 'Enquiry deleted successfully.');
}



     public function customerNotificationss()
    {
        $customers_notifications = DB::table('notifications')->get();
        // dd($customers_notifications);
        return view('admin.notifications.new_customer_list', compact('customers_notifications'));
    }

    public function orderNotificationss()
    {
        $customers_notifications = DB::table('notifications')->get();

        return view('admin.notifications.new_order_list', compact('customers_notifications'));
    }


    public function adminNotificationss()
    {
        $customers_notifications = DB::table('notifications')->get();
        //   $notifications = Notification::all();
        //   dd($customers_notifications);

        return view('admin.notifications.new_admin_list', compact('customers_notifications'));
    }













}
