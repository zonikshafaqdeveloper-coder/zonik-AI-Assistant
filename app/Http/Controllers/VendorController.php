<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorPaymentTerm;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
// Add excel to export function in that:
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VendorExport;
use App\Exports\VendorPaymentExport;

class VendorController extends Controller
{
    // Add export to excel function:
   public function exportVendors()
   {
    return Excel::download(new VendorExport, 'vendors.xlsx');
    }

    /**
     * Display vendor list
     */
    public function index()
    {
        
    $vendors = Vendor::with('paymentTerm')
        ->latest()
        ->get();
        
        // dd($vendors);
        
        return view('admin.vendors.index', compact('vendors'));
    }

    //Export Payment Terms:
    public function exportVendorPayment()
{
    return Excel::download(
        new VendorPaymentExport,
        'vendor_payment_details.xlsx'
    );
} 

    /**
     * Store new vendor
     */

    public function create()
    {
        return view('admin.vendors.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string',
            'lead_time' => 'required|integer|min:0',
            'moq_type' => 'nullable|in:BOX,LOOSE',
            'mobile' => 'required|digits_between:1,12',
            'email'  => 'required|email',
            'location' => 'required|string',
            'pincode'  => 'required|string',
            'pan_number'   => 'required|string|unique:vendors,pan_number',
            'pan_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'gst_number'   => 'required|string|unique:vendors,gst_number',
            'gst_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'fssai_number'   => 'required|string|unique:vendors,fssai_number',
            'fssai_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('pan_document')) {
            $file = $request->file('pan_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/vendor_pan'), $filename);
            $validated['pan_document'] = $filename;
            // Log::info('Vendor PAN Uploaded', ['file' => $filename]);
        }


        if ($request->hasFile('gst_document')) {
            $file = $request->file('gst_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/vendor_gst'), $filename);
            $validated['gst_document'] = $filename;
            // Log::info('Vendor GST Uploaded', ['file' => $filename]);
        }

     
        if ($request->hasFile('fssai_document')) {
            $file = $request->file('fssai_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/vendor_fssai'), $filename);
            $validated['fssai_document'] = $filename;
            // Log::info('Vendor FSSAI Uploaded', ['file' => $filename]);
        }
        
        $validated['lead_time'] = $request->lead_time ?? 0;
        $validated['moq_type'] = $request->moq_type ?? 'LOOSE';

        Vendor::create($validated);

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Vendor created successfully.');
    }


    /**
     * Update vendor
     */

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);

        return view('admin.vendors.edit', compact('vendor'));
    }


    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('vendors', 'name')->ignore($vendor->id),
            ],
            'mobile' => [
                'required',
                'string',
                'digits_between:1,12',
                Rule::unique('vendors', 'mobile')->ignore($vendor->id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('vendors', 'email')->ignore($vendor->id),
            ],
            'location' => 'required|string',
            'pincode'  => 'required|string',
        
            'pan_number' => [
                'required',
                'string',
                Rule::unique('vendors', 'pan_number')->ignore($vendor->id),
            ],
            'pan_document' => [
                $vendor->pan_document ? 'nullable' : 'required',
                'file','mimes:jpg,jpeg,png,pdf','max:2048'
            ],
        
            'gst_number' => [
                'required',
                'string',
                Rule::unique('vendors', 'gst_number')->ignore($vendor->id),
            ],
            'gst_document' => [
                $vendor->gst_document ? 'nullable' : 'required',
                'file','mimes:jpg,jpeg,png,pdf','max:2048'
            ],
        
            'fssai_number' => [
                'required',
                'string',
                Rule::unique('vendors', 'fssai_number')->ignore($vendor->id),
            ],
            'fssai_document' => [
                $vendor->fssai_document ? 'nullable' : 'required',
                'file','mimes:jpg,jpeg,png,pdf','max:2048'
            ],
        
            'lead_time' => 'required|integer|min:0',
            'moq_type' => 'nullable|in:BOX,LOOSE',
        ]);

        /* -------------------------
        PAN Document
        --------------------------*/
        if ($request->hasFile('pan_document')) {
            $file = $request->file('pan_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/vendor_pan'), $filename);
            $validated['pan_document'] = $filename;

            // Log::info('Vendor PAN Updated', ['file' => $filename]);
        }

        /* -------------------------
        GST Document
        --------------------------*/
        if ($request->hasFile('gst_document')) {
            $file = $request->file('gst_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/vendor_gst'), $filename);
            $validated['gst_document'] = $filename;

            // Log::info('Vendor GST Updated', ['file' => $filename]);
        }

        /* -------------------------
        FSSAI Document
        --------------------------*/
        if ($request->hasFile('fssai_document')) {
            $file = $request->file('fssai_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/vendor_fssai'), $filename);
            $validated['fssai_document'] = $filename;

            // Log::info('Vendor FSSAI Updated', ['file' => $filename]);
        }
        
        $validated['lead_time'] = $request->lead_time ?? $vendor->lead_time ?? 0;
        $validated['moq_type'] = $request->moq_type ?? $vendor->moq_type ?? 'LOOSE';

        $vendor->update($validated);

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    /**
     * Delete vendor
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->back()->with('success', 'Vendor deleted successfully.');
    }
    
    
     public function payment_term($id)
    {
        $vendor = Vendor::findOrFail($id);

        $paymentTerm = VendorPaymentTerm::where('vendor_id', $vendor->id)->first();

        return view('admin.vendors.payment_term', compact('vendor', 'paymentTerm'));
    }

    public function storePaymentTerm(Request $request, $vendorId)
{
    $validated = $request->validate([
        'credit_status'       => 'required|in:active,inactive',
        'credit_limit'        => 'nullable|numeric|min:0',
        'due_limit_days'      => 'nullable|integer|min:0',
        'verified_status'     => 'required|in:verified,unverified',
        'from_range'          => 'nullable|numeric|min:0',
        'to_range'            => 'nullable|numeric|min:0',
        'days'                => 'nullable|integer|min:0',
        'custom_payment_term' => 'nullable|boolean',
    ]);

    VendorPaymentTerm::updateOrCreate(
        ['vendor_id' => $vendorId],
        [
            'credit_status'       => $validated['credit_status'],
            'credit_limit'        => $validated['credit_limit'] ?? null,
            'due_limit_days'      => $validated['due_limit_days'] ?? null,
            'verified_status'     => $validated['verified_status'],
            'from_range'          => $validated['from_range'] ?? null,
            'to_range'            => $validated['to_range'] ?? null,
            'days'                => $validated['days'] ?? null,
            'custom_payment_term' => $request->has('custom_payment_term'),
        ]
    );

    return redirect()
        ->back()
        ->with('success', 'Vendor payment terms saved successfully.');
}
    
    
    
}
