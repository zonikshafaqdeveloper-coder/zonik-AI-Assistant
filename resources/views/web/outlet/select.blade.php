@extends('mobile.mobile-app')
@section('content')

<style>
.select-outlet-page {
    background: #f7f8fa;
    min-height: 100%;
    padding: 24px 16px 40px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.so-container {
    max-width: 640px;
    margin: 0 auto;
}

.page-heading h1 {
    font-size: 26px;
    font-weight: 800;
    color: #101828;
    margin: 0 0 4px;
}
.page-heading p {
    font-size: 14px;
    color: #667085;
    margin: 0 0 22px;
}

.outlet-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.outlet-card {
    background: #fff;
    border: 1.5px solid #e4e7ec;
    border-radius: 16px;
    padding: 18px;
}
.outlet-card.selected {
    background: #eef2ff;
    border-color: #4f5fff;
}
.outlet-card.inactive {
    opacity: 0.75;
}

.outlet-top {
    display: flex;
    gap: 14px;
}

.outlet-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.outlet-info {
    flex: 1;
    min-width: 0;
}

.outlet-name-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 7px;
    flex-wrap: wrap;
}
.outlet-name {
    font-size: 16px;
    font-weight: 700;
    color: #101828;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.outlet-location {
    font-size: 12.5px;
    color: #9aa1ac;
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    flex-shrink: 0;
}

.outlet-meta {
    font-size: 13px;
    color: #667085;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}

.outlet-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: 16px;
    flex-wrap: wrap;
}
.outlet-bottom.end {
    justify-content: flex-end;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    padding: 6px 12px 6px 6px;
    border-radius: 100px;
    white-space: nowrap;
}
.status-badge.current {
    background: #fff;
    border: 1.5px solid #4f5fff;
    color: #2f5ede;
}
.status-badge.current svg {
    background: #4f5fff;
    border-radius: 50%;
    padding: 3px;
    box-sizing: content-box;
    stroke: #fff !important;
}
.status-badge.inactive {
    background: #f1f2f6;
    color: #667085;
}

.outlet-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    font-weight: 700;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
}
.outlet-btn.select {
    background: #fff;
    color: #2f5ede;
    border: 1.5px solid #4f5fff;
    transition: background .15s;
}
.outlet-btn.select:hover {
    background: #f5f6ff;
}
.outlet-btn.selected {
    background: #4f5fff;
    color: #fff;
    cursor: default;
}
.outlet-btn.disabled {
    background: #f1f2f6;
    color: #98a2b3;
    cursor: not-allowed;
}

.empty-state {
    background: #fff;
    border: 1.5px dashed #cdd3e8;
    border-radius: 16px;
    padding: 48px 24px;
    text-align: center;
}
.empty-state h3 {
    font-size: 18px;
    font-weight: 700;
    color: #101828;
    margin: 18px 0 6px;
}
.empty-state p {
    font-size: 14px;
    color: #667085;
    max-width: 320px;
    margin: 0 auto;
    line-height: 1.5;
}

