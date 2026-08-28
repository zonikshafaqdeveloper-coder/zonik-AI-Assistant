<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KYCDocument;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use App\Notifications\NewEnqueryRequestCustomerNotification;
use App\Notifications\AdminVerificationNotification;
use App\Models\AdminNotification;
use Illuminate\Validation\Rule;



class OutletController extends Controller
{
    public function verifyOutlet($id)
    {
        $user = User::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }

        return view('web.verify-outlet', ['user' => $user]);
    }

    public function submitOutletDocuments(Request $request)
    {

        $request->validate([
            'pancard' => 'required|string|max:255',
            'pancard_docs' => 'required|file|mimes:jpg,pdf|max:2048',
            'billing_address' => 'required|string|max:255',
            'billing_pincode' => 'required|string|max:255',
            'outlet_pincode' => 'required|string|max:255',
            'outlet_address' => 'required|string|max:255',
        ]);

        // Store Pancard document
        $pancardDocsPath = $request->file('pancard_docs')->store('pancard_docs');

        // Check if gst and gst_docs are present in the request
        $gst = $request->has('gst') ? $request->input('gst') : null;
        $gstDocsPath = $request->hasFile('gst_docs') ? $request->file('gst_docs')->store('gst_docs') : null;

        // Check if fssai and fssai_docs are present in the request
        $fssai = $request->has('fssai') ? $request->input('fssai') : null;
        $fssaiDocsPath = $request->hasFile('fssai_docs') ? $request->file('fssai_docs')->store('fssai_docs') : null;

        // Find user
        $user = User::find($request->id);

        // Find or create KYC document
        $kycDocument = KYCDocument::firstOrNew([
            'user_id' => $request->id,
            'group_id' => auth()->id()
        ]);

        // Update KYC document fields
        $kycDocument->pan_no = $request->pancard;
        $kycDocument->pan_document = $pancardDocsPath;
        $kycDocument->gst_no = $gst;
        $kycDocument->gst_document = $gstDocsPath;
        $kycDocument->fssai = $fssai;
        $kycDocument->fssai_document = $fssaiDocsPath;
        $kycDocument->billing_address = $request->billing_address;
        $kycDocument->billing_pincode = $request->billing_pincode;
        $kycDocument->outlet_address = $request->outlet_address;
        $kycDocument->outlet_pincode = $request->outlet_pincode;
        $kycDocument->verified_status = 'unverified';
        $kycDocument->email = $user->email;
        $kycDocument->phone = $user->mobile_number;
        $kycDocument->save();

        // Update user verified status
        $user->update(['verified_status' => 'unverified']);





      $customMessage = "Request for new outlet '{$user->outlet_name}' addition has been sent successfully for Approval for placing order!";
      // Send New Enquiry Request Customer Notification to the admin
      auth()->user()->notify(new NewEnqueryRequestCustomerNotification($kycDocument->id, $customMessage));

       $notificationRecord = auth()->user()->notifications()->latest()->first(); // Get the most recent notification
    if ($notificationRecord) {
        $notificationRecord->update(['admin_read' => true]);
    }
      
      $title = "Verify the outlet '{$user->outlet_name}'";
      $url = route('edit-customer', [
          'id' => $request->id,
      ], false);
  
      // Save the AdminNotification
      $adminNotification = new UserNotification();
      $adminNotification->user_id = $request->id;
      $adminNotification->title = $title;
      $adminNotification->click_url = $url;
    //   dd($adminNotification);
      $adminNotification->save();


       

        // Redirect back with success message
        return redirect('profile')->with('success', 'Documents submitted successfully!');
    }
    
    
//     public function saveOutlet(Request $request)
// {
//     // \Log::info("Outlet Save Request Started", $request->all());


//     $validated = $request->validate([
//         'name'          => 'required|string',
//         'outlet_name'   => 'required|string',
//         'email'         => 'required|email',
//         'mobile_number' => 'required|string',
//         'location'      => 'required|string',
//         'pincode'       => 'required|string',

//         'pancard' => 'nullable|string',
//         'pancard_docs'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

//         'gst_no'   => 'nullable|string',
//         'gst_docs' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

//         'fssai'         => 'nullable|string',
//         'fssai_docs'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

