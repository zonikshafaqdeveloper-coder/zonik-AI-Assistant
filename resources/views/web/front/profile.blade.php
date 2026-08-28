@extends('web.layouts.app')

@section('content')
<!-- --------------order dropdown----------------- -->

<div class="container " style="font-family: " Public Sans", sans-serif">
    <div class="row">
        <div class="col-md-5 my-3">
            <div class="row justify-content-between">
                <div class="col-md-12 mb-3">
                    <h2 class="page-h">Profile Settings</h2>
                </div>
            </div>
            <div class="left-div">
                 <div class="mt-20">
                    <p class="left-h">Company Name</p>
                    <p class="left-sub-h" contenteditable="true">{{ auth()->user()->outlet_name }}</p>
                </div>
                
                <div class="mt-20">
                    <p class="left-h">User Name</p>
                    <p class="left-sub-h" contenteditable="true">{{ auth()->user()->name }}</p>
                </div>

                <div class="mt-20">
                    <p class="left-h">Login phone Number</p>
                    <p class="left-sub-h">{{ auth()->user()->mobile_number }}</p>
                </div>
               <div class="mt-20">
                    <p class="left-h">Email Id</p>
                    <p class="left-sub-h" contenteditable="true"> {{ auth()->user()->email }} </p>
                </div>
                <div class="mt-20">
                    <p class="left-h">Password</p>
                    <p class="left-sub-h" contenteditable="true"></p>
                </div>
                <div class="mt-20">
                    <p class="left-h">Address</p>
                    <p class="left-sub-h" contenteditable="true">{{ auth()->user()->location }} - {{ auth()->user()->pincode }}</p>
                </div>
                <div class="break-line"></div>
                  <div class="my-2">
                    <button type="button" class="btn red-btn " onclick="location.reload();">Update Details</button> 
                </div>
            </div>
        </div>

        <div class="col-md-7 my-3">
            <div class="row justify-content-between">
                <div class="col-md-4" style="width: max-content;">
                    <h2 class="page-h mr-3">Outlets</h2>
                </div>
                <div class="col-md-3 mb-1" style="width: max-content;"><button
                        class="btn theme-bg-color btn-md text-white fw-bold" data-bs-toggle="modal"
                        data-bs-target="#locationModal">Add Outlets</button></div>
            </div>

            @foreach($userData as $user)

            <div class="right-div">
                <div class="d-flex justify-content-between mb-2">
                    <div class="col-md-9 ">
                        <h2 class="right-h">{{ $user->name }} - {{ $user->outlet_name }}</h2>
                    </div>
                    <div class="col-md-2">
                        <p class="right-data">
                            @if($user->verified_status == 'verified')
                            <div class="customer_status d-sm-flex">
                                <div class="customer_bage"> <img
                                        src="https://cdn.shopify.com/s/files/1/0566/8241/4246/t/11/assets/icon-verified-1662629893290.png?v=1662629894"
                                        alt=""> </div>
                                <div class="customer_status_content">Verified</div>
                            </div>
                            @else
                            <div class="customer_status d-sm-flex">
                                <div class="customer_bage">
                                    <img src="https://cdn3d.iconscout.com/3d/premium/thumb/unverified-security-9031295-7516457.png?f=webp" alt=""> </div>
                                    <div class="customer_status_content text-danger">Unverified</div>
                            </div>
                            <a href="verify-outlet/{{ $user->id }}" style="font-size: small">Click to verify</a>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-1">
                        <a href="javascript:void(0);" onclick="confirmDelete('{{ $user->id }}')" class="right-data">
                            <i class="fa fa-trash text-danger"></i>
                        </a>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <p class="right-add">{{ $user->location }} - {{ $user->pincode }}</p>
                </div>
                <div class="break-line"></div>

                <div class="d-flex mt-4 align-items-center">
                    <div class="d-flex">
                        <i class="fa-solid fa-phone-volume" style="color: #828282; "></i>
                    </div>
                    <div class="d-flex">
                        <p class="right-data">{{ $user->mobile_number }}</p>
                    </div>
                </div>

                <div class="d-flex mt-2 align-items-center">
                    <div class="d-flex">
                        <i class="fa-solid fa-envelope" style="color: #828282;"></i>
                    </div>
                    <div class="d-flex">
                        <p class="right-data">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="modal location-modal fade theme-modal" id="locationModal" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-full screen-sm-down">
                <div class="modal-content modal-cust" id="mobileBox">
                    <div class="modal-header">
                        <h5 class="modal-title indexh5 mb-2" id="exampleModalLabel">Outlet form
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="location-list">
                            <div class="search-input">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" name="name" id="name" class="form-control mb-3"
                                            placeholder="Enter Your Name" required />
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="outlet_name" id="outlet_name" class="form-control mb-3"
                                            placeholder="Enter Your Outlet Name" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="tel" name="mobile" id="mobile" class="form-control mb-3" placeholder="Enter Your Mobile Number" required maxlength="10" pattern="[6-9]{1}[0-9]{9}" title="Mobile number must start with 6, 7, 8, or 9 and be 10 digits long" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);" />

                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" name="email" id="email" class="form-control mb-3"
                                            placeholder="abc@gmail.com" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" name="location" id="location" class="form-control mb-3"
                                            placeholder="Enter Your Location Name" required />
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="pincode" id="pincode_data" class="form-control mb-3"
                                            placeholder="Enter Your Pincode" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="messageBox" class="mb-3 error-message"></div>
                        <button type="button" class="btn red-btn addOutlet">Add Outlet</button>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <style>
        .page-h {
            letter-spacing: 0.5px;
            font-size: 30px;
        }

        .left-div {
            border: 2px solid #fffaff;
            padding: 15px 20px 15px 20px;
            border-radius: 5px;
            background-color: #fffaff;
            height: fit-content;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }

        .right-div {
            background-color: #fffaff;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 5px;
            padding: 15px 20px 15px 20px;
            border: 1px solid #ebebeb;
            margin-bottom: 20px;
            height: fit-content;
        }

        .right-h {
            font-weight: 500 !important;
            color: #363636 !important;
            font-size: 20px !important;
            letter-spacing: 0.2px;
        }

        .left-h {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.2px;
            color: #828282;
        }

        .left-sub-h {
            font-weight: 400;
            font-size: 18px;
            color: #363636;
            margin-top: 7px;
        }

        .right-data {
            font-size: 14px;
            font-weight: 600;

        }



        .edit-i {
            color: #E03546;
            font-weight: 500;
            font-size: 15px;

        }

        .right-add {
            margin-bottom: 24px;
            font-weight: 700;
            color: #828282;
        }

        .mt-20 {
            margin-top: 20px;
            margin-bottom: 24px;
        }

        .break-line {
            font-size: 0;
            width: 100%;
            border-top: 1px solid #ebeb;
        }

        .inner-h-img {
            width: 20px;
            height: -1px;
            margin-right: 6px;
        }


        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        @media (max-width: 767px) {
            .modal-dialog.modal-full {
                top: 10px;
                left: 0;
                width: 100%;
                height: 100%;
                margin: 0;
            }

            .d-sm-flex {
                display: flex
            }

            .modal-dialog.modal-full .modal-content {
                height: 100%;
                border-radius: 0;
            }

            .screen-sm-down .modal-dialog.modal-full {
                display: flex;
                align-items: flex-start;
                justify-content: center;
            }

            .screen-sm-down .modal-dialog.modal-full .modal-content {
                margin-top: 20px;
                /* Adjust as needed */
            }

            .page-h {
                letter-spacing: 0.5px;
                font-size: 28px;
            }

            .right-add {
                margin-bottom: 24px;
                font-weight: 500;
                color: #828282;
            }

            .left-sub-h {
                font-weight: 400;
                font-size: 16px;
                color: #363636;
                margin-top: 7px;
            }



            .right-h {
                font-weight: 500 !important;
                color: #363636 !important;
                font-size: 18px !important;
                letter-spacing: .5px;
            }

        }
    </style>
    <script>
  $('.addOutlet').click(function () {
    // Disable the button to prevent multiple clicks
    $(this).prop('disabled', true);

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
        success: function (data) {
            console.log(data);

            // Clear form fields
            $('#name').val('');
            $('#outlet_name').val('');
            $('#mobile').val('');
            $('#email').val('');
            $('#location').val('');
            $('#pincode_data').val('');

            // Clear error message box
            $('#messageBox').html('');

            // Close the modal
            $('#exampleModal').modal('hide');

            // Show SweetAlert success message
            Swal.fire({
                title: "Success!",
                text: "Your outlet has been added.",
                icon: "success",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Optional: Redirect the user
                    // window.location.href = '/profile';
                     window.location.href = data.redirect_url;
                }
            });
        },
        error: function (xhr) {
            // Re-enable the button
            $('.addOutlet').prop('disabled', false);

            // Parse error messages
          let response = xhr.responseJSON;

if (response?.error) {
    $('#messageBox').html(`<p>${response.error}</p>`).addClass('alert alert-danger');
} else if (response?.errors) {
    let errorHtml = '<ul>';
    Object.keys(response.errors).forEach(function (key) {
        errorHtml += `<li>${response.errors[key][0]}</li>`;
    });
    errorHtml += '</ul>';
    $('#messageBox').html(errorHtml).addClass('alert alert-danger');
} else {
    $('#messageBox')
        .html('<p>An unknown error occurred. Please try again later.</p>')
        .addClass('alert alert-danger');
}

        },
        complete: function () {
            // Re-enable the button after AJAX request completes
            $('.addOutlet').prop('disabled', false);
        }
    });
});




        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this outlet?")) {
                window.location.href = "delete-outlet/" + userId;
            }
        }
    </script>

    @endsection

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
    </script>
<script>
   document.addEventListener("DOMContentLoaded", function() {
    const editableFields = document.querySelectorAll('.left-sub-h[contenteditable="true"]');

    editableFields.forEach(function(field) {
        field.addEventListener('input', function() {
            const fieldName = field.previousElementSibling.textContent.trim();
            const newValue = field.textContent.trim();
            updateUserInfo(fieldName, newValue);
        });
    });

    function updateUserInfo(fieldName, newValue) {
        
       if(fieldName == 'Company Name'){
           fieldName = 'outlet_name';
       } else if(fieldName == 'User Name'){
                fieldName = 'name';
       } else if(fieldName == 'Email Id'){
                fieldName = 'email';
       }else if(fieldName.includes('Address')) {
            fieldName = 'location';
            newValue = newValue.split(' - ')[0];  // Extract only the location part
        }
        else if(fieldName == 'Password') {
            fieldName = 'password';
        }
        
    fetch('/update-user-info', {
        method: 'POST',
        body: JSON.stringify({ fieldName: fieldName, newValue: newValue }),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(response => {
        if (response.ok) {
            console.log('User information updated successfully');
        } else {
            console.error('Failed to update user information');
        }
    }).catch(error => {
        console.error('Error:', error);
    });
}

});


</script>