.add-outlet-card {
    background: #fff;
    border: 1.5px dashed #cdd3e8;
    border-radius: 16px;
    padding: 18px;
    margin-top: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.add-outlet-left {
    display: flex;
    align-items: center;
    gap: 14px;
}
.add-outlet-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: #dde3ff;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    flex-shrink: 0;
}
.add-outlet-icon svg { width: 24px; height: 24px; color: #4f5fff; }
.add-outlet-icon .plus-badge {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 20px;
    height: 20px;
    background: #4f5fff;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
}
.add-outlet-icon .plus-badge svg { width: 11px; height: 11px; }
.add-outlet-title {
    font-size: 15px;
    font-weight: 700;
    color: #101828;
    margin-bottom: 2px;
}
.add-outlet-desc {
    font-size: 12px;
    color: #667085;
    line-height: 1.4;
}
.btn-add-outlet {
    background: #4f5fff;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 11px 18px;
    font-size: 13px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    cursor: pointer;
}
.btn-add-outlet svg { width: 14px; height: 14px; }

@media (min-width: 768px) {
    .select-outlet-page { padding: 40px 24px; }
    .so-container {
        background: #fff;
        border-radius: 20px;
        padding: 32px 36px;
        box-shadow: 0 1px 3px rgba(16,24,40,0.05), 0 1px 2px rgba(16,24,40,0.04);
    }
    .page-heading h1 { font-size: 28px; }
}
</style>

<div class="select-outlet-page">
    <div class="so-container">

        <!-- ============ PAGE HEADING ============ -->
        <div class="page-heading">
            <h1>Select Outlet</h1>
            <p>Choose the outlet you want to order for</p>
        </div>

        <!-- ============ OUTLET LIST ============ -->
        @php
            $iconPalette = [
                ['bg' => '#dbe6ff', 'fg' => '#2f5ede'],
                ['bg' => '#ffe6d6', 'fg' => '#e2711d'],
                ['bg' => '#d9f4e6', 'fg' => '#1f9d63'],
                ['bg' => '#ece3fb', 'fg' => '#7c4fd6'],
            ];
        @endphp

        <div class="outlet-list">
            @forelse($outlets as $index => $outlet)
                @php
                    $isCurrent = isset($currentOutletId) && (string) $currentOutletId === (string) $outlet->id;
                    $isVerified = $outlet->verified_status === 'verified';
                    $colors = $iconPalette[$index % count($iconPalette)];
                @endphp

                <div class="outlet-card {{ $isCurrent ? 'selected' : '' }} {{ !$isVerified ? 'inactive' : '' }}">
                    <div class="outlet-top">
                        <div class="outlet-icon" style="background:{{ $colors['bg'] }};">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="{{ $colors['fg'] }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l1.5-5h15L21 9"/>
                                <path d="M3 9a2 2 0 0 0 4 0 2 2 0 0 0 4 0 2 2 0 0 0 4 0 2 2 0 0 0 4 0"/>
                                <path d="M5 9v10h14V9"/>
                                <path d="M9 19v-6h6v6"/>
                            </svg>
                        </div>

                        <div class="outlet-info">
                            <div class="outlet-name-row">
                                <div class="outlet-name">{{ $outlet->outlet_name }}</div>
                                <div class="outlet-location">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9aa1ac" stroke-width="2"><path d="M12 21s7-6.5 7-12a7 7 0 0 0-14 0c0 5.5 7 12 7 12z"/><circle cx="12" cy="9" r="2.5"/></svg>
                                    {{ $outlet->location }}
                                </div>
                            </div>

                            <div class="outlet-meta">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9aa1ac" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg>
                                Manager: {{ $outlet->name }}
                            </div>
                            <div class="outlet-meta">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9aa1ac" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                Mobile: {{ $outlet->mobile_number }}
                            </div>
                        </div>
                    </div>

                    <div class="outlet-bottom {{ !$isCurrent && $isVerified ? 'end' : '' }}">
                        @if($isCurrent)
                            <span class="status-badge current">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#2f5ede" stroke-width="3"><path d="M5 12l4 4L19 6"/></svg>
                                Current Outlet
                            </span>
                             <a href="{{ route('web.home') }}" class="outlet-btn selected">
                                Proceed
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>

                        @elseif($isVerified)
                            <a href="{{ route('web.outlet.choose', ['id' => $outlet->id]) }}" class="outlet-btn select">
                                Select Outlet
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2f5ede" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>

                        @else
                            <span class="status-badge inactive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#8a8f98" stroke-width="2"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                Pending Verification
                            </span>
                            <button class="outlet-btn disabled" disabled>Inactive</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#c7d4fb" stroke-width="1.5" style="margin:0 auto;"><path d="M3 9l1.5-5h15L21 9"/><path d="M3 9a2 2 0 0 0 4 0 2 2 0 0 0 4 0 2 2 0 0 0 4 0 2 2 0 0 0 4 0"/><path d="M5 9v10h14V9"/></svg>
                    <h3>No outlets yet</h3>
                    <p>Add your first outlet below to start placing orders.</p>
                </div>
            @endforelse
        </div>

        <!-- ============ ADD NEW OUTLET ============ -->
        <div class="add-outlet-card">
            <div class="add-outlet-left">
                <div class="add-outlet-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/><path d="M9 9h.01M15 9h.01M9 13h.01M15 13h.01"/></svg>
                    <span class="plus-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </span>
                </div>
                <div>
                    <div class="add-outlet-title">Add New Outlet</div>
                    <div class="add-outlet-desc">Create a new outlet to manage<br>orders separately</div>
                </div>
            </div>
           <a href="{{ route('web.outlet.create') }}" class="btn-add-outlet" style="justify-content:center; text-decoration:none;">
               
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Outlet
            </a>
        </div>

    </div>
</div>

<!-- Add Outlet Modal -->
<div class="modal location-modal fade theme-modal" id="locationModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-full screen-sm-down">
        <div class="modal-content modal-cust" id="mobileBox">
            <div class="modal-header">
                <h5 class="modal-title indexh5 mb-2" id="exampleModalLabel">Outlet form</h5>
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
                                <input type="tel" name="mobile" id="mobile" class="form-control mb-3"
                                    placeholder="Enter Your Mobile Number" required maxlength="10"
                                    pattern="[6-9]{1}[0-9]{9}"
                                    title="Mobile number must start with 6, 7, 8, or 9 and be 10 digits long"
                                    oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);" />
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

@endsection

@section('scripts')
<script>
$('.addOutlet').click(function () {
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
        url: '{{ route('web.outlet.store') }}',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#name').val('');
            $('#outlet_name').val('');
            $('#mobile').val('');
            $('#email').val('');
            $('#location').val('');
            $('#pincode_data').val('');
            $('#messageBox').html('');

            $('#locationModal').modal('hide');

            Swal.fire({
                title: "Success!",
                text: "Your outlet has been added.",
                icon: "success",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = data.redirect_url;
                }
            });
        },
        error: function (xhr) {
            $('.addOutlet').prop('disabled', false);

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
            $('.addOutlet').prop('disabled', false);
        }
    });
});
</script>
@endsection