//         'billing_address' => 'required|string|max:255',
//         'billing_pincode' => 'nullable|string|max:255',
//         'outlet_address'  => 'required|string|max:255',
//         'outlet_pincode'  => 'nullable|string|max:255',
//     ]);

    
//     try {

//         $group_id = $request->user_id;
//         // \Log::info("Group ID Received: " . $group_id);

//         $customerNumber = rand(100000, 999999);

//         $user = User::create([
//             'name'        => $validated['name'],
//             'outlet_name' => $validated['outlet_name'],
//             'email'       => $validated['email'],
//             'mobile_number' => $validated['mobile_number'],
//             'location'    => $validated['location'],
//             'pincode'     => $validated['pincode'],
//             'verified_status'  => 'unverified',
//             'type'         => 'outlet',
//             'customer_id'  => $customerNumber,
//             'priority'     => $group_id,
//         ]);

       
//         $pancardPath = $request->file('pancard_docs')->store('pancard_docs');
//         $gstPath     = $request->hasFile('gst_docs') ? $request->file('gst_docs')->store('gst_docs') : null;
//         $fssaiPath   = $request->hasFile('fssai_docs') ? $request->file('fssai_docs')->store('fssai_docs') : null;

     
//         $kyc = KYCDocument::firstOrNew([
//             'user_id'  => $user->id,
//             'group_id' => $group_id,
//         ]);

//         $kyc->pan_no          = $validated['pancard'];
//         $kyc->pan_document    = $pancardPath;
//         $kyc->gst_no          = $validated['gst_no'] ?? null;
//         $kyc->gst_document    = $gstPath;
//         $kyc->fssai           = $validated['fssai'] ?? null;
//         $kyc->fssai_document  = $fssaiPath;
//         $kyc->billing_address = $validated['billing_address'];
//         $kyc->billing_pincode = $validated['billing_pincode'];
//         $kyc->outlet_address  = $validated['outlet_address'];
//         $kyc->outlet_pincode = $validated['outlet_pincode'];
//         $kyc->verified_status = 'unverified';
//         $kyc->save();

//         return redirect()->route('customer.indexx')->with('success', 'Outlet added successfully!');

//     } catch (\Throwable $e) {
//         \Log::error("Outlet Save Failed", [
//             'error' => $e->getMessage(),
//             'line'  => $e->getLine(),
//             'file'  => $e->getFile(),
//         ]);

//         return back()->with('error', $e->getMessage())->withInput();
//     }
// }


public function saveOutlet(Request $request)
{
    $validated = $request->validate([
        'account_type'  => 'required|in:personal,business',

        'name'          => 'required|string',
        'outlet_name'   => 'required|string',
        'email'         => 'required|email',
        'mobile_number' => 'required|string',
        'location'      => 'required|string',
        'pincode'       => 'required|string',

        'pancard'       => 'required|string',
        'pancard_docs'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',

        'gst_no'        => 'nullable|required_if:account_type,business|string',
        'gst_docs'      => 'nullable|required_if:account_type,business|file|mimes:jpg,jpeg,png,pdf|max:10240',

        'fssai'         => 'nullable|required_if:account_type,business|string',
        'fssai_docs'    => 'nullable|required_if:account_type,business|file|mimes:jpg,jpeg,png,pdf|max:10240',

        'billing_address' => 'required|string|max:255',
        'billing_pincode' => 'nullable|string|max:255',
        'outlet_address'  => 'required|string|max:255',
        'outlet_pincode'  => 'nullable|string|max:255',
    ]);

    try {

        $group_id = $request->user_id;

        $customerNumber = rand(100000, 999999);

        $user = User::create([
            'name'            => $validated['name'],
            'outlet_name'     => $validated['outlet_name'],
            'email'           => $validated['email'],
            'mobile_number'   => $validated['mobile_number'],
            'location'        => $validated['location'],
            'pincode'         => $validated['pincode'],
            'verified_status' => 'unverified',
            'type'            => 'outlet',
            'customer_id'     => $customerNumber,
            'priority'        => $group_id,
        ]);

        $pancardPath = $request->file('pancard_docs')->store('pancard_docs');
        $gstPath     = $request->hasFile('gst_docs') ? $request->file('gst_docs')->store('gst_docs') : null;
        $fssaiPath   = $request->hasFile('fssai_docs') ? $request->file('fssai_docs')->store('fssai_docs') : null;

        $kyc = KYCDocument::firstOrNew([
            'user_id'  => $user->id,
            'group_id' => $group_id,
        ]);

        $kyc->account_type    = $validated['account_type'];
        $kyc->pan_no          = $validated['pancard'];
        $kyc->pan_document    = $pancardPath;
        $kyc->gst_no          = $validated['gst_no'] ?? null;
        $kyc->gst_document    = $gstPath;
        $kyc->fssai           = $validated['fssai'] ?? null;
        $kyc->fssai_document  = $fssaiPath;
        $kyc->billing_address = $validated['billing_address'];
        $kyc->billing_pincode = $validated['billing_pincode'];
        $kyc->outlet_address  = $validated['outlet_address'];
        $kyc->outlet_pincode  = $validated['outlet_pincode'];
        $kyc->verified_status = 'unverified';
        $kyc->save();

       
        return redirect()->route('outlet.create', ['user_id' => $group_id])
            ->with('success', 'Outlet added successfully!');

    } catch (\Throwable $e) {
        \Log::error("Outlet Save Failed", [
            'error' => $e->getMessage(),
            'line'  => $e->getLine(),
            'file'  => $e->getFile(),
        ]);

        return back()->with('error', $e->getMessage())->withInput();
    }
}


