@extends('mobile.mobile-app')
@section('content')

<style>
.aof-page {
    background: #f7f8fa;
    min-height: 100%;
    padding: 16px 16px 24px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.aof-container { max-width: 640px; margin: 0 auto; }

.aof-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}
.aof-top-left { display: flex; align-items: center; gap: 12px; }
.aof-back {
    width: 36px; height: 36px; border-radius: 50%;
    background: #fff; border: 1px solid #e4e7ec;
    display: flex; align-items: center; justify-content: center;
    color: #344054; text-decoration: none; flex-shrink: 0;
}
.aof-title h1 { font-size: 20px; font-weight: 800; color: #101828; margin: 0; }
.aof-title p { font-size: 12.5px; color: #667085; margin: 2px 0 0; }
.aof-help {
    display: flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 600; color: #4f5fff;
    text-decoration: none; white-space: nowrap;
}

.aof-tabs {
    display: flex;
    background: #eef0f3;
    border-radius: 12px;
    padding: 4px;
    margin-bottom: 16px;
}
.aof-tab {
    flex: 1;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 12px 8px;
    border-radius: 9px;
    font-size: 14px; font-weight: 700;
    color: #667085;
    cursor: pointer;
    border: none;
    background: transparent;
}
.aof-tab.active {
    background: #fff;
    color: #4f5fff;
    box-shadow: 0 1px 3px rgba(16,24,40,0.08);
}
.aof-tab.active.business { color: #d85a30; }
.aof-tab svg { width: 17px; height: 17px; }

.aof-info-banner {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #eef2ff;
    border: 1px solid #dde3ff;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 12.5px;
    color: #3d4a6b;
    margin-bottom: 16px;
}
.aof-info-banner.business {
    background: #fdf0ea;
    border-color: #fbe0d3;
    color: #7a3a1e;
}
.aof-info-banner svg { flex-shrink: 0; }

.aof-section {
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 14px;
}
.aof-section-head {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 4px;
}
.aof-section-icon {
    width: 34px; height: 34px; border-radius: 50%;
    background: #eef2ff;
    display: flex; align-items: center; justify-content: center;
    color: #4f5fff;
    flex-shrink: 0;
}
.aof-section-icon.business { background: #fdf0ea; color: #d85a30; }
.aof-section-title { font-size: 15px; font-weight: 700; color: #101828; }
.aof-section-desc { font-size: 12px; color: #98a2b3; margin: 2px 0 14px 44px; }

.aof-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 12px;
    margin-bottom: 12px;
}
.aof-field label {
    display: block;
    font-size: 12.5px;
    font-weight: 700;
    color: #344054;
    margin-bottom: 6px;
    line-height: 1.3;
}
.aof-field label .req { color: #e0442e; }
.aof-field input[type="text"],
.aof-field input[type="email"],
.aof-field input[type="tel"],
.aof-field input[type="number"],
.aof-field select,
.aof-field textarea {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid #e4e7ec;
    border-radius: 9px;
    padding: 11px 12px;
    font-size: 13.5px;
    color: #101828;
    background: #fff;
    text-overflow: ellipsis;
}
.aof-field input:disabled {
    background: #f4f5f7;
    color: #98a2b3;
}
.aof-field small {
    display: block;
    font-size: 11px;
    color: #98a2b3;
    margin-top: 4px;
}

@media (min-width: 480px) {
    .aof-row {
        grid-template-columns: 1fr 1fr;
    }
}
@media (min-width: 700px) {
    .aof-row.cols-3 {
        grid-template-columns: 1fr 1fr 1fr;
    }
}

.aof-upload {
    border: 1.5px dashed #d0d5dd;
    border-radius: 10px;
    padding: 16px 12px;
    text-align: center;
    cursor: pointer;
    position: relative;
}
.aof-upload input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}
.aof-upload-icon {
    width: 30px; height: 30px; border-radius: 50%;
    background: #eef2ff; color: #4f5fff;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 8px;
}
.aof-upload.business .aof-upload-icon { background: #fdf0ea; color: #d85a30; }
.aof-upload-title { font-size: 13px; font-weight: 700; color: #101828; }
.aof-upload-sub { font-size: 11px; color: #98a2b3; margin-top: 2px; }
.aof-upload.has-file .aof-upload-title { color: #1d9e75; }

.aof-accepted {
    background: #f4f5f7;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 11.5px;
    color: #667085;
}
.aof-accepted.business { background: #fdf0ea; color: #7a3a1e; }
.aof-accepted strong { display: block; font-size: 12px; color: #344054; margin-bottom: 6px; }
.aof-accepted.business strong { color: #7a3a1e; }
.aof-accepted ul { margin: 0; padding-left: 16px; }

.aof-toggle-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.aof-toggle-2 button {
    border: 1.5px solid #e4e7ec;
    border-radius: 9px;
    padding: 11px;
    font-size: 13.5px;
    font-weight: 700;
    color: #667085;
    background: #fff;
    cursor: pointer;
}
.aof-toggle-2 button.active {
    border-color: #4f5fff;
    color: #4f5fff;
}
.aof-toggle-2 button.active.business { border-color: #d85a30; color: #d85a30; }

.aof-phone-field {
    display: flex;
    border: 1px solid #e4e7ec;
    border-radius: 9px;
    overflow: hidden;
}
.aof-phone-prefix {
    padding: 11px 12px;
    background: #f4f5f7;
    font-size: 13.5px;
    color: #667085;
    border-right: 1px solid #e4e7ec;
}
.aof-phone-field input {
    border: none !important;
    flex: 1;
}

.aof-security {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #667085;
    background: #eef2ff;
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 16px;
}
.aof-security.business { background: #fdf0ea; color: #7a3a1e; }

.aof-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}
.aof-btn-cancel {
    border: 1.5px solid #4f5fff;
    color: #4f5fff;
    background: #fff;
    border-radius: 10px;
    padding: 13px 10px;
    font-size: 13.5px;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    white-space: nowrap;
}
.aof-btn-save {
    border: none;
    background: #4f5fff;
    color: #fff;
    border-radius: 10px;
    padding: 13px 10px;
    font-size: 13.5px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    white-space: nowrap;
    overflow: hidden;
}
.aof-btn-save span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.aof-btn-save svg { flex-shrink: 0; }
.aof-btn-save.business { background: #d85a30; }

.aof-tab-panel { display: none; }
.aof-tab-panel.active { display: block; }

@media (min-width: 768px) {
    .aof-page { padding: 32px 24px; }
    .aof-container {
        background: #fff;
        border-radius: 20px;
        padding: 28px 32px;
        box-shadow: 0 1px 3px rgba(16,24,40,0.05), 0 1px 2px rgba(16,24,40,0.04);
    }
}
</style>

<div class="aof-page">
    <div class="aof-container">

        <div class="aof-top">
            <div class="aof-top-left">
                <a href="{{ url()->previous() }}" class="aof-back" aria-label="Back">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                </a>
                <div class="aof-title">
                    <h1>Add New Outlet</h1>
                    <p>Add outlet details to start ordering</p>
                </div>
            </div>
            <a href="#" class="aof-help">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
                Need Help?
            </a>
        </div>

        <form id="addOutletForm" enctype="multipart/form-data">
            @csrf

            <div class="aof-tabs">
                <button type="button" class="aof-tab active" id="tabPersonalBtn" data-tab="personal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>
                    Personal
                </button>
                <button type="button" class="aof-tab business" id="tabBusinessBtn" data-tab="business">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/></svg>
                    Business
                </button>
            </div>

            <div class="aof-info-banner" id="infoBanner">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4f5fff" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                All information is secure and will be used only for order and delivery purposes.
            </div>

            <!-- Outlet Information (shared) -->
            <div class="aof-section">
                <div class="aof-section-head">
                    <div class="aof-section-icon" id="outletInfoIcon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l1-5h16l1 5"/><path d="M4 9v10h16V9"/><path d="M9 21v-6h6v6"/></svg>
                    </div>
                    <div class="aof-section-title">Outlet Information</div>
                </div>

                <div class="aof-row" style="margin-top:14px;">
                    <div class="aof-field">
                        <label>Outlet Name <span class="req">*</span></label>
                        <input type="text" name="outlet_name" id="outlet_name" placeholder="Enter outlet name" required>
                    </div>
                    <div class="aof-field">
                        <label>Outlet Type <span class="req">*</span></label>
                        <select name="outlet_type" id="outlet_type" required>
                            <option value="">Select outlet type</option>
                            <option value="restaurant">Restaurant</option>
                            <option value="cafe">Cafe</option>
                            <option value="cloud_kitchen">Cloud Kitchen</option>
                            <option value="catering">Catering</option>
                            <option value="retail">Retail</option>
                        </select>
                    </div>
                </div>

                <div class="aof-row">
                    <div class="aof-field">
                        <label>Legal Company Name</label>
                        <input type="text" name="legal_company_name" value="{{ $legalCompanyName ?? '' }}" disabled>
                    </div>
                    <div class="aof-field">
                        <label>Email <span class="req">*</span></label>
                        <input type="email" name="email" id="email" placeholder="Enter email address" required>
                    </div>
                </div>
            </div>

            <!-- ============ PERSONAL TAB ============ -->
            <div class="aof-tab-panel active" id="personalPanel">

                <div class="aof-section">
                    <div class="aof-section-head">
                        <div class="aof-section-icon" style="background:#4f5fff; color:#fff;">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>
                        </div>
                        <div class="aof-section-title">Personal Details</div>
                    </div>
                    <div class="aof-section-desc">Enter your PAN details and address to add a personal outlet.</div>

                    <div class="aof-row">
                        <div class="aof-field">
                            <label>PAN Card Number <span class="req">*</span></label>
                            <input type="text" name="pan_number" id="pan_number" placeholder="PAN card number" maxlength="10">
                            <small>PAN card is mandatory</small>
                        </div>
                        <div class="aof-field">
                            <label>PAN Card Upload <span class="req">*</span></label>
                            <label class="aof-upload" id="panUploadBox">
                                <input type="file" name="pan_docs" id="pan_docs" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="aof-upload-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </div>
                                <div class="aof-upload-title">Upload PAN Card</div>
                                <div class="aof-upload-sub">JPG, PNG, PDF (Max. 5MB)</div>
                            </label>
                        </div>
                    </div>

                    <div class="aof-row">
                        <div class="aof-field">
                            <label>Owner Photo ID Upload <span class="req">*</span></label>
                            <label class="aof-upload" id="ownerIdUploadBoxPersonal">
                                <input type="file" name="owner_id_docs" id="owner_id_docs_personal" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="aof-upload-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </div>
                                <div class="aof-upload-title">Upload Owner Photo ID</div>
                                <div class="aof-upload-sub">JPG, PNG, PDF (Max. 5MB)</div>
                            </label>
                        </div>
                        <div class="aof-field">
                            <label>&nbsp;</label>
                            <div class="aof-accepted">
                                <strong>Accepted Documents</strong>
                                <ul>
                                    <li>Aadhar Card</li>
                                    <li>Passport</li>
                                    <li>Driving License</li>
                                    <li>Voter ID</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ============ BUSINESS TAB ============ -->
            <div class="aof-tab-panel" id="businessPanel">

                <div class="aof-section">
                    <div class="aof-section-head">
                        <div class="aof-section-icon business">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        </div>
                        <div class="aof-section-title">Company PAN Details</div>
                    </div>
                    <div class="aof-section-desc">Enter company PAN details.</div>

                    <div class="aof-row">
                        <div class="aof-field">
                            <label>PAN of Company <span class="req">*</span></label>
                            <input type="text" name="company_pan_number" id="company_pan_number" placeholder="Enter PAN number" maxlength="10">
                        </div>
                        <div class="aof-field">
                            <label>Upload Company PAN <span class="req">*</span></label>
                            <label class="aof-upload business" id="companyPanUploadBox">
                                <input type="file" name="company_pan_docs" id="company_pan_docs" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="aof-upload-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </div>
                                <div class="aof-upload-title">Upload PAN Card</div>
                                <div class="aof-upload-sub">JPG, PNG, PDF (Max. 5MB)</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="aof-section">
                    <div class="aof-section-head">
                        <div class="aof-section-icon business">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6z"/></svg>
                    </div>
                        <div class="aof-section-title">Business Compliance</div>
                    </div>
                    <div class="aof-section-desc">Provide your business compliance details.</div>

                    <div class="aof-row">
                        <div class="aof-field">
                            <label>GST Number <span class="req">*</span></label>
                            <input type="text" name="gst_number" id="gst_number" placeholder="Enter GST number" maxlength="15">
                        </div>
                        <div class="aof-field">
                            <label>Upload GST Certificate <span class="req">*</span></label>
                            <label class="aof-upload business" id="gstUploadBox">
                                <input type="file" name="gst_docs" id="gst_docs" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="aof-upload-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </div>
                                <div class="aof-upload-title">Upload GST Certificate</div>
                                <div class="aof-upload-sub">JPG, PNG, PDF (Max. 5MB)</div>
                            </label>
                        </div>
                    </div>

                    <div class="aof-row">
                        <div class="aof-field">
                            <label>FSSAI Number <span class="req">*</span></label>
                            <input type="text" name="fssai_number" id="fssai_number" placeholder="Enter FSSAI number" maxlength="14">
                        </div>
                        <div class="aof-field">
                            <label>Upload FSSAI Certificate <span class="req">*</span></label>
                            <label class="aof-upload business" id="fssaiUploadBox">
                                <input type="file" name="fssai_docs" id="fssai_docs" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="aof-upload-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </div>
                                <div class="aof-upload-title">Upload FSSAI Certificate</div>
                                <div class="aof-upload-sub">JPG, PNG, PDF (Max. 5MB)</div>
                            </label>
                        </div>
                    </div>

                    <div class="aof-row">
                        <div class="aof-field">
                            <label>Owner Photo ID Upload <span class="req">*</span></label>
                            <small style="margin-top:-4px; margin-bottom:6px;">Upload any one of the below documents.</small>
                            <label class="aof-upload business" id="ownerIdUploadBoxBusiness">
                                <input type="file" name="owner_id_docs_business" id="owner_id_docs_business" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="aof-upload-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </div>
                                <div class="aof-upload-title">Upload Owner Photo ID</div>
                                <div class="aof-upload-sub">JPG, PNG, PDF (Max. 5MB)</div>
                            </label>
                        </div>
                        <div class="aof-field">
                            <label>&nbsp;</label>
                            <div class="aof-accepted business">
                                <strong>Accepted Documents</strong>
                                <ul>
                                    <li>Aadhaar Card</li>
                                    <li>PAN Card</li>
                                    <li>Passport</li>
                                    <li>Driving Licence</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Delivery Address (shared) -->
            <div class="aof-section">
                <div class="aof-section-head">
                    <div class="aof-section-icon" id="deliveryIcon">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div class="aof-section-title">Delivery Address <span class="req">*</span></div>
                </div>

                <div class="aof-field" style="margin-top:14px; margin-bottom:12px;">
                    <textarea name="full_address" id="full_address" rows="2" placeholder="Full Address (House / Building, Street, Area)" required></textarea>
                </div>

                <div class="aof-row">
                    <div class="aof-field">
                        <label>Suburb / Locality <span class="req">*</span></label>
                        <select name="suburb" id="suburb" required>
                            <option value="">Select Suburb</option>
                            <option value="Colaba">Colaba</option>
                            <option value="Churchgate">Churchgate</option>
                            <option value="Nariman Point">Nariman Point</option>
                            <option value="Fort">Fort</option>
                            <option value="Marine Lines">Marine Lines</option>
                            <option value="Charni Road">Charni Road</option>
                            <option value="Grant Road">Grant Road</option>
                            <option value="Tardeo">Tardeo</option>
                            <option value="Byculla">Byculla</option>
                            <option value="Mazgaon">Mazgaon</option>
                            <option value="Parel">Parel</option>
                            <option value="Lower Parel">Lower Parel</option>
                            <option value="Worli">Worli</option>
                            <option value="Prabhadevi">Prabhadevi</option>
                            <option value="Dadar">Dadar</option>
                            <option value="Matunga">Matunga</option>
                            <option value="Mahim">Mahim</option>
                            <option value="Sion">Sion</option>
                            <option value="Wadala">Wadala</option>
                            <option value="Sewri">Sewri</option>
                            <option value="Cuffe Parade">Cuffe Parade</option>
                            <option value="Antop Hill">Antop Hill</option>
                            <option value="Kalachowki">Kalachowki</option>
                            <option value="Lalbaug">Lalbaug</option>
                            <option value="Cotton Green">Cotton Green</option>
                            <option value="Reay Road">Reay Road</option>
                            <option value="King's Circle">King's Circle</option>
                            <option value="Bandra">Bandra</option>
                            <option value="Khar">Khar</option>
                            <option value="Santacruz">Santacruz</option>
                            <option value="Vile Parle">Vile Parle</option>
                            <option value="Andheri">Andheri</option>
                            <option value="Jogeshwari">Jogeshwari</option>
                            <option value="Goregaon">Goregaon</option>
                            <option value="Malad">Malad</option>
                            <option value="Kandivali">Kandivali</option>
                            <option value="Borivali">Borivali</option>
                            <option value="Dahisar">Dahisar</option>
                            <option value="Kurla">Kurla</option>
                            <option value="Chembur">Chembur</option>
                            <option value="Govandi">Govandi</option>
                            <option value="Deonar">Deonar</option>
                            <option value="Tilak Nagar">Tilak Nagar</option>
                            <option value="Ghatkopar">Ghatkopar</option>
                            <option value="Vikhroli">Vikhroli</option>
                            <option value="Kanjurmarg">Kanjurmarg</option>
                            <option value="Bhandup">Bhandup</option>
                            <option value="Nahur">Nahur</option>
                            <option value="Mulund">Mulund</option>
                            <option value="Powai">Powai</option>
                            <option value="Chandivali">Chandivali</option>
                            <option value="Airoli">Airoli</option>
                            <option value="Ghansoli">Ghansoli</option>
                            <option value="Kopar Khairane">Kopar Khairane</option>
                            <option value="Vashi">Vashi</option>
                            <option value="Sanpada">Sanpada</option>
                            <option value="Juinagar">Juinagar</option>
                            <option value="Nerul">Nerul</option>
                            <option value="Seawoods">Seawoods</option>
                            <option value="CBD Belapur">CBD Belapur</option>
                            <option value="Kharghar">Kharghar</option>
                            <option value="Kalamboli">Kalamboli</option>
                            <option value="Kamothe">Kamothe</option>
                            <option value="Panvel">Panvel</option>
                            <option value="New Panvel">New Panvel</option>
                            <option value="Taloja">Taloja</option>
                            <option value="Ulwe">Ulwe</option>
                            <option value="Dronagiri">Dronagiri</option>
                            <option value="Uran">Uran</option>
                            <option value="Thane">Thane</option>
                            <option value="Majiwada">Majiwada</option>
                            <option value="Balkum">Balkum</option>
                            <option value="Manpada">Manpada</option>
                            <option value="Kolshet">Kolshet</option>
                            <option value="Ghodbunder Road">Ghodbunder Road</option>
                            <option value="Kasarvadavali">Kasarvadavali</option>
                            <option value="Vartak Nagar">Vartak Nagar</option>
                            <option value="Panch Pakhadi">Panch Pakhadi</option>
                            <option value="Naupada">Naupada</option>
                            <option value="Wagle Estate">Wagle Estate</option>
                            <option value="Kalwa">Kalwa</option>
                            <option value="Mumbra">Mumbra</option>
                            <option value="Diva">Diva</option>
                            <option value="Kalyan">Kalyan</option>
                            <option value="Dombivli">Dombivli</option>
                            <option value="Thakurli">Thakurli</option>
                            <option value="Khopat">Khopat</option>
                            <option value="Nilje">Nilje</option>
                            <option value="Mira Road">Mira Road</option>
                            <option value="Bhayandar">Bhayandar</option>
                            <option value="Naigaon">Naigaon</option>
                            <option value="Vasai">Vasai</option>
                            <option value="Nalasopara">Nalasopara</option>
                            <option value="Virar">Virar</option>
                            <option value="Bhiwandi">Bhiwandi</option>
                            <option value="Anjurphata">Anjurphata</option>
                            <option value="Ranjnoli">Ranjnoli</option>
                            <option value="Ambernath">Ambernath</option>
                            <option value="Badlapur">Badlapur</option>
                        </select>
                    </div>
                    <div class="aof-field">
                        <label>&nbsp;</label>
                        <div class="aof-toggle-2" id="regionToggle">
                            <button type="button" class="active" data-region="east">East</button>
                            <button type="button" data-region="west">West</button>
                        </div>
                        <input type="hidden" name="region" id="region" value="east">
                    </div>
                </div>

                <div class="aof-row">
                    <div class="aof-field">
                        <label>City <span class="req">*</span></label>
                        <input type="text" name="city" id="city" placeholder="Enter City" required>
                    </div>
                    <div class="aof-field">
                        <label>Delivery Pin Code <span class="req">*</span></label>
                        <input type="text" name="delivery_pincode" id="delivery_pincode" placeholder="6-digit PIN code" maxlength="6" required>
                    </div>
                </div>
            </div>

            <!-- Receiver Details (shared) -->
            <div class="aof-section">
                <div class="aof-section-head">
                    <div class="aof-section-icon" style="background:#eef2ff; color:#4f5fff;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>
                    </div>
                    <div class="aof-section-title">Receiver Details <span class="req">*</span></div>
                </div>
                <div class="aof-section-desc">Person who will receive the order</div>

                <div class="aof-row">
                    <div class="aof-field">
                        <label>Receiver Name <span class="req">*</span></label>
                        <input type="text" name="receiver_name" id="receiver_name" placeholder="Enter receiver name" required>
                    </div>
                    <div class="aof-field">
                        <label>Receiver Mobile Number <span class="req">*</span></label>
                        <div class="aof-phone-field">
                            <span class="aof-phone-prefix">+91</span>
                            <input type="tel" name="receiver_mobile" id="receiver_mobile" placeholder="Mobile number" maxlength="10" pattern="[6-9]{1}[0-9]{9}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="aof-security" id="securityNote">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4f5fff" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Your information is safe with us and encrypted end-to-end.
            </div>

            <div class="aof-actions">
                <a href="{{ url()->previous() }}" class="aof-btn-cancel">Cancel</a>
                <button type="submit" class="aof-btn-save" id="saveOutletBtn">
                    <span id="saveBtnText">Save Outlet</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </div>

        </form>

    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {

    let activeTab = 'personal';

    function switchTab(tab) {
        activeTab = tab;

        $('.aof-tab').removeClass('active');
        $('#tab' + (tab === 'personal' ? 'Personal' : 'Business') + 'Btn').addClass('active');

        $('.aof-tab-panel').removeClass('active');
        $('#' + tab + 'Panel').addClass('active');

        const isBusiness = tab === 'business';

        $('#infoBanner').toggleClass('business', isBusiness);
        $('#outletInfoIcon').toggleClass('business', isBusiness);
        $('#deliveryIcon').toggleClass('business', isBusiness);
        $('#securityNote').toggleClass('business', isBusiness);
        $('#saveOutletBtn').toggleClass('business', isBusiness);
        $('#saveBtnText').text(isBusiness ? 'Save Business Outlet' : 'Save Outlet');
        $('#regionToggle button.active').toggleClass('business', isBusiness);
    }

    $('#tabPersonalBtn').on('click', function () { switchTab('personal'); });
    $('#tabBusinessBtn').on('click', function () { switchTab('business'); });

    $('#regionToggle button').on('click', function () {
        $('#regionToggle button').removeClass('active business');
        $(this).addClass('active');
        if (activeTab === 'business') $(this).addClass('business');
        $('#region').val($(this).data('region'));
    });

    // Show filename + green state once a file is chosen
    $('.aof-upload input[type="file"]').on('change', function () {
        const $box = $(this).closest('.aof-upload');
        const fileName = this.files.length ? this.files[0].name : '';

        if (fileName) {
            $box.addClass('has-file');
            $box.find('.aof-upload-title').text(fileName.length > 24 ? fileName.substring(0, 21) + '...' : fileName);
        }
    });

    $('#addOutletForm').on('submit', function (e) {
        e.preventDefault();

        $('#saveOutletBtn').prop('disabled', true);

        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we create your outlet',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData(this);
        formData.append('account_type', activeTab);

        $.ajax({
            url: '{{ route('web.outlet.store') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                Swal.fire({
                    title: "Success!",
                    text: "Your outlet has been added.",
                    icon: "success",
                    confirmButtonColor: "#4f5fff",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = data.redirect_url;
                    }
                });
            },
            error: function (xhr) {
                $('#saveOutletBtn').prop('disabled', false);

                let response = xhr.responseJSON;
                let errorItems = [];

                if (response?.error) {
                    errorItems = [response.error];
                } else if (response?.errors) {
                    Object.keys(response.errors).forEach(function (key) {
                        errorItems.push(response.errors[key][0]);
                    });
                } else {
                    errorItems = ['An unknown error occurred. Please try again later.'];
                }

                const listHtml = errorItems.map(function (msg) {
                    return `
                        <div style="display:flex; align-items:flex-start; gap:10px; text-align:left; padding:10px 0; border-bottom:1px solid #f1f2f4;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e0442e" stroke-width="2.5" style="flex-shrink:0; margin-top:2px;">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <span style="font-size:13.5px; color:#344054;">${msg}</span>
                        </div>
                    `;
                }).join('');

                Swal.fire({
                    title: errorItems.length > 1 ? 'Please fix the following' : 'Something went wrong',
                    html: `<div style="max-height:280px; overflow-y:auto;">${listHtml}</div>`,
                    icon: 'error',
                    confirmButtonText: 'Got it',
                    confirmButtonColor: '#4f5fff',
                    customClass: {
                        popup: 'aof-error-popup'
                    }
                });
            }
        });
    });

});
</script>
@endsection
