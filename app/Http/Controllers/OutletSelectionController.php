<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\KYCDocument;
use App\Notifications\NewEnqueryRequestCustomerNotification;

class OutletSelectionController extends Controller
{
    public function selectOutlet()
    {
        if (auth()->check()) {
            $priorityUsers = User::where('priority', auth()->user()->id)->get();

            foreach ($priorityUsers as $priorityUser) {
                if ($priorityUser->verified_status === 'verified' && $priorityUser->user_verified === 'false') {
                    $priorityUser->update(['user_verified' => true]);
                }
            }
        }
        
         $notificationCount = Notification::where('notifiable_id', auth()->user()->id)
        ->where('read', 'false')
        ->count();
        

        $outlets = User::where('priority', auth()->user()->id)
            ->where('type', 'outlet')
            ->get();

        if ($outlets->count() === 0) {
            return view('web.outlet.select', [
                'outlets' => $outlets,
                'currentOutletId' => null,
                'notificationCount' => $notificationCount,
            ]);
        }

        if ($outlets->count() === 1) {
            $onlyOutlet = $outlets->first();

            auth()->user()->update(['selected_outlet_id' => $onlyOutlet->id]);

            return redirect()->route('web.price.list');
        }

        $currentOutletId = auth()->user()->selected_outlet_id;

        return view('web.outlet.select', [
            'outlets'         => $outlets,
            'currentOutletId' => $currentOutletId,
            'notificationCount' => $notificationCount,
        ]);
    }
    
    
    public function manageOutlets()
{
    $notificationCount = Notification::where('notifiable_id', auth()->user()->id)
        ->where('read', 'false')
        ->count();

    $outlets = User::where('priority', auth()->user()->id)
        ->where('type', 'outlet')
        ->get();

    $currentOutletId = auth()->user()->selected_outlet_id;

    return view('web.outlet.select', [
        'outlets'           => $outlets,
        'currentOutletId'   => $currentOutletId,
        'notificationCount' => $notificationCount,
    ]);
}
    
    
    public function chooseOutlet($id)
    {
        $outlet = User::where('priority', auth()->user()->id)
            ->where('type', 'outlet')
            ->where('id', $id)
            ->firstOrFail();

        auth()->user()->update(['selected_outlet_id' => $outlet->id]);

        return redirect()->route('web.price.list');
    }
    