// public function update_save(Request $request, $id)
// {
//     $user = User::findOrFail($id);
//     $kyc  = KYCDocument::where('user_id', $user->id)->firstOrFail();

//     $validated = $request->validate([
//         'name'          => 'required|string',
//         //  Rule::unique('users', 'outlet_name')->ignore($user->user_id, 'user_id'),
//         'outlet_name' => [
//     'required',
//     'string',
// ],
//         'email'         => 'required|email|unique:users,email,' . $user->id,
//         'mobile_number' => 'required|string',
//         'location'      => 'required|string',
//         'pincode'       => 'required|string',

//         'pancard'       => 'required|string',
//         'pancard_docs'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

//         // 'gst_no'        => 'required_if:account_type,business|nullable|unique:k_y_c_documents,gst_no,' . $kyc->id,
        
//       'gst_no' => [
//             'nullable',
//             'required_if:account_type,business',
//             'string',
//         ],


//         'gst_docs'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

//         // 'fssai'         => 'required_if:account_type,business|nullable|unique:k_y_c_documents,fssai,' . $kyc->id,
//         'fssai' => [
//             'nullable',
//             'required_if:account_type,business',
//             'string',
//         ],
//         'fssai_docs'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

//         'billing_address' => 'required|string',
//         'billing_pincode' => 'nullable|string',
//         'outlet_address'  => 'required|string',
//         'outlet_pincode'  => 'nullable|string',
//     ]);

//     // Update User
//     // $user->update([
//     //     'name'          => $validated['name'],
//     //     'outlet_name'   => $validated['outlet_name'],
//     //     'email'         => $validated['email'],
//     //     'mobile_number' => $validated['mobile_number'],
//     //     'location'      => $validated['location'],
//     //     'pincode'       => $validated['pincode'],
//     // ]);
    
//     $user->update([
//     'name'          => $validated['name'] ?? $user->name,
//     'outlet_name'   => $validated['outlet_name'] ?? $user->outlet_name,
//     'email'         => $validated['email'] ?? $user->email,
//     'mobile_number' => $validated['mobile_number'] ?? $user->mobile_number,
//     'location'      => $validated['location'] ?? $user->location,
//     'pincode'       => $validated['pincode'] ?? $user->pincode,
// ]);

//     // Update KYC
//     $kyc->pan_no = $validated['pancard'];

//     if ($request->hasFile('pancard_docs')) {
//         $kyc->pan_document = $request->file('pancard_docs')->store('pancard_docs');
//     }

//     if ($request->hasFile('gst_docs')) {
//         $kyc->gst_document = $request->file('gst_docs')->store('gst_docs');
//     }

