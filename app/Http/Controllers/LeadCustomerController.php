<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadCustomer;

class LeadCustomerController extends Controller
{
     public function index()
    {
        $leadCustomers = LeadCustomer::latest()->get();
        return view('admin.lead_customer.index', compact('leadCustomers'));
    }

    public function create()
    {
        return view('admin.lead_customer.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'outlet_name'      => 'required|string|max:255',
            'location_cluster' => 'required|string|max:255',
            'area'             => 'required|string|max:255',
            'outbound_sale_name' => 'required|string|max:255',
            'inbound_account_lead' => 'required|string|max:255',
            'address'          => 'required|string|max:500',
            'mobile_number'    => 'required|string|max:15',
            'payment_term'     => 'required|numeric|min:0',
        ]);

        LeadCustomer::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lead Customer created successfully.',
            'redirect_url' => route('lead-customers.index')
        ]);
    }

    public function edit($id)
    {
        $leadCustomer = LeadCustomer::findOrFail($id);
        return view('admin.lead_customer.edit', compact('leadCustomer'));
    }

    public function update(Request $request, $id)
    {
        $leadCustomer = LeadCustomer::findOrFail($id);

        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'outlet_name'      => 'required|string|max:255',
            'location_cluster' => 'required|string|max:255',
            'area'             => 'required|string|max:255',
            'outbound_sale_name' => 'required|string|max:255',
            'inbound_account_lead' => 'required|string|max:255',
            'address'          => 'required|string|max:500',
            'mobile_number'    => 'required|string|max:15',
            'payment_term'     => 'required|numeric|min:0',
        ]);

        $leadCustomer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lead Customer updated successfully.',
            'redirect_url' => route('lead-customers.index')
        ]);
    }

    public function destroy($id)
    {
        $leadCustomer = LeadCustomer::findOrFail($id);
        $leadCustomer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lead Customer deleted successfully.'
        ]);
    }
}
