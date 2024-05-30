@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Add Coupon</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminCoupon') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form" class="form" action="{{ route('adminDoAddCoupon') }}">
                    <div class="card-body border-top p-9">

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Coupon Name</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="coupon_name" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Name" value="">
                                        <span id="coupon_nameErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Coupon Code</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="coupon_code" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Coupon Code" value="">
                                        <span id="coupon_codeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Coupon type</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select class="form-select mb-2" name="coupon_type" data-control="select2" data-hide-search="true" data-placeholder="Select Coupon Type">
                                            <option value="">Select Coupon Type</option>
                                            <option selected value="flat">Flat</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                        <span id="coupon_typeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Coupon Usage</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select class="form-select mb-2" name="coupon_usage" data-control="select2" data-hide-search="true" data-placeholder="Select Coupon Usage">
                                            <option value="">Select Coupon Usage</option>
                                            <option selected value="single">Single</option>
                                            <option value="multiple">Multiple</option>
                                        </select>
                                        <span id="coupon_usageErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Coupon Price</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="coupon_price" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Coupon Price" value="">
                                        <span id="coupon_priceErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>


                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Start Date</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="date" name="start_date" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Start Date" value="">
                                        <span id="start_dateErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">End Date</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="date" name="end_date" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="End Date" value="">
                                        <span id="end_dateErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>
                        


                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Status</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <select class="form-select mb-2" name="status" data-control="select2" data-hide-search="true" data-placeholder="Select Status">
                                            <option value="">Select Status</option>
                                            <option selected value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <span id="statusErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                    </div>

                    <div class="d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary" id="kt_form_submit">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">
                                Please wait...    
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>            
                </form>
            </div>
        </div>

    </div>
</div>

    <!--begin::Custom Javascript(used for this page only)-->
    <script type="text/javascript">
        dataUrl = '{{ route("getAdminCoupon") }}';        
    </script>
    <script src="{{ asset('public/backend/js/admin/coupon.js') }}"></script>

@endsection