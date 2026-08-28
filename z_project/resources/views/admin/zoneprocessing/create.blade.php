@extends('admin.layouts.appnew')

@section('content')
<div class="page-body">
    <body>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Create Zone Processing</h3>
                                    <form method="POST" action="{{ route('zoneprocessing.store') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="zone_name">Zone Name</label>
                                                    <input type="text" class="form-control" id="zone_name" name="zone_name" placeholder="Enter zone name" value="{{ old('zone_name') }}">
                                                    @error('zone_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="processing_time">Processing Time</label>
                                                    <input type="text" class="form-control" id="processing_time" name="processing_time" placeholder="Enter processing time" value="{{ old('processing_time') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="shipping_time">Shipping Time</label>
                                                    <input type="text" class="form-control" id="shipping_time" name="shipping_time" placeholder="Enter shipping time" value="{{ old('shipping_time') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="delivery_time">Delivery Time</label>
                                                    <input type="text" class="form-control" id="delivery_time" name="delivery_time" placeholder="Enter delivery time" value="{{ old('delivery_time') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="bulk_delivery_charges">Bulk Delivery Charges</label>
                                                    <input type="text" class="form-control" id="bulk_delivery_charges" name="bulk_delivery_charges" placeholder="Enter bulk delivery charges" value="{{ old('bulk_delivery_charges') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="single_delivery_charges">Single Delivery Charges</label>
                                                    <input type="text" class="form-control" id="single_delivery_charges" name="single_delivery_charges" placeholder="Enter single delivery charges" value="{{ old('single_delivery_charges') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="packing_charge">Packing Charge</label>
                                                    <input type="text" class="form-control" id="packing_charge" name="packing_charge" placeholder="Enter packing charge" value="{{ old('packing_charge') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="others_charges">Others Charges</label>
                                                    <input type="text" class="form-control" id="others_charges" name="others_charges" placeholder="Enter other charges" value="{{ old('others_charges') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="same_day_timing">Next Day Time Limit</label>
                                                    <input type="time" class="form-control" id="same_day_timing" name="same_day_timing" value="{{ old('same_day_timing') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="next_day_timing">Same Day Time Limit</label>
                                                    <input type="time" class="form-control" id="next_day_timing" name="next_day_timing" value="{{ old('next_day_timing') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="pay_on_delivery">Pay on Delivery</label>
                                                    <select class="form-control form-select" id="pay_on_delivery" name="pay_on_delivery">
                                                        <option value="yes" {{ old('pay_on_delivery') == 'yes' ? 'selected' : '' }}>Yes</option>
                                                        <option value="no" {{ old('pay_on_delivery') == 'no' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="min_order">Minimum Order Value For Free Delivery</label>
                                                    <input type="text" class="form-control" id="min_order" name="min_order" placeholder="Enter Minimum Order" value="{{ old('min_order') }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                <div class="form-group">
                    <label for="same_day_slot">Same Day Slot</label>
                    <input type="text" class="form-control" id="same_day_slot" name="same_day_slot" 
                        placeholder="e.g. 03:00 pm to 07:00 pm" 
                      >
                </div>
            </div>

                        <div class="col-md-3">
                        <div class="form-group">
                        <label for="next_day_slot">Next Day Slot</label>
                        <input type="text" class="form-control" id="next_day_slot" name="next_day_slot" 
                                placeholder="e.g. 10:30 am to 01:30 pm" 
                               >
                        </div>
                        </div>

                        <div class="col-md-3">
                        <div class="form-group">
                        <label for="week_day_slot">Rest of Week Slot</label>
                        <input type="text" class="form-control" id="week_day_slot" name="week_day_slot" 
                                placeholder="e.g. 12:00 pm to 07:00 pm" 
                               >
                        </div>
                        </div>


                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="order_above">Pay On Deliver Only Above</label>
                                                    <input type="text" class="form-control" id="order_above" name="order_above" placeholder="Enter other charges" value="{{ old('order_above') }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="regular_days">Regular Days</label><br>

                                                <input type="checkbox" 
                                                    id="regular_days" 
                                                    name="regular_days" 
                                                    value="1"
                                                    {{ old('regular_days') ? 'checked' : '' }}>
                                                    
                                                <label for="regular_days">Enable</label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="mb-2 fw-bold">Select Delivery Days:</label>
                                        
                                                @php
                                                $days = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
                                                @endphp
                                        
                                                <div class="row">
                                                    @foreach($days as $day)
                                                        <div class="col-4">
                                                            <div class="form-check">
                                                                <input class="form-check-input" 
                                                                       type="checkbox" 
                                                                       name="delivery_days[]" 
                                                                       value="{{ $day }}"
                                                                       id="{{ $day }}"
                                                                       {{ in_array($day, old('delivery_days', [])) ? 'checked' : '' }}>
                                                                
                                                                <label class="form-check-label" for="{{ $day }}">
                                                                    {{ ucfirst($day) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                        
                                            </div>
                                        </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control form-select" id="status" name="status">
                                                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</div>
@endsection
