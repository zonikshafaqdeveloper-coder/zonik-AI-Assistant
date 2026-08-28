@extends('web.layouts.app')

@section('content')
    <!-- --------------order dropdown----------------- -->

    <div class="container ">


<div class="row mt-5 mb-2">
    <div class="col-lg-5">
        <h2 class="page-h">Verify Outlet</h2>
    </div>
</div>



 <div class="row my-3">
    <div class="col-md-12 left-div">
        <form method="post" action="{{ route('submitoutletDocuments', ['id' => $user->id]) }}" enctype="multipart/form-data">

            @csrf
            <div class="row my-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Outlet Name<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="outlet_name" class="form-control" readonly aria-describedby="helpId"
                            value="{{ $user->outlet_name }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="email">Email<span class="text-danger">*</span></label>
                        <input type="text" name="email" id="email" class="form-control" readonly aria-describedby="helpId"
                            value="{{ $user->email }}">
                    </div>
                </div>
            </div>

            <div class="row">    
                  <label for="gst">Used for:<span class="text-danger">*</span></label>
                <div class="col-md-2">

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="Personal">
                        <label class="form-check-label" for="exampleRadios1">
                            Personal
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="Business" checked>
                        <label class="form-check-label" for="exampleRadios2">
                            Business
                        </label>
                    </div>
                </div>
            </div>

            <div class="row my-3" id="pancardRow">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="pancard">Pancard No<span class="text-danger">*</span></label>
                        <input type="text" name="pancard" id="pancard" class="form-control" placeholder="ABCDE1234F" aria-describedby="helpId" maxlength="10"  required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="pancard_docs">Upload Pancard<span class="text-danger">*</span></label>
                        <input type="file" name="pancard_docs" id="pancard_docs" class="form-control" aria-describedby="helpId" accept=".jpg,.pdf" required>
                    </div>
                </div>
            </div>

            <div class="row my-3" id="gstRow">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="gst">GST No <span class="text-danger">*</span></label>
                        <input type="text" name="gst" id="gst" class="form-control" placeholder="22AAAAA0000A1Z5" maxlength="15"  aria-describedby="helpId" >
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="gst_docs">Upload GST Document<span class="text-danger">*</span></label>
                        <input type="file" name="gst_docs" id="gst_docs" class="form-control" aria-describedby="helpId" accept=".jpg,.pdf"  >
                    </div>
                </div>
            </div>

            <div class="row my-3" id="fssaiRow">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fssai">FSSAI No<span class="text-danger">*</span></label>
                        <input type="text" name="fssai" id="fssai" class="form-control" placeholder="12345678901234" aria-describedby="helpId" maxlength="14">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fssai_docs">Upload FSSAI Document<span class="text-danger">*</span></label>
                        <input type="file" name="fssai_docs" id="fssai_docs" class="form-control" aria-describedby="helpId" accept=".jpg,.pdf" >
                    </div>
                </div>
            </div>


            <div class="row my-3">
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="billing_address">Billing Address<span class="text-danger">*</span></label>
                        <input type="text" name="billing_address" id="billing_address" class="form-control" placeholder="Billing Address" aria-describedby="helpId" required value="{{ $user->location }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="billing_pincode">Billing Pincode<span class="text-danger">*</span></label>
                        <input type="text" name="billing_pincode" id="billing_pincode" class="form-control" aria-describedby="helpId" required value="{{ $user->pincode }}">
                    </div>
                </div>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="copyAddressCheckbox">
                <label class="form-check-label" for="copyAddressCheckbox">Copy Billing Address to Outlet Address</label>
            </div>

            <div class="row my-3">
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="outlet_address">Outlet Address<span class="text-danger">*</span></label>
                        <input type="text" name="outlet_address" id="outlet_address" class="form-control" placeholder="Outlet Address" aria-describedby="helpId" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="outlet_pincode">Outlet Pincode<span class="text-danger">*</span></label>
                        <input type="text" name="outlet_pincode" id="outlet_pincode" class="form-control" aria-describedby="helpId"  required>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-theme">Submit Documents</button>
            </div>
        </form>
    </div>
 </div>