   public function createOutletForm()
{
    $legalCompanyName = auth()->user()->outlet_name ?? null;
    return view('web.outlet.create', compact('legalCompanyName'));
}

public function outletStore(Request $request)
{
    $accountType = $request->input('account_type', 'personal');

    $rules = [
        'outlet_name'       => 'required|string|max:255|unique:users,outlet_name',
        'email'             => 'required|string|email:rfc,filter|max:255|unique:users,email',
        'full_address'      => 'required|string|max:500',
        'suburb'            => 'required|string',
        'region'            => 'required|in:east,west',
        'city'              => 'required|string|max:100',
        'delivery_pincode'  => 'required|digits:6',
        'receiver_name'     => 'required|string|max:255',
        'receiver_mobile'   => 'required|digits:10|regex:/^[6-9][0-9]{9}$/',
    ];

    if ($accountType === 'business') {
        $rules += [
            'company_pan_number'     => 'required|string|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'company_pan_docs'       => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'gst_number'             => 'required|string|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            'gst_docs'               => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'fssai_number'           => 'required|digits:14',
            'fssai_docs'             => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'owner_id_docs_business' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    } else {
        $rules += [
            'pan_number'    => 'required|string|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'pan_docs'      => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'owner_id_docs' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }

    $validated = $request->validate($rules, [
        // Outlet Information
        'outlet_name.required'        => 'Please enter the outlet name.',
        'outlet_name.unique'          => 'An outlet with this name already exists.',
        'email.required'              => 'Please enter your email address.',
        'email.email'                 => 'Enter a valid email address.',
        'email.unique'                => 'This email is already registered.',

        // Personal
        'pan_number.required'         => 'Please enter your PAN card number.',
        'pan_number.regex'            => 'Enter a valid PAN number (e.g. ABCDE1234F).',
        'pan_docs.required'           => 'Please upload your PAN card document.',
        'pan_docs.mimes'              => 'PAN card must be a JPG, PNG, or PDF file.',
        'pan_docs.max'                => 'PAN card file size must be under 5MB.',
        'owner_id_docs.required'      => 'Please upload your Owner Photo ID.',
        'owner_id_docs.mimes'         => 'Owner Photo ID must be a JPG, PNG, or PDF file.',
        'owner_id_docs.max'           => 'Owner Photo ID file size must be under 5MB.',

        // Business
        'company_pan_number.required' => 'Please enter your company PAN number.',
        'company_pan_number.regex'    => 'Enter a valid PAN number (e.g. ABCDE1234F).',
        'company_pan_docs.required'   => 'Please upload your company PAN document.',
        'company_pan_docs.mimes'      => 'Company PAN must be a JPG, PNG, or PDF file.',
        'company_pan_docs.max'        => 'Company PAN file size must be under 5MB.',
        'gst_number.required'         => 'Please enter your GST number.',
        'gst_number.regex'            => 'Enter a valid 15-character GST number.',
        'gst_docs.required'           => 'Please upload your GST certificate.',
        'gst_docs.mimes'              => 'GST certificate must be a JPG, PNG, or PDF file.',
        'gst_docs.max'                => 'GST certificate file size must be under 5MB.',
        'fssai_number.required'       => 'Please enter your FSSAI number.',
        'fssai_number.digits'         => 'FSSAI number must be exactly 14 digits.',
        'fssai_docs.required'         => 'Please upload your FSSAI certificate.',
        'fssai_docs.mimes'            => 'FSSAI certificate must be a JPG, PNG, or PDF file.',
        'fssai_docs.max'              => 'FSSAI certificate file size must be under 5MB.',
        'owner_id_docs_business.required' => 'Please upload your Owner Photo ID.',
        'owner_id_docs_business.mimes'    => 'Owner Photo ID must be a JPG, PNG, or PDF file.',
        'owner_id_docs_business.max'      => 'Owner Photo ID file size must be under 5MB.',

        // Delivery Address
        'full_address.required'       => 'Please enter the delivery address.',
        'suburb.required'             => 'Please select a suburb or locality.',
        'region.required'             => 'Please select East or West.',
        'city.required'                => 'Please enter the city.',
        'delivery_pincode.required'   => 'Please enter the delivery pin code.',
        'delivery_pincode.digits'     => 'Pin code must be exactly 6 digits.',

        // Receiver Details
        'receiver_name.required'      => "Please enter the receiver's name.",
        'receiver_mobile.required'    => "Please enter the receiver's mobile number.",
        'receiver_mobile.digits'      => 'Mobile number must be exactly 10 digits.',
        'receiver_mobile.regex'       => 'Mobile number must start with 6, 7, 8, or 9 and be 10 digits long.',
    ]);

    DB::beginTransaction();

    try {
        $customerNumber = rand(100000, 999999);

        $regionLabel   = ucfirst($validated['region']);
        $locationValue = trim($validated['suburb'] . ' ' . $validated['city']);

        $outlet = User::create([
            'name'            => $validated['receiver_name'],
            'outlet_name'     => $validated['outlet_name'],
            'email'           => $validated['email'],
            'mobile_number'   => $validated['receiver_mobile'],
            'location'        => $locationValue,
            'pincode'         => $validated['delivery_pincode'],
            'type'            => 'outlet',
            'customer_id'     => $customerNumber,
            'priority'        => auth()->id(),
            'verified_status' => 'unverified',
            'user_verified'   => 'false',
            'new_user'        => 'true',
            'credit_status'   => 'Active',
            'status'          => 'Active',
        ]);

        $panPath = $accountType === 'business'
            ? $request->file('company_pan_docs')->store('pancard_docs')
            : $request->file('pan_docs')->store('pancard_docs');

        $gstPath     = $accountType === 'business' ? $request->file('gst_docs')->store('gst_docs') : null;
        $fssaiPath   = $accountType === 'business' ? $request->file('fssai_docs')->store('fssai_docs') : null;
        $ownerIdPath = $accountType === 'business'
            ? $request->file('owner_id_docs_business')->store('owner_id_docs')
            : $request->file('owner_id_docs')->store('owner_id_docs');

        $fullAddressWithRegion = $validated['full_address'] . ', ' . $regionLabel;

        KYCDocument::create([
            'user_id'           => $outlet->id,
            'account_type'      => $accountType,
            'group_id'          => auth()->id(),
            'phone'             => $validated['receiver_mobile'],
            'pan_no'            => $accountType === 'business' ? $validated['company_pan_number'] : $validated['pan_number'],
            'pan_document'      => $panPath,
            'gst_no'            => $validated['gst_number'] ?? null,
            'gst_document'      => $gstPath,
            'fssai'             => $validated['fssai_number'] ?? null,
            'fssai_document'    => $fssaiPath,
            'owner_id_document' => $ownerIdPath,
            'billing_address'   => $fullAddressWithRegion,
            'billing_pincode'   => $validated['delivery_pincode'],
            'outlet_address'    => $fullAddressWithRegion,
            'outlet_pincode'    => $validated['delivery_pincode'],
            'verified_status'   => 'unverified',
        ]);

        // --- Notification 1: to the CUSTOMER (the logged-in user who created this outlet) ---
        $customerMessage = "The outlet '{$validated['outlet_name']}' had been created successfully please wait for approval or call to Zonik team.";

        auth()->user()->notify(new NewEnqueryRequestCustomerNotification($outlet->id, $customerMessage));

        // --- Notification 2: to the ADMIN, via UserNotification ---
        $adminTitle = "Verify the outlet '{$outlet->outlet_name}'";
        $adminUrl   = route('edit-customer', ['id' => $outlet->id], false);

        $adminNotification = new UserNotification();
        $adminNotification->user_id   = $outlet->id;
        $adminNotification->title     = $adminTitle;
        $adminNotification->click_url = $adminUrl;
        $adminNotification->save();

        $totalOutlets = User::where('priority', auth()->id())->where('type', 'outlet')->count();

        if ($totalOutlets === 1) {
            auth()->user()->update(['selected_outlet_id' => $outlet->id]);
        }

        DB::commit();

        return response()->json([
            'message'      => $customerMessage,
            'redirect_url' => route('web.outlet.select'),
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();

        \Log::error('Outlet Save Failed', [
            'error' => $e->getMessage(),
            'line'  => $e->getLine(),
        ]);

        return response()->json(['error' => $e->getMessage()], 500);
    }
}


}