//     if ($request->hasFile('fssai_docs')) {
//         $kyc->fssai_document = $request->file('fssai_docs')->store('fssai_docs');
//     }



//         $kyc->gst_no          = $validated['gst_no'] ?? null;
//         $kyc->fssai           = $validated['fssai'] ?? null;
//         $kyc->billing_address = $validated['billing_address'];
//         $kyc->billing_pincode = $validated['billing_pincode'] ?? null;
//         $kyc->outlet_address  = $validated['outlet_address'];
//         $kyc->outlet_pincode  = $validated['outlet_pincode'] ?? null;


//         $kyc->save();

//     return redirect()->route('edit.outlet', $user->id)
//         ->with('success', 'Outlet updated successfully!');
// }


public function update_save(Request $request, $id)
{
    $user = User::findOrFail($id);
    $kyc  = KYCDocument::where('user_id', $user->id)->firstOrFail();

    try {

        $validated = $request->validate([
            'account_type'  => 'required|in:personal,business',

            'name'          => 'required|string',
            'outlet_name'   => 'required|string',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'mobile_number' => 'required|string',
            'location'      => 'required|string',
            'pincode'       => 'required|string',

            'pancard'       => 'required|string',
            'pancard_docs'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',

            'gst_no'        => 'nullable|required_if:account_type,business|string',
            'gst_docs'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',

            'fssai'         => 'nullable|required_if:account_type,business|string',
            'fssai_docs'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',

            'billing_address' => 'required|string',
            'billing_pincode' => 'nullable|string',
            'outlet_address'  => 'required|string',
            'outlet_pincode'  => 'nullable|string',
        ]);

        $user->update([
            'name'          => $validated['name'],
            'outlet_name'   => $validated['outlet_name'],
            'email'         => $validated['email'],
            'mobile_number' => $validated['mobile_number'],
            'location'      => $validated['location'],
            'pincode'       => $validated['pincode'],
        ]);

        $kyc->account_type = $validated['account_type'];
        $kyc->pan_no        = $validated['pancard'];

        if ($request->hasFile('pancard_docs')) {
            $kyc->pan_document = $request->file('pancard_docs')->store('pancard_docs');
        }

        if ($request->hasFile('gst_docs')) {
            $kyc->gst_document = $request->file('gst_docs')->store('gst_docs');
        }

        if ($request->hasFile('fssai_docs')) {
            $kyc->fssai_document = $request->file('fssai_docs')->store('fssai_docs');
        }

        // Clear business-only fields if switched back to personal
        if ($validated['account_type'] === 'personal') {
            $kyc->gst_no   = null;
            $kyc->fssai    = null;
        } else {
            $kyc->gst_no = $validated['gst_no'] ?? null;
            $kyc->fssai  = $validated['fssai'] ?? null;
        }

        $kyc->billing_address = $validated['billing_address'];
        $kyc->billing_pincode = $validated['billing_pincode'] ?? null;
        $kyc->outlet_address  = $validated['outlet_address'];
        $kyc->outlet_pincode  = $validated['outlet_pincode'] ?? null;

        $kyc->save();

        return redirect()->route('edit.outlet', $user->id)
            ->with('success', 'Outlet updated successfully!');

    } catch (\Throwable $e) {
        \Log::error("Outlet Update Failed", [
            'error' => $e->getMessage(),
            'line'  => $e->getLine(),
            'file'  => $e->getFile(),
        ]);

        return back()->with('error', $e->getMessage())->withInput();
    }
}



    public function deleteOutlet($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        KYCDocument::where('user_id', $id)->delete();

        // Delete the user
        $user->delete();

        return redirect()->back()->with('success', 'Outlet and associated documents deleted successfully.');
    }

    public function update_outlet(Request $request)
    {

        $validatedData = $request->validate([
            'shippingAddress' => 'required',
            'pincode' => 'required',
        ]);

        $outlet = KYCDocument::where('user_id', $request->input('outlet_id'))->first();

        $outlet->outlet_address = $validatedData['shippingAddress'];
        $outlet->outlet_pincode = $validatedData['pincode'];
        $outlet->save();

        // Redirect back or return a response
        return redirect()->back()->with('success', 'Shipping address updated successfully!');
    }


}