<script>
       @if(session('error'))
        showToast('error', '{{ session('error') }}');
    @endif

    @if(session('success'))
        showToast('success', '{{ session('success') }}');
    @endif

    function showToast(icon, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        Toast.fire({
            icon: icon,
            title: message
        });
    }
</script>



    <style>
        .page-h{
            letter-spacing: 0.5px;
            font-size:40px;
        }

        .left-div{
            border: 2px solid #f0f0f0;
            padding: 15px 20px 15px 20px;
            border-radius: 5px;
            background-color: #f0f0f0;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }

        .right-div{
            background-color: #f0f0f0;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 5px;
            padding: 15px 20px 15px 20px;
            border: 1px solid #ebebeb;
            margin-bottom: 20px;
            height : fit-content;
        }

        .right-h{
            font-weight : 700 !important;
            color: #363636 !important;
            font-size : 24px !important;
            letter-spacing: 0.2px;
        }

        .left-h{
                font-size: 14px;
                font-weight: 600;
                letter-spacing: 0.2px;
                color: #828282;
        }

        .left-sub-h{
            font-weight: 400;
            font-size: 18px;
            color: #363636;
            margin-top: 7px;
        }

        .right-data{
            font-size:14px;
            font-weight: 600;

        }

        .edit-i{
        color:#E03546;
        font-weight : 500 ;
        font-size :15px;

        }

        .right-add{
          margin-bottom: 24px;
          font-weight : 700;
          color: #828282;
        }

        .form-control{
            padding: 10px 18px 10px 17px !important;
            border-radius: 5px !important
            }


    </style>
    <script>
    $('.addOutlet').click(function() {
        var formData = {
            name: $('#name').val(),
            outlet_name: $('#outlet_name').val(),
            mobile_number: $('#mobile').val(),
            email: $('#email').val(),
            location: $('#location').val(),
            pincode: $('#pincode_data').val()
        };

        $.ajax({
            type: 'POST',
            url: '/outlet/store',
            data: formData,
            headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
            success: function(data) {
                console.log(data);
                $('#name').val('');
                $('#outlet_name').val('');
                $('#mobile').val('');
                $('#email').val('');
                $('#location').val('');
                $('#pincode_data').val('');
                // Close the modal
                $('#exampleModal').modal('hide');
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
            }
        });
    });
</script>
<script>
    document.getElementById('copyAddressCheckbox').addEventListener('change', function() {
        if (this.checked) {
            var billingAddress = document.getElementById('billing_address').value;
            var billingPincode = document.getElementById('billing_pincode').value;
            document.getElementById('outlet_address').value = billingAddress;
            document.getElementById('outlet_pincode').value = billingPincode;
        } else {
            // Clear outlet address and pincode if checkbox is unchecked
            document.getElementById('outlet_address').value = '';
            document.getElementById('outlet_pincode').value = '';
        }
    });

    $(document).ready(function () {
    // Hide all rows initially except the Business row
    $("#gstRow, #fssaiRow").hide();

    // Set the default radio button (Business) as selected
    if ($('input[type="radio"][name="exampleRadios"]:checked').val() === "Business") {
        $("#pancardRow, #gstRow, #fssaiRow").show();
    } else {
        $("#pancardRow").show();
        $("#gstRow, #fssaiRow").hide();
    }

    // Event listener for radio button change
    $('input[type="radio"][name="exampleRadios"]').change(function () {
        var selectedValue = $(this).val();
        if (selectedValue === "Personal") { // Personal
            $("#pancardRow").show();
            $("#gstRow, #fssaiRow").hide();
        } else if (selectedValue === "Business") { // Business
            $("#pancardRow, #gstRow, #fssaiRow").show();
        }
    });
});

</script>


@endsection

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<style>
    label{
        font-weight: 500;

    }
</style>
