@extends('admin.vwAdminMaster')

@section('content')

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid " >
    <div id="kt_app_content_container" class="app-container  container-fluid ">

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0 cursor-pointer">
                
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Edit Shipping</h3>
                </div>

                <div class="card-title">
                    <a href="{{ route('adminShipping') }}" class="btn btn-primary">Back</a>
                </div>

            </div>
            
            <div id="kt_account_settings_profile_details" class="collapse show">                
                <form id="kt_form_update" class="form" action="{{ route('adminDoUpdateShipping') }}">
                    <div class="card-body border-top p-9">

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Pincode</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="pincode" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Pincode" value="{{ $shipping->pincode }}">
                                        <span id="pincodeErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6">Free Shipping</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="free_shipping" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Price" value="{{ $shipping->free_shipping }}">
                                        <span id="free_shippingErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>


                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">Under 500gm</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="under_500gm" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Price" value="{{ !empty($shipping->under_500gm)? $shipping->under_500gm : 0 }}">
                                        <span id="under_500gmErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">500-1000 GM</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="from500_1000gm" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Price" value="{{ $shipping->from500_1000gm; }}">
                                        <span id="500_1000gmErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">1000-2000 GM</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="from1000_2000gm" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Price" value="{{ $shipping->from1000_2000gm; }}">
                                        <span id="1000_2000gmErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                        <div class="row mb-6">                    
                            <label class="col-lg-3 col-form-label fw-semibold fs-6 required">2000-3000 GM</label>
                            <div class="col-lg-9">                        
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <input type="text" name="from2000_3000gm" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Price" value="{{ $shipping->from2000_3000gm }}">
                                        <span id="2000_3000gmErr" class="text-danger"></span>
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
                                            <option {{ $shipping->is_active == 1? 'selected':'' }} selected value="1">Active</option>
                                            <option {{ $shipping->is_active == 0? 'selected':'' }} value="0">Inactive</option>
                                        </select>
                                        <span id="statusErr" class="text-danger"></span>
                                    </div>                            
                                </div>
                            </div>                    
                        </div>

                    </div>

                    <div class="d-flex justify-content-end py-6 px-9">

                        <input type="hidden" name="id" value="{{ $shipping->id }}">

                        <button type="submit" class="btn btn-primary" id="kt_form_update_submit">
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
        dataUrl = '{{ route("getAdminCategory") }}';        
    </script>
    <script src="{{ asset('public/backend/js/admin/category.js') }}"></script>

@endsection